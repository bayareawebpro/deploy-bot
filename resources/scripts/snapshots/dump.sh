#!/usr/bin/env bash
$(which mysqldump) --quick --single-transaction --no-create-db $1 > $2;
