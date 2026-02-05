#!/bin/bash -xe

if [ ! -f /opt/app/issuer-key.pem ]; then
    openssl ecparam -name prime256v1 -genkey -noout -out /opt/app/issuer-key.pem
fi
composer install --ignore-platform-reqs
npm install --userconfig /run/secrets/npmrc
npm run build

if [ ! -f /opt/app/.env ]; then
    cp /opt/app/.env.coordination /opt/app/.env
fi

grep ^APP_KEY=$ /opt/app/.env && php artisan key:generate

exit 0
