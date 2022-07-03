#!/bin/bash

docker run --rm \
    -v $PWD:/local openapitools/openapi-generator-cli generate \
    -i docs/petstore.yaml \
    -g typescript-axios \
    -o web/client