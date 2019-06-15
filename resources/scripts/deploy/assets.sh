#!/usr/bin/env bash
cd $1 && npm install --quiet --no-progress && npm run prod;
