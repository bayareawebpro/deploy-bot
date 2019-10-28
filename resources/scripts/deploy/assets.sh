#!/usr/bin/env bash
cd "$1" || echo "Failed Changing Directory to $1" && exit 1
npm install --silent --no-progress --no-optional
npm run prod
