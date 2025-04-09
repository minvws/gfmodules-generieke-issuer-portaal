Auto-generated README for gfmodules-generieke-issuer-portaal-private

## Development setup

Requirements:
- php
- composer
- npm

Run the following commands to run this application in docker using ```sail```.



```bash
composer install
npm install
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
# Currently not able to load the key
openssl ecparam -name secp256k1 -genkey -noout -out key.pem
```

The application is available at http://localhost:8498/flow.


It is possible to test the connection to the Issuer API using the following command:

```bash
sail artisan app:make-credential
```


## External services

The VC issuer uses external opensource services to issue credentials. The following services are used:

- [Issuer API](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-issuer-api)
- [Verifier API](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-verifier-api)
- [Dev Wallet](https://github.com/walt-id/waltid-identity/tree/main/waltid-applications/waltid-web-wallet)
  - [Wallet API](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-wallet-api)  


## Wallet

To store the credentials, a wallet is needed.

It is possible to setup a development wallet using the following command:

```bash
git clone git@github.com/walt-id/waltid-identity.git && cd waltid-identity
cd docker-compose && docker compose --profile apps --profile services up
```

After this the wallet is available at http://localhost:7101.

