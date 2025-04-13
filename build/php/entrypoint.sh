#!/usr/bin/env sh

set -eu

mode=${CONTAINER_MODE:-"octane"}

if [ "${mode}" = "octane" ]; then
  exec php artisan octane:start --host=0.0.0.0 --port=8000 --watch
elif [ "${mode}" = "horizon" ]; then
  exec php artisan horizon
elif [ "${mode}" = "reverb" ]; then
  exec php artisan reverb:start
elif [ "${mode}" = "scheduler" ]; then
  exec php artisan schedule:work
elif [ "${mode}" = "telegram" ]; then
  exec php artisan nutgram:run
else
  echo "Container mode mismatched."
  exit 1
fi
