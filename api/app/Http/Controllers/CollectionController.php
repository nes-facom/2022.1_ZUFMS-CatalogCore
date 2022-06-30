<?php

namespace App\Http\Controllers;

use App\DTO\Input\ListOccurrenceInputDTO;
use App\Repository\GenericRepository;
use App\Services\CollectionService;
use App\Services\SpreadSheetService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CollectionController extends CRUDController
{
    private CollectionService  $collectionService;
    private $spreadSheetService;
    private $avaliable_term_columns = [];

    /**
     * @param $spreadSheetService
     */
    public function __construct(SpreadSheetService $spreadSheetService, CollectionService  $collectionService)
    {
        parent::__construct(
           new GenericRepository('biological_occurrence_view',"OccurrenceID"),
        );
        $this->spreadSheetService = $spreadSheetService;
        $this->collectionService=$collectionService;
        $this->setupAutoCompleteFields();
    }

    public function uploadDocumentReturnJson(Request $request){
        set_time_limit(0);
        return $this->spreadSheetService->sheetToJson($request);
    }

    public function file(Request $request){
        set_time_limit(0);
        return $this->spreadSheetService->insertSheetToDatabase($request);
    }

    public function createMany(Request $request){
        unset($request['access_token']);
        return $this->collectionService->insertManyFromRequest($request);
    }
    /*
    * @Override
    */
    public function getAll(Request $request): \Illuminate\Http\JsonResponse
    {
        unset($request['access_token']);

        $available_term_columns = $this->collectionService->available_term_columns;
        $available_sections = $this->collectionService->available_sections;

        $validator = Validator::make($request->all(), [
            'sortBy' => [Rule::in($available_term_columns)],
            'artificial:section' => [Rule::in($available_sections)],
            'limit' => 'integer',
            'start' => 'integer'
        ]);

        if ($validator->fails()) {
            $errors = array_map(fn (string $description) => [
                'code' => 2,
                'title' => 'Dado inválido',
                'description' => $description
            ],  $validator->errors()->all());

            return response()->json([
                'errors' => $errors
            ]);
        }
        $validated = $validator->safe();

        $occurrences = [];
        try {
            $occurrences = $this->collectionService->getAll($validated);
        }catch (\Exception $e){
            return response()->json([
                'errors' => [
                    [
                        'code'=> 2,
                        'title'=> 'Erro interno',
                        'description' => "Um erro interno inesperado ocorreu",
                    ]
                ]
            ], 500);
        }
        return response()->json($occurrences);
    }

    public function getAutocomplete(Request $request) {
        $validator = Validator::make($request->all(), [
            'term' => ['required', Rule::in($this->avaliable_term_columns)],
            'value' => 'required|string',
            'limit' => 'integer',
            'start' => 'integer'
        ]);

        if ($validator->fails()) {
            $errors = array_map(fn (string $description) => [
                'code' => 2,
                'title' => 'Dado inválido',
                'description' => $description
            ],  $validator->errors()->all());

            return response()->json([
                'errors' => $errors
            ]);
        }

        $validated = $validator->safe();

        $column = $validated['term'];
        $start = isset($validated['start']) ? (int) $validated['start'] : NULL;
        $limit = isset($validated['limit']) ? (int) $validated['limit'] : NULL;

        $where_clause = ['ILIKE', $validated['value'] . '%'];

        $autocomplete_query_result = DB::table($column)
            ->offset($start)
            ->limit($limit)
            ->where('value', ...$where_clause)
            ->orderBy('value')
            ->pluck('value');

        return response()->json($autocomplete_query_result->toArray());
    }

    private function setupAutoCompleteFields(){
        $string = file_get_contents(base_path() . '/resources/assets/zufmscore.schema.json');
        $json_a = json_decode($string, true);
        $properties =$json_a['properties'];
        foreach ($properties as $key => $innerList) {
            $existKey = array_key_exists("\$zufmscore:autocomplete", $innerList);
            if($existKey){
                $isAutoComplete = $innerList["\$zufmscore:autocomplete"];
                if($isAutoComplete == true){
                    $this->avaliable_term_columns[] = $key;
                }
            }
        }
    }
}
