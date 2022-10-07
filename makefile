name := file_service

.PHONY: app

compose = docker-compose -f .dev/docker-compose.yml -p="$(name)"
migration = $(app) vendor/bin/doctrine-migrations
app = $(compose) exec -T app

app:
	@echo App console:
	@$(compose) exec app bash

install: up
	@$(app) composer install
	@$(app) cp .env.example .env
	@echo Installation complete.
restart: down up
	@echo restart complete.
up:
	@$(compose) up -d
down:
	@$(compose) down
destroy:
	@$(compose) down --rmi all

migrate:
	@$(migration) migrate --no-interaction
migration:
	@$(migration) generate

cs:
	@$(app) vendor/bin/phpcs -v
csf:
	@$(app) vendor/bin/phpcbf

composer:
	@$(app) composer install