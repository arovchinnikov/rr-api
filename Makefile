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
	@echo installation complete.
up:
	@$(compose) up -d
destroy:
	@$(compose) down --rmi all

cs:
	@$(app) vendor/bin/phpcs -v
csf:
	@$(app) vendor/bin/phpcbf
