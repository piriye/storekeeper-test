
DOCKER_RUN    = docker-compose run --rm web
PHPUNIT       = ./vendor/bin/phpunit
PHPSTAN       = ./vendor/bin/phpstan
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer

PHONY: start install update test start-js-dev check-standards lint-fix

start: build
	docker-compose up -d

build:
	$(DOCKER_RUN) npm run build

start-js-dev:
	$(DOCKER_RUN) npm run start

clean:
	$(DOCKER_RUN) npm run prebuild

install:
	$(DOCKER_RUN) composer install
	$(DOCKER_RUN) npm install

update:
	$(DOCKER_RUN) composer update
	$(DOCKER_RUN) npm update

test:
	$(DOCKER_RUN) $(PHPUNIT)

check-standards:
	$(DOCKER_RUN) $(PHPSTAN)
	$(DOCKER_RUN) $(PHP_CS_FIXER) fix --dry-run
	$(DOCKER_RUN) npm run lint

lint-fix:
	$(DOCKER_RUN) npm run lint --fix
	$(DOCKER_RUN) $(PHP_CS_FIXER) fix