#!/usr/bin/env bash

set -eu

git config core.hooksPath .git/hooks

echo "Hooks folder has been reverted to .git/hooks"