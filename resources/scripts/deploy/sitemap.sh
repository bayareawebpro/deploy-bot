#!/usr/bin/env bash
cd "$1" && \
php artisan sitemap:generate
