CREATE OR REPLACE FUNCTION func_get_inherited_scopes(scope_name TEXT) RETURNS TEXT[] AS $$
    SELECT descendant_scope.name AS scope_name FROM scope_closure_table
        JOIN scope AS ancestor_scope ON ancestor_scope.id = ancestor 
        JOIN scope AS descendant_scope ON descendant_scope.id = descendant
        WHERE ancestor_scope.name = scope_name;
$$ LANGUAGE sql;

CREATE OR REPLACE VIEW "client_inherited_scope" AS  
    SELECT "client_allowed_scope".client_id, "ancestor_scope".id AS inherited_from_scope_id, 
                        "ancestor_scope".name AS inherited_from_scope_name, "descendant_scope".id AS inherited_scope_id,
                        "descendant_scope".name AS inherited_scope_name
            FROM "client_allowed_scope"
            LEFT JOIN "scope_closure_table" ON "scope_closure_table".ancestor = "client_allowed_scope".scope_id
            LEFT JOIN "scope" AS "descendant_scope" ON "descendant_scope".id = "scope_closure_table".descendant
            LEFT JOIN "scope" AS "ancestor_scope" ON "ancestor_scope".id = "scope_closure_table".ancestor;

CREATE OR REPLACE VIEW "user_inherited_scope" AS  
    SELECT "user_allowed_scope".user_id, "ancestor_scope".id AS inherited_from_scope_id, 
                        "ancestor_scope".name AS inherited_from_scope_name, "descendant_scope".id AS inherited_scope_id,
                        "descendant_scope".name AS inherited_scope_name
            FROM "user_allowed_scope"
            LEFT JOIN "scope_closure_table" ON "scope_closure_table".ancestor = "user_allowed_scope".scope_id
            LEFT JOIN "scope" AS "descendant_scope" ON "descendant_scope".id = "scope_closure_table".descendant
            LEFT JOIN "scope" AS "ancestor_scope" ON "ancestor_scope".id = "scope_closure_table".ancestor;