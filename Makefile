docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)
queue_list         := add-anime,random-anime,anime-list,mail

.PHONY: supervisor
supervisor:
	$(info Launching supervisor...)
	$(docker_compose_bin) exec -d app supervisord

.PHONY: scheduler
scheduler:
	$(info Launching Laravel scheduler...)
	$(docker_compose_bin) exec -d app ./artisan schedule:work

.PHONY: queues
queues:
	$(info Launching queue worker...)
	$(docker_compose_bin) exec -d app ./artisan queue:work --queue=$(queue_list)

.PHONY: build
build:
	$(info Building app containers...)
	$(docker_compose_bin) up -d --build

.PHONY: up
up:
	$(info Launching app containers...)
	$(docker_compose_bin) up -d;
	@make supervisor
	@make scheduler
	@make queues

.PHONY: down
down:
	$(info Shutting down app containers...)
	$(docker_compose_bin) down --remove-orphans

.PHONY: app
app:
	$(info Entering app container...)
	$(docker_compose_bin) exec app bash

.PHONY: install
install:
	$(info Initialising app...)
	@cp .env.example .env;
	@make build;
	$(docker_compose_bin) exec app cp .env.example .env;
	$(docker_compose_bin) exec app composer install;
	$(docker_compose_bin) exec app ./artisan key:generate;
	$(docker_compose_bin) exec app ./artisan migrate --seed;
	$(docker_compose_bin) exec app ./artisan anime-list:parse;

.PHONY: test
test:
	$(docker_compose_bin) exec app ./artisan test

.PHONY: psalm
psalm:
	$(docker_compose_bin) exec app vendor/bin/psalm

.PHONY: optimize
optimize:
	$(docker_compose_bin) exec app ./artisan optimize:clear;
	$(docker_compose_bin) exec app ./artisan optimize;

.PHONY: ide-helper
ide-helper:
	$(docker_compose_bin) exec app ./artisan ide-helper:generate;
	$(docker_compose_bin) exec app ./artisan ide-helper:model --reset -W;
	$(docker_compose_bin) exec app ./artisan ide-helper:meta;