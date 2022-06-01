<?php

namespace App\Services;

use App\DTO\Input\ListOccurrenceInputDTO;
use App\DTO\Input\OccurrenceInputDTO;
use App\Repository\CollectionRepository;
use Illuminate\Http\Request;
use Opis\JsonSchema\{Errors\ErrorFormatter, Validator,};


class CollectionService
{

    private Validator $validator;
    private \JsonMapper\JsonMapperInterface $mapper;
    private CollectionRepository $collectionRepository;

    public function __construct()
    {
        $this->collectionRepository = new CollectionRepository("biological_occurrence_view");
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

        $jsonBody = json_encode($request->all());
        $jsonBody = $this->remove_null_values($jsonBody);
        $arrayError = [];

        $body = json_decode($jsonBody);


        $listOccurrence = ListOccurrenceInputDTO::constructEmpty();

        $keys = array_keys($body);
        $size = count($body);

        for ($i = 0; $i < $size; $i++) {

            $key = $keys[$i];
            $occurrence = $body[$key];

            $uniqueOccurrence = OccurrenceInputDTO::constructEmpty();
            $result = OccurrenceInputDTO::validate($occurrence, $this->validator);


            if ($result->isValid()) {
                $this->mapper->mapObject($occurrence, $uniqueOccurrence);
                $listOccurrence->occurrences[] = $uniqueOccurrence;
            } else {
                $error = $result->error();
                $formatter = new ErrorFormatter();
                $errorDescription = $formatter->format($error, false);
                $errorKeys = array_keys($errorDescription);
                $term = $errorKeys[0];
                $arrayError[] =
                    array(
                        "code" => 2,
                        "title" => "Dado inválido",
                        "description" => $errorDescription[$term],
                        "_index" => $i,
                        "_term" => $term

                    );
            }
        }
        /*
         * "errors": [
    {
      "code": 4,
      "title": "Erro interno",
      "description": "Um erro interno inesperado ocorreu"
    }
  ]
         *
         * */
        if (empty($arrayError)) {
            try {
                foreach ($listOccurrence as &$ocurrence){

                    $this->collectionRepository->createOne($ocurrence);
                }
            } catch (Exception $e) {
                return response()->json(
                    array(
                        "errors" => array("code" => 4,
                            "title" => "Error interno",
                            "description" => "Um erro interno inesperado ocorreu"),), 500);
            }
           return response()->json(
                array('email' => "",)
                , 200);
        } else {
            return response()->json(
                array('errors' => $arrayError,)

                , 400);
        }


    }

}

