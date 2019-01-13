.DEFAULT_GOAL := check
.PHONY: lint phpstan phpcs phpcbf tests tester-coverage echo-failed-tests composer-install

PHPCS_CACHE_DIR := /tmp/cache
PHPCS_ARGS := --standard=ruleset.xml --extensions=php,phpt --encoding=utf-8 --cache=$(PHPCS_CACHE_DIR)/phpcs --tab-width=4 -sp Core Tests
PHPCPD_ARGS := Core --exclude Endpoint/ --exclude Sql/ --exclude Task/ --names-exclude=CompleteDescription.php
TESTER_ARGS := -o console -s -p php -c Tests/php.ini -l /var/log/nette_tester.log

check: lint phpstan phpcs tests
ci: lint phpstan phpcs tests tester-coverage

help:               ## help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

lint:               ## lint
	vendor/bin/parallel-lint -e php,phpt Core Tests

phpstan:            ## phpstan
	vendor/bin/phpstan analyse -l max -c phpstan.neon Core

phpcs:              ## phpcs
	@mkdir -p $(PHPCS_CACHE_DIR)
	vendor/bin/phpcs $(PHPCS_ARGS)

phpcbf:             ## phpcbf
	vendor/bin/phpcbf $(PHPCS_ARGS)

tests:              ## tests
	vendor/bin/tester $(TESTER_ARGS) Tests/

tester-coverage:
	vendor/bin/tester $(TESTER_ARGS) -d extension=xdebug.so Tests/ --coverage tester-coverage.xml --coverage-src Core/

echo-failed-tests:
	@for i in $(find Tests -name \*.actual); do echo "--- $i"; cat $i; echo; echo; done
	@for i in $(find Tests -name \*.expected); do echo "--- $i"; cat $i; echo; echo; done

composer-install:
	composer install --no-interaction --prefer-dist --no-scripts --no-progress --no-suggest --classmap-authoritative
