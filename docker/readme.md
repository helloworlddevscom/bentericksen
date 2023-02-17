# Setup

### Prerequisites

#### Docker
- https://www.docker.com/get-started

#### Docker Compose
- https://docs.docker.com/compose/install/

#### SSH Key

You must have your ssh public key set up with the MT servers in order to pull the database locally.
Edit docker-compose.yml if your key is not in the standard location:

```
- ~/.ssh/id_rsa:/root/.ssh/id_rsa
```


## Auto setup

Runs through all the setup processes once ssh key is in place.

```
./docker-setup.sh

```

## Manual setup

### Build docker images

```
docker-compose build
```

### Composer Install

Install app dependencies

```
docker-compose run --rm php composer install
```

### Import data


```
docker-compose run --rm db ./import.sh
```


### Build assets


#### Initial setup
```
docker-compose run --rm node ./setup.sh
```

#### Building

```
docker-compose run --rm node npm run prod
```

## Usage

### Launch stack

```
docker-compose up
```

- Local server will be available at `http://hrdirector.localhost`

- Mail server will be available at `http://localhost:1080/`

- Adminer server will be available at `http://localhost:8080/`

### Pull DB

Import database from environment

Options: [dev|staging|prod]

Defaults to dev

```
docker-compose run --rm db ./import.sh [environment]
```

### Check stack

In another terminal window you can run this to check on your server status:

```
docker ps
```

### Stop stack

In the directory you started the stack in, run the following:

```
dock-compose down
```


### Testing

In order to run tests locally, you must first log into the github docker repository in order to pull the prebuilt images.


```
docker login docker.pkg.github.com -u [GITHUB USERNAME] -p [GITHUB PERSONAL ACCESS TOKEN]
```

If you'd rather not do this, you can switch the docker-compose.[test].yml file to use their respective build locations to build the images locally.

For example the unit testing stack which uses docker-compose.testing.yml goes from:

```
php:
    image: docker.pkg.github.com/helloworlddevs/bentericksen/bentericksen-php:latest
```

to:

```
php:
    build:
      context: ./docker/php
```

once update, you must run build with the respective docker file:


```
docker-compose -f docker-compose.testing.yml build
```

#### Unit Tests

```
docker-compose -f docker-compose.testing.yml run --rm php
```

#### E2E Tests

To emulate CI e2e testing:

```
docker-compose -f docker-compose.e2e.yml run --rm php ./test-setup.sh
docker-compose -f docker-compose.e2e.yml run --rm node
```

To run e2e testing locally:

```
./test-e2e-local.sh
```
