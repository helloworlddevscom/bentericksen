#!/bin/bash

# crontab
# * * * * * /code/laravel/check-job.sh > /code/laravel/check-job.log
BASE=/code/laravel
SCRIPTDIR=/code/laravel

command="artisan queue:work"
running=$(ps ax | grep -v grep | grep "$command" | wc -l)
retry_file=$SCRIPTDIR/job-retries.txt
max_retries=5

if [ ! -f "$retry_file" ]; then
  echo 0 > $retry_file
fi

current_retry=$(cat $retry_file)

if [ $current_retry -gt $max_retries ]; then
  echo "FATAL ERROR, BENT QUEUES PROCESS CANNOT BE RESTARTED!!!!"
  exit
fi

if [ $running -gt 0 ]; then
  echo 0 > $retry_file
  echo "Health check success"
else
  echo $(($(cat $retry_file)+1)) > $retry_file
  echo "Command is not running. Attempting to restart"
  $SCRIPTDIR/run-queue.sh
fi

current_retry=$(cat $retry_file)

if [ $current_retry -eq $max_retries ]; then
  echo "FATAL ERROR, BENT QUEUES PROCESS CANNOT BE RESTARTED!!!!"

cat << EOF > $SCRIPTDIR/error_email.txt
Subject: Bent Jobs are down
The Bent Ericksen queue worker is down and can not be restarted.'
EOF

  sendmail $(echo {mike,dan,jeff}@helloworlddevs.com | tr ' ' ,) < $SCRIPTDIR/error_email.txt
fi