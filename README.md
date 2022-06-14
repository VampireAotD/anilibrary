# Anilibrary

Application for scrapping anime

---

## Branches:

### - [`main`](https://github.com/VampireAotD/anilibrary)
### - [`refactor`](https://github.com/VampireAotD/anilibrary/tree/refactor)

--- 

## Build

To build image you can use Makefile commands

First, you need to build images : 

```shell
make build # docker-compose up -d --build
```

After that you need to place anime list in **src/storage/lists** folder
so that app could parse it.

> **WARNING**: Anime list should be called animeList and be a valid JSON

Now we can initialize application :

```shell
make install
```

or if you want to do it step by step :

```shell
cp .env.example .env
docker-compose run --rm app cp .env.example .env
docker-compose run --rm app composer update
docker-compose run --rm app ./artisan key:generate
docker-compose run --rm app ./artisan migrate --seed
docker-compose run --rm app ./artisan anime-list:parse
```

---

## Launch

To launch application you can use following command : 
```shell
make up
```

or if you want to do it step by step :

```shell
docker-compose up -d;
docker-compose exec -d app ./artisan telebot:polling;
docker-compose exec -d app ./artisan schedule:work;
docker-compose exec -d app ./artisan queue:work --queue=$(queue_list);
```

**queue_list** is a variable from Makefile,
basically you can just type name of queues you want
to listen

List of queues :

```
add-anime,random-anime,anime-list,mail
```

Also you can use **Supervisor** that will insure that
bot will always work

```shell
make supervisor
```

or 

```shell
docker-compose run -d --name supervisor app supervisord
```

---

## TODO:
1. Move parsers logic to Go microservice
2. Add Elasticsearch and Logstash
3. Write tests
---