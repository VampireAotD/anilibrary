#!/usr/bin/env bash

ENV_PATH=../.env

# Load .env variables
export $(grep -v '^#' ${ENV_PATH} | xargs)

docker exec database /usr/bin/mysqldump -u root \
--password=${MYSQL_ROOT_PASSWORD} ${MYSQL_DATABASE} > ${MYSQL_BACKUP_PATH}
