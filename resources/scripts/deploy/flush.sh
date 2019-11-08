#!/usr/bin/env bash
cd "$1" && \
php artisan optimize:clear && \
php artisan telescope:clear
