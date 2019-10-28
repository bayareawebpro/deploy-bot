#!/usr/bin/env bash
mysql --batch --skip-column-names -e 'SHOW DATABASES' | grep "$1"
