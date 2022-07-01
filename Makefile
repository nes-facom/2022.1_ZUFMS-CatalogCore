SHELL := /bin/bash 

gen_sql_schema:
	@dbml2sql docs/schema.dbml --pgsql
gen_sql_scopes: 
	@python3 infra/scripts/generate_scopes_sql.py docs/scopes.bpmn
gen_code_pdf:
	@infra/scripts/code2pdf/script.sh