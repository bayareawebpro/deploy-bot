#!/usr/bin/env bash
mysql -e 'SET autocommit=0; USE `'"$1"'`; source '"$2"'; COMMIT;';
