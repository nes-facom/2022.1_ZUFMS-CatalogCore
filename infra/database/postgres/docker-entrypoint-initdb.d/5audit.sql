CREATE TABLE audit (
    table_name text not null,
    record_pks text[],
    timestamp timestamp with time zone not null default current_timestamp,
    action char(1) NOT NULL check (action in ('I','D','U')),
    old_data jsonb,
    new_data jsonb,
    query text
);

CREATE OR REPLACE FUNCTION jsonb_diff_val(val1 JSONB,val2 JSONB)
RETURNS JSONB AS $$
DECLARE
  result JSONB;
  v RECORD;
BEGIN
   result = val1;
   FOR v IN SELECT * FROM jsonb_each(val2) LOOP
     IF result @> jsonb_build_object(v.key,v.value)
        THEN result = result - v.key;
     ELSIF result ? v.key THEN CONTINUE;
     ELSE
        result = result || jsonb_build_object(v.key,'null');
     END IF;
   END LOOP;
   RETURN result;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION get_table_pks(table_name TEXT) RETURNS TEXT[] AS $$
    SELECT array_agg(attname::TEXT) as pks
        FROM pg_index
        JOIN pg_attribute ON attrelid = indrelid AND attnum = ANY(indkey) 
        WHERE indrelid = table_name::regclass AND indisprimary
        GROUP BY indrelid;
$$ LANGUAGE SQL;

CREATE OR REPLACE FUNCTION get_record_pks(table_record jsonb, table_name TEXT) RETURNS TEXT[] AS $$
    SELECT array_agg(table_record->pk::TEXT) FROM unnest(get_table_pks(table_name)) AS pk
$$ LANGUAGE SQL;

CREATE OR REPLACE FUNCTION audit_trigger() RETURNS trigger AS $$
BEGIN
    if (TG_OP = 'UPDATE') then
        insert into audit (table_name,record_pks,action,old_data,new_data,query) 
            values (TG_TABLE_NAME::TEXT,get_record_pks(row_to_json(NEW)::jsonb, TG_TABLE_NAME::TEXT),substring(TG_OP,1,1),jsonb_diff_val(row_to_json(OLD)::JSONB, row_to_json(NEW)::JSONB),jsonb_diff_val(row_to_json(NEW)::JSONB, row_to_json(OLD)::JSONB),current_query());
        RETURN NEW;
    elsif (TG_OP = 'DELETE') then
        insert into audit (table_name,record_pks,action,old_data,query) 
            values (TG_TABLE_NAME::TEXT,get_record_pks(row_to_json(NEW)::jsonb, TG_TABLE_NAME::TEXT),substring(TG_OP,1,1),row_to_json(OLD)::JSONB,current_query());
        RETURN OLD;
    elsif (TG_OP = 'INSERT') then
        insert into audit (table_name,record_pks,action,new_data,query) 
            values (TG_TABLE_NAME::TEXT,get_record_pks(row_to_json(NEW)::jsonb, TG_TABLE_NAME::TEXT),substring(TG_OP,1,1),row_to_json(NEW)::JSONB,current_query());
        RETURN NEW;
    else
        RAISE WARNING '[AUDIT.IF_MODIFIED_FUNC] - Other action occurred: %, at %',TG_OP,now();
        RETURN NULL;
    end if;
END;
$$
LANGUAGE plpgsql;

CREATE VIEW audit_conflict AS SELECT 
    EXTRACT(EPOCH FROM (audit.timestamp - audit2.timestamp)) as timestamp_diff, audit.table_name, audit.record_pks, 
	audit.action as audit1_action, audit2.action as audit2_action, audit.old_data as audit1_old_data, 
	audit2.old_data as audit2_old_data, audit.new_data as audit1_new_data, audit2.new_data as audit2_new_data
	FROM audit 
		JOIN audit as audit2 ON audit2.record_pks = audit.record_pks
	WHERE EXTRACT(EPOCH FROM (audit.timestamp - audit2.timestamp)) BETWEEN 1 AND 30;

