<div align="center">
<img src=".github/assets/zufms-logo.png" width="100" />

## Coleção Zoológica Online ZUFMS

Coleção Zoológica – Incremento e movimentação do acervo

<img src="https://shields.io/badge/stack-Laravel-FF2D20?logo=laravel&style=flat-square" alt="stack-laravel"/>
<img src="https://shields.io/badge/stack-Vue.js-4FC08D?logo=vuedotjs&style=flat-square" alt="stack-vuejs"/>
<img src="https://shields.io/badge/lang-TypeScript-3178C6?logo=typescript&style=flat-square" alt="lang-typescript"/>

</div>

## Scripts

#### Geração do schema do banco

##### Dependências

- dbml2sql
  - Instalação
  ```
  npm install -g @dbml/cli
  ```

```bash
make gen_sql_schema > infra/database/postgres/docker-entrypoint-initdb.d/2schema.sql
```

#### Geração da seed para popular o banco com os scopes
```bash
make gen_sql_scopes > infra/database/postgres/docker-entrypoint-initdb.d/6seed_scopes.sql
```