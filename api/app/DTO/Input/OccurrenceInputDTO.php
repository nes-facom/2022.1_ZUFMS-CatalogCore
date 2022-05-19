<?php

namespace App\DTO\Input;

use Illuminate\Http\Request;

class OccurrenceInputDTO
{
    private $artificialSection;
    private $dctermsModified;
    private $informationWithheld;
    private $basisOfRecord;
    private $institutionCode;
    private $collectionCode;
    private $dctermsBibliographicCitation;
    private $datasetName;
    private $artificialShelfLocation;
    private $artificialFlaskLocation;
    private $artificialShippingGuide;
    private $catalogNumber;
    private $otherCatalogNumbers;
    private $recordedBy;
    private $recordNumber;
    private $preparations;
    private $individualCount;
    private $sex;
    private $lifeStage;
    private $reproductiveCondition;
    private $establishmentMeans;
    private $behavior;
    private $occurrenceRemarks;
    private $disposition;
    private $associatedReferences;
    private $associatedMedia;
    private $occurrenceID;
    private $associatedOccurrences;
    private $previousIdentifications;
    private $fieldNumber;
    private $day;
    private $month;
    private $year;
    private $eventTime;
    private $eventDate;
    private $verbatimEventDate;
    private $samplingProtocol;
    private $habitat;
    private $eventRemarks;
    private $fieldNotes;
    private $measurementRemarks;
    private $continent;
    private $country;
    private $countryCode;
    private $verbatimLocality;
    private $stateProvince;
    private $county;
    private $municipality;
    private $locality;
    private $decimalLatitude;
    private $decimalLongitude;
    private $verbatimLatitude;
    private $verbatimLongitude;
    private $coordinatePrecision;
    private $geodeticDatum;
    private $footprintWKT;
    private $minimumElevationInMeters;
    private $maximumElevationInMeters;
    private $waterBody;
    private $minimumDepthInMeters;
    private $maximumDepthInMeters;
    private $locationRemarks;
    private $identificationQualifier;
    private $identifiedBy;
    private $dateIdentified;
    private $typeStatus;
    private $scientificName;
    private $scientificNameAuthorship;
    private $acceptedNameUsage;
    private $subgenus;
    private $genus;
    private $artificialSubtribe;
    private $artificialTribe;
    private $artificialSubfamily;
    private $family;
    private $artificialSuperfamily;
    private $artifcialInfraorder;
    private $artificialSuborder;
    private $order;
    private $artificialSuperorder;
    private $artificialSubclass;
    private $class;
    private $artificialSubphylum;
    private $phylum;
    private $kingdom;
    private $specificEpithet;
    private $infraspecificEpithet;
    private $taxonRank;
    private $taxonomicStatus;
    private $originalNameUsage;
    private $vernacularName;
    private $nomenclaturalCode;
    private $nameAccordingTo;
    private $relationshipOfResource;

