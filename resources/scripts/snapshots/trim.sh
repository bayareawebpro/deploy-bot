#!/usr/bin/env bash
cd "$1" || return;
if [ "$1" == "$PWD" ]; then
  rm -f `ls -t | awk 'NR>10'`;
else
  echo "Failed to clean snapshots."
fi
