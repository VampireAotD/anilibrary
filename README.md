# Anilibrary

Application for scraping anime data from different supported sites. For scraping uses microservice
written in Go - [`anilibrary-scraper`](https://github.com/VampireAotD/anilibrary-scraper/tree/v1).

---

## Branches:

### - [`main`](https://github.com/VampireAotD/anilibrary)

### - [`dev`](https://github.com/VampireAotD/anilibrary/tree/dev)

--- 

## Build

Before building Anilibrary you need to place anime list in **src/storage/lists** folder
so that application could parse it.

> **WARNING**: File name must be called `animeList.json` and has a valid JSON

You can skip this step and build Anilibrary if you don't need scraped data.

To build Anilibrary you can use Makefile command `install` :

```shell
make install
```

it will ensure that all images were built correctly, launch Supervisor,
scheduler and queues worker. Queue list can be found in Makefile,
it is called **queue_list**.

Example of queue_list :

```
queue_list := add-anime,random-anime,anime-list,mail
```

---

## Bot config

Project is using [`Telebot`](https://github.com/westacks/telebot) library
so all bots configs can be found in `src/config/telebot.php`.

Also, some tests can require additional call of `fake()`
method, more info in this [`issue`](https://github.com/westacks/telebot/issues/58).

---

## TODO:

1. <del>Move parsers logic to Go microservice</del>
2. Add Elasticsearch and Logstash
3. Write more tests

---