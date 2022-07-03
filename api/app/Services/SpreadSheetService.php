<?php

namespace App\Services;

use App\DTO\Sheet\SheetDTO;
use App\Exceptions\ValidationOccurrenceException;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;

class SpreadSheetService
{
    private $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    public function sheetToJson(Request $request): \Illuminate\Http\JsonResponse
    {
        $json_body = json_encode($this->generateJsonFromRequestFile($request));
        $validatedOccurrences = $this->collectionService->validateJsonAndReturnListOccurrence($json_body);

        return response()->json($validatedOccurrences, 200);
    }

    private function generateJsonFromRequestFile(Request $request): array
    {
        $sheetDTO = $this->readFile($request);

        $tableHead = [];
        for ($col = 0; $col < $sheetDTO->getColumnCount() && $sheetDTO->getSheet()->getCellByColumnAndRow($col, 2)->getValue() != ''; $col++) {
            $columnName = $sheetDTO->getSheet()->getCellByColumnAndRow($col, 1)->getValue();
            $tableHead[] = $columnName;
        }
        $tableBody = [];
        for ($row = 3; $row <= $sheetDTO->getRowCount(); $row++) {
            $tableRow = [];
            $tableRow['artificial:section'] = $sheetDTO->getSection();
            for ($col = 0; $col < count($tableHead); $col++) {
                $value = $sheetDTO->getSheet()->getCellByColumnAndRow($col, $row)->getValue();
                $value = trim($value);
                $value = trim($value, " \t\n\r\0\x0B\xC2\xA0");
                if ($value == 'x' || $value == '' || $value == 'nulo') {
                    $value = null;
                }
                $tableRow[$tableHead[$col]] = $this->parseValue($value, $tableHead[$col]);
            }
            $tableBody[] = $tableRow;
        }
        return $tableBody;
    }

    private function readFile(Request $request)
    {
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

    public function parseValue($value, $head)
    {
        switch ($head) {
            case "day":
            case "month":
            case "year":
                return (int)$value;
            case "decimalLatitude":
            case "decimalLongitude":
            case "minimumElevationInMeters":
            case "maximumElevationInMeters":
            case "coordinatePrecision":
            case "minimumDepthInMeters":
            case "maximumDepthInMeters":
                return (float)$value;
            case "artificial:section":
                switch ($value) {
                    case "ACA":
                        $value = "Acanthocephala";
                        break;
                    case "AMP":
                        $value = "Amphibia";
                        break;
                    case "ANN":
                        $value = "Annelida";
                        break;
                    case "ARC":
                        $value = "Archaeognatha";
                        break;
                    case "AVE":
                        $value = "Aves";
                        break;
                    case "BLA":
                        $value = "Blattodea";
                        break;
                    case "CHE":
                        $value = "Chelicerata";
                        break;
                    case "CHI":
                        $value = "Chiroptera";
                        break;
                    case "COB":
                        $value = "Collembola";
                        break;
                    case "COL":
                        $value = "Coleoptera";
                        break;
                    case "CRU":
                        $value = "Crustacea";
                        break;
                    case "DER":
                        $value = "Dermaptera";
                        break;
                    case "DIP":
                        $value = "Diptera";
                        break;
                    case "EMB":
                        $value = "Embioptera";
                        break;
                    case "EPH":
                        $value = "Ephemeroptera";
                        break;
                    case "FOS":
                        $value = "Fósseis";
                        break;
                    case "HEM":
                        $value = "Hemiptera";
                        break;
                    case "HYM":
                        $value = "Hymenoptera";
                        break;
                    case "ISO":
                        $value = "Isoptera";
                        break;
                    case "LEP":
                        $value = "Lepidoptera";
                        break;
                    case "MAN":
                        $value = "Mantodea";
                        break;
                    case "MEC":
                        $value = "Mecoptera";
                        break;
                    case "MEG":
                        $value = "Megaloptera";
                        break;
                    case "MID":
                        $value = "Mídias Digitais";
                        break;
                    case "MNV":
                        $value = "Mamíferos Não-Voadores";
                        break;
                    case "MOL":
                        $value = "Mollusca";
                        break;
                    case "MYR":
                        $value = "Myriapoda";
                        break;
                    case "NEM":
                        $value = "Nematoda";
                        break;
                    case "NEU":
                        $value = "Neuroptera";
                        break;
                    case "ODO":
                        $value = "Odonata";
                        break;
                    case "ORT":
                        $value = "Orthoptera";
                        break;
                    case "PHA":
                        $value = "Phasmatodea";
                        break;
                    case "PIS":
                        $value = "Pisces";
                        break;
                    case "PLA":
                        $value = "Platyhelminthes";
                        break;
                    case "PLE":
                        $value = "Plecoptera";
                        break;
                    case "PSO":
                        $value = "Psocodea";
                        break;
                    case "REP":
                        $value = "Reptilia";
                        break;
                    case "SIP":
                        $value = "Siphonaptera";
                        break;
                    case "TEC":
                        $value = "Tecidos";
                        break;
                    case "THY":
                        $value = "Thysanoptera";
                        break;
                    case "TRI":
                        $value = "Trichoptera";
                        break;
                    case "ZYG":
                        $value = "Zygentoma";
                        break;
                }
                return $value;

            default:
                return $value;
        }
    }

    public function insertSheetToDatabase(Request $request): \Illuminate\Http\JsonResponse
    {
        $occurrencesJson = $this->generateJsonFromRequestFile($request);

        return $this->collectionService->insertManyFromJson(json_encode($occurrencesJson));
    }
}
