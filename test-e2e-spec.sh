#!/bin/bash

BROWSER=$1

DOCKERFILE=$2

BROWSER_STACK_USER=$3

BROWSER_STACK_KEY=$4

SPEC=${5:-*}

if [ -z "$BROWSER" ]
 then
  echo -e "Please specify browser: \c"

  read BROWSER
fi


if [ -z "$DOCKERFILE" ]
 then
  echo -e "Please specify dockerfile: \c"

  read DOCKERFILE
fi

if [ -z "$BROWSER_STACK_USER" ]
 then
  echo -e "Please specify BrowserStack User: \c"

  read BROWSER_STACK_USER
fi

if [ -z "$BROWSER_STACK_KEY" ]
 then
  echo -e "Please specify BrowserStack Key: \c"

  read BROWSER_STACK_KEY
fi

docker-compose down --remove-orphans

rm -rf ./data-e2e

docker-compose -f $DOCKERFILE run --rm php ./test-setup.sh

docker-compose down --remove-orphans

docker-compose -f $DOCKERFILE run --rm\
  -e BROWSER_STACK_USER=$BROWSER_STACK_USER\
  -e BROWSER_STACK_KEY=$BROWSER_STACK_KEY\
  -e BROWSER=$BROWSER\
  -e SPEC=$SPEC\
  node ./test.sh 

exit $?