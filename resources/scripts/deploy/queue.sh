#!/usr/bin/env bash
cd "$1" && \
php artisan queue:restart
