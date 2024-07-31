<p align="center">
  <img src="art/logo.svg" alt="Anilibrary logo" style="width: 75%">
</p>

Application for scraping anime data from different supported sites

[![Frontend build](https://github.com/VampireAotD/anilibrary/actions/workflows/frontend-build.yml/badge.svg)](https://github.com/VampireAotD/anilibrary/actions/workflows/frontend-build.yml)
[![Backend build](https://github.com/VampireAotD/anilibrary/actions/workflows/backend-build.yml/badge.svg)](https://github.com/VampireAotD/anilibrary/actions/workflows/backend-build.yml)

:warning: This project has a lot more to improve, for example: code base, working with other services, frontend part,
and, in time, it all **will be improved**, but for now, **Anilibrary is not considered stable, breaking changes will
occur, so for now - it need to be used for development purposes only!**

---

## Related services:

To fully use **Anilibrary** you can also use one or all services that are related to it:

1. [`Scraper`](https://github.com/VampireAotD/anilibrary-scraper) - a service that allows you to scrape anime data from
   different sites.
2. [`ELK`](https://github.com/VampireAotD/anilibrary-elk) - service that is used for advanced logging and search.
3. [`Monitoring`](https://github.com/VampireAotD/anilibrary-monitoring) - service for collecting different metrics and
   traces from all **Anilibrary** services.
4. [`gRPC`](https://github.com/VampireAotD/anilibrary-grpc) - service that is used for communication using gRPC,
   generates client and server implementations for different languages.

--- 

## Build and deployment

Before you start to work with **Anilibrary**, you need to create `.env` file in the project root:

```sh
cp ./.env.example ./.env
```

and `.env` file in the `src` directory:

```sh
cp ./src/.env.example ./src/.env
```

After that you must fill up all required environment variables.

### Variables

#### For containers

This variables will be located in project root `.env` file and are required for containers to run properly.

| Variable                 | Default Value | Description                                                          |
|--------------------------|---------------|----------------------------------------------------------------------|
| `XDEBUG_MODE`            | coverage      | Used to set the mode for Xdebug. By default it is set to `coverage`. |
| `SERVER_PORT`            | 80            | Specifies the port on which Nginx will run.                          |
| `NGINX_LOGS_TO_LOGSTASH` | false         | Indicates whether Nginx logs should be sent to Logstash.             |
| `NODE_PORT`              | 5173          | Specifies the port on which the Vite server will run.                |
| `DB_DATABASE`            | anilibrary    | The name of the database to be used.                                 |
| `DB_PORT`                | 3306          | Specifies the port for the database connection.                      |
| `DB_USER`                | (empty)       | The username for the database connection.                            |
| `DB_PASSWORD`            | (empty)       | The password for the database connection.                            |
| `DB_ROOT_PASSWORD`       | (empty)       | The root password for the database connection.                       |
| `REDIS_PORT`             | 6379          | Specifies the port for the Redis connection.                         |
| `REDIS_PASSWORD`         | (empty)       | The password for the Redis connection.                               |

#### Variables for Anilibrary

This variables will be in **./src/.env** and are required for application to properly work with containers and other
services.

| Variable           | Description                                                                                                               |
|--------------------|---------------------------------------------------------------------------------------------------------------------------|
| `DB_*`             | Set of variables for database connection, use values from **.env** for containers.                                        |
| `REDIS_*`          | Set of variables for **Redis** connection, use values from **.env** for containers.                                       |
| `TELEGRAM_*`       | Set of variables for Telegram bot.                                                                                        |
| `CLOUDINARY_*`     | Set of variables for Cloudinary storage.                                                                                  |
| `ELASTICSEARCH_*`  | Set of variables for **Elasticsearch**, use with [ELK stack microservice](https://github.com/VampireAotD/anilibrary-elk). |
| `JWT_SECRET`       | Secret for communication with different **Anilibrary** microservices.                                                     |
| `SCRAPER_URL`      | Url for **scraper microservice**.                                                                                         |
| `LOGSTASH_ADDRESS` | Url for **Logstash** receiver.                                                                                            |

### Launching

After filling up all environment variables you can proceed installation of **Anilibrary** by using:

```sh
make install
```

---

## Testing

Currently, **MySQL** is primary DB for **Anilibrary**, so it will be used for tests as well. Container with testing
database, which is called `anilibrary-testing-database` will be up whenever you launch all other containers via Docker.

To run tests for backend use:

```sh
make test
```

To run tests for frontend use:

```sh
make frontend-test
```

---

## Commits

Before commiting use must ensure that project is tested, passed all static checks and has the same code style across it.

To do that you can use different commands, for example, to run static analysis use:

```sh
make psalm
```

```sh
make phpstan
```

To ensure that project has same code style use:

```sh
make pint
```

To make life easier and automatically run all this commands for you before commit, **Git hooks** are used. To run them,
**Anilibrary** uses [Lefthook](https://github.com/evilmartians/lefthook), so you must install it as well.

---

## Logs

As mentioned before, **Anilibrary** has its own [ELK stack](https://github.com/VampireAotD/anilibrary-elk), so logs from
it or **Nginx** can be sent to **Logstash**, and be visualised in **Kibana**. To send **Nginx** logs to **Logstash**,
you need to set `NGINX_LOGS_TO_LOGSTASH`variable to `true` in `.env` and specify `LOGSTASH_URL` for **Nginx** container
in `compose.yml`, like this:

```diff
# compose.yml

nginx:
+  environment:
+    LOGSTASH_URL: <your-url>
```
