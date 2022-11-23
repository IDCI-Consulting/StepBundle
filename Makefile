# Variables

target_container ?= php
php_sources ?= .
js_sources ?= Resources/public/js/editor

# Bash Commands

.PHONY: command
command:
	docker-compose run --rm $(target_container) $(cmd)


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
