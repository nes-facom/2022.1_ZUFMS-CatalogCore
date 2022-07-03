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
            repository: new GenericRepository('biological_occurrence_view', "occurrenceID"),
            entity_pk: 'occurrenceID'
        );
        $this->spreadSheetService = $spreadSheetService;
        $this->collectionService = $collectionService;
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
    public function count(Request $request) {
        $available_sections = $this->collectionService->available_sections;

        $validator = Validator::make($request->all(), [
            'artificial:section' => [Rule::in($available_sections)],
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

        $data = DB::table('biological_occurrence_view')
            ->when($validated['artificial:section'], function ($query, $section) {
                $query->where('artificial:section', $section);
            })
            ->count();

        return response()->json($data);
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

    /*
    * @Override
    */
    public function getOne(Request $request): \Illuminate\Http\JsonResponse
    {
        unset($request['access_token']);

        $input = [
            ...$request->all(), 
            "occurrenceID" => $request->route('occurrenceID')
        ];

        $validator = Validator::make($input, [
            'occurrenceID' => 'required|string',
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

        $occurrence = DB::table('biological_occurrence_view')
            ->where("occurrenceID", $validated['occurrenceID'])
            ->first();

        return response()->json($occurrence);
    }

    public function getAutocomplete(Request $request) {
        $validator = Validator::make($request->all(), [
            'term' => ['required', Rule::in($this->avaliable_term_columns)],
            'value' => 'nullable|string',
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