    /**
     * @param $artificialSection
     * @param $dctermsModified
     * @param $informationWithheld
     * @param $basisOfRecord
     * @param $institutionCode
     * @param $collectionCode
     * @param $dctermsBibliographicCitation
     * @param $datasetName
     * @param $artificialShelfLocation
     * @param $artificialFlaskLocation
     * @param $artificialShippingGuide
     * @param $catalogNumber
     * @param $otherCatalogNumbers
     * @param $recordedBy
     * @param $recordNumber
     * @param $preparations
     * @param $individualCount
     * @param $sex
     * @param $lifeStage
     * @param $reproductiveCondition
     * @param $establishmentMeans
     * @param $behavior
     * @param $occurrenceRemarks
     * @param $disposition
     * @param $associatedReferences
     * @param $associatedMedia
     * @param $occurrenceID
     * @param $associatedOccurrences
     * @param $previousIdentifications
     * @param $fieldNumber
     * @param $day
     * @param $month
     * @param $year
     * @param $eventTime
     * @param $eventDate
     * @param $verbatimEventDate
     * @param $samplingProtocol
     * @param $habitat
     * @param $eventRemarks
     * @param $fieldNotes
     * @param $measurementRemarks
     * @param $continent
     * @param $country
     * @param $countryCode
     * @param $verbatimLocality
     * @param $stateProvince
     * @param $county
     * @param $municipality
     * @param $locality
     * @param $decimalLatitude
     * @param $decimalLongitude
     * @param $verbatimLatitude
     * @param $verbatimLongitude
     * @param $coordinatePrecision
     * @param $geodeticDatum
     * @param $footprintWKT
     * @param $minimumElevationInMeters
     * @param $maximumElevationInMeters
     * @param $waterBody
     * @param $minimumDepthInMeters
     * @param $maximumDepthInMeters
     * @param $locationRemarks
     * @param $identificationQualifier
     * @param $identifiedBy
     * @param $dateIdentified
     * @param $typeStatus
     * @param $scientificName
     * @param $scientificNameAuthorship
     * @param $acceptedNameUsage
     * @param $subgenus
     * @param $genus
     * @param $artificialSubtribe
     * @param $artificialTribe
     * @param $artificialSubfamily
     * @param $family
     * @param $artificialSuperfamily
     * @param $artifcialInfraorder
     * @param $artificialSuborder
     * @param $order
     * @param $artificialSuperorder
     * @param $artificialSubclass
     * @param $class
     * @param $artificialSubphylum
     * @param $phylum
     * @param $kingdom
     * @param $specificEpithet
     * @param $infraspecificEpithet
     * @param $taxonRank
     * @param $taxonomicStatus
     * @param $originalNameUsage
     * @param $vernacularName
     * @param $nomenclaturalCode
     * @param $nameAccordingTo
     * @param $relationshipOfResource
     */
    public function __construct($artificialSection, $dctermsModified, $informationWithheld, $basisOfRecord, $institutionCode, $collectionCode, $dctermsBibliographicCitation, $datasetName, $artificialShelfLocation, $artificialFlaskLocation, $artificialShippingGuide, $catalogNumber, $otherCatalogNumbers, $recordedBy, $recordNumber, $preparations, $individualCount, $sex, $lifeStage, $reproductiveCondition, $establishmentMeans, $behavior, $occurrenceRemarks, $disposition, $associatedReferences, $associatedMedia, $occurrenceID, $associatedOccurrences, $previousIdentifications, $fieldNumber, $day, $month, $year, $eventTime, $eventDate, $verbatimEventDate, $samplingProtocol, $habitat, $eventRemarks, $fieldNotes, $measurementRemarks, $continent, $country, $countryCode, $verbatimLocality, $stateProvince, $county, $municipality, $locality, $decimalLatitude, $decimalLongitude, $verbatimLatitude, $verbatimLongitude, $coordinatePrecision, $geodeticDatum, $footprintWKT, $minimumElevationInMeters, $maximumElevationInMeters, $waterBody, $minimumDepthInMeters, $maximumDepthInMeters, $locationRemarks, $identificationQualifier, $identifiedBy, $dateIdentified, $typeStatus, $scientificName, $scientificNameAuthorship, $acceptedNameUsage, $subgenus, $genus, $artificialSubtribe, $artificialTribe, $artificialSubfamily, $family, $artificialSuperfamily, $artifcialInfraorder, $artificialSuborder, $order, $artificialSuperorder, $artificialSubclass, $class, $artificialSubphylum, $phylum, $kingdom, $specificEpithet, $infraspecificEpithet, $taxonRank, $taxonomicStatus, $originalNameUsage, $vernacularName, $nomenclaturalCode, $nameAccordingTo, $relationshipOfResource)
    {
        $this->artificialSection = $artificialSection;
        $this->dctermsModified = $dctermsModified;
        $this->informationWithheld = $informationWithheld;
        $this->basisOfRecord = $basisOfRecord;
        $this->institutionCode = $institutionCode;
        $this->collectionCode = $collectionCode;
        $this->dctermsBibliographicCitation = $dctermsBibliographicCitation;
        $this->datasetName = $datasetName;
        $this->artificialShelfLocation = $artificialShelfLocation;
        $this->artificialFlaskLocation = $artificialFlaskLocation;
        $this->artificialShippingGuide = $artificialShippingGuide;
        $this->catalogNumber = $catalogNumber;
        $this->otherCatalogNumbers = $otherCatalogNumbers;
        $this->recordedBy = $recordedBy;
        $this->recordNumber = $recordNumber;
        $this->preparations = $preparations;
        $this->individualCount = $individualCount;
        $this->sex = $sex;
        $this->lifeStage = $lifeStage;
        $this->reproductiveCondition = $reproductiveCondition;
        $this->establishmentMeans = $establishmentMeans;
        $this->behavior = $behavior;
        $this->occurrenceRemarks = $occurrenceRemarks;
        $this->disposition = $disposition;
        $this->associatedReferences = $associatedReferences;
        $this->associatedMedia = $associatedMedia;
        $this->occurrenceID = $occurrenceID;
        $this->associatedOccurrences = $associatedOccurrences;
        $this->previousIdentifications = $previousIdentifications;
        $this->fieldNumber = $fieldNumber;
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
        $this->eventTime = $eventTime;
        $this->eventDate = $eventDate;
        $this->verbatimEventDate = $verbatimEventDate;
        $this->samplingProtocol = $samplingProtocol;
        $this->habitat = $habitat;
        $this->eventRemarks = $eventRemarks;
        $this->fieldNotes = $fieldNotes;
        $this->measurementRemarks = $measurementRemarks;
        $this->continent = $continent;
        $this->country = $country;
        $this->countryCode = $countryCode;
        $this->verbatimLocality = $verbatimLocality;
        $this->stateProvince = $stateProvince;
        $this->county = $county;
        $this->municipality = $municipality;
        $this->locality = $locality;
        $this->decimalLatitude = $decimalLatitude;
        $this->decimalLongitude = $decimalLongitude;
        $this->verbatimLatitude = $verbatimLatitude;
        $this->verbatimLongitude = $verbatimLongitude;
        $this->coordinatePrecision = $coordinatePrecision;
        $this->geodeticDatum = $geodeticDatum;
        $this->footprintWKT = $footprintWKT;
        $this->minimumElevationInMeters = $minimumElevationInMeters;
        $this->maximumElevationInMeters = $maximumElevationInMeters;
        $this->waterBody = $waterBody;
        $this->minimumDepthInMeters = $minimumDepthInMeters;
        $this->maximumDepthInMeters = $maximumDepthInMeters;
        $this->locationRemarks = $locationRemarks;
        $this->identificationQualifier = $identificationQualifier;
        $this->identifiedBy = $identifiedBy;
        $this->dateIdentified = $dateIdentified;
        $this->typeStatus = $typeStatus;
        $this->scientificName = $scientificName;
        $this->scientificNameAuthorship = $scientificNameAuthorship;
        $this->acceptedNameUsage = $acceptedNameUsage;
        $this->subgenus = $subgenus;
        $this->genus = $genus;
        $this->artificialSubtribe = $artificialSubtribe;
        $this->artificialTribe = $artificialTribe;
        $this->artificialSubfamily = $artificialSubfamily;
        $this->family = $family;
        $this->artificialSuperfamily = $artificialSuperfamily;
        $this->artifcialInfraorder = $artifcialInfraorder;
        $this->artificialSuborder = $artificialSuborder;
        $this->order = $order;
        $this->artificialSuperorder = $artificialSuperorder;
        $this->artificialSubclass = $artificialSubclass;
        $this->class = $class;
        $this->artificialSubphylum = $artificialSubphylum;
        $this->phylum = $phylum;
        $this->kingdom = $kingdom;
        $this->specificEpithet = $specificEpithet;
        $this->infraspecificEpithet = $infraspecificEpithet;
        $this->taxonRank = $taxonRank;
        $this->taxonomicStatus = $taxonomicStatus;
        $this->originalNameUsage = $originalNameUsage;
        $this->vernacularName = $vernacularName;
        $this->nomenclaturalCode = $nomenclaturalCode;
        $this->nameAccordingTo = $nameAccordingTo;
        $this->relationshipOfResource = $relationshipOfResource;
    }

