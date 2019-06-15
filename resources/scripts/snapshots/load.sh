#!/usr/bin/env bash
$(which mysql) -e 'SET autocommit=0; USE `'"$1"'`; source '"$2"'; COMMIT;';
