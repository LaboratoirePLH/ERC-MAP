#!/bin/bash
set -e

# source variables
source /env-data.sh
# start postgres (init script of parent container stops it)
su - postgres -c "${POSTGRES} -D ${DATADIR} -c config_file=${CONF} ${LOCALONLY} &"
# wait for postgres to come up
until su - postgres -c "psql -l"; do
  sleep 1
done
echo "postgres ready"

# check if database already exists
RESULT=`su - postgres -c "psql -t -c \"SELECT count(1) from pg_database where datname='polytheisms';\""`
if [[  ${RESULT} -eq 0 ]]; then
    # execute scripts
    su - postgres -c "psql -v ON_ERROR_STOP=1 -c \"CREATE USER polytheisms WITH ENCRYPTED PASSWORD 'polytheisms';\""

    su - postgres -c "psql -v ON_ERROR_STOP=1 -c \"CREATE DATABASE polytheisms TEMPLATE template0 ENCODING 'UTF8' LC_COLLATE 'fr_FR.UTF-8' LC_CTYPE 'fr_FR.UTF-8';\""
    su - postgres -c "psql -v ON_ERROR_STOP=1 -c \"GRANT ALL PRIVILEGES ON DATABASE polytheisms TO polytheisms;\""

    su - postgres -c "psql -v ON_ERROR_STOP=1 -c \"SELECT datname, datcollate, datctype FROM pg_database;\""

    su - postgres -c "psql -v ON_ERROR_STOP=1 -d polytheisms -c \"CREATE EXTENSION postgis;\""
else
    echo "polytheisms db already exists"
fi

#shut down postgres
PID=`cat ${PG_PID}`
kill -TERM ${PID}

# Wait for background postgres main process to exit
while [[ "$(ls -A ${PG_PID} 2>/dev/null)" ]]; do
  sleep 1
done