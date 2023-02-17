#!/bin/bash
set -eo pipefail

cd "$(dirname "$0")/.."

BASE_DIR="$PWD"


# This will fail (or unexpectedly succeed) if your local username is bamboo,
# and if your HOME dir is /home/bamboo. This logic's here primarily as a
# convenience for local development.

# PHP + Laravel
cd "$BASE_DIR/docker/php"

docker login -u danlinn -p 274b6ed7-289e-4fb7-a0df-03c3d23ab813

docker build -t hrdirector/laravel:2.0 .

cd ../../laravel

docker run -t --rm -u "$UID" -v "$(pwd)":/code/laravel -w /code/laravel hrdirector/laravel:2.0 /bin/sh -c "php -v; composer -V; composer install --no-progress --no-ansi --no-suggest --prefer-source --no-interaction; chown -R $UID vendor; composer dump-autoload"

cd "$BASE_DIR" || exit

# Node Modules / Vue.js
# The laravel container used above has node 8. We need node 10, so we use a different image for the npm tasks.
docker run -t --rm -v "$(pwd)":/app -w /app -e 'HOME=/tmp' mhart/alpine-node:14 \
  sh -c "apk --update --no-progress add bash build-base lcms2-dev libpng-dev python2 && cd laravel && echo 'Node version: ' && node -v && npm install --no-progress && npm run prod ; chown -R $UID node_modules ../httpdocs/assets/"
# The alpine container complained that 'su' is not setuid, and sudo complained
# that "$UID" doesn't exist, so we "chown -R" on root owned files

echo "Build complete!"
