#!/usr/bin/env bash

set -eu

. ../.env

docker exec database /usr/bin/mysqldump -u root \
--password="${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" > "${MYSQL_BACKUP_PATH}"
