#!/usr/bin/env bash
cd "$1" || return
php artisan optimize:clear
php artisan telescope:clear
