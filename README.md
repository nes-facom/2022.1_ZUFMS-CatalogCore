<div align="center">
<img src=".github/assets/zufms-logo.png" width="100" />

## Coleção Zoológica Online ZUFMS

Coleção Zoológica – Incremento e movimentação do acervo

<img src="https://shields.io/badge/stack-Laravel-FF2D20?logo=laravel&style=flat-square" alt="stack-laravel"/>
<img src="https://shields.io/badge/stack-Vue.js-4FC08D?logo=vuedotjs&style=flat-square" alt="stack-vuejs"/>
<img src="https://shields.io/badge/lang-TypeScript-3178C6?logo=typescript&style=flat-square" alt="lang-typescript"/>

</div>

## Visão Geral

## Dependências

- Docker version 20.10.17, build 100c701 (recomendado)
- Docker Compose version v2.6.0 (recomendado)
  - Instruções de instalação (Ubuntu) https://docs.docker.com/engine/install/ubuntu/

## Uso

### Ambiente de desenvolvimento


#### Subir o ambiente

```bash
sudo docker compose up
```

#### Voltar o ambiente ao estado inicial

```bash 
sudo docker compose down
sudo docker compose up --build
```
### Problemas conhecidos

Algumas distros linux possuem alguns problemas relacionados ao permissionamento dos volumes do Docker, como foi o caso do Ubuntu 20.04 utilizado na maioria das máquinas utilizadas no desenvolvimento do projeto.

A distro utilizada que não sofreu desses problemas foi o Fedora versões 35 e 36.

Seguem algumas resoluções para problemas ocasionados por essa situação:

- API não realizou o download das dependências (criação da pasta api/vendor) (ambiente de desenvolvimento)
  ```bash
  sudo docker exec -it zufms_api bash
  composer install
  composer update
  ```
  - Caso não seja possível executar os comandos acima, comente a seguinte linha do arquivo `docker-compose.yml`:
  ```yml
  api:
    container_name: zufms_api
    build: ./api
    depends_on:
      - db
    volumes:
    # Comentar essa linha deixará de realizar a montagem dos dados da sua máquina no container, perdendo a funcionalidade de Hot Reload da API durante o desenvolvimento
    #  - ./api:/var/www:z
  ``` 

- API não carregou os dados do `.env`
  ```bash
  sudo docker exec -it zufms_api bash
  php artisan config:clear
  ```

- API não carregou corretamente as rotas
  ```bash
  sudo docker exec -it zufms_api bash
  php artisan route:clear
  php artisan route:cache
  ```

### Ambiente de produção

#### Subir o ambiente

```bash
sudo docker compose -f docker-compose.prod.yml up
```

#### Voltar o ambiente ao estado inicial

```bash 
sudo docker compose down
sudo docker volume rm zufms_web_build zufms_postgres_data
sudo docker compose up --build
```

## Scripts auxiliares

#### Geração do schema do banco

Script utilizado quando alguma alteração no schema do banco (`docs/schema.dbml`) é realizada. Os arquivos gerados (`infra/database/postgres/docker-entrypoint-initdb.d/2schema.sql`) não devem ser alterados manualmente, para preservar a integridade dos dados da documentação.

Para visualização gráfica do `schema.dbml`, utilize o site [dbdiagram.io](https://dbdiagram.io/home)

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

Os escopos de autorização utilizados na aplicação são salvos no banco de dados para, futuramente, permitir sua manipulação com a aplicação em operação.

A geração inicial da seed desses escopos é feita através do consumo do arquivo `docs/scopes.bpmn`. Uma visualização deste arquivo no formato de imagem está disponível em `docs/scopes.png`.

O script a seguir lê este arquivo, e gera os `INSERT`s necessários no banco para alimentar a (`Closure Table`)[https://www.slideshare.net/billkarwin/models-for-hierarchical-data] que armazena a hieraquia entre os escopos no banco de dados.

##### Dependências

- Python 3 

```bash
make gen_sql_scopes > infra/database/postgres/docker-entrypoint-initdb.d/7seed_scopes.sql
```

#### Atualização da lib client da API para a web

```bash
make gen_api_client
```

#### Geração do PDF contendo o código da aplicação

```bash
make gen_code_pdf
```

## Colaboradores

<table>
  <tr>
    <td align="center">
      <a href="#">
        <img src="https://avatars.githubusercontent.com/u/37355465?v=4" width="100px;" alt="Foto do Murilo Mamede"/><br>
        <sub>
          <b>Murilo Mamede</b>
        </sub>
      </a>
    </td>
    <td align="center">
      <a href="#">
        <img src="https://avatars.githubusercontent.com/u/7810622?v=4" width="100px;" alt="Foto do Lucas Barbosa"/><br>
        <sub>
          <b>Lucas Barbosa</b>
        </sub>
      </a>
    </td>
    <td align="center">
      <a href="#">
        <img src="https://avatars.githubusercontent.com/u/20213046?v=4" width="100px;" alt="Foto do Yan Sérgio"/><br>
        <sub>
          <b>Yan Sérgio</b>
        </sub>
      </a>
    </td>
    <td align="center">
      <a href="#">
        <img src="https://avatars.githubusercontent.com/u/47815249?v=4" width="100px;" alt="Foto do Marcos David Almeida"/><br>
        <sub>
          <b>Marcos David Almeida</b>
        </sub>
      </a>
    </td>
    <td align="center">
      <a href="#">
        <img src="https://nes.facom.ufms.br/storage/files/people/1647479634-leonardo-peralta-piassi.jpg" width="100px;" alt="Foto do Leonardo Peraltas"/><br>
        <sub>
          <b>Leonardo Peralta</b>
        </sub>
      </a>
    </td>
  </tr>
</table>


## Como contribuir

Veja o arquivo `CONTRIBUTING.md`