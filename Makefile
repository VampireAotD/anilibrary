docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)
queue_list         := add-anime,random-anime,anime-list

.PHONY: build
build:
	$(info Building app containers...)
	$(docker_compose_bin) up -d --build

.PHONY: up
up:
	$(info Launching app containers...)
	$(docker_compose_bin) up -d;
	$(docker_compose_bin) exec -d app ./artisan telebot:polling;
	$(docker_compose_bin) exec -d app ./artisan schedule:work;
	$(docker_compose_bin) exec -d app ./artisan queue:work --queue=$(queue_list);

.PHONY: down
down:
	$(info Shutting down app containers...)
	$(docker_compose_bin) down

.PHONY: app
app:
	$(info Entering app container...)
	docker exec -it php bash

.PHONY: install
install:
	$(info Initialising app...)
	@cp .env.example .env;
	$(docker_compose_bin) run --rm app cp .env.example .env;
	$(docker_compose_bin) run --rm app ./artisan migrate:fresh --seed;
	$(docker_compose_bin) run --rm app ./artisan anime-list:parse;

.PHONY: supervisor
supervisor:
	$(info Launching supervisor...)
	docker-compose run -d app supervisord