    public static function fromRequest(Request $request)
    {
        return new OccurrenceInputDTO(
            $request->get('artificial:section'),
            $request->get('dcterms:modified'),
            $request->get('informationWithheld'),
            $request->get('basisOfRecord'),
            $request->get('institutionCode'),
            $request->get('collectionCode'),
            $request->get('dcterms:bibliographicCitation'),
            $request->get('datasetName'),
            $request->get('artificial:shelfLocation'),
            $request->get('artificial:flaskLocation'),
            $request->get('artificial:shippingGuide'),
            $request->get('catalogNumber'),
            $request->get('otherCatalogNumbers'),
            $request->get('recordedBy'),
            $request->get('recordNumber'),
            $request->get('preparations'),
            $request->get('individualCount'),
            $request->get('sex'),
            $request->get('lifeStage'),
            $request->get('reproductiveCondition'),
            $request->get('establishmentMeans'),
            $request->get('behavior'),
            $request->get('occurrenceRemarks'),
            $request->get('disposition'),
            $request->get('associatedReferences'),
            $request->get('associatedMedia'),
            $request->get('occurrenceID'),
            $request->get('associatedOccurrences'),
            $request->get('previousIdentifications'),
            $request->get('fieldNumber'),
            $request->get('day'),
            $request->get('month'),
            $request->get('year'),
            $request->get('eventTime'),
            $request->get('eventDate'),
            $request->get('verbatimEventDate'),
            $request->get('samplingProtocol'),
            $request->get('habitat'),
            $request->get('eventRemarks'),
            $request->get('fieldNotes'),
            $request->get('measurementRemarks'),
            $request->get('continent'),
            $request->get('country'),
            $request->get('countryCode'),
            $request->get('verbatimLocality'),
            $request->get('stateProvince'),
            $request->get('county'),
            $request->get('municipality'),
            $request->get('locality'),
            $request->get('decimalLatitude'),
            $request->get('decimalLongitude'),
            $request->get('verbatimLatitude'),
            $request->get('verbatimLongitude'),
            $request->get('coordinatePrecision'),
            $request->get('geodeticDatum'),
            $request->get('footprintWKT'),
            $request->get('minimumElevationInMeters'),
            $request->get('maximumElevationInMeters'),
            $request->get('waterBody'),
            $request->get('minimumDepthInMeters'),
            $request->get('maximumDepthInMeters'),
            $request->get('locationRemarks'),
            $request->get('identificationQualifier'),
            $request->get('identifiedBy'),
            $request->get('dateIdentified'),
            $request->get('typeStatus'),
            $request->get('scientificName'),
            $request->get('scientificNameAuthorship'),
            $request->get('acceptedNameUsage'),
            $request->get('subgenus'),
            $request->get('genus'),
            $request->get('artificial:subtribe'),
            $request->get('artificial:tribe'),
            $request->get('artificial:subfamily'),
            $request->get('family'),
            $request->get('artificial:superfamily'),
            $request->get('artifcial:infraorder'),
            $request->get('artificial:suborder'),
            $request->get('order'),
            $request->get('artificial:superorder'),
            $request->get('artificial:subclass'),
            $request->get('class'),
            $request->get('artificial:subphylum'),
            $request->get('phylum'),
            $request->get('kingdom'),
            $request->get('specificEpithet'),
            $request->get('infraspecificEpithet'),
            $request->get('taxonRank'),
            $request->get('taxonomicStatus'),
            $request->get('originalNameUsage'),
            $request->get('vernacularName'),
            $request->get('nomenclaturalCode'),
            $request->get('nameAccordingTo'),
            $request->get('relationshipOfResource')
        );
    }
}
