DOCKER_COMPOSE = docker-compose
EXEC_PHP       = $(DOCKER_COMPOSE) exec php-fpm
EXEC_HTTP       = $(DOCKER_COMPOSE) exec apache
DOCKER_COMPOSE_FILE = -f docker-compose.yml


local-build: local-compose-file dc-build init-local
dev-build: dev-compose-file dc-build init-dev
prod-build: dc-build init-prod
init-local: dc-down dc-up composer-i clear-cache
init-dev: dc-down dc-up dc-certbot composer-i migrate swagger-generate fixtures clear-cache
init-prod: dc-down dc-up dc-certbot composer-i migrate clear-cache


local-compose-file:
	$(eval DOCKER_COMPOSE_FILE = -f docker-compose.yml)

dev-compose-file:
	$(eval DOCKER_COMPOSE_FILE = -f docker-compose.base.yml -f docker-compose.dev.yml )

dc-build:
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_FILE) build postgres php-fpm apache

dc-up:
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_FILE) up -d postgres php-fpm apache

dc-down:
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_FILE) down --remove-orphans

bash:
	$(EXEC_PHP) sh

abash:
	$(EXEC_HTTP) sh

composer-i:
	$(EXEC_PHP) sh -c " composer install"

clear-cache:
	$(EXEC_PHP) sh -c "  php artisan route:clear &&  php artisan config:cache && php artisan view:clear && php artisan route:clear && php artisan optimize:clear"

swagger-generate:
	$(EXEC_PHP) sh -c " php artisan l5-swagger:generate"
