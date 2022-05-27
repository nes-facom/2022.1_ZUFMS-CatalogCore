<?php

namespace App\Http\Controllers;

use App\Services\SpreadSheetService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AcervoController extends Controller
{
    private $spreadSheetService;

    /**
     * @param $spreadSheetService
     */
    public function __construct(SpreadSheetService $spreadSheetService)
    {
        $this->spreadSheetService = $spreadSheetService;
    }


    public function uploadDocument(Request $request){
        return $this->spreadSheetService->sheetToJson($request);
    }

    public function getAutocomplete(Request $request) {
        // TODO: Criar service para realizar a leitura do JSON Schema no
        //       __construct para obter os termos pesquisáveis à partir dele
        // 
        // $zufmsCoreSchemaPath = "";
        // $zufmsCoreSchemaString = File::get($zufmsCoreSchemaPath);
        // $zufmsCoreSchema = json_decode($zufmsCoreSchemaString, true);
        // $avaliableTermColumns = array_keys($zufmsCoreSchema['properties']);

        $avaliable_term_columns = ['lifeStage', 'sex'];

        $validator = Validator::make($request->all(), [
            'term' => ['required', Rule::in($avaliable_term_columns)],
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

        $autocomplete_query_result = DB::table(column)
            ->offset($start)
            ->limit($limit)
            ->where('value', ...$where_clause)
            ->orderBy('value')
            ->pluck('value');

        return response()->json($autocomplete_query_result->toArray());
    }
}
