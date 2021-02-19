include .env

DC=docker-compose -f docker-compose.yml
DC_EXEC=$(DC) exec

DOCKER_USER_RUN="$(USER_ID):$(USER_GROUP)"


PHP_SERVICE = php
PHP_EXEC = $(DC_EXEC) $(PHP_SERVICE)

NGINX_SERVICE = nginx
NGINX_EXEC = $(DC_EXEC) $(NGINX_SERVICE)


init: env.init upd composer.install doctrine.migrations.migrate countries.sync

env.init:
	cp -f .env .env.local
	sed -i 's/db_user:db_password@127.0.0.1:3306\/db_name?serverVersion=5.7/root:root@db:3306\/symfony/' .env.local

composer.install:
	$(PHP_EXEC) composer i -n

test: test.env test.build test.init.db test.run
test.docker: test.env test.docker.build test.docker.init.db test.docker.run

test.env:
	cp .env.test .env.test.local || exit 0

CODECEPTION_RUN = ../../vendor/bin/codecept
TEST_BUILD = cd src/TripBundle && $(CODECEPTION_RUN) build

test.build:
	$(TEST_BUILD)
test.docker.build:
	$(PHP_EXEC) bash -c "$(TEST_BUILD)"

TEST_RUN = cd src/TripBundle && \
           	php -d xdebug.mode=coverage $(CODECEPTION_RUN) run --coverage-xml
test.run:
	$(TEST_RUN)
test.docker.run:
	$(PHP_EXEC) bash -c "$(TEST_RUN)"

test.docker.init: test.docker.init.db
	$(PHP_EXEC) ln -s /var/www/symfony/vendor/bin/codecept /usr/bin/codecept

TEST_DB_CREATE = bin/console doctrine:database:create --if-not-exists --no-interaction -e test -v
TEST_DB_MIGRATE = bin/console doctrine:migrations:migrate --no-interaction -e test -v
test.init.db:
	$(TEST_DB_CREATE)
	$(TEST_DB_MIGRATE)
test.docker.init.db:
	$(PHP_EXEC) $(TEST_DB_CREATE)
	$(PHP_EXEC) $(TEST_DB_MIGRATE)

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

gen.openapi.php.symfony:
	docker run --user "$(DOCKER_USER_RUN)" --rm \
		-v "${PWD}/src:/local/src" \
		openapitools/openapi-generator-cli:latest generate \
	-g php-symfony \
	-o /local/src/TripBundle \
	-i /local/src/TripBundle/Resources/api/openapi.yaml \
	-p invokerPackage=TripBundle \
	-p bundleName=Trip \
	-p bundleAlias=trip

gen.openapi.mysql:
	docker run --user "$(DOCKER_USER_RUN)" --rm \
		-v "${PWD}/src:/local/src" \
		-v "${PWD}/assets/openapi.yaml:/local/openapi.yaml" \
		openapitools/openapi-generator-cli:latest generate \
	-g mysql-schema \
	-o /local/src/mysql.sql \
	-i /local/openapi.yaml


countries.sync:
	$(PHP_EXEC) bin/console trip:countries:sync || exit 0

cc:
	$(PHP_EXEC) bin/console cache:clear

db.init: doctrine.database.create doctrine.migrations.migrate

doctrine.database.create:
	$(PHP_EXEC) bin/console doctrine:database:create --no-interaction --if-not-exists

doctrine.migrations.migrate:
	$(PHP_EXEC) bin/console doctrine:migrations:migrate --no-interaction

doctrine.migrations.diff:
	$(PHP_EXEC) bin/console doctrine:migrations:diff

act.run:
	act $(ARGS)