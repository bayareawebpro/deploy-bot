#!/usr/bin/env bash
cd "$1" || return;
rm -f `ls -t | awk 'NR>10'`;
