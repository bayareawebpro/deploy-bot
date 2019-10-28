#!/usr/bin/env bash
cd "$1" || echo "Failed Changing Directory to $1" && exit 1
php artisan optimize;
php artisan route:cache;
php artisan config:cache;
php artisan view:cache;
