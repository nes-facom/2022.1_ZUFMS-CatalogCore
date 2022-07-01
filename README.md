<div align="center">
<img src=".github/assets/zufms-logo.png" width="100" />

## Coleção Zoológica Online ZUFMS

Coleção Zoológica – Incremento e movimentação do acervo

<img src="https://shields.io/badge/stack-Laravel-FF2D20?logo=laravel&style=flat-square" alt="stack-laravel"/>
<img src="https://shields.io/badge/stack-Vue.js-4FC08D?logo=vuedotjs&style=flat-square" alt="stack-vuejs"/>
<img src="https://shields.io/badge/lang-TypeScript-3178C6?logo=typescript&style=flat-square" alt="lang-typescript"/>

</div>

## Requisitos

Docker version 20.10.17, build 100c701 (recomendado)

Docker Compose version v2.6.0 (recomendado)

https://docs.docker.com/engine/install/ubuntu/

## Ambiente de desenvolvimento

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
## Ambiente de produção

```docker-compose -f docker-compose.yml```

```docker-compose.prod.yml up api```
