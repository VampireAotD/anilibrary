compose  := $(shell command -v docker-compose || echo docker compose)
frontend := $(compose) exec app pnpm

.PHONY: help
help:
	@printf "\nUsage: make <command>\n"
	@grep -E '^[a-z.A-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: install
install: ## Create .env files, install frontend and backend dependencies, build images.
	./scripts/install.sh

.PHONY: up
up: ## Start all containers.
	$(compose) up -d

.PHONY: build
build: ## Build images.
	$(compose) up -d --build

.PHONY: down
down: ## Shut down all containers and remove orphans.
	$(compose) down --remove-orphans

.PHONY: octane-status
octane-status: ## Check Laravel Octane status.
	$(compose) exec app ./artisan octane:status

.PHONY: horizon-status
horizon-status: ## Check Laravel Horizon status.
	$(compose) exec horizon ./artisan horizon:status

.PHONY: app-sh
app-sh: ## Enter app container.
	$(compose) exec app sh

.PHONY: psalm
psalm: ## Run Psalm.
	$(compose) exec app vendor/bin/psalm

.PHONY: phpstan
phpstan: ## Run PHPStan.
	$(compose) exec app vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: pint
pint: ## Run Laravel Pint.
	$(compose) exec app vendor/bin/pint --config pint.json

.PHONY: infection
infection: ## Run Infection mutation tests.
	$(compose) exec app vendor/bin/infection --threads=4

.PHONY: optimize
optimize: ## Optimize Laravel app.
	$(compose) exec app ./artisan optimize:clear;
	$(compose) exec app ./artisan optimize;

.PHONY: test
test: ## Run backend tests.
	$(compose) exec app ./artisan test

.PHONY: frontend-watch
frontend-watch: ## Start frontend dev server.
	$(frontend) dev

.PHONY: frontend-build
frontend-build: ## Build frontend assets.
	$(frontend) build

.PHONY: frontend-test
frontend-test: ## Run frontend tests.
	$(frontend) test

.PHONY: prettier-check
prettier-check: ## Run prettier.
	$(frontend) prettier-check

.PHONY: prettier-fix
prettier-fix: ## Fix prettier errors.
	$(frontend) prettier-write

.PHONY: eslint-check
eslint-check: ## Run eslint.
	$(frontend) lint

.PHONY: eslint-fix
eslint-fix: ## Fix eslint errors.
	$(frontend) lint-fix

.PHONY: ziggy-generate
ziggy-generate: ## Generate Ziggy routes for frontend tests.
	$(compose) exec app ./artisan ziggy:generate --types
