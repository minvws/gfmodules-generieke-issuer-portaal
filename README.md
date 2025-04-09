# Generic VC Issuer

PoC application to issue Verifiable Credentials.
NOTE: This is a Proof of Concept application and not intended for production use.

## Development setup

Requirements:
- php
- composer
- npm

Run the following commands to run this application.

```bash
cp .env.example .env
composer install
php artisan key:generate
npm install
npm run build
vendor/bin/sail up -d
```

It is possible to generate an EC key for VC signing using:

```bash
openssl ecparam -name prime256v1 -genkey -noout -out secrets/key.pem

# Currently not able to load the key with secp256k1
#openssl ecparam -name secp256k1 -genkey -noout -out key.pem
```

The application is available at http://localhost:8600/flow.

The wallet is available at http://localhost:8610.

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
