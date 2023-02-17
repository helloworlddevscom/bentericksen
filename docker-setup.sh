#!/bin/bash

docker-compose build

docker-compose run --rm db ./import.sh && docker-compose run --rm php composer install

docker-compose run --rm node ./setup.sh

chmod -R 0755 laravel/storage

docker-compose run --rm php php artisan cache:clear

docker-compose run --rm php php artisan key:generate 

docker-compose run --rm php php artisan config:cache