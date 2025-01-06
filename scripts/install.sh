#!/usr/bin/env bash

set -eu
set -o pipefail

function info {
  echo "⠿ $1"
}

function log {
  echo "[+] $1"
}

function err {
  echo "[x] Error: $1"
}

info 'Installing Anilibrary'

log 'Building images'
docker compose build

log 'Installing dependencies'
docker compose run -d --name anilibrary-dependencies app
docker compose exec app composer install
docker compose exec app pnpm install --frozen-lockfile

log 'Generating Laravel app key'
docker compose exec app ./artisan key:generate

log 'Launching containers'
docker rm -f anilibrary-dependencies
docker compose up -d

log 'Running migrations'
docker compose exec app ./artisan migrate --seed

log 'Resolving owner'
docker compose exec app ./artisan setup:create-owner

info 'Anilibrary has been successfully installed!'
