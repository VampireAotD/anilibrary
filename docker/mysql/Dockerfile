# syntax=docker/dockerfile:1

ARG MYSQL_VERSION=8.0

FROM mysql:${MYSQL_VERSION}

COPY --link ./conf.d/anilibrary.cnf /etc/mysql/conf.d/
COPY --link ./docker-entrypoint-initdb.d /docker-entrypoint-initdb.d/

EXPOSE 3306