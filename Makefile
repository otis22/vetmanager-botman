build:
	docker-compose -f ./.docker/docker-compose.yml build $(c)
serve:
	docker-compose -f ./.docker/docker-compose.yml up -d $(c)
down:
	docker-compose -f ./.docker/docker-compose.yml down $(c)
unit:
	docker-compose -f ./.docker/docker-compose.yml run php-fpm ./vendor/bin/phpunit --testsuite=Unit
enter:
	docker-compose -f ./.docker/docker-compose.yml exec php-fpm bash

all: build serve unit

.PHONY: build
