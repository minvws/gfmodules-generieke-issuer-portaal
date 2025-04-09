# Generic VC Issuer

PoC application to issue Verifiable Credentials.
NOTE: This is a Proof of Concept application and not intended for production use.

## Development setup

Requirements:
- php
- composer
- npm
- openssl

Run the following commands to run this application.

```bash
cp .env.example .env
composer install
php artisan key:generate
openssl ecparam -name prime256v1 -genkey -noout -out secrets/key.pem
npm install
npm run build
vendor/bin/sail up -d
```

The application is available at http://localhost:8600/flow.

The wallet is available at http://localhost:8610.

It is possible to test the connection to the Issuer API using the following command:

```bash
sail artisan app:make-credential
```

## Credential Signing

To sign credentials, the application uses a private key. The private key can be generated using the following command:

```bash
openssl ecparam -name prime256v1 -genkey -noout -out secrets/key.pem

# Currently not able to load the key with secp256k1
#openssl ecparam -name secp256k1 -genkey -noout -out key.pem
```

This is currently only for development purposes. In production, the private key should be generated with a secure algorithm and should be stored in a secure location.

## External services

The VC issuer uses external opensource services to issue credentials. The following services are used:

- [Issuer API](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-issuer-api)
- [Verifier API](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-verifier-api)
- [Dev Wallet](https://github.com/walt-id/waltid-identity/tree/main/waltid-applications/waltid-web-wallet)
  - [Wallet API](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-wallet-api)

## Current status

The application is able to issue credentials using the Issuer API of walt.id and
it is possible to load the credential in the walt.id dev wallet.

There is nu support for revocation of credentials yet.
It would be possible to revoke credentials by using external status services.

## External status services

... 
