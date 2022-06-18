<?php

namespace App\DTO\Input;
use Opis\JsonSchema\Validator;

class OccurrenceInputDTO
{
    public ?string $artificialSection;
    public ?string $dctermsModified;
    public ?string $informationWithheld;
    public ?string $basisOfRecord;
    public ?string $institutionCode;
    public ?string $collectionCode;
    public ?string $dctermsBibliographicCitation;
    public ?string $datasetName;
    public ?string $artificialShelfLocation;
    public ?string $artificialFlaskLocation;
    public ?string $artificialShippingGuide;
    public ?string $catalogNumber;
    public ?string $otherCatalogNumbers;
    public ?string $recordedBy;
    public ?string $recordNumber;
    public ?string $preparations;
    public ?string $individualCount;
    public ?string $sex;
    public ?string $lifeStage;
    public ?string $reproductiveCondition;
    public ?string $establishmentMeans;
    public ?string $behavior;
    public ?string $occurrenceRemarks;
    public ?string $disposition;
    public ?string $associatedReferences;
    public ?string $associatedMedia;
    public ?string $occurrenceID;
    public ?string $associatedOccurrences;
    public ?string $previousIdentifications;
    public ?string $fieldNumber;
    public ?int $day;
    public ?int $month;
    public ?int $year;
    public ?string $eventTime;
    public ?string $eventDate;
    public ?string $verbatimEventDate;
    public ?string $samplingProtocol;
    public ?string $habitat;
    public ?string $eventRemarks;
    public ?string $fieldNotes;
    public ?string $measurementRemarks;
    public ?string $continent;
    public ?string $country;
    public ?string $countryCode;
    public ?string $verbatimLocality;
    public ?string $stateProvince;
    public ?string $county;
    public string $municipality;
    public ?string $locality;
    public ?float $decimalLatitude;
    public ?float $decimalLongitude;
    public ?string $verbatimLatitude;
    public ?string $verbatimLongitude;
    public ?float $coordinatePrecision;
    public ?string $geodeticDatum;
    public ?string $footprintWKT;
    public ?float $minimumElevationInMeters;
    public ?float $maximumElevationInMeters;
    public ?string $waterBody;
    public ?float $minimumDepthInMeters;
    public ?float $maximumDepthInMeters;
    public ?string $locationRemarks;
    public ?string $identificationQualifier;
    public ?string $identifiedBy;
    public ?string $dateIdentified;
    public ?string $typeStatus;
    public ?string $scientificName;
    public ?string $scientificNameAuthorship;
    public ?string $acceptedNameUsage;
    public ?string $subgenus;
    public ?string $genus;
    public ?string $artificialSubtribe;
    public ?string $artificialTribe;
    public ?string $artificialSubfamily;
    public ?string $family;
    public ?string $artificialSuperfamily;
    public ?string $artificialInfraorder;
    public ?string $artificialSuborder;//61542202191
    public ?string $order;
    public ?string $artificialSuperorder;
    public ?string $artificialSubclass;
    public ?string $class;
    public ?string $artificialSubphylum;
    public ?string $phylum;
    public ?string $kingdom;
    public ?string $specificEpithet;
    public ?string $infraspecificEpithet;
    public ?string $taxonRank;
    public ?string $taxonomicStatus;
    public ?string $originalNameUsage;
    public ?string $vernacularName;
    public ?string $nomenclaturalCode;
    public ?string $nameAccordingTo;
    public ?string $relationshipOfResource;

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
        $this->artificialInfraorder = $artifcialInfraorder;
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

