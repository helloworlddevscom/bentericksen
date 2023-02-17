# Purpose

This is the web app for the Bent Ericksen HR Director software (hrdirector.bentericksen.com).

This is a self-contained web app and does not include the code for the Bent Ericksen corporate/marketing site (bentericksen.com)

# Hosting overview

The Bent Ericksen sites are hosted on AWS and managed by Metal Toad.

* Dev: https://bentericksen-dev.metaltoad-sites.com (branch: dev)
* Feature: https://bentericksen-feature.metaltoad-sites.com/auth/login (branch: feature)
    * This site is used for development of the current feature/project (e.g., Bonus Pro or Digital Signature)
    * It's OK to check this out to a different branch if you want to test something. 
* Staging: https://bentericksen-staging.metaltoad-sites.com/auth/login (branch: staging)
* Prod: http://hrdirector.bentericksen.com/ (branch: prod)

# Build instructions

```Shell
cd laravel
composer install
bundler install
cp .env.example .env
php artisan key:generate
```

* edit `./laravel/.env` and change database name and credentials to match your system
* make sure the folder `./laravel/storage` is writable by Apache

## Database creation

There are two options for creating the database for this application. The first
will load up test data, while the second will only create the tables and a test
admin user necessary to use the base system

### Option 1: Pull dev DB (preferred)

* Create a MySQL database. Update .env file with the DB name.
* Run `cap dev db:pull`
* Run `make cc` (clears cache and runs DB migrations)

### Option 2: Migrate test data

* Create a MySQL database
* Import the contents of `./database`
    * ```cat database/20160113/*.sql | mysql -u <user> -p<password> <dbname>```
* Run `php ./laravel/artisan migrate`

### Option 3: Fresh install

* Create a MySql Database
* Ensure autoload file is up-to-date: `cd laravel; composer dump-autoload; cd ..`
* Run `php ./laravel/artisan migrate --seed`

This will create an admin user with the following credentials:

* username: `admin@bentericksen.com`
* password: `passpass`

### Creating users for QA

There is a script to add several test users (one in each role) to the database for QA purposes. This assumes you used
Option 1 above (pulling the dev db) and needs to be done after you pull the db.

Note: never run this on the servers! This is for local use only.

