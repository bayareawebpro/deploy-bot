#!/usr/bin/env bash
cd "$1" || exit 1
npm install --production --silent --no-progress --no-optional
npm run prod

