#!/usr/bin/env bash

# For dev purposes only

set -eu

source ../../.env

mkdir -p "${MYSQL_BACKUP_PATH}"

backup_filename=anilibrary-backup_$(date +'%Y-%m-%d').sql

docker exec database mysqldump -u root \
--password="${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" > "${MYSQL_BACKUP_PATH}/$backup_filename"