.PHONY: build down app up

build:
	docker-compose up -d --build

up:
	docker-compose up -d

down:
	docker-compose down

app:
	docker exec -it php bash