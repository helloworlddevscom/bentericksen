#!/bin/bash

docker-compose run php supervisord

docker-compose run php supervisorctl start laravel-worker:*
