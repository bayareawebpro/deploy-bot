#!/usr/bin/env bash
cd $1;
php artisan view:clear;
php artisan cache:clear;
php artisan telescope:clear;
