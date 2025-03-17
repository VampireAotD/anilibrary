compose  := $(shell command -v docker-compose || echo docker compose)
frontend := $(compose) exec app pnpm

.PHONY: help install up build down octane-status horizon-status app-sh phpstan \
        pint-check pint-fix infection rector-check rector-fix test \
        frontend-watch frontend-build frontend-test prettier-check \
        prettier-fix eslint-check eslint-fix ziggy-generate

help:
	@printf "\nUsage: make <command>\n"
	@grep -E '^[a-z.A-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Create .env files, install frontend and backend dependencies, build images.
	./scripts/install.sh

up: ## Start all containers.
	$(compose) up -d

build: ## Build images.
	$(compose) up -d --build

down: ## Shut down all containers and remove orphans.
	$(compose) down --remove-orphans

octane-status: ## Check Laravel Octane status.
	$(compose) exec app ./artisan octane:status

horizon-status: ## Check Laravel Horizon status.
	$(compose) exec horizon ./artisan horizon:status

app-sh: ## Enter app container.
	$(compose) exec app sh

phpstan: ## Run PHPStan.
	$(compose) exec app vendor/bin/phpstan analyse --memory-limit=2G

pint-check: ## Run Laravel Pint in test mode.
	$(compose) exec app vendor/bin/pint --config pint.json --test

pint-fix: ## Run Laravel Pint to fix code.
	$(compose) exec app vendor/bin/pint --config pint.json

infection: ## Run Infection mutation tests.
	$(compose) exec app vendor/bin/infection --threads=4

rector-check: ## Run Rector in dry-run mode.
	$(compose) exec app vendor/bin/rector process --dry-run

rector-fix: ## Run Rector to fix code.
	$(compose) exec app vendor/bin/rector process

test: ## Run backend tests.
	$(compose) exec app ./artisan test --parallel

frontend-watch: ## Start frontend dev server.
	$(frontend) dev

frontend-build: ## Build frontend assets.
	$(frontend) build

frontend-test: ## Run frontend tests.
	$(frontend) test

prettier-check: ## Run prettier.
	$(frontend) prettier-check

prettier-fix: ## Fix prettier errors.
	$(frontend) prettier-write

eslint-check: ## Run eslint.
	$(frontend) lint

eslint-fix: ## Fix eslint errors.
	$(frontend) lint-fix

ziggy-generate: ## Generate Ziggy routes for frontend tests.
	$(compose) exec app ./artisan ziggy:generate --types
