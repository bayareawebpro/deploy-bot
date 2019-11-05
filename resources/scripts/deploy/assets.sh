#!/usr/bin/env bash
cd "$1" || return
npm install --no-progress --no-optional
npm run prod

