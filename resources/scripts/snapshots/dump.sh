#!/usr/bin/env bash
mysqldump --quick --single-transaction --no-create-db --default-character-set=utf8mb4 "$1" > "$2";
echo "Database Dumped: $1 => $2"
