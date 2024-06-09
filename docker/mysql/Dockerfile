# syntax=docker/dockerfile:1

ARG MYSQL_VERSION=8.0

FROM mysql:${MYSQL_VERSION}

COPY ./conf.d/anilibrary.cnf /etc/mysql/conf.d/
COPY ./docker-entrypoint-initdb.d /docker-entrypoint-initdb.d/

EXPOSE 3306