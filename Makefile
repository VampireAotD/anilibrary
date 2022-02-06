.PHONY: build down app up install

docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)

build:
	$(docker_compose_bin) up -d --build

up:
	$(docker_compose_bin) up -d

down:
	$(docker_compose_bin) down

app:
	docker exec -it php bash

install:
	@cp .env.example .env;
	$(docker_compose_bin) run --rm app cp txt1.txt txt2.txt;
	$(docker_compose_bin) run --rm app ./artisan migrate:fresh --seed;