#!/bin/bash

BASEDIR=$(dirname "$0")
BASE=${1:-$BASEDIR}
FILE=$BASE/run-queue.lock
LOGS=$BASEDIR/run-queue.logs

touch $FILE
touch $LOGS

while [[ -f "$FILE" ]]; do
    echo "Run Queue Active"
    echo "$BASE/artisan"
    /usr/local/bin/php $BASE/artisan queue:work --timeout=0 >> $LOGS
done &
