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

        $avaliableTermColumns = ['lifeStage', 'sex'];

        $validator = Validator::make($request->all(), [
            'term' => ['required', 'string', Rule::in($avaliableTermColumns)],
            'value' => 'required|string',
            'limit' => 'integer',
            'start' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                    'code' => 2,
                    'title' => 'Dado inválido',
                    'description' => $validator->errors()->all()
                ]
            ]);
        }

        $validated = $validator->safe();

        $column = $validated['term'];
        $start = isset($validated['start']) ? (int) $validated['start'] : NULL;
        $limit = isset($validated['limit']) ? (int) $validated['limit'] : NULL;
        
        // TODO: Validar se o autocomplete deve ser do tipo: 
        //       1) "bc" -> "bcd", "bcde"
        //       2) "bc" -> "aaabc", "abc", "bcd", "bcde" (usado atualmente)
        $whereClause = ['ILIKE', '%' . $validated['value'] . '%'];

        $autocompleteQueryResult = DB::table(column)
            ->offset($start)
            ->limit($limit)
            ->where('value', ...$whereClause)
            ->orderBy('value')
            ->pluck('value');

        return response()->json($autocompleteQueryResult->toArray());
    }
}
