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

.PHONY: phpstan
phpstan: ## Run PHPStan.
	$(compose) exec app vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: pint-check
pint-check: ## Run Laravel Pint in test mode.
	$(compose) exec app vendor/bin/pint --config pint.json --test

.PHONY: pint-fix
pint-fix: ## Run Laravel Pint to fix code.
	$(compose) exec app vendor/bin/pint --config pint.json

.PHONY: infection
infection: ## Run Infection mutation tests.
	$(compose) exec app vendor/bin/infection --threads=4

.PHONY: rector-check
rector-check: ## Run Rector in dry-run mode.
	$(compose) exec app vendor/bin/rector process --dry-run

.PHONY: rector-fix
rector-fix: ## Run Rector to fix code.
	$(compose) exec app vendor/bin/rector process

.PHONY: test
test: ## Run backend tests.
	$(compose) exec app ./artisan test --parallel

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
