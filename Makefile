container_name = step-bundle-php

bash:
	docker exec -it $(container_name) bash

composer-add-github-token:
	docker exec -it $(container_name) composer config --global github-oauth.github.com $(token)

composer-update:
	docker exec -it $(container_name) composer update

command:
	docker exec -it $(container_name) $(cmd)

run-phpunit:
	docker exec -it $(container_name) ./vendor/bin/phpunit --coverage-text
