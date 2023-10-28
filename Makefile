compose      := $(shell command -v docker-compose || command -v "docker compose")
yarn         := $(compose) run --service-ports --rm node yarn

.PHONY: up
up:
	$(info Launching app containers...)
	$(compose) up -d;
	@make supervisor
	@make scheduler

.PHONY: build
build:
	$(info Building app containers...)
	$(compose) up -d --build

.PHONY: down
down:
	$(info Shutting down app containers...)
	$(compose) down --remove-orphans

.PHONY: supervisor
supervisor:
	$(info Launching supervisor...)
	$(compose) exec -d app supervisord

.PHONY: scheduler
scheduler:
	$(info Launching Laravel scheduler...)
	$(compose) exec -d app ./artisan schedule:work

.PHONY: horizon
horizon:
	$(info Launching Laravel Horizon...)
	$(compose) exec -d app ./artisan horizon

.PHONY: horizon-pause
horizon-pause:
	$(info Pausing Laravel Horizon...)
	$(compose) exec -d app ./artisan horizon:pause

.PHONY: horizon-continue
horizon-continue:
	$(info Re-enabling Laravel Horizon...)
	$(compose) exec -d app ./artisan horizon:continue

.PHONY: horizon-status
horizon-status:
	$(info Checking Laravel Horizon status...)
	$(compose) exec app ./artisan horizon:status

.PHONY: horizon-terminate
horizon-terminate:
	$(info Terminating Laravel Horizon...)
	$(compose) exec -d app ./artisan horizon:terminate

.PHONY: app
app:
	$(info Entering app container...)
	$(compose) exec app bash

.PHONY: install
install:
	./scripts/install.sh

.PHONY: test
test:
	$(compose) exec app ./artisan test

.PHONY: psalm
psalm:
	$(compose) exec app vendor/bin/psalm

.PHONY: phpstan
phpstan:
	$(compose) exec app vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: pint
pint:
	$(compose) exec app vendor/bin/pint --config pint.json

.PHONY: optimize
optimize:
	$(compose) exec app ./artisan optimize:clear;
	$(compose) exec app ./artisan optimize;

.PHONY: ide-helper
ide-helper:
	$(compose) exec app ./artisan ide-helper:generate;
	$(compose) exec app ./artisan ide-helper:model --reset -W;
	$(compose) exec app ./artisan ide-helper:meta;

.PHONY: yarn-watch
yarn-watch:
	$(yarn) run dev

.PHONY: yarn-build
yarn-build:
	$(yarn) run build

.PHONY: prettier-check
prettier-check:
	$(yarn) prettier-check

.PHONY: prettier-write
prettier-write:
	$(yarn) prettier-write

.PHONY: yarn-lint
yarn-lint:
	$(yarn) lint

.PHONY: yarn-lint-fix
yarn-lint-fix:
	$(yarn) lint-fix

.PHONY: yarn-test
yarn-test:
	$(yarn) test