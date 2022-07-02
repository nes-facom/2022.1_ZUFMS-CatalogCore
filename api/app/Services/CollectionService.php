<?php

namespace App\Services;

use App\DTO\Input\ListOccurrenceInputDTO;
use App\DTO\Input\OccurrenceInputDTO;
use App\Exceptions\DuplicatedKeyException;
use App\Exceptions\ValidationOccurrenceException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Opis\JsonSchema\{Errors\ErrorFormatter, Validator,};
use JsonMapper\JsonMapperFactory;
use JsonMapper\JsonMapperInterface;


class CollectionService
{

    private Validator $validator;
    private JsonMapperInterface $mapper;
    public array $available_term_columns;
    public array $available_sections;

    public function __construct()
    {
        $this->fillAvailableTermColumnsAndAvailableSections();
        $this->mapper = (new JsonMapperFactory())->bestFit();
        $this->validator = new Validator();
        $this->validator->resolver()->registerFile(
            'https://inbio.ufms.br/zufms/zufmscore.schema.json',
            base_path() . '/resources/assets/zufmscore.schema.json'
        );
    }

    private function fillAvailableTermColumnsAndAvailableSections(): void
    {
        $string = file_get_contents(base_path() . '/resources/assets/zufmscore.schema.json');
        $json_a = json_decode($string, true);
        $properties =$json_a['properties'];
        $keys = array_keys($properties);
        $size = count($properties);
        $availableColumns = [];
        $availableSections = [];
        for ($i = 0; $i < $size; $i++) {
            $key =   $keys[$i];
            $availableColumns[] =$key;

            if($key == 'artificial:section'){

                $sections = $properties[$key]['oneOf'];

                foreach ($sections as &$section){
                    $availableSections[] = $section['const'];
                }
            }
        }
        $this->available_sections = $availableSections;
        $this->available_term_columns =  $availableColumns;
    }

    public function occurrence_is_rascunho($array): bool
    {
        $rascunho_values = ['ocultar', 'tombar girino', 'fazer etiqueta', 'mapinguari'];

        if(array_key_exists('informationWithheld', $array)){
            foreach ($rascunho_values as &$rascunho){
                if(  str_contains(  strtolower( $array['informationWithheld']), $rascunho)){
                    return true;
                }
            }
            return false;
        }else{
            return true;
        }
    }

    public function insertManyFromJson($jsonBody): \Illuminate\Http\JsonResponse
    {
        try {
            $listOccurrences = $this->validateJsonAndReturnListOccurrence($jsonBody);

            try {
                $insertedOccurrences = $this->insertListOccurrenceInDatabase($listOccurrences);

            } catch (Exception $e) {
                $error_description = "Um erro interno inesperado ocorreu";
                $error_title = "Erro interno";
                if ($e instanceof DuplicatedKeyException) {
                    $error_description = $e->getMessage();
                }
                $error_string = $e->getMessage();
                $error_array = explode("\n", $error_string, 3);
                if ($e->getCode() == 23505) {
                    $error_description = $error_array[0] . $error_array[1];
                } else {
                    $error_description = $error_array[0];
                }

                return response()->json(
                    array(
                        "errors" => array(array("code" => 4,
                            "title" => $error_title,
                            "description" => $error_description)),), 500);

            }
        } catch (ValidationOccurrenceException $e) {
            return response()->json(
                array('errors' => $e->errorsArray)
                , 400);
        }

        if (!empty($insertedOccurrences)) {
            return response()->json(
                $insertedOccurrences
                , 200);
        } else {
            return response()->json(
                []
                , 200);
        }
    }

    public function insertManyFromRequest(Request $request): void
    {
        $vl = $request->all();
        $jsonBody = json_encode($vl);
        $this->insertManyFromJson($jsonBody);
    }

    public function getAll($input): array
    {
        $validated = $input;
        $start = isset($validated['start']) ? (int) $validated['start'] : NULL;
        $limit = isset($validated['limit']) ? (int) $validated['limit'] : NULL;
        $sortBy = isset($validated['sortBy']) ? (string) $validated['sortBy'] :  'occurrenceID';
        $section = isset($validated['artificial:section']) ? (string) $validated['artificial:section'] :  null;

       $query = DB::table('biological_occurrence_view')->orderBy($sortBy);
        if($section != null){
            $query = $query->where('artificial:section',"=" ,$section);
        }
      return $query->limit($limit)->offset($start)->get()->toArray();
    }

    /**
     * @return ListOccurrenceInputDTO list occurrence
     * @throws ValidationOccurrenceException if has validation error
     */
    public function validateJsonAndReturnListOccurrence($jsonBody): ListOccurrenceInputDTO
    {
        $jsonBody = $this->remove_null_values($jsonBody);
        $validationErrors = [];
        $body = json_decode($jsonBody);

        $listOccurrence = ListOccurrenceInputDTO::constructEmpty();

        $keys = array_keys($body);
        $size = count($body);

        for ($i = 0; $i < $size; $i++) {
            $result;
            $key = $keys[$i];
            $jsonOccurrence = $body[$key];
            $skip_validation = false;
            if($this->occurrence_is_rascunho(json_decode(json_encode($jsonOccurrence),true))){
                $skip_validation = true;
            }else{
                $result = OccurrenceInputDTO::validate($jsonOccurrence, $this->validator);
            }


            if ($result->isValid() || $skip_validation) {
                $listOccurrence->occurrences[] = OccurrenceInputDTO::fromArray($jsonOccurrence, $this->mapper);
            } else {
                $error = $result->error();
                $formatter = new ErrorFormatter();
                $errorDescription = $formatter->format($error, false);
                $errorKeys = array_keys($errorDescription);
                $term = $errorKeys[0];
                $validationErrors[] =
                    array(
                        "code" => 2,
                        "title" => "Dado inválido",
                        "description" => $errorDescription[$term],
                        "_index" => $i,
                        "_term" => $term

                    );
            }
        }
        if (!empty($validationErrors)) {
            throw new ValidationOccurrenceException($validationErrors);
        } else {
            return $listOccurrence;
        }
    }

    private function remove_null_values($json)
    {
        return preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json);
    }

    public function insertListOccurrenceInDatabase(ListOccurrenceInputDTO $listOccurrence)
    {
        $occurrencesError = [];
        $insertedOccurrences = [];

        foreach ($listOccurrence->occurrences as &$occurrence) {

            if (!$this->hasOccurrence($occurrence)) {
                $isInserted ;
                $occurrenceArray = $occurrence->toArray();
                if($this->occurrence_is_rascunho($occurrenceArray)){
                    $isInserted = DB::table('rascunho')->insert($occurrenceArray);
                }else{
                    try {
                        $isInserted = DB::table('biological_occurrence_view')->insert($occurrenceArray);
                    }catch (\Exception $e){
                        $occurrencesError[] = $occurrenceArray['occurrenceID'];
                    }
                }

                if ($isInserted) {
                    $insertedOccurrences[] = $occurrenceArray;
                }
            } else {
                throw new DuplicatedKeyException("Ocorrência [" . $occurrence->occurrenceID . "] já cadastrada na base de dados.");
            }
        }
        return $occurrencesError;


    }

    function hasOccurrence(OccurrenceInputDto $occurrence): bool
    {
        $occurrenceFounded = DB::table('biological_occurrence')->where('occurrenceID', $occurrence->occurrenceID)->first();
        return $occurrenceFounded != null;
    }
}

