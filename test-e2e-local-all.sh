#!/bin/bash
DOCKERFILE=docker-compose.e2e-local.yml

docker-compose down --remove-orphans

rm -rf ./data-e2e

docker-compose -f $DOCKERFILE run --rm php ./test-setup.sh

docker-compose down

docker-compose -f $DOCKERFILE up -d

echo "Testing server ready..."