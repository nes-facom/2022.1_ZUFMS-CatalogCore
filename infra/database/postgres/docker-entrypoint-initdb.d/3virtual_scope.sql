CREATE OR REPLACE FUNCTION func_get_inherited_scopes(scope_name TEXT) RETURNS TEXT[] AS $$
    SELECT array_agg(descendant_scope.name) AS scopes FROM scope_closure_table
        JOIN scope AS ancestor_scope ON ancestor_scope.id = ancestor 
        JOIN scope AS descendant_scope ON descendant_scope.id = descendant
        WHERE ancestor_scope.name = 'admin'
        GROUP BY ancestor_scope.name;
$$ LANGUAGE sql;