#!/bin/bash

./test-e2e.sh windows.chrome docker-compose.e2e.yml $1 $2

exit $?