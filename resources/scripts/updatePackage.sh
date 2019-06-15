#!/usr/bin/env bash
cd $1 && git pull && composer update --ansi --no-dev;