* Download this tinker script (note the .txt extension, which is a github restriction [no .php])
  * [add-qa-user-with-roles.txt](https://github.com/metaltoad/bentericksen/files/2752373/add-qa-user-with-roles.txt)
  * It's not necessary to rename the file. Keep it with the .txt extension.
* Run:

```
make composer-ida cc
php laravel/artisan tinker < ~/Downloads/add-qa-user-with-roles.txt
```

This will give you the following users:

* qa+admin@metaltoad.com
* qa+owner@metaltoad.com
* qa+consultant@metaltoad.com
* qa+manager@metaltoad.com
* qa+employee@metaltoad.com

They all belong to the Business named 'Metal Toad' and have the same password: `fff`

## PDF generation

In order to support the generation of PDFs (for manual creation),
[wkhtmltopdf](https://wkhtmltopdf.org/) needs to be installed. This can be done
with [Homebrew](https://brew.sh/). Run the following in the root of the repo:

```Shell
brew install Caskroom/cask/wkhtmltopdf
echo "\nWKHTMLTOPDF_PATH=`brew --prefix`/bin/wkhtmltopdf" >> laravel/.env
```

# Local Development

> Local stack is now setup to use docker. Please see [docker setup instructions](docker/readme.md)

Generate SSL certificate for localhost use

```
$ openssl req -config docker/nginx/openssl.cnf -new -sha256 -newkey rsa:2048 -nodes -keyout docker/nginx/nginx-selfsigned-hrdirector.key -x509 -days 825 -out docker/nginx/nginx-selfsigned-hrdirector.crt
```

### IMPORTANT: Minimum node.js requirements:

```
node: 10.6.0
npm: 6.1.0
```

The [Laravel 5 IDE Helper Generator](https://github.com/barryvdh/laravel-ide-helper)
is made available in this app via:

```bash
composer require --dev barryvdh/laravel-ide-helper
```

**NOTE**: the restriction to the `dev` (non-`production`) environment. To
(re-)generate, run:

```bash
php artisan ide-helper:generate # phpDoc generation for Laravel Facades
php artisan ide-helper:models   # phpDocs for models
php artisan ide-helper:meta     # PhpStorm Meta file

# - or -
make gen-ide-helpers
```
## Compiling JS assets
Some features require compiling JS assets (i.e Vue.js components on BonusPro). To compile the assets, or to watch for changes (Browsersync), execute the scripts below: 
```
cd laravel/
npm i
npm run dev
```
As a developer convenience, the following targets are available in the project
root `Makefile`:
```
make npm-run-dev
make npm-run-prod
make npm-run-watch
make npm-install
```

**NOTE:** The npm script uses BrowserSync to watch for changes in the code and
*reload your browser.

The `laravel/webpack.mix.js` file sets:
`proxy: 'https://bentericksen.localhost',` for use with `make npm-run-watch`
(which'll `open https://localhost:3000/`.)

What this means is that if you've checked out the git clone locally as
something other than `bentericksen` (like `bent`) then you will either have
to rename it to `bentericksen`, or make a symlink so that the `proxy` will
work.

## Upgrading NPM Packages

Some of the NPM packages are there for specific reasons, and/or are pinned to a specific version. See ./PACKAGE-MANAGER-NOTES.md for more info.

# Testing

This project contains automated tests for both the back-end PHP code (using PHPUnit) and the front-end VueJS code (using Jest).

## Setting up your local environment for running tests

There are a couple steps to set up your local environment to run the PHP tests.

1. Create an empty database in MySQL with a name like 'bent_test'
2. Create a .env file for the testing environment:

```
cp laravel/.env.testing.example laravel/.env.testing
```

Now, edit that file just like you did with the regular .env file above. Make sure the DB_DATABASE setting matches the database name from step 1. 

## Running Tests

To run the tests, use one of the following commands:

```
# run all tests
make test

# run only phpunit tests
make phpunit

# run only JavaScript tests
make js-test
```

Warning: While it's possible to run the tests by running PHPUnit directly, this is a bad idea. Laravel likes to run tests against the regular database, thus erasing the data in your development DB. The Makefile has special logic to force Laravel to use the test DB for the tests instead, leaving your data untouched. So, always use the Makefile.

## Coverage Report

You can run the PHPUnit tests and generate a coverage report by running:

```
make phpunit-coverage
```

To view the report, open laravel/coverage-report/index.html in a browser.

## Writing Back End Tests

Put your PHPUnit tests in laravel/tests/Unit. See the ReportTest class for an example of how to write a test, or see [the Laravel documentation](https://laravel.com/docs/5.6/testing).

## Writing Front End Tests

The Vue app (BonusPro) contains JavaScript unit tests built with the "@vue/test-utils" package.
 
You can write new tests by creating a new file with the `.spec.js` extension anywhere in the repo. The test-utils package will auto-detect them wherever they are. See laravel/resources/assets/js/utils.spec.js for an example.

For more information, consult the [vue-test-utils documentation](https://vue-test-utils.vuejs.org)

# Performance Tuning

There is a JMeter test suite in the repo at {root}/tests/jmeter/bent.jmx.

## Running the JMeter tests

First, Install and run the JMeter GUI:
 
```
brew install jmeter
jmeter
```

(or see https://jmeter.apache.org/usermanual/get-started.html#install for manual instructions)

1. Open the file bent.jmx in the JMeter GUI
2. Click on the "HR Director Test Plan"
3. Enter the domain (either your localhost domain, or the dev site)
4. Enter the password for the "Owner" user (password is stored in LastPass)
5. Run the test
6. Watch the "Aggregate Report" or "Summary Report" pages to see the response times for the various pages
7. If the error rate in the table is high for a given request, look in the "View Results Tree" page for error details (e.g., 500 error, bad password, etc.)
8. Some requests have names like "Policy 12456". If these give a 500 error, it's probably because the ID number is incorrect in the request. Go to the dev site, view the policy list, and find the IDs of a different policy. Then update the request in JMeter and try again.
9. Observe any slow pages (response times > 1-2 seconds). Open the pages in a browser and investigate why they're slow (e.g., slow queries appearing in the Laravel Debug Bar)
10. Note that some pages may be faster on localhost than on the dev/staging sites. It might be easier to identify slow pages by testing those sites, instead of localhost.

# E-Mail
For development purposes, you can enable Laravel to use a service, like Mailgun or Amazon SES, instead of sendmail on a local environment. 

Note: In non-prod environments, the system is configured to send emails only to @metaltoad.com and @bentericksen.com
 email addresses. For users that have email addresses on other domains, the system will record the emails in "test"
 status and not send them. You can view these by finding the user in the Login List on the Admin dashboard and clicking
 "Email Log".

## Configuring Mailgun
1. Visit `mailgun.com` and setup an account. There is a free package that is enough for dev purposes. Make sure to use the sandbox domain created.
2. Open your `.env` file
3. Set the variable: `MAIL_DRIVER = mailgun` 
4. Add the following variables with information from your account created in Mailgun: 
```
MAILGUN_DOMAIN = [ enter your domain here (USE SANDBOX) ]
MAILGUN_SECRET = [ enter your API private key here ]
```

## Alternate email option: MailDev

MailDev is a Node-based SMTP server that can run locally on a laptop. See [the MailDev website](https://danfarrelly.nyc/MailDev/)
 for installation and usage instructions.

To use MailDev with this project, just edit your .env file and change SMTP settings to the following:

```
MAIL_DRIVER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_USE_SSL=false
MAIL_USE_TLS=false
```

Then, open a browser to http://localhost:1080 and you will see a dummy inbox.

## Code Style and Linting

This project uses ESLint to check JavaScript and Vue code styles. These checks are run by Codacy with every pull request.

To run the same ESLint checks locally, run the following command:

```
npm run lint
```

You can also set up ESLint to run in PhpStorm at File | Settings | Languages & Frameworks | JavaScript | Code Quality Tools | ESLint.
 
You need to add the following string in "Extra eslint options":

```
--ext .js,.vue
```

### Note on NPM Packages for eslint

Codacy uses older versions of eslint and several plugins. Do not upgrade the versions of any eslint* package in package.json without checking if Codacy supports that version. You can find the list of plugins and the version they support at https://github.com/codacy/codacy-eslint/blob/master/build.sbt.

Basically, if `npm i` gives you any "peer dependency" warnings about eslint or its plugins, the Codacy scans will probably fail.

# Development & Deployment Process
This project uses BambooCI and Capistrano.

To view a list of capistrano tasks `cap -T`

We use a branch per environment:

* dev
* staging
* prod

## Recommended Tools:

 - [Commitizen](http://commitizen.github.io/cz-cli/)
 - [Standard Version](https://github.com/conventional-changelog/standard-version)

## Dev:

 - All new branches should be created from the `prod` branch: `git checkout prod && git checkout -b [new branch]`
 - Open pull request per feature / hotfix when ready.
 - Merge into dev

## Staging:

 - Don't Merge Dev directly into staging.
 - Merge specific branch for feature / hotfix into staging when promoted.
 
## Production:

 - Merge staging into production
 - Bump version and tag using [Semantic Versioning](https://semver.org/)
 	- `cd laravel && standard-version && git push --follow-tags`

# Database Seeding

The following instructions explain how to copy databases around. This may include seeding your local DB from the one
 on the server (e.g., dev -> local), or copying a DB from one server environment to another (e.g., prod -> staging).

## Downloading the Database

Note: If downloading from production, see the sanitizing step below.

To download the DB, use the Capistrano command.
```
cap db:pull
```

When copying the DB from production to any other environment, it's necessary to flag all the businesses as "DO NOT CONTACT", to stop the system sending email notification to production users. There is an Artisan command in the repo for this:

```
cd laravel
php artisan sanitizeData
```

Note: The "sanitizeData" command destroys some data and should never be run in production. The code contains a built-in
 safeguard which prevents it from running if APP_ENV is set to "production" in the .env file.

## Pushing the database a server

Generate the DB dump of your local DB. Navigate to your project root, then:

``` 
cap db:dump
rm database/default_staging.sql.gz (optional)
mv database/local_dev.sql.gz database/default_staging.sql.gz
```

Note: the file name must match the environment you're pushing to. E.g., to push to dev, you would need to name the file
"default_dev.sql.gz" and to push to staging, you would name it "default_staging.sql.gz".

Now push the DB to the server:

```
cap staging db:push
```


## Setting up xDebug with Docker
###In Chrome:   
(Assuming you are using chrome with xdebug extension.   If not… why not?   
- Install extension:   
- chrome://extensions/ —> search for `xdebug`

Verify IDE is set correctly: 
- Select `details` in extensions manager
- Expand `extension options`
- Make sure your IDE key is set to PhpStorm (IDE key = PHPSTORM). (click save)

###PhpStorm:
Set xdebug port:
- Preferences -> Languages & Frameworks -> PHP -> Debug | Xdebug: Debug port = `9010`
    * (NOTE: There does not seem to be consistency in port selection here, but you need to use 9010… or you need to change the DockerFile (php)

Create Server for xdebug:
- Preferences -> Languages & Frameworks -> PHP -> Servers
    * (Click +)
    * Name:  `PHPSERVERDOCKER`
    * Host:  `hrdirector.localhost`
    * Port: `80`
    * Select (User path mapping)

Setup Run/Debug Configuration:
- (In tool bar, on the left of where you click the phone to “Listen for PHP debug connection”, on drop-down menu select “Edit Configurations…"
    * (Click +)
    * Select `“Remote PHP Debug"`
    * Name:  `docker`
    * Select:  Flight debug connection by IDE key
        * Server:  `PHPSERVERDOCKER`
        * IDE Key: `PHPSTORM`

# Troubleshooting

## 500 error on the login page when first setting up
Make sure the folder /laravel/storage is writable by the web server.   Try clearing the cached configs

```
php artisan config:clear
```
(and if needed)
```
php artisan route:clear
php artisan cache:clear
```
