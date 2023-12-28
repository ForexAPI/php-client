DOCKER_COMPOSE=docker compose
DOCKER_COMPOSE_EXEC=$(DOCKER_COMPOSE) exec
PHP_EXEC=$(DOCKER_COMPOSE_EXEC) -e XDEBUG_MODE=off -e PHP_CS_FIXER_IGNORE_ENV=1 -e SYMFONY_DEPRECATIONS_HELPER=disabled php

default: help

help: # Show help for each of the Makefile recipes.
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done

build: docker-down docker-up start # Build or rebuild whole environment (this will DESTROY everything)

stop: # Stop all containers
	$(DOCKER_COMPOSE) stop

start: # Start all containers
	$(DOCKER_COMPOSE) up -d

restart: stop start # Restart all containers

docker-down: # Stop and remove containers, networks, images, and volumes
	$(DOCKER_COMPOSE) down --remove-orphans -v

up: docker-up # Alias for docker-up
docker-up:
	$(DOCKER_COMPOSE) up -d --build --remove-orphans

fix: # Run php-cs-fixer
	@$(PHP_EXEC) ./vendor/bin/php-cs-fixer fix --allow-risky yes

test: # Run phpunit
	@$(PHP_EXEC) ./vendor/bin/phpunit --testdox

phpstan: # Run phpstan
	@$(PHP_EXEC) ./vendor/bin/phpstan

