CREATE OR REPLACE VIEW "biological_occurrence_view" AS SELECT
  "artificial:section".value AS "artificial:section",
  "dcterms:modified",
  "informationWithheld",
  "basisOfRecord".value AS "basisOfRecord",
  "institutionCode".value AS "institutionCode",
  "collectionCode".value AS "collectionCode",
  "dcterms:bibliographicCitation",
  "datasetName".value AS "datasetName",
  "artificial:shelfLocation".value AS "artificial:shelfLocation",
  "artificial:flaskLocation".value AS "artificial:flaskLocation",
  "artificial:shippingGuide",
  "catalogNumber",
  "otherCatalogNumbers",
  "recordedBy".value AS "recordedBy",
  "recordNumber",
  "preparations".value AS "preparations",
  "individualCount",
  "sex".value AS "sex",
  "lifeStage".value AS "lifeStage",
  "reproductiveCondition".value AS "reproductiveCondition",
  "establishmentMeans".value AS "establishmentMeans",
  "behavior".value AS "behavior",
  "occurrenceRemarks",
  "disposition".value AS "disposition",
  "associatedReferences",
  "associatedMedia",
  "occurrenceID",
  "associatedOccurrences",
  "previousIdentifications",
  "fieldNumber",
  "day",
  "month",
  "year",
  "eventTime",
  "eventDate",
  "verbatimEventDate",
  "samplingProtocol",
  "habitat".value AS "habitat",
  "eventRemarks",
  "fieldNotes",
  "measurementRemarks",
  "continent".value AS "continent",
  "country".value AS "country",
  "countryCode",
  "verbatimLocality".value AS "verbatimLocality",
  "stateProvince".value AS "stateProvince",
  "county".value AS "county",
  "municipality".value AS "municipality",
  "locality".value AS "locality",
  "decimalLatitude",
  "decimalLongitude",
  "verbatimLatitude",
  "verbatimLongitude",
  "coordinatePrecision",
  "geodeticDatum".value AS "geodeticDatum",
  "footprintWKT",
  "minimumElevationInMeters",
  "maximumElevationInMeters",
  "waterBody".value AS "waterBody",
  "minimumDepthInMeters",
  "maximumDepthInMeters",
  "locationRemarks",
  "identificationQualifier".value AS "identificationQualifier",
  "identifiedBy".value AS "identifiedBy",
  "dateIdentified",
  "typeStatus",
  "scientificName",
  "scientificNameAuthorship",
  "acceptedNameUsage",
  "subgenus".value AS "subgenus",
  "genus".value AS "genus",
  "family".value AS "family",
  "order".value AS "order",
  "class".value AS "class",
  "phylum".value AS "phylum",
  "kingdom".value AS "kingdom",
  "artificial:subfamily".value AS "artificial:subfamily",
  "artificial:subtribe".value AS "artificial:subtribe",
  "artificial:tribe".value AS "artificial:tribe",
  "artificial:superfamily".value AS "artificial:superfamily",
  "artificial:infraorder".value AS "artificial:infraorder",
  "artificial:suborder".value AS "artificial:suborder",
  "artificial:superorder".value AS "artificial:superorder",
  "artificial:subclass".value AS "artificial:subclass",
  "artificial:subphylum".value AS "artificial:subphylum",
  "specificEpithet",
  "infraspecificEpithet",
  "taxonRank",
  "taxonomicStatus".value AS "taxonomicStatus",
  "originalNameUsage",
  "vernacularName",
  "nomenclaturalCode".value AS "nomenclaturalCode",
  "nameAccordingTo".value AS "nameAccordingTo",
  "relationshipOfResource"
  FROM biological_occurrence AS bo
  LEFT JOIN "artificial:section" ON "artificial:section".id = bo."artificial:section_id"
  LEFT JOIN "basisOfRecord" ON "basisOfRecord".id = bo."basisOfRecord_id"
  LEFT JOIN "institutionCode" ON "institutionCode".id = bo."institutionCode_id"
  LEFT JOIN "collectionCode" ON "collectionCode".id = bo."collectionCode_id"
  LEFT JOIN "datasetName" ON "datasetName".id = bo."datasetName_id"
  LEFT JOIN "artificial:shelfLocation" ON "artificial:shelfLocation".id = bo."artificial:shelfLocation_id"
  LEFT JOIN "artificial:flaskLocation" ON "artificial:flaskLocation".id = bo."artificial:flaskLocation_id"
  LEFT JOIN "recordedBy_biological_occurrence" ON "recordedBy_biological_occurrence".biological_occurrence_id = bo."occurrenceID"
  LEFT JOIN "recordedBy" ON "recordedBy".id = "recordedBy_biological_occurrence"."recordedBy_id"
  LEFT JOIN "preparations" ON "preparations".id = bo."preparations_id"
  LEFT JOIN "sex" ON "sex".id = bo."sex_id"
  LEFT JOIN "lifeStage" ON "lifeStage".id = bo."lifeStage_id"
  LEFT JOIN "reproductiveCondition" ON "reproductiveCondition".id = bo."reproductiveCondition_id"
  LEFT JOIN "establishmentMeans" ON "establishmentMeans".id = bo."establishmentMeans_id"
  LEFT JOIN "behavior" ON "behavior".id = bo."behavior_id"
  LEFT JOIN "disposition" ON "disposition".id = bo."disposition_id"
  LEFT JOIN "habitat" ON "habitat".id = bo."habitat_id"
  LEFT JOIN "locality" ON "locality".id = bo."locality_id"
  	LEFT JOIN "municipality" ON "municipality".id = "locality"."municipality_id"
  	LEFT JOIN "county" ON "county".id = "municipality"."county_id"
  	LEFT JOIN "stateProvince" ON "stateProvince".id = "county"."stateProvince_id"
  	LEFT JOIN "country" ON "country"."countryCode" = "stateProvince"."country_id"
  	LEFT JOIN "continent" ON "continent".id = "country"."continent_id"
  	LEFT JOIN "verbatimLocality" ON "verbatimLocality".id = "locality"."verbatimLocality_id"
	LEFT JOIN "geodeticDatum" ON "geodeticDatum".id = "locality"."geodeticDatum_id"
  LEFT JOIN "waterBody" ON "waterBody".id = bo."waterBody_id"
  LEFT JOIN "identificationQualifier" ON "identificationQualifier".id = bo."identificationQualifier_id"
  LEFT JOIN "identifiedBy_biological_occurrence" ON "identifiedBy_biological_occurrence"."biological_occurrence_id" = bo."occurrenceID"
  	LEFT JOIN "identifiedBy" ON "identifiedBy".id = "identifiedBy_biological_occurrence".value
  LEFT JOIN "specie" ON "specie".id = bo."specie_id"
  	LEFT JOIN "subgenus" ON "subgenus".id = "specie"."subgenus_id"
	LEFT JOIN "genus" ON "genus".id = "subgenus"."genus_id"
	LEFT JOIN "family" ON "family".id = "genus"."family_id"
	LEFT JOIN "order" ON "order".id = "family"."order_id"
	LEFT JOIN "class" ON "class".id = "order"."class_id"
	LEFT JOIN "phylum" ON "phylum".id = "class"."phylum_id"
	LEFT JOIN "kingdom" ON "kingdom".id = "phylum"."kingdom_id"
	LEFT JOIN "artificial:subtribe" ON "artificial:subtribe".id = "specie"."artificial:subtribe_id"
	LEFT JOIN "artificial:tribe" ON "artificial:tribe".id = "specie"."artificial:tribe_id"
	LEFT JOIN "artificial:superfamily" ON "artificial:superfamily".id = "specie"."artificial:superfamily_id"
	LEFT JOIN "artificial:infraorder" ON "artificial:infraorder".id = "specie"."artificial:infraorder_id"
	LEFT JOIN "artificial:suborder" ON "artificial:suborder".id = "specie"."artificial:suborder_id"
	LEFT JOIN "artificial:superorder" ON "artificial:superorder".id = "specie"."artificial:superorder_id"
	LEFT JOIN "artificial:subclass" ON "artificial:subclass".id = "specie"."artificial:subclass_id"
	LEFT JOIN "artificial:subphylum" ON "artificial:subphylum".id = "specie"."artificial:subphylum_id"	
	LEFT JOIN "taxonomicStatus" ON "taxonomicStatus".id = "specie"."taxonomicStatus_id"
	LEFT JOIN "nomenclaturalCode" ON "nomenclaturalCode".id = "specie"."nomenclaturalCode_id"
	LEFT JOIN "nameAccordingTo" ON "nameAccordingTo".id = "specie"."nameAccordingTo_id"
	LEFT JOIN "artificial:subfamily" ON "artificial:subfamily".id = "specie"."artificial:subfamily_id";

