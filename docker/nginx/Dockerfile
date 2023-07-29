FROM nginx

COPY ./nginx.conf /etc/nginx
COPY ./conf.d/default.conf /etc/nginx/conf.d/
COPY ./docker-entrypoint.d/logs-to-logstash.sh /docker-entrypoint.d/

RUN chmod +x /docker-entrypoint.d/logs-to-logstash.sh