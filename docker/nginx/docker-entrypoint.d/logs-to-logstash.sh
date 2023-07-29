#!/usr/bin/env sh

set -e

if [ "$APP_ENV" = "production" ]; then
  sed -i "s|access_log.*|access_log syslog:server=$LOGSTASH_URL logstash;|g" /etc/nginx/conf.d/default.conf
  sed -i "s|error_log.*|error_log syslog:server=$LOGSTASH_URL notice;|g" /etc/nginx/conf.d/default.conf
fi
