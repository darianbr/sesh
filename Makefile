test: deps
	vendor/bin/phpunit

coverage: deps
	phpdbg -qrr vendor/bin/phpunit -c phpunit.coverage.xml

lint: $(shell find src)
	composer validate --no-check-publish
	vendor/bin/php-cs-fixer fix

deps: vendor

prepare: lint coverage

ci: lint
	vendor/bin/phpunit

.PHONY: FORCE test coverage lint deps prepare ci

vendor: composer.lock
	composer install

composer.lock: composer.json
	composer update

src/%.php: FORCE
	@php -l $@
