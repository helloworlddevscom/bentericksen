## BonusPro E2E testing overview
All E2E test now can be composed of a base test (located in tests/E2E ) along with a seed (located in /assets/js/store/mock-data )


---
## Set up the dev server

`./test-e2e-local-all.sh` - This will set up all of the required steps needed to run an e2e dev server.


---

## Local testing
To run a test locally:

- Set Node:  nvm use 14.17.1
- Start Testing Setup:  ./test-e2e-local-all.sh
- update .env and .env.testing ENV's
- cd laravel & build latest JS:  npm install

`yarn test:e2e:local --spec <E2E test>`

EX:  `yarn test:e2e:local --spec at-bonusPro-4m-20p-equal`

---

## Generate a new E2E test

To generate a new template for testing, use the genTestCase.sh script

Usage:  (note:  script located in/laravel/resources/assets/js/store/mock-data )

./genTestCase.sh - Generate generic test (and seed) with 2m, 15%, hours bonus.  2 months passing.  
./genTestCase.sh -h - Show script usage and options
./genTestCase.sh -x true -n <test name>  - delete a testcase (...as each testcase creates a seed and test, this is a shortcut to delete any tests you don't want easily)
EX:  ./genTestCase.sh -x true -n gen-2m-15p-hours will delete the default testcase from the repo

---
## Running tests

The spec takes wildcards, so to run a short battery of tests:
* `yarn test:e2e:local --spec at-bonusPro-4m-20p-equal` --> to run a single, specific test
* `yarn test:e2e:local --spec at-bonusPro-4m-20p` --> to run all types of test that match month/percent average
* `yarn test:e2e:local --spec at-bonusPro-4m` --> to run all 4m test batches
* `yarn test:e2e:local --spec at-bonusPro` --> to run all bonusPro automated test (at)

---
## Hygiene Plan Refactor

For the hygiene plan, a base E2E test was created to facilitate test-driven development
yarn test:e2e:local --spec tdd-bonusPro-base

- This test creates a 5th employee (3 asst/admin, & 2 hygiene).
- This test successfully runs through initialization of a dual plan (both normal AND hygiene), but presently fails in the month-to-month as the hygiene plan functionality is not complete.


### Frequent Problems

#### Chrome error:

> session not created: This version of ChromeDriver only supports Chrome version 84

This indicates there is a mismatch between your local version of chrome and the installed chromedriver. The easiest fix is to update your chromedriver version. To do this, edit laravel/package.json file to read `>=94' for chromedriver in the dependency listings and re-install your packages.

#### Data error:

If there is problem with missing plans, login issues or similar. Delete the contents of `data-e2e` folder and re-boot your test environment.