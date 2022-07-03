#!/bin/bash

docker build --rm -t zufms_code2pdf infra/scripts/code2pdf
docker run --name zufms_code2pdf -v "$(pwd)":/app/ zufms_code2pdf