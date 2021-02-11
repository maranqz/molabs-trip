include .env

DC=docker-compose -f docker-compose.yml
DC_EXEC=$(DC) exec

DOCKER_USER_RUN="$(USER_ID):$(USER_GROUP)"


PHP_SERVICE = php
PHP_EXEC = $(DC_EXEC) $(PHP_SERVICE)

NGINX_SERVICE = nginx
NGINX_EXEC = $(DC_EXEC) $(NGINX_SERVICE)


init: env.init up composer.install

env.init:
	cp -f .env .env.local

composer.install:
	$(PHP_EXEC) composer i -n

## DOCKER-COMPOSE
up:
	$(DC) up $a $o

upd:
	$(DC) up -d $a $o

start:
	$(DC) start $a $o

stop:
	$(DC) stop $a $o

rebuild:
	$(DC) up -d --build --force-recreate $a $o

down:
	$(DC) down $a $o

php.bash:
	$(PHP_EXEC) bash $a $o

nginx.reload:
	$(NGINX_EXEC) nginx -s reload


gen.openapi.bash:
	mkdir -p out
	docker run --user "$(DOCKER_USER_RUN)" --rm \
		-v "${PWD}/out:/local/out" \
		-v "$(pwd)/assets/openapi.yaml:/local/openapi.yaml" \
		openapitools/openapi-generator-cli:latest  cat /local/openapi.yaml

gen.openapi:
	docker run --user "$(DOCKER_USER_RUN)" --rm \
		-v "${PWD}/src:/local/src" \
		-v "${PWD}/assets/openapi.yaml:/local/openapi.yaml" \
		openapitools/openapi-generator-cli:latest generate \
	-g php-symfony \
	-o /local/src/TripBundle \
	-i /local/openapi.yaml \
	-p invokerPackage=TripBundle \
	-p bundleName=TripBundle