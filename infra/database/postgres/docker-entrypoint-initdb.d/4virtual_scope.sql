CREATE OR REPLACE FUNCTION func_get_inherited_scopes(scope_name TEXT) RETURNS TEXT AS $$
    SELECT descendant_scope.name AS scope_name FROM scope_closure_table
        JOIN scope AS ancestor_scope ON ancestor_scope.id = ancestor 
        JOIN scope AS descendant_scope ON descendant_scope.id = descendant
        WHERE ancestor_scope.name = scope_name;
$$ LANGUAGE sql;

CREATE OR REPLACE VIEW "client_inherited_scope" AS  
    SELECT cas.client_id, "ancestor_scope".id AS inherited_from_scope_id, 
        "ancestor_scope".name AS inherited_from_scope_name, "descendant_scope".id AS inherited_scope_id,
        "descendant_scope".name AS inherited_scope_name FROM client_allowed_scope AS cas
    LEFT JOIN scope_closure_table as ct ON ct.ancestor = cas.scope_id OR EXISTS (SELECT FROM scope_closure_table as ct2 WHERE ct2.ancestor = cas.scope_id AND ct2.descendant = ct.descendant)
    LEFT JOIN scope AS ancestor_scope ON ancestor_scope.id = ct.ancestor
    LEFT JOIN scope AS descendant_scope ON descendant_scope.id = ct.descendant;

CREATE OR REPLACE VIEW "user_inherited_scope" AS  
    SELECT uas.user_id, "ancestor_scope".id AS inherited_from_scope_id, 
        "ancestor_scope".name AS inherited_from_scope_name, "descendant_scope".id AS inherited_scope_id,
        "descendant_scope".name AS inherited_scope_name FROM user_allowed_scope AS uas
    LEFT JOIN scope_closure_table as ct ON ct.ancestor = uas.scope_id OR EXISTS (SELECT FROM scope_closure_table as ct2 WHERE ct2.ancestor = uas.scope_id AND ct2.descendant = ct.descendant)
    LEFT JOIN scope AS ancestor_scope ON ancestor_scope.id = ct.ancestor
    LEFT JOIN scope AS descendant_scope ON descendant_scope.id = ct.descendant;