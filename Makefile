compose  := $(shell command -v docker-compose || echo docker compose)
frontend := $(compose) exec node pnpm

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
	@make supervisor

.PHONY: down
down: ## Shut down all containers and remove orphans.
	$(compose) down --remove-orphans

.PHONY: build
build: ## Build images.
	$(compose) up -d --build

.PHONY: supervisor
supervisor: ## Launch supervisor. Use is to manage Horizon, scheduler, Telegram long polling.
	$(compose) exec -d app supervisord -c /etc/supervisor/supervisord.conf

.PHONY: scheduler
scheduler: ## Launch Laravel scheduler.
	$(compose) exec -d app ./artisan schedule:work

.PHONY: horizon
horizon: ## Launch Laravel Horizon. Horizon is used to launch and manage queues.
	$(compose) exec -d app ./artisan horizon

.PHONY: horizon-pause
horizon-pause: ## Pause Laravel Horizon.
	$(compose) exec -d app ./artisan horizon:pause

.PHONY: horizon-continue
horizon-continue: ## Resume Laravel Horizon.
	$(compose) exec -d app ./artisan horizon:continue

.PHONY: horizon-status
horizon-status: ## Check Laravel Horizon status.
	$(compose) exec app ./artisan horizon:status

.PHONY: horizon-terminate
horizon-terminate: ## Terminate Laravel Horizon.
	$(compose) exec -d app ./artisan horizon:terminate

.PHONY: app-sh
app-sh: ## Enter app container.
	$(compose) exec app sh

.PHONY: test
test: ## Run backend tests.
	@make test-db-up
	$(compose) exec app ./artisan test
	@make test-db-down

.PHONY: psalm
psalm: ## Run Psalm.
	$(compose) exec app vendor/bin/psalm

.PHONY: phpstan
phpstan: ## Run PHPStan.
	$(compose) exec app vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: pint
pint: ## Run Laravel Pint.
	$(compose) exec app vendor/bin/pint --config pint.json

.PHONY: optimize
optimize: ## Optimize Laravel app.
	$(compose) exec app ./artisan optimize:clear;
	$(compose) exec app ./artisan optimize;

.PHONY: ide-helper
ide-helper: ## Generate Laravel IDE helpers.
	$(compose) exec app ./artisan ide-helper:generate;
	$(compose) exec app ./artisan ide-helper:meta;
	$(compose) exec app ./artisan ide-helper:models -M;
	$(compose) exec app ./artisan ide-helper:eloquent

.PHONY: test-db-up
test-db-up: ## Start testing database.
	$(compose) -f compose.testing.yml up --build -d

.PHONY: test-db-down
test-db-down: ## Shut down testing database.
	$(compose) -f compose.testing.yml down

.PHONY: backup
backup: ## Create database backup.
	./scripts/mysql/backup.sh

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
