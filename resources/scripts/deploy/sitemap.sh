#!/usr/bin/env bash
cd "$1" || return
php artisan sitemap:generate
