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

### Required variables:

1. **MySQL:**

   For proper MySQL database connectivity, the following required environment variables are needed:

    - `MYSQL_USER` - MySQL database username with necessary privileges to access the database.
    - `MYSQL_PASSWORD` - Password for the MySQL database user.
    - `MYSQL_ROOT_PASSWORD` - Password for the MySQL root user.

2. **Redis:**

   To enable Redis support, set the following required environment variables:

    - `REDIS_PASSWORD` - Password for accessing the Redis server.

3. **Application:**

   Anilibrary is written using Laravel, which also relies on some required environment variables which are located in
   **src/.env**:

    - `DB_HOST` - Hostname of MySQL, you can specify it in **docker-compose.yml** using **container_name** or **name of
      the service** which is **database**.
    - `DB_PORT` - Value of `MYSQL_PORT` from **.env** in the project root.
    - `DB_DATABASE` - Value of `MYSQL_DATABASE` from **.env** in the project root.
    - `DB_USERNAME` - Value of `MYSQL_USER` from **.env** in the project
      root.
    - `DB_PASSWORD` - Value of `MYSQL_PASSWORD` from **.env** in the project root.
    - `REDIS_HOST` - Hostname of Redis, you can specify it in **docker-compose.yml** using **container_name** or **name
      of the service** which is **redis**.
    - `REDIS_PORT` - Value of `REDIS_PORT` from **.env** in the project root.
    - `REDIS_PASSWORD` - Value of `REDIS_PASSWORD` from **.env** in the project root.

### Optional:

1. **MySQL:**

   Some MySQL parameters can be set to default values, but you may customize them if needed:

    - `MYSQL_DATABASE` - Sets the database name, by default it will be **anilibrary**.
    - `MYSQL_PORT` - Sets the database port, by default it will be **3306**.
    - `MYSQL_BACKUP_PATH` - If you want to backup your database you can specify path on where to store the backup
      itself. For more convenience you can use script **backup.sh**, which is located in **scripts/mysql**.

2. **Redis:**

   Additionally, you can use optional Redis environment variables:

    - `REDIS_PORT` - Sets the Redis port, by default it will be **6379**.

3. **Application:**

   In addition to the required variables, you can specify some variables that are optional, but needed to work fully
   with Anilibrary:

    - `XDEBUG_MODE` - If you want to enable **Xdebug** you can set this variable to **on**.
    - `PUSHER_*` - If you want to use real-time notifications, specify your [Pusher](https://pusher.com) credentials.
    - `TELEGRAM_*` - If you also want to have Anilibrary bot, specify your Telegram bot credentials.
    - `TELEGRAM_WHITELIST` - Specify Telegram IDs who can work with bot.
    - `CLOUDINARY_*` - All images are stored in **Cloudinary**, so if you want to use this storage, specify your
      [Cloudinary](https://cloudinary.com) credentials.
    - `JWT_SECRET` - If you want to use **Scraper**, you need to specify this variable here and in **Scraper** as well.
    - `SCRAPER_URL` - Also, you need to specify it url if you want to use it.
    - `LOGSTASH_ADDRESS` - If you want to send visualize your logs in Kibana, send them to **ELK** service using
      Logstash, and specify it url here.
    - `ELASTICSEARCH_*` - If you want to have proper search, specify your Elasticsearch credentials.

After configuration, you can use `make frontned-watch` to access website.

---

## Some quality of life

1. **Git hooks**

   For code quality you can use things like **Psalm**, **PHPStan** and **Laravel Pint**. You can use them directly in
   container, or via Makefile,like this:
   ```shell
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
   configured in **ELK** service. To send logs you need to specify **APP_ENV** and **LOGSTASH_URL** for nginx container,
   like this:

   ```diff
   nginx:
   +  environment:
   +    APP_ENV: production
   +    LOGSTASH_URL: <your-url>
   ```

3. **Testing**

   You can test your frontend and backend using these commands:
   ```shell
   make test #for PHP tests
   make frontend-test # for TS tests
   ```

---

## Known issues

1. Anilibrary is using [`Telebot`](https://github.com/westacks/telebot) library for managing bot, and due to it
   limitations, some tests can require additional call of `fake()` method, or cannot be made because of `mock()` method,
   more info in
   this [`issue`](https://github.com/westacks/telebot/issues/58).