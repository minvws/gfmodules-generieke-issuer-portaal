Auto-generated README for gfmodules-generieke-issuer-portaal-private

## Development setup

Requirements:
- php
- composer
- npm

Run the following commands to run this application in docker using ```sail```.



```bash
composer install
npm run build
vendor/bin/sail up -d
vendor/bin/sail artisan key:generate
```


It is possible to generate an EC key using:

```bash
openssl ecparam -name prime256v1 -genkey -noout -out key.pem
```
or:

```bash
openssl ecparam -name secp256k1 -genkey -noout -out key.pem
```
