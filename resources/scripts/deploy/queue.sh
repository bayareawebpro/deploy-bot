#!/usr/bin/env bash
cd "$1" && \
#php artisan queue:restart
php artisan horizon:purge
php artisan horizon:terminate
