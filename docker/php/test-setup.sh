#!/bin/bash

n=0
until [ $n -ge 25 ]
do
    nc -z -v -w30 db 3306 && break

    n=$[$n+1]

    echo -e "\nWaiting for database connection...\n"
    sleep 5
done

sleep 10

nc -z -v -w30 db 3306 || exit 1

chown -R www-data:www-data storage

chmod -R 0755 storage

php artisan config:cache --env=testing

php artisan migrate

php artisan db:seed


