CREATE TABLE "artificial:section" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "basisOfRecord" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "institutionCode" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "collectionCode" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "datasetName" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "artificial:shelfLocation" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "artificial:flaskLocation" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "recordedBy" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "recordedBy_biological_occurrence" (
  "recordedBy_id" int,
  "biological_occurrence_id" text,
  "recordNumber" int,
  PRIMARY KEY ("recordedBy_id", "biological_occurrence_id")
);

CREATE TABLE "preparations" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "sex" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "lifeStage" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "reproductiveCondition" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "establishmentMeans" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "behavior" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "disposition" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "associatedOccurrences" (
  "biological_occurrence_id" text,
  "value" text,
  PRIMARY KEY ("biological_occurrence_id", "value")
);

CREATE TABLE "habitat" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "continent" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "country" (
  "countryCode" text PRIMARY KEY,
  "value" text NOT NULL,
  "continent_id" int
);

CREATE TABLE "verbatimLocality" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "stateProvince" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "country_id" text
);

CREATE TABLE "county" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "stateProvince_id" int
);

CREATE TABLE "municipality" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "county_id" int
);

CREATE TABLE "geodeticDatum" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "locality" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE,
  "decimalLatitude" text UNIQUE,
  "decimalLongitude" text UNIQUE,
  "verbatimLatitude" text,
  "verbatimLongitude" text,
  "coordinatePrecision" text,
  "geodeticDatum_id" int,
  "footprintWKT" text,
  "minimumElevationInMeters" text,
  "maximumElevationInMeters" text,
  "municipality_id" int,
  "verbatimLocality_id" int
);

CREATE TABLE "waterBody" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "identificationQualifier" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "identifiedBy" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "identifiedBy_biological_occurrence" (
  "biological_occurrence_id" text PRIMARY KEY,
  "value" int,
  "dateIdentified" date
);

CREATE TABLE "kingdom" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "phylum" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "kingdom_id" int
);

CREATE TABLE "class" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "phylum_id" int
);

CREATE TABLE "order" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "class_id" int
);

CREATE TABLE "family" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "order_id" int
);

CREATE TABLE "artificial:subfamily" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "family_id" int
);

CREATE TABLE "genus" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "family_id" int
);

CREATE TABLE "subgenus" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "genus_id" int
);

CREATE TABLE "taxonomicStatus" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "nomenclaturalCode" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "nameAccordingTo" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL
);

CREATE TABLE "specie" (
  "id" serial PRIMARY KEY,
  "typeStatus" text,
  "scientificName" text,
  "scientificNameAuthorship" text,
  "acceptedNameUsage" text,
  "subgenus_id" int,
  "artificial:subfamily_id" int,
  "specificEpithet" text,
  "infraspecificEpithet" text,
  "taxonRank" text,
  "taxonomicStatus_id" int,
  "originalNameUsage" text,
  "vernacularName" text,
  "nomenclaturalCode_id" int,
  "nameAccordingTo_id" int
);

CREATE TABLE "biological_occurrence" (
  "occurrenceID" text PRIMARY KEY,
  "artificial:section_id" int NOT NULL,
  "dcterms:modified" timestamp NOT NULL,
  "informationWithheld" text,
  "basisOfRecord_id" int,
  "institutionCode_id" int,
  "collectionCode_id" int,
  "dcterms:bibliographicCitation" text,
  "datasetName_id" int,
  "artificial:shelfLocation_id" int,
  "artificial:flaskLocation_id" int,
  "artificial:shippingGuide" text,
  "catalogNumber" text UNIQUE,
  "otherCatalogNumbers" text,
  "preparations_id" int,
  "individualCount" int,
  "sex_id" int,
  "lifeStage_id" int,
  "reproductiveCondition_id" int,
  "establishmentMeans_id" int,
  "behavior_id" int,
  "occurrenceRemarks" text,
  "disposition_id" int,
  "associatedReferences" text,
  "associatedMedia" text,
  "previousIdentifications" text,
  "fieldNumber" text,
  "day" text,
  "month" text,
  "year" text,
  "eventTime" interval,
  "eventDate" interval,
  "verbatimEventDate" text,
  "samplingProtocol" text,
  "habitat_id" int,
  "eventRemarks" text,
  "fieldNotes" text,
  "measurementRemarks" text,
  "specie_id" int,
  "locality_id" int,
  "waterBody_id" int,
  "minimumDepthInMeters" text,
  "maximumDepthInMeters" text,
  "locationRemarks" text,
  "identificationQualifier_id" int,
  "identifiedBy_id" int,
  "relationshipOfResource" text
);

ALTER TABLE "recordedBy_biological_occurrence" ADD FOREIGN KEY ("recordedBy_id") REFERENCES "recordedBy" ("id");

ALTER TABLE "recordedBy_biological_occurrence" ADD FOREIGN KEY ("biological_occurrence_id") REFERENCES "biological_occurrence" ("occurrenceID");

ALTER TABLE "associatedOccurrences" ADD FOREIGN KEY ("biological_occurrence_id") REFERENCES "biological_occurrence" ("occurrenceID");

