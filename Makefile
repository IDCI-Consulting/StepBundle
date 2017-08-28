# Variables

target_container ?= php
php_sources ?= .
js_sources ?= Resources/public/js/editor

# Bash Commands

.PHONY: command
command:
	docker-compose run --rm $(target_container) $(cmd)

# NodeJs commands

.PHONY: yarn
yarn:
	docker-compose run --rm node yarn $(cmd) $(options)

.PHONY: npm-install
npm-install:
	docker-compose run --rm node npm install $(options)

.PHONY: karma
karma:
	docker-compose run --rm node ./node_modules/karma/bin/karma start $(options)

.PHONY: gulp
gulp:
	docker-compose run --rm node gulp $(task)

.PHONY: eslint
eslint:
	docker-compose run --rm node eslint $(js_sources)

.PHONY: webpack-build-dev
webpack-build-dev:
	docker-compose run --rm node npm run build

.PHONY: webpack-build-prod
webpack-build-prod:
	docker-compose run --rm node npm run build-prod

# PHP commands

.PHONY: composer-add-github-token
composer-add-github-token:
	docker-compose run --rm $(target_container) composer config --global github-oauth.github.com $(token)

.PHONY: composer-update
composer-update:
	docker-compose run --rm $(target_container) composer update

.PHONY: composer-install
composer-install:
	docker-compose run --rm $(target_container) composer install

.PHONY: phploc
phploc:
	docker run -i -v `pwd`:/project jolicode/phaudit bash -c "phploc $(php_sources); exit $$?"

.PHONY: phpcs
phpcs:
	docker run -i -v `pwd`:/project jolicode/phaudit bash -c "phpcs $(php_sources) --extensions=php --ignore=vendor,app/cache,Tests/cache    --standard=PSR2; exit $$?"

.PHONY: phpcpd
phpcpd:
	docker run -i -v `pwd`:/project jolicode/phaudit bash -c "phpcpd $(php_sources); exit $$?"

.PHONY: phpdcd
phpdcd:
	docker run -i -v `pwd`:/project jolicode/phaudit bash -c "phpdcd $(php_sources); exit $$?"


# Symfony bundle commands

.PHONY: phpunit
phpunit: ./vendor/bin/phpunit
	docker-compose run --rm $(target_container) ./vendor/bin/phpunit --coverage-text
