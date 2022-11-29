.PHONY: composer-install
composer-install:
	docker run -it -v `pwd`:/app composer install --ignore-platform-req=ext-gd

.PHONY: phpunit
phpunit: composer-install
	docker run -it -v `pwd`:/app mobtitude/php-xdebug:7.2-cli php /app/vendor/bin/phpunit -c /app/phpunit.xml.dist /app/Tests --coverage-text