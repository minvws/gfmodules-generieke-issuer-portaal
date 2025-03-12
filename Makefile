# Suppresses Make-specific output. Remove for more debugging info.
.SILENT:

# Make commands be run with `bash` instead of the default `sh`
#SHELL='/usr/bin/env bash'

all: help

setup: setup-config setup-npm setup-php  ## Setup the project

setup-config:
	. scripts/setup-config.sh

setup-npm:
	. scripts/setup-npm.sh

setup-php:
	. scripts/setup-php.sh

run: ## Run the project
	npm run build
	php artisan serve --port=9000


test: test-php test-js ## Runs tests

test-php: ## Test PHP
	echo "Clearing cache and running tests"
	php artisan route:clear && php artisan config:clear
	php artisan security-check:now
	vendor/bin/phpstan analyse
	vendor/bin/phpcs
	vendor/bin/psalm

test-js: ## Test Javascript/CSS
	yarn lint
	yarn test

help: ## Display available commands
	echo "Available make commands:"
	echo
	grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
