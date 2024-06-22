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

To fully use Anilibrary you can also use one or all services that are related to it:

1. [`Scraper`](https://github.com/VampireAotD/anilibrary-scraper) - a service that allows you to scrape anime data from
   different sites.
2. [`ELK`](https://github.com/VampireAotD/anilibrary-elk) - service that is used for advanced logging and search.
3. [`Monitoring`](https://github.com/VampireAotD/anilibrary-monitoring) - service for collecting different metrics and
   traces from all Anilibrary services.
4. [`gRPC`](https://github.com/VampireAotD/anilibrary-grpc) - service that is used for communication using gRPC,
   generates client and server implementations for different languages.

--- 

## Build and deployment

Before you start to work with Anilibrary, you need to fill all required environment variables which will be located in
**.env** and **src/.env**. To acquire them, you can use script **install.sh** or launch it by using `make install`.

### Variables

#### For containers

This variables will be located in **.env** and are required for container to run properly.

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

| Variable           | Description                                                                                                                   |
|--------------------|-------------------------------------------------------------------------------------------------------------------------------|
| `DB_*`             | Set of variables for database connection, use values from **.env** for containers.                                            |
| `REDIS_*`          | Set of variables for Redis connection, use values from **.env** for containers.                                               |
| `TELEGRAM_*`       | Set of variables for Telegram bot.                                                                                            |
| `CLOUDINARY_*`     | Set of variables for Cloudinary storage.                                                                                      |
| `ELASTICSEARCH_*`  | Set of variables for Elasticsearch, use with [monitoring microservice](https://github.com/VampireAotD/anilibrary-monitoring). |
| `JWT_SECRET`       | Secret for communication with different Anilibrary microservices.                                                             |
| `SCRAPER_URL`      | Url for scraper microservice.                                                                                                 |
| `LOGSTASH_ADDRESS` | Url for Logstash receiver.                                                                                                    |

---

## Some quality of life

1. **Git hooks**

   For code quality you can use things like **Psalm**, **PHPStan** and **Laravel Pint**. You can use them directly in
   container, or via Makefile,like this:
   ```sh
   make phpstan
   make psalm
   make pint
   ```
   But if you don't want to do it manually you can write in **pre-commit** hook which is located in **.hooks**
   directory.
   To apply this and other hooks you can use **apply_hooks.sh** script, which is located in **scripts/git** folder. If
   you don't want to you those anymore, you can use **revoke_hooks** script.

2. **Nginx logs to ELK service**

   If you want to visualize you Nginx logs in Kibana you can send them to Elasticsearch via Logstash. All of them are
   configured in **ELK** service. To send logs you need to set **NGINX_LOGS_TO_LOGSTASH** variable to **true** in
   **.env** and specify **LOGSTASH_URL** for Nginx container, like this:

   ```diff
   # compose.yml
   
   nginx:
   +  environment:
   +    LOGSTASH_URL: <your-url>
   ```

3. **Testing**

   You can test your frontend and backend using these commands:
   ```sh
   make test #for PHP tests
   make frontend-test # for TS tests
   ```