ALTER TABLE "associatedOccurrences" ADD FOREIGN KEY ("value") REFERENCES "biological_occurrence" ("catalogNumber");

ALTER TABLE "country" ADD FOREIGN KEY ("continent_id") REFERENCES "continent" ("id");

ALTER TABLE "stateProvince" ADD FOREIGN KEY ("country_id") REFERENCES "country" ("countryCode");

ALTER TABLE "county" ADD FOREIGN KEY ("stateProvince_id") REFERENCES "stateProvince" ("id");

ALTER TABLE "municipality" ADD FOREIGN KEY ("county_id") REFERENCES "county" ("id");

ALTER TABLE "locality" ADD FOREIGN KEY ("geodeticDatum_id") REFERENCES "geodeticDatum" ("id");

ALTER TABLE "locality" ADD FOREIGN KEY ("municipality_id") REFERENCES "municipality" ("id");

ALTER TABLE "locality" ADD FOREIGN KEY ("verbatimLocality_id") REFERENCES "verbatimLocality" ("id");

ALTER TABLE "identifiedBy_biological_occurrence" ADD FOREIGN KEY ("biological_occurrence_id") REFERENCES "biological_occurrence" ("occurrenceID");

ALTER TABLE "identifiedBy_biological_occurrence" ADD FOREIGN KEY ("value") REFERENCES "identifiedBy" ("id");

ALTER TABLE "phylum" ADD FOREIGN KEY ("kingdom_id") REFERENCES "kingdom" ("id");

ALTER TABLE "class" ADD FOREIGN KEY ("phylum_id") REFERENCES "phylum" ("id");

ALTER TABLE "order" ADD FOREIGN KEY ("class_id") REFERENCES "class" ("id");

ALTER TABLE "family" ADD FOREIGN KEY ("order_id") REFERENCES "order" ("id");

ALTER TABLE "artificial:subfamily" ADD FOREIGN KEY ("family_id") REFERENCES "family" ("id");

ALTER TABLE "genus" ADD FOREIGN KEY ("family_id") REFERENCES "family" ("id");

ALTER TABLE "subgenus" ADD FOREIGN KEY ("genus_id") REFERENCES "genus" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("subgenus_id") REFERENCES "subgenus" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:subfamily_id") REFERENCES "artificial:subfamily" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("taxonomicStatus_id") REFERENCES "taxonomicStatus" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("nomenclaturalCode_id") REFERENCES "nomenclaturalCode" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("nameAccordingTo_id") REFERENCES "nameAccordingTo" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("artificial:section_id") REFERENCES "artificial:section" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("basisOfRecord_id") REFERENCES "basisOfRecord" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("institutionCode_id") REFERENCES "institutionCode" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("collectionCode_id") REFERENCES "collectionCode" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("datasetName_id") REFERENCES "datasetName" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("artificial:shelfLocation_id") REFERENCES "artificial:shelfLocation" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("artificial:flaskLocation_id") REFERENCES "artificial:flaskLocation" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("preparations_id") REFERENCES "preparations" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("sex_id") REFERENCES "sex" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("lifeStage_id") REFERENCES "lifeStage" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("reproductiveCondition_id") REFERENCES "reproductiveCondition" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("establishmentMeans_id") REFERENCES "establishmentMeans" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("behavior_id") REFERENCES "behavior" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("disposition_id") REFERENCES "disposition" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("habitat_id") REFERENCES "habitat" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("specie_id") REFERENCES "specie" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("locality_id") REFERENCES "locality" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("waterBody_id") REFERENCES "waterBody" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("identificationQualifier_id") REFERENCES "identificationQualifier" ("id");

ALTER TABLE "biological_occurrence" ADD FOREIGN KEY ("identifiedBy_id") REFERENCES "identifiedBy" ("id");


COMMENT ON COLUMN "locality"."verbatimLatitude" IS 'virtual';

COMMENT ON COLUMN "locality"."verbatimLongitude" IS 'virtual';

COMMENT ON COLUMN "locality"."minimumElevationInMeters" IS 'validar normalizacao';

COMMENT ON COLUMN "locality"."maximumElevationInMeters" IS 'validar normalizacao';

COMMENT ON COLUMN "specie"."acceptedNameUsage" IS 'virtual';

COMMENT ON COLUMN "specie"."taxonRank" IS 'validar normalizacao';

COMMENT ON COLUMN "biological_occurrence"."dcterms:modified" IS 'default now';

COMMENT ON COLUMN "biological_occurrence"."associatedReferences" IS 'validar normalizacao';

COMMENT ON COLUMN "biological_occurrence"."associatedMedia" IS 'validar normalizacao';

COMMENT ON COLUMN "biological_occurrence"."day" IS 'virtual';

COMMENT ON COLUMN "biological_occurrence"."month" IS 'virtual';

COMMENT ON COLUMN "biological_occurrence"."year" IS 'virtual';

COMMENT ON COLUMN "biological_occurrence"."verbatimEventDate" IS 'virtual';

COMMENT ON COLUMN "biological_occurrence"."minimumDepthInMeters" IS 'validar normalizacao (locality?)';

COMMENT ON COLUMN "biological_occurrence"."maximumDepthInMeters" IS 'validar normalizacao (locality?)';