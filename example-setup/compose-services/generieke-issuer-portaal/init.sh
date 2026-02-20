#!/bin/bash -xe

# set user from docker compose
if [ ! -z "$WWWUSER" ]; then
    usermod -u "$WWWUSER" sail
fi

# fix permissions for named docker volume
# needed because the named volume is owned by root
# and the sail user needs write permissions to it
if ! su -s /bin/sh sail -c "test -w '/opt/app'"; then
    chown sail:sail "/opt/app"
fi

# sail user executes init sail
gosu "$WWWUSER" /opt/app/init-sail.sh

echo "starting container from init.."
start-container
