container_php54 = step-bundle-php54
container_php56 = step-bundle-php56

# PHP 5.4
php54_bash:
	docker exec -it $(container_php54) bash

php54_composer-add-github-token:
	docker exec -it $(container_php54) composer config --global github-oauth.github.com $(token)

php54_composer-update:
	docker exec -it $(container_php54) composer update

php54_command:
	docker exec -it $(container_php54) $(cmd)

php54_run-test:
	docker exec -it $(container_php54) ./vendor/bin/phpunit --coverage-text

# PHP 5.6
php56_bash:
	docker exec -it $(container_php56) bash

php56_composer-add-github-token:
	docker exec -it $(container_php56) composer config --global github-oauth.github.com $(token)

php56_composer-update:
	docker exec -it $(container_php56) composer update

php56_command:
	docker exec -it $(container_php56) $(cmd)

php56_run-test:
	docker exec -it $(container_php56) ./vendor/bin/phpunit --coverage-text
