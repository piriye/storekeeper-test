
DOCKER_RUN    = docker-compose run --rm web
PHPUNIT       = ./vendor/bin/phpunit
PHPSTAN       = ./vendor/bin/phpstan
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer


start:
	docker-compose up -d

install:
	$(DOCKER_RUN) composer install

update:
	$(DOCKER_RUN) composer update

test:
	$(DOCKER_RUN) $(PHPUNIT)

lint-php:
	$(DOCKER_RUN) $(PHPSTAN)
	$(DOCKER_RUN) $(PHP_CS_FIXER) fix --dry-run

fix-php: ## Fix files with php-cs-fixer
	$(DOCKER_RUN) $(PHP_CS_FIXER) fix