from sys import argv
import xml.etree.ElementTree as ET

xmlns = {'bpmn': 'http://www.omg.org/spec/BPMN/20100524/MODEL'}


def task_to_dict_value(i, task):
    return (
        task.attrib['id'],
        {
            'index': i,
            'name': task.attrib['name'],
            'parent': getattr(task.find('bpmn:incoming', xmlns), 'text', None)})


def gen_scope_map(task_scopes):
    return dict([task_to_dict_value(i, task) for i, task in enumerate(task_scopes)])


def gen_intersection_map(sequence_flow_list):
    return dict([(intersection.attrib['id'], intersection.attrib) for intersection in sequence_flow_list])


def gen_scopes_sql_entries(task_scopes):
    return [(i, scope.attrib['name'], '') for i, scope in enumerate(task_scopes)]


def gen_closure_table_sql_entries(scopes_map, intersections_map):
    closure_sql_entries = []

    for scope in scopes_map.values():
        parent = scope['parent']

        closure_sql_entries.append(((scope['index']), (scope['index'])))

        while parent is not None:
            source_ref = intersections_map[parent]['sourceRef']
            parent_scope = scopes_map[source_ref]
            closure_sql_entries.append(
                ((parent_scope['index']), (scope['index'])))

            parent = parent_scope['parent']

    return closure_sql_entries


def format_sql_entries(table_name, values, sql_entries):
    sql_entries_string = ', '.join(map(str, sql_entries))
    return f'INSERT INTO "{table_name}" {values} VALUES {sql_entries_string};'


def main():
    if len(argv) < 2:
        print("usage: generate_scopes_sql.py path/to/scopes.bpmn")
        exit()

    scopes_bpmn_path = argv[1]

    tree = ET.parse(scopes_bpmn_path)
    definitions = tree.getroot()

    task_scopes = definitions.findall('./bpmn:process/bpmn:task', xmlns)
    sequence_flow_list = definitions.findall(
        './bpmn:process/bpmn:sequenceFlow', xmlns)

    scope_map = (gen_scope_map(task_scopes))
    scopes_sql_entries = gen_scopes_sql_entries(task_scopes)
    intersection_map = gen_intersection_map(sequence_flow_list)
    closure_table_sql_entries = gen_closure_table_sql_entries(
        scope_map, intersection_map)

    print(
        format_sql_entries(
            'scope', '(id, name, description)', scopes_sql_entries),
        format_sql_entries('scope_closure_table',
                           '(ancestor, descendant)',  closure_table_sql_entries),
        sep='\n\n')


if __name__ == "__main__":
    main()