CREATE TRIGGER "user_audit" BEFORE INSERT OR UPDATE OR DELETE ON "user" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "client_audit" BEFORE INSERT OR UPDATE OR DELETE ON "client" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "scope_audit" BEFORE INSERT OR UPDATE OR DELETE ON "scope" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "user_allowed_scope_audit" BEFORE INSERT OR UPDATE OR DELETE ON "user_allowed_scope" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "client_allowed_scope_audit" BEFORE INSERT OR UPDATE OR DELETE ON "client_allowed_scope" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "scope_closure_table_audit" BEFORE INSERT OR UPDATE OR DELETE ON "scope_closure_table" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "access_token_audit" BEFORE INSERT OR UPDATE OR DELETE ON "access_token" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "otp_audit" BEFORE INSERT OR UPDATE OR DELETE ON "otp" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:section_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:section" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "basisOfRecord_audit" BEFORE INSERT OR UPDATE OR DELETE ON "basisOfRecord" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "institutionCode_audit" BEFORE INSERT OR UPDATE OR DELETE ON "institutionCode" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "collectionCode_audit" BEFORE INSERT OR UPDATE OR DELETE ON "collectionCode" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "datasetName_audit" BEFORE INSERT OR UPDATE OR DELETE ON "datasetName" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:shelfLocation_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:shelfLocation" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:flaskLocation_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:flaskLocation" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "preparations_audit" BEFORE INSERT OR UPDATE OR DELETE ON "preparations" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "sex_audit" BEFORE INSERT OR UPDATE OR DELETE ON "sex" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "lifeStage_audit" BEFORE INSERT OR UPDATE OR DELETE ON "lifeStage" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "reproductiveCondition_audit" BEFORE INSERT OR UPDATE OR DELETE ON "reproductiveCondition" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "establishmentMeans_audit" BEFORE INSERT OR UPDATE OR DELETE ON "establishmentMeans" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "behavior_audit" BEFORE INSERT OR UPDATE OR DELETE ON "behavior" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "disposition_audit" BEFORE INSERT OR UPDATE OR DELETE ON "disposition" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "habitat_audit" BEFORE INSERT OR UPDATE OR DELETE ON "habitat" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "continent_audit" BEFORE INSERT OR UPDATE OR DELETE ON "continent" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "country_audit" BEFORE INSERT OR UPDATE OR DELETE ON "country" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "verbatimLocality_audit" BEFORE INSERT OR UPDATE OR DELETE ON "verbatimLocality" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "stateProvince_audit" BEFORE INSERT OR UPDATE OR DELETE ON "stateProvince" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "county_audit" BEFORE INSERT OR UPDATE OR DELETE ON "county" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "municipality_audit" BEFORE INSERT OR UPDATE OR DELETE ON "municipality" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "geodeticDatum_audit" BEFORE INSERT OR UPDATE OR DELETE ON "geodeticDatum" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "locality_audit" BEFORE INSERT OR UPDATE OR DELETE ON "locality" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "waterBody_audit" BEFORE INSERT OR UPDATE OR DELETE ON "waterBody" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "identificationQualifier_audit" BEFORE INSERT OR UPDATE OR DELETE ON "identificationQualifier" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "kingdom_audit" BEFORE INSERT OR UPDATE OR DELETE ON "kingdom" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "phylum_audit" BEFORE INSERT OR UPDATE OR DELETE ON "phylum" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:subphylum_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:subphylum" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "class_audit" BEFORE INSERT OR UPDATE OR DELETE ON "class" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:subclass_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:subclass" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:superorder_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:superorder" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "order_audit" BEFORE INSERT OR UPDATE OR DELETE ON "order" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:suborder_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:suborder" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:infraorder_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:infraorder" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:superfamily_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:superfamily" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "family_audit" BEFORE INSERT OR UPDATE OR DELETE ON "family" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:subfamily_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:subfamily" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "genus_audit" BEFORE INSERT OR UPDATE OR DELETE ON "genus" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:tribe_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:tribe" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "artificial:subtribe_audit" BEFORE INSERT OR UPDATE OR DELETE ON "artificial:subtribe" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "subgenus_audit" BEFORE INSERT OR UPDATE OR DELETE ON "subgenus" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "taxonomicStatus_audit" BEFORE INSERT OR UPDATE OR DELETE ON "taxonomicStatus" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "nomenclaturalCode_audit" BEFORE INSERT OR UPDATE OR DELETE ON "nomenclaturalCode" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "nameAccordingTo_audit" BEFORE INSERT OR UPDATE OR DELETE ON "nameAccordingTo" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "specie_audit" BEFORE INSERT OR UPDATE OR DELETE ON "specie" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();
CREATE TRIGGER "biological_occurrence_audit" BEFORE INSERT OR UPDATE OR DELETE ON "biological_occurrence" FOR EACH ROW EXECUTE PROCEDURE audit_trigger();