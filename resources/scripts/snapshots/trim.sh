#!/usr/bin/env bash
if [[ -d $1 ]]; then
    cd $1 && rm -f `ls -t | awk NR> $2`;
    echo "Snapshots directory cleaned successfully.";
else
    echo "Snapshots directory does not exist." 1>&2;
fi
