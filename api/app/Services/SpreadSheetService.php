<?php

namespace App\Services;

use App\DTO\Sheet\SheetDTO;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;

class SpreadSheetService
{
    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function readFile(Request $request){
        $fileRequest = $request->file('file');
        $section = $request->get('section');

        $reader = new ReaderCsv();
        $reader->setDelimiter(',');

        $spreadSheet = $reader->load($fileRequest);
        $sheet = $spreadSheet->getActiveSheet();
        $workSheeInfo = $reader->listWorksheetInfo($fileRequest);

        $totalRows = $workSheeInfo[0]['totalRows'];
        $totalCols = $workSheeInfo[0]['totalColumns'];

        return new SheetDTO($totalCols, $totalRows, $sheet, $section);
    }
}
