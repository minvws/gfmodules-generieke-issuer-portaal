#!/bin/bash -xe

# set user from docker compose
if [ ! -z "$WWWUSER" ]; then
	usermod -u "$WWWUSER" sail
fi

# sail user executes init sail
gosu "$WWWUSER" /opt/app/init-sail.sh

echo "starting container from init.."
start-container
