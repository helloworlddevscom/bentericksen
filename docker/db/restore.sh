#!/bin/bash
source laravel/.env

docker-entrypoint.sh mysqld &

n=0
until [ $n -ge 25 ]
do
    nc -z -v -w30 localhost 3306 && break

    n=$[$n+1]

    echo -e "\nWaiting for database connection...\n"
    sleep 5
done

nc -z -v -w30 localhost 3306 || exit 1

gunzip -c database/default_$1.sql.gz | mysql -u$DB_USERNAME -h localhost  -p$DB_PASSWORD $DB_DATABASE