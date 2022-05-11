# Contribuindo com este repositório

Através deste documento, padronizamos a forma como as operações sobre este repositório acontecem. É importante que tanto colaboradores externos quanto internos sigam os padrões aqui estabelecidos para que possamos todos estar a par das alterações realizadas no produto e para que isto aconteça da maneira mais eficiente possível.

#### Sumário

- [Sobre o projeto](#sobre-o-projeto)
- [Como contribuir](#como-contribuir)
  - [Sinalizar bugs](#sinalizar-bugs)
  - [Indicar adição/melhoria nas funcionalidades](#indicar-adicaomelhoria-nas-funcionalidades)
  - [Desenvolvimento das alterações](#desenvolvimento-das-alteracoes)
    - [Criação de branchs](#criacao-de-branchs)
    - [Mensagens de commit](#mensagens-de-commit)
  - [Pull Requests](#pull-requests)
  - [Versionamento](#versionamento)
- [Informações adicionais](#additional-notes)

## Sobre o projeto

A documentação geral do projeto pode ser encontrada na pasta [docs](docs). Informações adicionais específicas de cada módulo podem ser encontradas em seus respectivos `README.md`s.

O versionamento do projeto segue a especificação [SemVer](https://semver.org/lang/pt-BR/), que estabelece o versionamento semântico das entregas.

## Como contribuir

Uma vez fazendo parte da equipe de desenvolvimento da aplicação, sua rotina de desenvolvimento deve conter as seguintes situações:

### Sinalizar bugs

Sempre que identificar um bug na aplicação, verifique se este já não foi reportado. Se não, uma issue deve ser aberta seguindo o [template de issue](.github/ISSUE_TEMPLATE.md). Ofereça o máximo possível de informação a respeito, e, se possível, fotos e/ou vídeos do problema acontecendo.

### Indicar adição/melhoria nas funcionalidades

Verifique se esta funcionalidade está alinhada com a [documentação do projeto](docs) e se já não há alguma informação a seu respeito. Se não, uma issue pode ser aberta seguindo o [template de issue](.github/ISSUE_TEMPLATE.md). 

### Desenvolvimento das alterações

Durante o desenvolvimento das alterações no código do repositório, alguns padrões devem ser seguidos:

#### Criação de branchs

O projeto conta com duas branchs canônicas. A `main`, que reflete o código em operação em produção, e a `dev`, que reflete as alterações que serão introduzidas na próxima versão da aplicação. 

Sempre que for iniciar o desenvolvimento de algo, crie uma branch baseada na branch `dev` para suas alterações. O nome da branch deve seguir o padrão `tipo/nome-alteração`, onde 

- o tipo deve seguir os tipos utilizados na [Conventional Commits](https://www.conventionalcommits.org/pt-br/v1.0.0/#resumo);
- o nome da alteração um nome descritivo e sucinto para a alteração realizada;

Ex.: `feat/rotas-crud-empenho`

#### Mensagens de commit

Todas as mensagens de commit devem seguir a especificação [Conventional Commits](https://www.conventionalcommits.org/pt-br/v1.0.0/). Sempre que necessário, você *deve* incluir descrições elucidativas no corpo da mensagem do commit:

```bash
git add .
git commit 
```

```txt
fix: Corrige o problema X

Y foi realizado para resolver o problema. Z e W foram levados em consideração.
A discussão relacionada ao problema pode ser encontrada na issue #1

TODO: Implementar pendências A, B e C
```

### Pull Requests

Uma vez desenvolvidas as alterações no código, uma PR deve ser aberta para realizar a adição das mudanças realizadas na branch `dev` seguindo o [template de PR](.github/PR_TEMPLATE.md). Caso as mudanças não tenham sido finalizadas, a *label* `work-in-progress` deve ser adicionada à PR.

Todas as referências a versão atual do projeto devem ser alteradas seguindo a especificação [SemVer](https://semver.org/lang/pt-BR/).

Assim que uma nova versão for estabilizada, uma PR da branch `dev` na `main` deve ser realizada por algum administrador do repositório, dando origem à uma nova *release* do projeto.

### Informações adicionais