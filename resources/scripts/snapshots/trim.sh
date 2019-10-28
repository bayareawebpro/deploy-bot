#!/usr/bin/env bash
cd "$1" && rm -f `ls -t | awk 'NR>10'`;
