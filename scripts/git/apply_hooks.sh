#!/usr/bin/env bash

set -eu

if [ ! -d ".git" ]; then
  echo ".git folder not found, try git install"
  exit 1
fi

if [ ! -d ".hooks" ]; then
  echo ".hooks folder not found"
  exit 1
fi

if find ".hooks/" -maxdepth 1 -type f -quit 2>/dev/null; then
  echo "Applying hooks..."

  find ".hooks/" -follow -type f -print | sort -V | while read -r f; do
    chmod +x "$f"
    echo "$(basename "$f") applied"
  done
else
  echo "No hooks found in .hooks"
fi

git config core.hooksPath .hooks

echo "Hooks folder has been set to .hooks!"