    public static function constructEmpty(): OccurrenceInputDTO
    {
        return new OccurrenceInputDTO(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,0,0,0,null,null,null,null,null,null,null,null,null,null,null,null,null,null,"",null,0.0,0.0,null,null,0.0,null,null,0.0,0.0,null,0.0,0.0,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,"");
    }
    public static function validate($request,Validator $validator): \Opis\JsonSchema\ValidationResult
    {
        return $validator->validate($request,'https://inbio.ufms.br/zufms/zufmscore.schema.json');
    }
    public static function fromArray($jsonOccurrence, $mapper): OccurrenceInputDTO{
        $uniqueOccurrence = OccurrenceInputDTO::constructEmpty();

        $mapper->mapObject($jsonOccurrence, $uniqueOccurrence);
        $tempArrayOccurrence =  json_decode(json_encode($jsonOccurrence, true), true);




        // $uniqueOccurrence->informationWithheld = $tempArrayOccurrence["informationWithheld"] ?? "";
        // $uniqueOccurrence->basisOfRecord = $tempArrayOccurrence["basisOfRecord"] ?? "";
        // $uniqueOccurrence->institutionCode = $tempArrayOccurrence["institutionCode"] ?? "";
        // $uniqueOccurrence->collectionCode = $tempArrayOccurrence["collectionCode"] ?? "";
        // $uniqueOccurrence->datasetName = $tempArrayOccurrence["datasetName"] ?? "";
        // $uniqueOccurrence->catalogNumber = $tempArrayOccurrence["catalogNumber"] ?? "";
        // $uniqueOccurrence->otherCatalogNumbers = $tempArrayOccurrence["otherCatalogNumbers"] ?? "";
        // $uniqueOccurrence->recordedBy = $tempArrayOccurrence["recordedBy"] ?? "";
        // $uniqueOccurrence->recordNumber = $tempArrayOccurrence["recordNumber"] ?? "";
        // $uniqueOccurrence->preparations = $tempArrayOccurrence["preparations"] ?? "";
        // $uniqueOccurrence->individualCount = $tempArrayOccurrence["individualCount"] ?? "";
        // $uniqueOccurrence->sex = $tempArrayOccurrence["sex"] ?? "";
        // $uniqueOccurrence->lifeStage = $tempArrayOccurrence["lifeStage"] ?? "";
        // $uniqueOccurrence->reproductiveCondition = $tempArrayOccurrence["reproductiveCondition"] ?? "";
        // $uniqueOccurrence->establishmentMeans = $tempArrayOccurrence["establishmentMeans"] ?? "";
        // $uniqueOccurrence->behavior = $tempArrayOccurrence["behavior"] ?? "";
        // $uniqueOccurrence->occurrenceRemarks = $tempArrayOccurrence["occurrenceRemarks"] ?? "";
        // $uniqueOccurrence->disposition = $tempArrayOccurrence["disposition"] ?? "";
        // $uniqueOccurrence->associatedReferences = $tempArrayOccurrence["associatedReferences"] ?? "";
        // $uniqueOccurrence->associatedMedia = $tempArrayOccurrence["associatedMedia"] ?? "";
        // $uniqueOccurrence->occurrenceID = $tempArrayOccurrence["occurrenceID"] ?? "";
        // $uniqueOccurrence->associatedOccurrences = $tempArrayOccurrence["associatedOccurrences"] ?? "";
        // $uniqueOccurrence->previousIdentifications = $tempArrayOccurrence["previousIdentifications"] ?? "";
        // $uniqueOccurrence->fieldNumber = $tempArrayOccurrence["fieldNumber"] ?? "";
        // $uniqueOccurrence->day = $tempArrayOccurrence["day"] ?? "";
        // $uniqueOccurrence->month = $tempArrayOccurrence["month"] ?? "";
        // $uniqueOccurrence->year = $tempArrayOccurrence["year"] ?? "";
        // $uniqueOccurrence->eventTime = $tempArrayOccurrence["eventTime"] ?? "";
        // $uniqueOccurrence->eventDate = $tempArrayOccurrence["eventDate"] ?? "";
        // $uniqueOccurrence->verbatimEventDate = $tempArrayOccurrence["verbatimEventDate"] ?? "";
        // $uniqueOccurrence->samplingProtocol = $tempArrayOccurrence["samplingProtocol"] ?? "";
        // $uniqueOccurrence->habitat = $tempArrayOccurrence["habitat"] ?? "";
        // $uniqueOccurrence->eventRemarks = $tempArrayOccurrence["eventRemarks"] ?? "";
        // $uniqueOccurrence->fieldNotes = $tempArrayOccurrence["fieldNotes"] ?? "";
        // $uniqueOccurrence->measurementRemarks = $tempArrayOccurrence["measurementRemarks"] ?? "";
        // $uniqueOccurrence->continent = $tempArrayOccurrence["continent"] ?? "";
        // $uniqueOccurrence->country = $tempArrayOccurrence["country"] ?? "";
        // $uniqueOccurrence->countryCode = $tempArrayOccurrence["countryCode"] ?? "";
        // $uniqueOccurrence->verbatimLocality = $tempArrayOccurrence["verbatimLocality"] ?? "";
        // $uniqueOccurrence->stateProvince = $tempArrayOccurrence["stateProvince"] ?? "";
        // $uniqueOccurrence->county = $tempArrayOccurrence["county"] ?? "";
        // $uniqueOccurrence->municipality = $tempArrayOccurrence["municipality"] ?? "";
        // $uniqueOccurrence->locality = $tempArrayOccurrence["locality"] ?? "";
        // $uniqueOccurrence->decimalLatitude = $tempArrayOccurrence["decimalLatitude"] ?? "";
        // $uniqueOccurrence->decimalLongitude = $tempArrayOccurrence["decimalLongitude"] ?? "";
        // $uniqueOccurrence->verbatimLatitude = $tempArrayOccurrence["verbatimLatitude"] ?? "";
        // $uniqueOccurrence->verbatimLongitude = $tempArrayOccurrence["verbatimLongitude"] ?? "";
        // $uniqueOccurrence->coordinatePrecision = $tempArrayOccurrence["coordinatePrecision"] ?? "";
        // $uniqueOccurrence->geodeticDatum = $tempArrayOccurrence["geodeticDatum"] ?? "";
        // $uniqueOccurrence->footprintWKT = $tempArrayOccurrence["footprintWKT"] ?? "";
        // $uniqueOccurrence->minimumElevationInMeters = $tempArrayOccurrence["minimumElevationInMeters"] ?? 0.0;
        // $uniqueOccurrence->maximumElevationInMeters = $tempArrayOccurrence["maximumElevationInMeters"] ?? 0.0;
        // $uniqueOccurrence->waterBody = $tempArrayOccurrence["waterBody"] ?? "";
        // $uniqueOccurrence->minimumDepthInMeters = $tempArrayOccurrence["minimumDepthInMeters"] ?? 0.0;
        // $uniqueOccurrence->maximumDepthInMeters = $tempArrayOccurrence["maximumDepthInMeters"] ?? 0.0;
        // $uniqueOccurrence->locationRemarks = $tempArrayOccurrence["locationRemarks"] ?? "";
        // $uniqueOccurrence->identificationQualifier = $tempArrayOccurrence["identificationQualifier"] ?? "";
        // $uniqueOccurrence->identifiedBy = $tempArrayOccurrence["identifiedBy"] ?? "";
        // $uniqueOccurrence->dateIdentified = $tempArrayOccurrence["dateIdentified"] ?? null;
        // $uniqueOccurrence->typeStatus = $tempArrayOccurrence["typeStatus"] ?? "";
        // $uniqueOccurrence->scientificName = $tempArrayOccurrence["scientificName"] ?? "";
        // $uniqueOccurrence->scientificNameAuthorship = $tempArrayOccurrence["scientificNameAuthorship"] ?? "";
        // $uniqueOccurrence->acceptedNameUsage = $tempArrayOccurrence["acceptedNameUsage"] ?? "";
        // $uniqueOccurrence->subgenus = $tempArrayOccurrence["subgenus"] ?? "";
        // $uniqueOccurrence->genus = $tempArrayOccurrence["genus"] ?? "";
        // $uniqueOccurrence->family = $tempArrayOccurrence["family"] ?? "";
        // $uniqueOccurrence->order = $tempArrayOccurrence["order"] ?? "";
        // $uniqueOccurrence->class = $tempArrayOccurrence["class"] ?? "";
        // $uniqueOccurrence->phylum = $tempArrayOccurrence["phylum"] ?? "";
        // $uniqueOccurrence->kingdom = $tempArrayOccurrence["kingdom"] ?? "";
        // $uniqueOccurrence->specificEpithet = $tempArrayOccurrence["specificEpithet"] ?? "";
        // $uniqueOccurrence->infraspecificEpithet = $tempArrayOccurrence["infraspecificEpithet"] ?? "";
        // $uniqueOccurrence->taxonRank = $tempArrayOccurrence["taxonRank"] ?? "";
        // $uniqueOccurrence->taxonomicStatus = $tempArrayOccurrence["taxonomicStatus"] ?? "";
        // $uniqueOccurrence->originalNameUsage = $tempArrayOccurrence["originalNameUsage"] ?? "";
        // $uniqueOccurrence->vernacularName = $tempArrayOccurrence["vernacularName"] ?? "";
        // $uniqueOccurrence->nomenclaturalCode = $tempArrayOccurrence["nomenclaturalCode"] ?? "";
        // $uniqueOccurrence->nameAccordingTo = $tempArrayOccurrence["nameAccordingTo"] ?? "";
        // $uniqueOccurrence->relationshipOfResource = $tempArrayOccurrence["relationshipOfResource"] ?? "";


        $uniqueOccurrence->dctermsModified = $tempArrayOccurrence["dcterms:modified"] ?? null;
        $uniqueOccurrence->artificialSection = $tempArrayOccurrence["artificial:section"] ?? null;
        $uniqueOccurrence->artificialShelfLocation = $tempArrayOccurrence["artificial:shelfLocation"] ?? null;
        $uniqueOccurrence->artificialFlaskLocation = $tempArrayOccurrence["artificial:flaskLocation"] ?? null;
        $uniqueOccurrence->artificialShippingGuide = $tempArrayOccurrence["artificial:shippingGuide"] ?? null;
        $uniqueOccurrence->artificialSubfamily = $tempArrayOccurrence["artificial:subfamily"] ?? null;
        $uniqueOccurrence->artificialSubtribe = $tempArrayOccurrence["artificial:subtribe"] ?? null;
        $uniqueOccurrence->artificialTribe = $tempArrayOccurrence["artificial:tribe"] ?? null;
        $uniqueOccurrence->artificialSuperfamily = $tempArrayOccurrence["artificial:superfamily"] ?? null;
        $uniqueOccurrence->artificialInfraorder = $tempArrayOccurrence["artificial:infraorder"] ?? null;
        $uniqueOccurrence->artificialSuperorder = $tempArrayOccurrence["artificial:superorder"] ?? null;
        $uniqueOccurrence->artificialSuborder = $tempArrayOccurrence["artificial:suborder"] ?? null;
        $uniqueOccurrence->artificialSubclass = $tempArrayOccurrence["artificial:subclass"] ?? null;
        $uniqueOccurrence->artificialSubphylum = $tempArrayOccurrence["artificial:subphylum"] ?? null;
        $uniqueOccurrence->dctermsBibliographicCitation = $tempArrayOccurrence["dcterms:bibliographicCitation"] ?? null;


        return $uniqueOccurrence;
    }
    public function toArray(): array{
        $uniqueOccurrence = $this;
        $tempArrayOccurrence = (array) $this;
        $tempArrayOccurrence["dcterms:modified"] = $uniqueOccurrence->dctermsModified;
        $tempArrayOccurrence["artificial:section"] = $uniqueOccurrence->artificialSection;
        $tempArrayOccurrence["artificial:shelfLocation"] = $uniqueOccurrence->artificialShelfLocation;
        $tempArrayOccurrence["artificial:flaskLocation"] = $uniqueOccurrence->artificialFlaskLocation;
        $tempArrayOccurrence["artificial:shippingGuide"] = $uniqueOccurrence->artificialShippingGuide;
        $tempArrayOccurrence["artificial:subfamily"] = $uniqueOccurrence->artificialSubfamily;
        $tempArrayOccurrence["artificial:subtribe"] = $uniqueOccurrence->artificialSubtribe;
        $tempArrayOccurrence["artificial:tribe"] = $uniqueOccurrence->artificialTribe;
        $tempArrayOccurrence["artificial:superfamily"] = $uniqueOccurrence->artificialSuperfamily;
        $tempArrayOccurrence["artificial:infraorder"] = $uniqueOccurrence->artificialInfraorder;
        $tempArrayOccurrence["artificial:superorder"] = $uniqueOccurrence->artificialSuperorder;
        $tempArrayOccurrence["artificial:suborder"] = $uniqueOccurrence->artificialSuborder;
        $tempArrayOccurrence["artificial:subclass"] = $uniqueOccurrence->artificialSubclass;
        $tempArrayOccurrence["artificial:subphylum"] = $uniqueOccurrence->artificialSubphylum;
        $tempArrayOccurrence["dcterms:bibliographicCitation"] = $uniqueOccurrence->dctermsBibliographicCitation;



        unset($tempArrayOccurrence["dctermsModified"]);
        unset($tempArrayOccurrence["artificialSection"]);
        unset($tempArrayOccurrence["artificialShelfLocation"]);
        unset($tempArrayOccurrence["artificialFlaskLocation"]);
        unset($tempArrayOccurrence["artificialShippingGuide"]);
        unset($tempArrayOccurrence["artificialSubfamily"]);
        unset($tempArrayOccurrence["artificialSubtribe"]);
        unset($tempArrayOccurrence["artificialTribe"]);
        unset($tempArrayOccurrence["artificialSuperfamily"]);
        unset($tempArrayOccurrence["artificialInfraorder"]);
        unset($tempArrayOccurrence["artificialSuperorder"]);
        unset($tempArrayOccurrence["artificialSuborder"]);
        unset($tempArrayOccurrence["artificialSubclass"]);
        unset($tempArrayOccurrence["artificialSubphylum"]);
        unset($tempArrayOccurrence["dctermsBibliographicCitation"]);
        unset($tempArrayOccurrence["eventTime"]);
        unset($tempArrayOccurrence["eventDate"]);
        return $tempArrayOccurrence;
    }
}
