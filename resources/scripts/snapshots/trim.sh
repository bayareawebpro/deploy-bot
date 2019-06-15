#!/usr/bin/env bash
if [[ -z $2 ]]; then
    SNAPSHOTS_MAX=8
else
    SNAPSHOTS_MAX=$2
fi
if [[ -d $1 ]]; then
    cd $1 && rm -f `ls -t | awk 'NR>'"$SNAPSHOTS_MAX"''`;
    echo "Snapshots directory cleaned successfully.";
else
    echo "Snapshots directory does not exist." 1>&2;
fi
