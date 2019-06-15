#!/usr/bin/env bash
cd $1 && npm install --silent --no-progress --no-optional && npm run prod;
