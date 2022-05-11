<?php

namespace App\Services;

use App\DTO\Sheet\SheetDTO;
use App\Models\BiologicalOccurrenceView;
use Illuminate\Support\Facades\Log;

class SheetToDatabaseService
{
    public function insertSheetToDatabase(SheetDTO $sheetDTO){
        $databaseColumns = [];
        for ($col = 0; $col < $sheetDTO->getColumnCount() && $sheetDTO->getSheet()->getCellByColumnAndRow($col, 2)->getValue() != ''; $col++) {
            $columnName = $sheetDTO->getSheet()->getCellByColumnAndRow($col, 2)->getValue();
//            if(!$this->checkArtificialColumn($columnName)){
//                $columnName = $sheetDTO->getSheet()->getCellByColumnAndRow($col, 2)->getValue();
//            }
//            else{
//                $columnName = $this->checkArtificialColumn($columnName);
//            }

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

    private function checkArtificialColumn(string $nameToCheck){
        $nameToCheck = strtolower($nameToCheck);

        $artificialColumns = [
            'subfamília' => 'artificial:subfamily',
            'localização na coleção (prateleira)' => 'artificial:shelfLocation',
            'localização na coleção (frasco)' => 'artificial:flaskLocation',
            'guia de remessa' => 'artificial:shippingGuide',
            'subtribo' => 'artificial:subtribe',
            'sribo' => 'artificial:tribe',
            'superfamilia' => 'artificial:superfamily',
            'snfraordem' => 'artifcial:infraorder',
            'subordem' => 'artificial:suborder',
            'superordem' => 'artificial:superorder',
            'subclasse' => 'artificial:subclasse',
            'subfilo' => 'artificial:subphylum',
            'localização na coleção (armário)' => 'artificial:shelfLocation',
            'localização na coleção (gaveta)' => 'artificial:flaskLocation'
        ];

        return array_key_exists($nameToCheck, $artificialColumns)
            ? $artificialColumns[$nameToCheck]
            : false;
    }
}
