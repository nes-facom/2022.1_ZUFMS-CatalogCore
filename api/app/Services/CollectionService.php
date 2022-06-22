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

    public function __construct()
    {
        $this->mapper = (new JsonMapperFactory())->bestFit();
        $this->validator = new Validator();
        $this->validator->resolver()->registerFile(
            'https://inbio.ufms.br/zufms/zufmscore.schema.json',
            base_path() . '/resources/assets/zufmscore.schema.json'
        );

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

            $key = $keys[$i];
            $jsonOccurrence = $body[$key];

            $result = OccurrenceInputDTO::validate($jsonOccurrence, $this->validator);

            if ($result->isValid()) {
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
        $insertedOccurrences = [];

        foreach ($listOccurrence->occurrences as &$occurrence) {

            if (!$this->hasOccurrence($occurrence)) {
                $occurrenceArray = $occurrence->toArray();
                DB::connection()->enableQueryLog();
                $isInserted = DB::table('biological_occurrence_view')->insert($occurrenceArray);
                if ($isInserted) {
                    $insertedOccurrences[] = $occurrenceArray;
                }
            } else {
                throw new DuplicatedKeyException("Ocorrência [" . $occurrence->occurrenceID . "] já cadastrada na base de dados.");
            }
        }
        return $insertedOccurrences;


    }

    function hasOccurrence(OccurrenceInputDto $occurrence): bool
    {
        $occurrenceFounded = DB::table('biological_occurrence')->where('occurrenceID', $occurrence->occurrenceID)->first();
        return $occurrenceFounded != null;
    }
}

