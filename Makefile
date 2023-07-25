compose      := $(shell command -v docker-compose || command -v "docker compose")
queue_list   := mail,register,upsert-anime,scrape-anime,pusher-scrape-response
yarn         := $(compose) run --service-ports --rm node

.PHONY: supervisor
supervisor:
	$(info Launching supervisor...)
	$(compose) exec -d app supervisord

.PHONY: scheduler
scheduler:
	$(info Launching Laravel scheduler...)
	$(compose) exec -d app ./artisan schedule:work

.PHONY: queues
queues:
	$(info Launching queue worker...)
	$(compose) exec -d app ./artisan queue:work --queue=$(queue_list) --daemon

.PHONY: build
build:
	$(info Building app containers...)
	$(compose) up -d --build

.PHONY: up
up:
	$(info Launching app containers...)
	$(compose) up -d;
	@make supervisor
	@make scheduler
	@make queues

.PHONY: down
down:
	$(info Shutting down app containers...)
	$(compose) down --remove-orphans

.PHONY: app
app:
	$(info Entering app container...)
	$(compose) exec app bash

.PHONY: install
install:
	./bin/install.sh

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