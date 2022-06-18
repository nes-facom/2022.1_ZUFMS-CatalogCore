<?php

namespace App\Services;

use App\DTO\Sheet\SheetDTO;
use App\Models\BiologicalOccurrenceView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;

class SpreadSheetService
{
    public function parseValue($value, $head){
        switch ($head){
            case "day":
            case "month":
            case "year":
                return (int) $value;
            case "decimalLatitude":
            case "decimalLongitude":
            case "minimumElevationInMeters":
            case "maximumElevationInMeters":
                return (float) $value;
            default:
                return $value;
        }
    }
    public function sheetToJson(Request $request){
        $sheetDTO = $this->readFile($request);

        $tableHead = [];
        for ($col = 0; $col < $sheetDTO->getColumnCount() && $sheetDTO->getSheet()->getCellByColumnAndRow($col, 2)->getValue() != ''; $col++) {
            $columnName = $sheetDTO->getSheet()->getCellByColumnAndRow($col, 2)->getValue();
            $tableHead[] = $columnName;
        }
        $tableBody = [];
        for ($row = 3; $row <= $sheetDTO->getRowCount(); $row++) {
            $tableRow = [];
            $tableRow['artificial:section'] = $sheetDTO->getSection();
            for ($col = 0; $col < count($tableHead); $col++) {
                $value = $sheetDTO->getSheet()->getCellByColumnAndRow($col, $row)->getValue();
                $value = trim($value);
                if($value == 'x' || $value == '' || $value == 'nulo' || $value == 'nenhum' ||  $value == 'nenhuma'){
                    $value = null;
                }
                $tableRow[$tableHead[$col]] =   $this->parseValue($value,$tableHead[$col]);
            }
            array_push($tableBody, $tableRow);
        }

        return response()->json($tableBody, 200);
    }

    public function insertSheetToDatabase(Request $request){
        $sheetDTO = $this->readFile($request);

        $databaseColumns = [];
        for ($col = 0; $col < $sheetDTO->getColumnCount() && $sheetDTO->getSheet()->getCellByColumnAndRow($col, 2)->getValue() != ''; $col++) {
            $columnName = $sheetDTO->getSheet()->getCellByColumnAndRow($col, 2)->getValue();
            $databaseColumns[] = $columnName;
        }

        for ($row = 3; $row <= $sheetDTO->getRowCount(); $row++) {
            $dataToInsert = [];
            $dataToInsert['artificial:section'] = $sheetDTO->getSection();
            Log::debug(print_r($databaseColumns, true));
            for ($col = 0; $col < count($databaseColumns); $col++) {
                $value = $sheetDTO->getSheet()->getCellByColumnAndRow($col, $row)->getValue();
                Log::debug($databaseColumns[$col] . ' - ' . $value);
                $dataToInsert[$databaseColumns[$col]] = $value == '' ? null : $value;
            }
            print_r($dataToInsert, true);
            BiologicalOccurrenceView::query()->insert($dataToInsert);
        }
    }

    private function readFile(Request $request){
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
