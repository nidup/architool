#!/usr/bin/env bash

docker-compose run --rm devtools /bin/bash -c "php bin/console $1 $2 $3 $4 $5 $6"
