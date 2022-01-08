.PHONY: setup build down app

setup:
	docker-compose up -d --build

build:
	docker-compose up -d

down:
	docker-compose down

app:
	docker exec -it php bash