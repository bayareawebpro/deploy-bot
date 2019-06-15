#!/usr/bin/env bash
# Database Name
DB_STAGING=staging;
DB_PRODUCTION=production;

# Snapshots
SNAPSHOTS_MAX=8;
SNAPSHOTS_DIR='/home/forge/snapshots';

# Release Snapshot (snapshots/production/[hash].sql)
#SNAPSHOT_FILE='test.sql'
SNAPSHOT_FILE='{{ sha }}.sql';
SNAPSHOT_DIR="$SNAPSHOTS_DIR/$DB_PRODUCTION";
SNAPSHOT_PATH="$SNAPSHOT_DIR/$SNAPSHOT_FILE";

# Production Dump (snapshots/production-latest.sql)
CURRENT_FILE="$DB_PRODUCTION-latest.sql";
CURRENT_PATH="$SNAPSHOTS_DIR/$CURRENT_FILE";

#Dump Production Database
function dumpProduction(){
    $(which mysqldump) --single-transaction=TRUE --no-create-db --quick ${DB_PRODUCTION} > ${CURRENT_PATH};
}

#Sync Staging Database to Production
function syncToProduction(){
    $(which mysql) -e 'SET autocommit=0; USE `'"$DB_PRODUCTION"'`; source '"$SNAPSHOT_PATH"'; COMMIT;';
}

#Create Snapshot from Staging
function createSnapshot(){
    $(which mysqldump) --quick --single-transaction --no-create-db ${DB_STAGING} > ${SNAPSHOT_PATH};
}

#Trim Old Snapshots
function trimSnapshots(){
    cd ${SNAPSHOT_DIR} && rm -f `ls -t | awk 'NR>'"$SNAPSHOTS_MAX"''`;
}

## BEGIN SNAPSHOT PROCEDURE
echo "Dumping Production Database: $CURRENT_FILE";
dumpProduction

if [[ -f ${SNAPSHOT_PATH} ]]; then
    echo "Restoring Snapshot to Production: $SNAPSHOT_FILE";
    syncToProduction;
else
    echo "Dumping Staging Database to Snapshot: $SNAPSHOT_FILE";
    createSnapshot;

    echo "Committing Staging Snapshot to Production: $SNAPSHOT_FILE";
    syncToProduction;

    echo "Trimming Old Snapshots...";
    trimSnapshots;
fi
