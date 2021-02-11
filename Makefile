include .env

DC=docker-compose -f docker-compose.yml
DC_EXEC=$(DC) exec


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
