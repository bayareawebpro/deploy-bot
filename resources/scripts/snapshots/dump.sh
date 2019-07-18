#!/usr/bin/env bash
$(which mysqldump) --quick --single-transaction --no-create-db --default-character-set=utf8mb4 $1 > $2;
