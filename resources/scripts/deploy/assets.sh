#!/usr/bin/env bash'
echo "Changing Directory: $1"
cd "$1" && npm install --production --silent --no-progress --no-optional && npm run prod

