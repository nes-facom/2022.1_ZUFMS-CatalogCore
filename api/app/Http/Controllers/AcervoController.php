<?php

namespace App\Http\Controllers;

use App\Services\SpreadSheetService;
use Illuminate\Http\Request;

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
}
