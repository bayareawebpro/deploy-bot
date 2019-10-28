#!/usr/bin/env bash
cd "$1" || echo "Failed Changing Directory to $1" && exit 1
php artisan view:clear;
php artisan cache:clear;
php artisan telescope:clear;
