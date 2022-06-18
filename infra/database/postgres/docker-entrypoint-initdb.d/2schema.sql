CREATE TYPE "access_token_sub_type" AS ENUM (
  'client',
  'user'
);

CREATE TABLE "user" (
  "id" uuid PRIMARY KEY DEFAULT (uuid_generate_v4()),
  "email" text UNIQUE NOT NULL
);

CREATE TABLE "client" (
  "id" uuid PRIMARY KEY DEFAULT (uuid_generate_v4()),
  "name" text NOT NULL,
  "secret" text NOT NULL,
  "callback_url" text NOT NULL
);

CREATE TABLE "scope" (
  "id" int PRIMARY KEY,
  "name" text UNIQUE NOT NULL,
  "description" text
);

CREATE TABLE "user_allowed_scope" (
  "user_id" uuid,
  "scope_id" int,
  PRIMARY KEY ("user_id", "scope_id")
);

CREATE TABLE "client_allowed_scope" (
  "client_id" uuid,
  "scope_id" int,
  PRIMARY KEY ("client_id", "scope_id")
);

CREATE TABLE "scope_closure_table" (
  "ancestor" int,
  "descendant" int,
  PRIMARY KEY ("ancestor", "descendant")
);

CREATE TABLE "access_token" (
  "jti" uuid PRIMARY KEY DEFAULT (uuid_generate_v4()),
  "refresh_token" text,
  "sub_type" access_token_sub_type NOT NULL DEFAULT 'user',
  "expires_in" timestamp NOT NULL,
  "issued_at" timestamp NOT NULL DEFAULT (now()),
  "scope" text
);

CREATE TABLE "access_token_user_sub" (
  "access_token_jti" uuid,
  "user_id" uuid,
  PRIMARY KEY ("access_token_jti", "user_id")
);

CREATE TABLE "access_token_client_sub" (
  "access_token_jti" uuid,
  "client_id" uuid,
  PRIMARY KEY ("access_token_jti", "client_id")
);

CREATE TABLE "otp" (
  "value" text PRIMARY KEY,
  "email" text NOT NULL,
  "state" text,
  "issued_at" timestamp NOT NULL DEFAULT (now()),
  "requested_with_access_token" uuid NOT NULL
);

CREATE TABLE "artificial:section" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "basisOfRecord" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "institutionCode" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "collectionCode" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "datasetName" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "artificial:shelfLocation" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "artificial:flaskLocation" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "recordedBy" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "recordedBy_biological_occurrence" (
  "recordedBy_id" int,
  "biological_occurrence_id" text,
  "recordNumber" text,
  PRIMARY KEY ("recordedBy_id", "biological_occurrence_id")
);

CREATE TABLE "preparations" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "sex" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "lifeStage" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "reproductiveCondition" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "establishmentMeans" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "behavior" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "disposition" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "habitat" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "continent" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "country" (
  "countryCode" text PRIMARY KEY,
  "value" text NOT NULL,
  "continent_id" int NOT NULL
);

CREATE TABLE "verbatimLocality" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "stateProvince" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "country_id" text NOT NULL
);

CREATE TABLE "county" (
  "id" serial PRIMARY KEY,
  "value" text NOT NULL,
  "stateProvince_id" int NOT NULL
);

CREATE TABLE "municipality" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "county_id" int NOT NULL
);

CREATE TABLE "geodeticDatum" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "locality" (
  "id" serial PRIMARY KEY,
  "value" text,
  "decimalLatitude" real,
  "decimalLongitude" real,
  "verbatimLatitude" text,
  "verbatimLongitude" text,
  "coordinatePrecision" real,
  "geodeticDatum_id" int,
  "footprintWKT" text,
  "minimumElevationInMeters" real,
  "maximumElevationInMeters" real,
  "municipality_id" int NOT NULL,
  "verbatimLocality_id" int
);

CREATE TABLE "waterBody" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "identificationQualifier" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "identifiedBy" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "identifiedBy_biological_occurrence" (
  "biological_occurrence_id" text PRIMARY KEY,
  "value" int,
  "dateIdentified" text
);

CREATE TABLE "kingdom" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "phylum" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "kingdom_id" int NOT NULL
);

CREATE TABLE "artificial:subphylum" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "phylum_id" int NOT NULL
);

CREATE TABLE "class" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "phylum_id" int NOT NULL
);