CREATE OR REPLACE FUNCTION func_setof_biological_occurrence (new_ref anyelement) RETURNS setof biological_occurrence AS $$
BEGIN
	WITH "inserted_continent_id" AS (
		INSERT INTO "continent" (value) (SELECT new_ref."continent" WHERE new_ref."continent" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "continent_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_continent_id"), (SELECT id FROM "continent" WHERE value = new_ref."continent")) AS id
	), "inserted_country_countryCode" AS (
		INSERT INTO "country" ("countryCode", value, "continent_id") (SELECT new_ref."countryCode", new_ref."country", id FROM "continent_id" WHERE new_ref."countryCode" IS NOT NULL AND new_ref."country" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING "countryCode"
	), "country_countryCode" AS (
		SELECT COALESCE((SELECT "countryCode" FROM "inserted_country_countryCode"), (SELECT "countryCode" FROM "country" WHERE value = new_ref."country")) AS "countryCode"
	), "inserted_stateProvince_id" AS (
		INSERT INTO "stateProvince" (value, "country_id") (SELECT new_ref."stateProvince", "countryCode" FROM "country_countryCode" WHERE new_ref."stateProvince" IS NOT NULL AND "countryCode" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "stateProvince_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_stateProvince_id"), (SELECT id FROM "stateProvince" WHERE value = new_ref."stateProvince")) AS id
	), "inserted_county_id" AS (
		INSERT INTO "county" (value, "stateProvince_id") (SELECT new_ref."county", id FROM "stateProvince_id" WHERE new_ref."county" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "county_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_county_id"), (SELECT id FROM "county" WHERE value = new_ref."county")) AS id
	), "inserted_municipality_id" AS (
		INSERT INTO "municipality" (value, "county_id") (SELECT new_ref."municipality", id FROM "county_id" WHERE new_ref."municipality" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "municipality_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_municipality_id"), (SELECT id FROM "municipality" WHERE value = new_ref."municipality")) AS id
	), "inserted_geodeticDatum_id" AS (
		INSERT INTO "geodeticDatum" (value) (SELECT new_ref."geodeticDatum" WHERE new_ref."geodeticDatum" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "geodeticDatum_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_geodeticDatum_id"), (SELECT id FROM "geodeticDatum" WHERE value = new_ref."geodeticDatum")) AS id
	), "inserted_verbatimLocality_id" AS (
		INSERT INTO "verbatimLocality" (value) (SELECT new_ref."verbatimLocality" WHERE new_ref."verbatimLocality" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "verbatimLocality_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_verbatimLocality_id"), (SELECT id FROM "verbatimLocality" WHERE value = new_ref."verbatimLocality")) AS id
	), "inserted_kingdom_id" AS (
		INSERT INTO "kingdom" (value) (SELECT new_ref."kingdom" WHERE new_ref."kingdom" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "kingdom_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_kingdom_id"), (SELECT id FROM "kingdom" WHERE value = new_ref."kingdom")) AS id
	), "inserted_phylum_id" AS (
		INSERT INTO "phylum" (value, "kingdom_id") (SELECT new_ref."phylum", id FROM "kingdom_id" WHERE new_ref."phylum" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "phylum_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_phylum_id"), (SELECT id FROM "phylum" WHERE value = new_ref."phylum")) AS id
	), "inserted_class_id" AS (
		INSERT INTO "class" (value, "phylum_id") (SELECT new_ref."class", id FROM "phylum_id" WHERE new_ref."class" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "class_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_class_id"), (SELECT id FROM "class" WHERE value = new_ref."class")) AS id
	), "inserted_order_id" AS (
		INSERT INTO "order" (value, "class_id") (SELECT new_ref."order", id FROM "class_id" WHERE new_ref."order" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "order_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_order_id"), (SELECT id FROM "order" WHERE value = new_ref."order")) AS id
	), "inserted_family_id" AS (
		INSERT INTO "family" (value, "order_id") (SELECT new_ref."family", id FROM "order_id" WHERE new_ref."family" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "family_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_family_id"), (SELECT id FROM "family" WHERE value = new_ref."family")) AS id
	), "inserted_genus_id" AS (
		INSERT INTO "genus" (value, "family_id") (SELECT new_ref."genus", id FROM "family_id" WHERE new_ref."genus" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "genus_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_genus_id"), (SELECT id FROM "genus" WHERE value = new_ref."genus")) AS id
	), "inserted_subgenus_id" AS (
		INSERT INTO "subgenus" (value, "genus_id") (SELECT new_ref."subgenus", id FROM "genus_id" WHERE new_ref."subgenus" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "subgenus_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_subgenus_id"), (SELECT id FROM "subgenus" WHERE value = new_ref."subgenus")) AS id
	), "inserted_artificial:subfamily_id" AS (
		INSERT INTO "artificial:subfamily" (value, "family_id") (SELECT new_ref."artificial:subfamily", id FROM "family_id" WHERE new_ref."artificial:subfamily" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:subfamily_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:subfamily_id"), (SELECT id FROM "artificial:subfamily" WHERE value = new_ref."artificial:subfamily")) AS id
	), "inserted_artificial:tribe_id" AS (
		INSERT INTO "artificial:tribe" (value, "family_id") (SELECT new_ref."artificial:tribe", id FROM "family_id" WHERE new_ref."artificial:tribe" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:tribe_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:tribe_id"), (SELECT id FROM "artificial:tribe" WHERE value = new_ref."artificial:tribe")) AS id
	), "inserted_artificial:subtribe_id" AS (
		INSERT INTO "artificial:subtribe" (value, "tribe_id") (SELECT new_ref."artificial:subtribe", id FROM "artificial:tribe_id" WHERE new_ref."artificial:subtribe" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:subtribe_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:subtribe_id"), (SELECT id FROM "artificial:subtribe" WHERE value = new_ref."artificial:subtribe")) AS id
	), "inserted_artificial:superfamily_id" AS (
		INSERT INTO "artificial:superfamily" (value, "order_id") (SELECT new_ref."artificial:superfamily", id FROM "order_id" WHERE new_ref."artificial:superfamily" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id 
	), "artificial:superfamily_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:superfamily_id"), (SELECT id FROM "artificial:superfamily" WHERE value = new_ref."artificial:superfamily")) AS id
	), "inserted_artificial:infraorder_id" AS (
		INSERT INTO "artificial:infraorder" (value, "order_id") (SELECT new_ref."artificial:infraorder", id FROM "order_id" WHERE new_ref."artificial:infraorder" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:infraorder_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:infraorder_id"), (SELECT id FROM "artificial:infraorder" WHERE value = new_ref."artificial:infraorder")) AS id
	), "inserted_artificial:suborder_id" AS (
		INSERT INTO "artificial:suborder" (value, "order_id") (SELECT new_ref."artificial:suborder", id FROM "order_id" WHERE new_ref."artificial:suborder" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:suborder_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:suborder_id"), (SELECT id FROM "artificial:suborder" WHERE value = new_ref."artificial:suborder")) AS id
	), "inserted_artificial:superorder_id" AS (
		INSERT INTO "artificial:superorder" (value, "class_id") (SELECT new_ref."artificial:superorder", id FROM "class_id" WHERE new_ref."artificial:superorder" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:superorder_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:superorder_id"), (SELECT id FROM "artificial:suborder" WHERE value = new_ref."artificial:superorder")) AS id
	), "inserted_artificial:subclass_id" AS (
		INSERT INTO "artificial:subclass" (value, "class_id") (SELECT new_ref."artificial:subclass", id FROM "class_id" WHERE new_ref."artificial:subclass" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:subclass_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:subclass_id"), (SELECT id FROM "artificial:subclass" WHERE value = new_ref."artificial:subclass")) AS id
	), "inserted_artificial:subphylum_id" AS (
		INSERT INTO "artificial:subphylum" (value, "phylum_id") (SELECT new_ref."artificial:subphylum", id FROM "phylum_id" WHERE new_ref."artificial:subphylum" IS NOT NULL AND id IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:subphylum_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:subphylum_id"), (SELECT id FROM "artificial:subphylum" WHERE value = new_ref."artificial:subphylum")) AS id
	), "inserted_taxonomicStatus_id" AS (
		INSERT INTO "taxonomicStatus" (value) (SELECT new_ref."taxonomicStatus" WHERE new_ref."taxonomicStatus" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "taxonomicStatus_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_taxonomicStatus_id"), (SELECT id FROM "taxonomicStatus" WHERE value = new_ref."taxonomicStatus")) AS id
	), "inserted_nomenclaturalCode_id" AS (
		INSERT INTO "nomenclaturalCode" (value) (SELECT new_ref."nomenclaturalCode" WHERE new_ref."nomenclaturalCode" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "nomenclaturalCode_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_nomenclaturalCode_id"), (SELECT id FROM "nomenclaturalCode" WHERE value = new_ref."nomenclaturalCode")) AS id
	), "inserted_nameAccordingTo_id" AS (
		INSERT INTO "nameAccordingTo" (value) (SELECT new_ref."nameAccordingTo" WHERE new_ref."nameAccordingTo" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "nameAccordingTo_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_nameAccordingTo_id"), (SELECT id FROM "nameAccordingTo" WHERE value = new_ref."nameAccordingTo")) AS id
	), "inserted_recordedBy_id" AS (
		INSERT INTO "recordedBy" (value) (SELECT unnest(string_to_array(new_ref."recordedBy", ';')) AS value WHERE new_ref."recordedBy" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "recordedBy_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_recordedBy_id"), (SELECT id FROM "recordedBy" WHERE value = ANY (string_to_array(new_ref."recordedBy", '; ')))) AS id
	), "inserted_identifiedBy_id" AS (
		INSERT INTO "identifiedBy" (value) (SELECT unnest(string_to_array(new_ref."recordedBy", ';')) AS value WHERE new_ref."recordedBy" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "identifiedBy_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_identifiedBy_id"), (SELECT id FROM "identifiedBy" WHERE value = ANY (string_to_array(new_ref."identifiedBy", '; ')))) AS id
	), "inserted_datasetName_id" AS (
		INSERT INTO "datasetName" (value) (SELECT new_ref."datasetName" WHERE new_ref."datasetName" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "datasetName_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_datasetName_id"), (SELECT id FROM "datasetName" WHERE value = new_ref."datasetName")) AS id
	), "inserted_artificial:shelfLocation_id" AS (
		INSERT INTO "artificial:shelfLocation" (value) (SELECT new_ref."artificial:shelfLocation" WHERE new_ref."artificial:shelfLocation" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:shelfLocation_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:shelfLocation_id"), (SELECT id FROM "artificial:shelfLocation" WHERE value = new_ref."artificial:shelfLocation")) AS id
	), "inserted_artificial:flaskLocation_id" AS (
		INSERT INTO "artificial:flaskLocation" (value) (SELECT new_ref."artificial:flaskLocation" WHERE new_ref."artificial:flaskLocation" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:flaskLocation_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:flaskLocation_id"), (SELECT id FROM "artificial:flaskLocation" WHERE value = new_ref."artificial:flaskLocation")) AS id
	), "inserted_sex_id" AS (
		INSERT INTO "sex" (value) (SELECT new_ref."sex" WHERE new_ref."sex" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "sex_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_sex_id"), (SELECT id FROM "sex" WHERE value = new_ref."sex")) AS id
	), "inserted_reproductiveCondition_id" AS (
		INSERT INTO "reproductiveCondition" (value) (SELECT new_ref."reproductiveCondition" WHERE new_ref."reproductiveCondition" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "reproductiveCondition_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_reproductiveCondition_id"), (SELECT id FROM "reproductiveCondition" WHERE value = new_ref."reproductiveCondition")) AS id
	), "inserted_disposition_id" AS (
		INSERT INTO "disposition" (value) (SELECT new_ref."disposition" WHERE new_ref."disposition" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "disposition_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_disposition_id"), (SELECT id FROM "disposition" WHERE value = new_ref."disposition")) AS id
	), "inserted_habitat_id" AS (
		INSERT INTO "habitat" (value) (SELECT new_ref."habitat" WHERE new_ref."habitat" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "habitat_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_habitat_id"), (SELECT id FROM "habitat" WHERE value = new_ref."habitat")) AS id
	), "inserted_waterBody_id" AS (
		INSERT INTO "waterBody" (value) (SELECT new_ref."waterBody" WHERE new_ref."waterBody" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "waterBody_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_waterBody_id"), (SELECT id FROM "waterBody" WHERE value = new_ref."waterBody")) AS id
	), "inserted_identificationQualifier_id" AS (
		INSERT INTO "identificationQualifier" (value) (SELECT new_ref."identificationQualifier" WHERE new_ref."identificationQualifier" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "identificationQualifier_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_identificationQualifier_id"), (SELECT id FROM "identificationQualifier" WHERE value = new_ref."identificationQualifier")) AS id
	), "inserted_artificial:section_id" AS (
		INSERT INTO "artificial:section" (value) (SELECT new_ref."artificial:section" WHERE new_ref."artificial:section" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "artificial:section_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_artificial:section_id"), (SELECT id FROM "artificial:section" WHERE value = new_ref."artificial:section")) AS id
	), "inserted_basisOfRecord_id" AS (
		INSERT INTO "basisOfRecord" (value) (SELECT new_ref."basisOfRecord" WHERE new_ref."basisOfRecord" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "basisOfRecord_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_basisOfRecord_id"), (SELECT id FROM "basisOfRecord" WHERE value = new_ref."basisOfRecord")) AS id
	), "inserted_institutionCode_id" AS (
		INSERT INTO "institutionCode" (value) (SELECT new_ref."institutionCode" WHERE new_ref."institutionCode" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "institutionCode_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_institutionCode_id"), (SELECT id FROM "institutionCode" WHERE value = new_ref."institutionCode")) AS id
	), "inserted_collectionCode_id" AS (
		INSERT INTO "collectionCode" (value) (SELECT new_ref."collectionCode" WHERE new_ref."collectionCode" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "collectionCode_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_collectionCode_id"), (SELECT id FROM "collectionCode" WHERE value = new_ref."collectionCode")) AS id
	), "inserted_preparations_id" AS (
		INSERT INTO "preparations" (value) (SELECT new_ref."preparations" WHERE new_ref."preparations" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "preparations_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_preparations_id"), (SELECT id FROM "preparations" WHERE value = new_ref."preparations")) AS id
	), "inserted_lifeStage_id" AS (
		INSERT INTO "lifeStage" (value) (SELECT new_ref."lifeStage" WHERE new_ref."lifeStage" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "lifeStage_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_lifeStage_id"), (SELECT id FROM "lifeStage" WHERE value = new_ref."lifeStage")) AS id
	), "inserted_establishmentMeans_id" AS (
		INSERT INTO "establishmentMeans" (value) (SELECT new_ref."establishmentMeans" WHERE new_ref."establishmentMeans" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "establishmentMeans_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_establishmentMeans_id"), (SELECT id FROM "establishmentMeans" WHERE value = new_ref."establishmentMeans")) AS id
	), "inserted_behavior_id" AS (
		INSERT INTO "behavior" (value) (SELECT new_ref."behavior" WHERE new_ref."behavior" IS NOT NULL) ON CONFLICT DO NOTHING RETURNING id
	), "behavior_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_behavior_id"), (SELECT id FROM "behavior" WHERE value = new_ref."behavior")) AS id
	), "inserted_specie_id" AS (
		INSERT INTO "specie" ("typeStatus", "scientificName", "scientificNameAuthorship", "acceptedNameUsage", "subgenus_id", "artificial:subfamily_id", "artificial:subtribe_id", "artificial:tribe_id", "artificial:superfamily_id", "artificial:infraorder_id", "artificial:suborder_id", "artificial:superorder_id",	"artificial:subclass_id", "artificial:subphylum_id", "specificEpithet", "infraspecificEpithet", "taxonRank", "taxonomicStatus_id", "originalNameUsage", "vernacularName", "nomenclaturalCode_id", "nameAccordingTo_id") (SELECT new_ref."typeStatus", new_ref."scientificName", new_ref."scientificNameAuthorship", new_ref."acceptedNameUsage", "subgenus_id".id AS "subgenus_id", "artificial:subfamily_id".id AS "artificial:subfamily_id" , "artificial:subtribe_id".id AS "artificial:subtribe_id", "artificial:tribe_id".id AS "artificial:tribe_id", "artificial:superfamily_id".id AS "artificial:superfamily_id", "artificial:infraorder_id".id AS "artificial:infraorder_id", "artificial:suborder_id".id AS "artificial:suborder_id", "artificial:superorder_id".id AS "artificial:superorder_id", "artificial:subclass_id".id AS "artificial:subclass_id", "artificial:subphylum_id".id AS "artificial:subphylum_id", new_ref."specificEpithet", new_ref."infraspecificEpithet", new_ref."taxonRank", "taxonomicStatus_id".id AS "taxonomicStatus_id", new_ref."originalNameUsage", new_ref."vernacularName", "nomenclaturalCode_id".id AS "nomenclaturalCode_id", "nameAccordingTo_id".id AS "nameAccordingTo_id" FROM "subgenus_id", "artificial:subfamily_id", "artificial:subtribe_id", "artificial:tribe_id", "artificial:superfamily_id", "artificial:infraorder_id", "artificial:suborder_id", "artificial:superorder_id", "artificial:subclass_id", "artificial:subphylum_id", "taxonomicStatus_id", "nomenclaturalCode_id", "nameAccordingTo_id") ON CONFLICT DO NOTHING RETURNING id
	), "specie_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_specie_id"), (SELECT id FROM "specie" WHERE "acceptedNameUsage" = new_ref."acceptedNameUsage")) AS id
	), "inserted_locality_id" AS (
		INSERT INTO "locality" ("value", "decimalLatitude", "decimalLongitude", "verbatimLatitude", "verbatimLongitude", "coordinatePrecision", "geodeticDatum_id", "footprintWKT", "minimumElevationInMeters", "maximumElevationInMeters", "municipality_id", "verbatimLocality_id") (SELECT new_ref."locality" AS value, new_ref."decimalLatitude", new_ref."decimalLongitude", new_ref."verbatimLatitude", new_ref."verbatimLongitude", new_ref."coordinatePrecision", "geodeticDatum_id".id AS "geodeticDatum_id", new_ref."footprintWKT", new_ref."minimumElevationInMeters", new_ref."maximumElevationInMeters", "municipality_id".id AS "municipality_id", "verbatimLocality_id".id AS "verbatimLocality_id" FROM  "geodeticDatum_id", "municipality_id", "verbatimLocality_id") ON CONFLICT DO NOTHING RETURNING id
	), "locality_id" AS (
		SELECT COALESCE((SELECT id FROM "inserted_locality_id"), (SELECT locality.id FROM "locality", "municipality_id" WHERE value = new_ref."locality" AND "decimalLongitude" = new_ref."decimalLongitude" AND "decimalLatitude" = new_ref."decimalLatitude" AND locality."municipality_id" = "municipality_id".id)) AS id
	), "insert_biological_occurrence" AS (
		INSERT INTO "biological_occurrence" ("occurrenceID", "artificial:section_id", "dcterms:modified", "informationWithheld", "basisOfRecord_id", "institutionCode_id", "collectionCode_id", "dcterms:bibliographicCitation", "datasetName_id", "artificial:shelfLocation_id", "artificial:flaskLocation_id", "artificial:shippingGuide", "catalogNumber", "otherCatalogNumbers", "preparations_id", "individualCount", "sex_id", "lifeStage_id", "reproductiveCondition_id", "establishmentMeans_id", "behavior_id", "occurrenceRemarks", "disposition_id", "associatedReferences", "associatedMedia", "previousIdentifications", "associatedOccurrences","fieldNumber", "day", "month", "year", "eventTime", "eventDate", "verbatimEventDate", "samplingProtocol", "habitat_id", "eventRemarks", "fieldNotes", "measurementRemarks", "specie_id", "locality_id", "waterBody_id", "minimumDepthInMeters", "maximumDepthInMeters", "locationRemarks", "identificationQualifier_id", "relationshipOfResource") (SELECT new_ref."occurrenceID", "artificial:section_id".id AS "artificial:section_id" , new_ref."dcterms:modified", new_ref."informationWithheld", "basisOfRecord_id".id AS "basisOfRecord_id", "institutionCode_id".id AS "institutionCode_id", "collectionCode_id".id AS "collectionCode_id", new_ref."dcterms:bibliographicCitation", "datasetName_id".id AS "datasetName_id", "artificial:shelfLocation_id".id AS "artificial:shelfLocation_id", "artificial:flaskLocation_id".id AS "artificial:flaskLocation_id", new_ref."artificial:shippingGuide", new_ref."catalogNumber", new_ref."otherCatalogNumbers", "preparations_id".id AS "preparations_id", new_ref."individualCount", "sex_id".id AS "sex_id", "lifeStage_id".id AS "lifeStage_id", "reproductiveCondition_id".id AS "reproductiveCondition_id", "establishmentMeans_id".id AS "establishmentMeans_id", "behavior_id".id AS "behavior_id", new_ref."occurrenceRemarks", "disposition_id".id AS "disposition_id", new_ref."associatedReferences", new_ref."associatedMedia", new_ref."previousIdentifications",new_ref."associatedOccurrences", new_ref."fieldNumber", new_ref."day", new_ref."month", new_ref."year", new_ref."eventTime", new_ref."eventDate", new_ref."verbatimEventDate", new_ref."samplingProtocol", "habitat_id".id AS "habitat_id", new_ref."eventRemarks", new_ref."fieldNotes", new_ref."measurementRemarks", "specie_id".id AS "specie_id", "locality_id".id AS "locality_id", "waterBody_id".id AS "waterBody_id", new_ref."minimumDepthInMeters", new_ref."maximumDepthInMeters", new_ref."locationRemarks", "identificationQualifier_id".id AS "identificationQualifier_id", new_ref."relationshipOfResource" FROM "artificial:section_id", "basisOfRecord_id", "institutionCode_id", "collectionCode_id", "datasetName_id", "artificial:shelfLocation_id", "artificial:flaskLocation_id", "preparations_id", "sex_id", "lifeStage_id", "reproductiveCondition_id", "establishmentMeans_id", "behavior_id", "disposition_id", "habitat_id", "specie_id", "locality_id", "waterBody_id", "identificationQualifier_id" WHERE "artificial:section_id".id IS NOT NULL AND new_ref."dcterms:modified" IS NOT NULL)
	),"insert_recordedBy_biological_occurrence" AS (
		INSERT INTO "recordedBy_biological_occurrence" ("recordedBy_id", "biological_occurrence_id", "recordNumber") (SELECT id AS "recordedBy_id", new_ref."occurrenceID", new_ref."recordNumber" FROM "recordedBy_id") ON CONFLICT DO NOTHING
	) INSERT INTO "identifiedBy_biological_occurrence" (value, "biological_occurrence_id") (SELECT id AS value, new_ref."occurrenceID" FROM "identifiedBy_id") ON CONFLICT DO NOTHING;
END
$$ LANGUAGE plpgsql;

CREATE OR REPLACE RULE "_INSERT" AS
    ON INSERT TO biological_occurrence_view 
    DO INSTEAD SELECT func_setof_biological_occurrence(NEW.*);