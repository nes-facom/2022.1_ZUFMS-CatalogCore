<?php

namespace App\Services;

use App\DTO\Input\ListOccurrenceInputDTO;
use App\DTO\Input\OccurrenceInputDTO;
use App\Models\BiologicalOccurrenceView;
use App\Repository\CollectionRepository;
use Exception;
use Illuminate\Http\Request;
use Opis\JsonSchema\{Errors\ErrorFormatter, Validator,};
use function Pest\Laravel\json;


class CollectionService
{

    private Validator $validator;
    private \JsonMapper\JsonMapperInterface $mapper;
    private CollectionRepository $collectionRepository;

    public function __construct()
    {
        $this->collectionRepository = new CollectionRepository("biological_occurrence_view", "occurrenceID");
        $this->mapper = (new \JsonMapper\JsonMapperFactory())->bestFit();
        $this->validator = new Validator();
        $this->validator->resolver()->registerFile(
            'https://inbio.ufms.br/zufms/zufmscore.schema.json',
            base_path() . '/resources/assets/zufmscore.schema.json'
        );

    }

    private function remove_null_values($json)
    {
        return preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json);
    }

    public function insertMany(Request $request)
    {
        $vl = $request->all();
        $acess_token = $vl["access_token"];
        unset($vl["access_token"]);

        $jsonBody = json_encode($vl);

        $jsonBody = $this->remove_null_values($jsonBody);
        $validationErrors = [];
        $body = json_decode($jsonBody);

        $listOccurrence = ListOccurrenceInputDTO::constructEmpty();

        $keys = array_keys($body);
        $size = count($body);

        for ($i = 0; $i < $size; $i++) {

            $key = $keys[$i];
            $jsonOccurrence = $body[$key];

            $occurrence = OccurrenceInputDTO::constructEmpty();

            $result = OccurrenceInputDTO::validate($jsonOccurrence, $this->validator);


            if ($result->isValid()) {

                $listOccurrence->occurrences[] = OccurrenceInputDTO::fromArray($jsonOccurrence,$this->mapper);
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
        $insertedOccurrences = [];
        if (empty($validationErrors)) {
            try {
                foreach ($listOccurrence->occurrences as &$ocurrence){
                    $occurrenceArray = $ocurrence->toArray();
                    $isInserted = BiologicalOccurrenceView::query()->insert($occurrenceArray);
                    if($isInserted){
                        $insertedOccurrences[]= $occurrenceArray;
                    }
                }
            } catch (Exception $e) {
                $error_description = "Um erro interno inesperado ocorreu";

                if($e->getCode() == 23505){
                    $error_string = $e->getMessage();

                    $error_array = explode("\n", $error_string,  3);
                    $error_description = $error_array[0].$error_array[1];
                }

                return response()->json(
                    array(
                        "errors" => array(array("code" => 4,
                            "title" => "Error interno",
                            "description" => $error_description)),), 500);
            }

        } else {
            return response()->json(
                array('errors' => $validationErrors)

                , 400);
        }
        if(!empty($insertedOccurrences)){
            return response()->json(
                $insertedOccurrences

                , 200);
        }

    }

}

