# syntax=docker/dockerfile:1

ARG NGINX_VERSION=1.27

FROM nginx:${NGINX_VERSION}-alpine

COPY ./nginx.conf /etc/nginx
COPY ./conf.d/default.conf /etc/nginx/conf.d/
COPY ./docker-entrypoint.d/logs-to-logstash.sh /docker-entrypoint.d/

RUN chmod +x /docker-entrypoint.d/logs-to-logstash.sh

EXPOSE 80