#!/bin/bash
echo "Starting php-fpm..."

php-fpm &

echo "Starting queue worker..."

php /code/laravel/artisan queue:work --queue=policy-updates --timeout=0 &

php /code/laravel/artisan queue:work --timeout=0


