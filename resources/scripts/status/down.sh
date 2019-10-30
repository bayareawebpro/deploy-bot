#!/usr/bin/env bash
cd "$1" || exit 1
php artisan down
