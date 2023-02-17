gen-ide-helpers:
	@cd laravel && php artisan ide-helper:generate # phpDoc generation for Laravel Facades
	@cd laravel && php artisan ide-helper:models   # phpDocs for models
	@cd laravel && php artisan ide-helper:meta     # PhpStorm Meta file

cc:
	@cd laravel && php artisan cache:clear
	@cd laravel && php artisan config:cache
	@cd laravel && php artisan view:clear
	@cd laravel && php artisan route:clear
	@cd laravel && php artisan migrate --no-interaction --force

composer-ida: is-composer-available
	@rm -rvf laravel/bootstrap/cache/*
	@cd laravel && composer install && composer dump-autoload -o

npm-run-dev: npm-install
	@cd laravel && npm run dev

npm-run-prod: npm-install
	@cd laravel && npm run prod

npm-run-watch: npm-install
	@cd laravel && npm run watch

npm-install:
	@cd laravel && npm install

# -- testing --
test: js-test phpunit

js-test:
	@cd laravel && npm run test

# Note: to make PHPUnit use the test DB (and not nuke your dev db)
# we have to cache the testing config, run the tests, then cache the
# local config again.
# If tests use the wrong DB, check that you have a .env.testing file in your project
phpunit: is-test-env-available
	@cd laravel \
		&& php artisan config:cache --env=testing \
		&& vendor/bin/phpunit \
		; php artisan config:cache

phpunit-coverage: is-test-env-available
	@cd laravel \
		&& php artisan config:cache --env=testing \
		&& vendor/bin/phpunit --coverage-html coverage-report \
		; php artisan config:cache

# ------------------------------------------------------------------------------
# Misc

is-composer-available:
	@command -v composer > /dev/null

is-test-env-available:
	@test -f laravel/.env.testing || \
		{ echo "ERROR: Test env file (.env.testing) does not exist! Please create this file before running tests."; exit 1; }
