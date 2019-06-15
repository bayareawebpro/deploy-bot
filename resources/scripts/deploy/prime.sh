#!/usr/bin/env bash
cd $1;
php artisan optimize;
php artisan route:cache;
php artisan config:cache;
php artisan view:cache;
