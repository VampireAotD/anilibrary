# Anilibrary

Application for scraping anime data from different supported sites

:warning: **This branch is under development and isn't suitable for production use!**

[![Build](https://github.com/VampireAotD/anilibrary/actions/workflows/build.yml/badge.svg)](https://github.com/VampireAotD/anilibrary/actions/workflows/build.yml)

---

## Services:

- [`scraper`](https://github.com/VampireAotD/anilibrary-scraper)
- [`elk`](https://github.com/VampireAotD/anilibrary-elk)
- [`monitoring`](https://github.com/VampireAotD/anilibrary-monitoring)

--- 

## Build

Before building Anilibrary you may want to place anime list in **src/storage/lists** folder
so that application could parse it.

> **WARNING**: File name must be called `anime-list.json` and has a valid JSON

You can skip this step and build Anilibrary if you don't need scraped data to fill database.

To build Anilibrary you can use Makefile command `install` :

```shell
make install
```

it will ensure that all images were built correctly, launch Supervisor,
scheduler and queues worker. Queue list can be found in Makefile,
it is called **queue_list**.

Example of **queue_list** :

```
queue_list := register-user,mail
```

---

## Bot config

Project is using [`Telebot`](https://github.com/westacks/telebot) library
so all bots configs can be found in `src/config/telebot.php`.

Also, some tests can require additional call of `fake()`
method, or cannot be made because of `mock()` method, more info in
this [`issue`](https://github.com/westacks/telebot/issues/58).

---