CREATE TABLE "artificial:subclass" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "class_id" int NOT NULL
);

CREATE TABLE "artificial:superorder" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "class_id" int NOT NULL
);

CREATE TABLE "order" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "class_id" int NOT NULL
);

CREATE TABLE "artificial:suborder" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "order_id" int NOT NULL
);

CREATE TABLE "artificial:infraorder" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "order_id" int NOT NULL
);

CREATE TABLE "artificial:superfamily" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "order_id" int NOT NULL
);

CREATE TABLE "family" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "order_id" int NOT NULL
);

CREATE TABLE "artificial:subfamily" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "family_id" int NOT NULL
);

CREATE TABLE "genus" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "family_id" int NOT NULL
);

CREATE TABLE "artificial:tribe" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "family_id" int NOT NULL
);

CREATE TABLE "artificial:subtribe" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "tribe_id" int NOT NULL
);

CREATE TABLE "subgenus" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL,
  "genus_id" int NOT NULL
);

CREATE TABLE "taxonomicStatus" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "nomenclaturalCode" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "nameAccordingTo" (
  "id" serial PRIMARY KEY,
  "value" text UNIQUE NOT NULL
);

CREATE TABLE "specie" (
  "id" serial PRIMARY KEY,
  "typeStatus" text,
  "scientificName" text,
  "scientificNameAuthorship" text,
  "acceptedNameUsage" text UNIQUE NOT NULL,
  "kingdom_id" int,
  "phylum_id" int,
  "artificial:subphylum_id" int,
  "class_id" int,
  "artificial:subclass_id" int,
  "artificial:superorder_id" int,
  "order_id" int,
  "artificial:suborder_id" int,
  "artificial:infraorder_id" int,
  "artificial:superfamily_id" int,
  "family_id" int,
  "artificial:subfamily_id" int,
  "genus_id" int,
  "artificial:tribe_id" int,
  "artificial:subtribe_id" int,
  "subgenus_id" int,
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
  "individualCount" text,
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
  "eventTime" text,
  "eventDate" text,
  "associatedOccurrences" text,
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
  "relationshipOfResource" text
);

CREATE UNIQUE INDEX ON "country" ("value", "continent_id");

CREATE UNIQUE INDEX ON "stateProvince" ("value", "country_id");

CREATE UNIQUE INDEX ON "county" ("value", "stateProvince_id");

CREATE UNIQUE INDEX ON "municipality" ("value", "county_id");

CREATE UNIQUE INDEX ON "locality" ("value", "decimalLatitude", "decimalLongitude", "municipality_id");

CREATE UNIQUE INDEX ON "identifiedBy_biological_occurrence" ("value", "biological_occurrence_id");

CREATE UNIQUE INDEX ON "phylum" ("value", "kingdom_id");

CREATE UNIQUE INDEX ON "artificial:subphylum" ("value", "phylum_id");

CREATE UNIQUE INDEX ON "class" ("value", "phylum_id");

CREATE UNIQUE INDEX ON "artificial:subclass" ("value", "class_id");

CREATE UNIQUE INDEX ON "artificial:superorder" ("value", "class_id");

CREATE UNIQUE INDEX ON "order" ("value", "class_id");

CREATE UNIQUE INDEX ON "artificial:suborder" ("value", "order_id");

CREATE UNIQUE INDEX ON "artificial:infraorder" ("value", "order_id");

CREATE UNIQUE INDEX ON "artificial:superfamily" ("value", "order_id");

CREATE UNIQUE INDEX ON "family" ("value", "order_id");

CREATE UNIQUE INDEX ON "artificial:subfamily" ("value", "family_id");

CREATE UNIQUE INDEX ON "genus" ("value", "family_id");

CREATE UNIQUE INDEX ON "artificial:tribe" ("value", "family_id");

CREATE UNIQUE INDEX ON "artificial:subtribe" ("value", "tribe_id");

CREATE UNIQUE INDEX ON "subgenus" ("value", "genus_id");

CREATE UNIQUE INDEX ON "specie" ("artificial:subfamily_id", "artificial:subtribe_id", "artificial:tribe_id", "artificial:superfamily_id", "artificial:subclass_id", "artificial:subphylum_id");

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

ALTER TABLE "user_allowed_scope" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("id") ON DELETE CASCADE;

