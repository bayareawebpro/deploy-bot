#!/usr/bin/env bash
if [[ -z $2 ]]; then
    SNAPSHOTS_MAX=8
else
    SNAPSHOTS_MAX=$2
fi
cd $1 && rm -f `ls -t | awk NR> ${SNAPSHOTS_MAX}`;
