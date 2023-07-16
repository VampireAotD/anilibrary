#!/usr/bin/env bash

set -eu
set -o pipefail

function log {
  echo "[+] $1"
}

function err {
  echo "[x] Error: $1"
}

function set_compose_bin {
  if which docker-compose >/dev/null 2>&1; then
    echo docker-compose
    return 0
  fi

  if which docker compose >/dev/null 2>&1; then
    echo docker compose
    return 0
  fi

  echo "Couldn't find any version of docker compose"
  return 1
}

compose=$(set_compose_bin) || {
  err "$compose"
  exit 1
}

echo '⠿ Installing Anilibrary'

log 'Creating .env file with values from .env.example in root'
cp ./.env.example ./.env

log 'Creating Laravel .env file with values from .env.example in src'
cp ./src/.env.example ./src/.env

log 'Building images'
$compose up -d --build

log 'Installing Composer packages'
$compose exec app composer install

log 'Generating Laravel app key'
$compose exec app ./artisan key:generate

log 'Creating database'
$compose exec app ./artisan migrate --seed

log 'Parsing anime list'

if ! $compose exec app ./artisan anime-list:parse >/dev/null 2>&1; then
  echo '>> No anime list were found, skipping...'
fi

log 'Resolving owner'
$compose exec app ./artisan setup:create-owner

echo '⠿ Anilibrary has been successfully installed!'
