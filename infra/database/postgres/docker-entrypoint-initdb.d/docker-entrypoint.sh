#!/bin/bash

for file in `ls *.sql`; do
    psql -v $POSTGRES_DB -f $file
done