<?php

namespace App\Http\Controllers;

use App\Models\BiologicalOccurrenceView;
use App\Services\SheetToDatabaseService;
use App\Services\SpreadSheetService;
use Illuminate\Http\Request;

class TesteController extends Controller
{
    private $sheetToDatabaseService;
    private $spreadSheetService;

    /**
     * @param $sheetToDatabaseService
     * @param $spreadSheetService
     */
    public function __construct(SheetToDatabaseService $sheetToDatabaseService, SpreadSheetService $spreadSheetService)
    {
        $this->sheetToDatabaseService = $sheetToDatabaseService;
        $this->spreadSheetService = $spreadSheetService;
    }


    public function teste(Request $request){
        $sheet = $this->spreadSheetService->readFile($request);
        $this->sheetToDatabaseService->insertSheetToDatabase($sheet);
    }

    public function testeProvado(){
        BiologicalOccurrenceView::query()->insert(
            [
                'artificial:section' => 'teste',
                'dcterms:modified' => '2017-08-28 00:00:00',
                'informationWithheld' => null,
                'basisOfRecord' => 'PreservedSpecimen',
                'institutionCode' => 'UFMS',
                'collectionCode' => 'UFMS',
                'dcterms:bibliographicCitation' => null,
                'datasetName' => null,
                'artificial:shelfLocation' => 'P047',
                'artificial:flaskLocation' => 'AGRUPAR LOTE',
                'artificial:shippingGuide' => null,
                'catalogNumber' => 'ZUFMS-AMP00002',
                'otherCatalogNumbers' => 'CHAFB0180',
                'recordedBy' => 'BÉDA, A. F.',
                'recordNumber' => null,
                'preparations' => 'animal inteiro (ETOH 70%)',
                'individualCount' => '1',
                'sex' => null,
                'lifeStage' => null,
                'reproductiveCondition' => null,
                'establishmentMeans' => 'selvagem',
                'behavior' => null,
                'occurrenceRemarks' => null,
                'disposition' => 'em coleção',
                'associatedReferences' => null,
                'associatedMedia' => null,
                'occurrenceID' => 'br:UFMS:ZUFMS:ZUFMS-AMP00002',
                'associatedOccurrences' => null,
                'previousIdentifications' => null,
                'fieldNumber' => null,
                'day' => '15',
                'month' => '10',
                'year' => '1993',
                'eventTime' => null,
                'eventDate' => '1993-10-15',
                'verbatimEventDate' => '15.X.1993',
                'samplingProtocol' => null,
                'habitat' => null,
                'eventRemarks' => null,
                'fieldNotes' => null,
                'measurementRemarks' => null,
                'continent' => 'América do Sul',
                'country' => 'Brasil',
                'countryCode' => 'BRA',
                'verbatimLocality' => null,
                'stateProvince' => 'Mato Grosso do Sul',
                'county' => 'Aquidauana',
                'municipality' => null,
                'locality' => 'Córrego São João Dias',
                'decimalLatitude' => null,
                'decimalLongitude' => null,
                'verbatimLatitude' => null,
                'verbatimLongitude' => null,
                'coordinatePrecision' => null,
                'geodeticDatum' => null,
                'footprintWKT' => null,
                'minimumElevationInMeters' => null,
                'maximumElevationInMeters' => null,
                'waterBody' => null,
                'minimumDepthInMeters' => null,
                'maximumDepthInMeters' => null,
                'locationRemarks' => 'vazante do córrego',
                'identificationQualifier' => null,
                'identifiedBy' => 'BÉDA, A. F.',
                'dateIdentified' => '1993-10-15',
                'typeStatus' => null,
                'scientificName' => 'Physalaemus albonotatus',
                'scientificNameAuthorship' => '(Steindachner, 1864)',
                'acceptedNameUsage' => 'Physalaemus albonotatus (Steindachner, 1864)',
                'subgenus' => null,
                'genus' => 'Physalaemus',
                'family' => 'Leptodactylidae',
                'order' => 'Anura',
                'class' => 'Amphibia',
                'phylum' => 'Chordata',
                'kingdom' => 'Animalia',
                'artificial:subfamily' => 'Leiuperinae',
                'specificEpithet' => 'albonotatus',
                'infraspecificEpithet' => null,
                'taxonRank' => 'espécie',
                'taxonomicStatus' => 'aceito',
                'originalNameUsage' => null,
                'vernacularName' => null,
                'nomenclaturalCode' => 'ICZN',
                'nameAccordingTo' => 'Frost, Darrel R., 2021',
                'relationshipOfResource' => null,
            ]
        );
    }
}
