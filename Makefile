
DOCKER_RUN    = docker-compose run --rm web
PHPUNIT       = ./vendor/bin/phpunit
PHPSTAN       = ./vendor/bin/phpstan
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer

PHONY: start install update test start-js-dev check-standards lint-fix help build

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

start: build ## start the docker services
	docker-compose up -d

build: ## build javascript files
	$(DOCKER_RUN) npm run build

start-js-dev: ## build javascript node server, webpack dev server with hot reload
	$(DOCKER_RUN) npm run start

clean: ## clean build
	$(DOCKER_RUN) npm run prebuild

install: ## install all js/php libraries
	$(DOCKER_RUN) composer install
	$(DOCKER_RUN) npm install

update: ## update all js/php libraries
	$(DOCKER_RUN) composer update
	$(DOCKER_RUN) npm update

test: ## run tests
	$(DOCKER_RUN) $(PHPUNIT)

check-standards: ## check if code complies to standards
	$(DOCKER_RUN) $(PHPSTAN)
	$(DOCKER_RUN) $(PHP_CS_FIXER) fix --dry-run
	$(DOCKER_RUN) npm run lint

lint-fix: ## lint code (fixes most of check-standards)
	$(DOCKER_RUN) npm run lint-fix
	$(DOCKER_RUN) $(PHP_CS_FIXER) fix
