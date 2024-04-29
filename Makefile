.PHONY: app-install app-test fix-php-style
default: help

help: # Display this help
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: \033[36m\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

app-install: ## Install app dependencies (composer)
	docker run -it --rm --name composer-particle -v "$$PWD":/particle -w /particle composer:latest install --prefer-dist

app-test: ## Test app (quality and unit tests)
	docker run -it --rm --name php-particle -v "$$PWD":/particle -w /particle php:8.2-cli php vendor/bin/php-cs-fixer fix --dry-run --diff --verbose
	docker run -it --rm --name php-particle -v "$$PWD":/particle -w /particle php:8.2-cli php vendor/bin/phpstan analyse
	docker run -it --rm --name php-particle -v "$$PWD":/particle -w /particle php:8.2-cli php ./vendor/bin/pest

fix-php-style: ## Fix php-cs
	docker run -it --rm --name php-particle -v "$$PWD":/particle -w /particle php:8.2-cli php vendor/bin/php-cs-fixer fix --verbose
