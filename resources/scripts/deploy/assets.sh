#!/usr/bin/env bash
cd "$1" || return
echo "Compiling Assets @ $CWD"
npm install --production --silent --no-progress --no-optional
npm run prod