ALTER TABLE "user_allowed_scope" ADD FOREIGN KEY ("scope_id") REFERENCES "scope" ("id");

ALTER TABLE "client_allowed_scope" ADD FOREIGN KEY ("client_id") REFERENCES "client" ("id");

ALTER TABLE "client_allowed_scope" ADD FOREIGN KEY ("scope_id") REFERENCES "scope" ("id");

ALTER TABLE "scope_closure_table" ADD FOREIGN KEY ("ancestor") REFERENCES "scope" ("id");

ALTER TABLE "scope_closure_table" ADD FOREIGN KEY ("descendant") REFERENCES "scope" ("id");

ALTER TABLE "access_token_user_sub" ADD FOREIGN KEY ("access_token_jti") REFERENCES "access_token" ("jti");

ALTER TABLE "access_token_user_sub" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("id");

ALTER TABLE "access_token_client_sub" ADD FOREIGN KEY ("access_token_jti") REFERENCES "access_token" ("jti");

ALTER TABLE "access_token_client_sub" ADD FOREIGN KEY ("client_id") REFERENCES "client" ("id");

ALTER TABLE "otp" ADD FOREIGN KEY ("email") REFERENCES "user" ("email");

ALTER TABLE "otp" ADD FOREIGN KEY ("requested_with_access_token") REFERENCES "access_token" ("jti");

ALTER TABLE "recordedBy_biological_occurrence" ADD FOREIGN KEY ("recordedBy_id") REFERENCES "recordedBy" ("id");

ALTER TABLE "recordedBy_biological_occurrence" ADD FOREIGN KEY ("biological_occurrence_id") REFERENCES "biological_occurrence" ("occurrenceID");

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

ALTER TABLE "artificial:subphylum" ADD FOREIGN KEY ("phylum_id") REFERENCES "phylum" ("id");

ALTER TABLE "class" ADD FOREIGN KEY ("phylum_id") REFERENCES "phylum" ("id");

ALTER TABLE "artificial:subclass" ADD FOREIGN KEY ("class_id") REFERENCES "class" ("id");

ALTER TABLE "artificial:superorder" ADD FOREIGN KEY ("class_id") REFERENCES "class" ("id");

ALTER TABLE "order" ADD FOREIGN KEY ("class_id") REFERENCES "class" ("id");

ALTER TABLE "artificial:suborder" ADD FOREIGN KEY ("order_id") REFERENCES "order" ("id");

ALTER TABLE "artificial:infraorder" ADD FOREIGN KEY ("order_id") REFERENCES "order" ("id");

ALTER TABLE "artificial:superfamily" ADD FOREIGN KEY ("order_id") REFERENCES "order" ("id");

ALTER TABLE "family" ADD FOREIGN KEY ("order_id") REFERENCES "order" ("id");

ALTER TABLE "artificial:subfamily" ADD FOREIGN KEY ("family_id") REFERENCES "family" ("id");

ALTER TABLE "genus" ADD FOREIGN KEY ("family_id") REFERENCES "family" ("id");

ALTER TABLE "artificial:tribe" ADD FOREIGN KEY ("family_id") REFERENCES "family" ("id");

ALTER TABLE "artificial:subtribe" ADD FOREIGN KEY ("tribe_id") REFERENCES "artificial:tribe" ("id");

ALTER TABLE "subgenus" ADD FOREIGN KEY ("genus_id") REFERENCES "genus" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("kingdom_id") REFERENCES "kingdom" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("phylum_id") REFERENCES "phylum" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:subphylum_id") REFERENCES "artificial:subphylum" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("class_id") REFERENCES "class" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:subclass_id") REFERENCES "artificial:subclass" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:superorder_id") REFERENCES "artificial:superorder" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("order_id") REFERENCES "order" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:suborder_id") REFERENCES "artificial:suborder" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:infraorder_id") REFERENCES "artificial:infraorder" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:superfamily_id") REFERENCES "artificial:superfamily" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("family_id") REFERENCES "family" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:subfamily_id") REFERENCES "artificial:subfamily" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("genus_id") REFERENCES "genus" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:tribe_id") REFERENCES "artificial:tribe" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("artificial:subtribe_id") REFERENCES "artificial:subtribe" ("id");

ALTER TABLE "specie" ADD FOREIGN KEY ("subgenus_id") REFERENCES "subgenus" ("id");

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

