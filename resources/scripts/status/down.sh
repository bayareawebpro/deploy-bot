#!/usr/bin/env bash
cd "$1" || echo "Failed Changing Directory to $1" && exit 1
php artisan down
