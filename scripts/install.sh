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
  elif docker compose version >/dev/null 2>&1; then
    echo docker compose
    return 0
  else
    echo "Couldn't find any version of docker compose"
    return 1
  fi
}

compose=$(set_compose_bin) || {
  err "$compose"
  exit 1
}

echo '⠿ Installing Anilibrary'

log 'Creating .env file with values from .env.example in root'
if [ ! -f ./.env ]; then
  cp ./.env.example ./.env
fi

log 'Creating Laravel .env file with values from .env.example in src'
if [ ! -f ./src/.env ]; then
  cp ./src/.env.example ./src/.env
fi

log 'Building images'
$compose up -d --build

log 'Installing frontend dependencies'
$compose exec app pnpm install --frozen-lockfile

log 'Installing Composer packages'
$compose exec app composer install

log 'Generating Laravel app key'
$compose exec app ./artisan key:generate

log 'Creating database'
$compose exec app ./artisan migrate --seed

log 'Resolving owner'
$compose exec app ./artisan setup:create-owner

log 'Parsing anime list'
if ! $compose exec app ./artisan anime-list:parse >/dev/null 2>&1; then
  echo '>> No anime list were found, skipping...'
fi

echo '⠿ Anilibrary has been successfully installed!'
