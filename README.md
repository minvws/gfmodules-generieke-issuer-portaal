# Generiek Issuer Portaal

PoC application to issue Verifiable Credentials.
NOTE: This is a Proof of Concept application and not intended for production use.


> [!IMPORTANT]
> ## Disclaimer
> 
> This project and all associated code serve solely as documentation
> and demonstration purposes to illustrate potential system
> communication patterns and architectures.
> 
> This codebase:
> 
> - Is NOT intended for production use
> - Does NOT represent a final specification
> - Should NOT be considered feature-complete or secure
> - May contain errors, omissions, or oversimplified implementations
> - Has NOT been tested or hardened for real-world scenarios
> - Is not guaranteed to follow any versioning scheme
> 
> The code examples are only meant to help understand concepts and demonstrate possibilities.
> 
> By using or referencing this code, you acknowledge that you do so at your own
> risk and that the authors assume no liability for any consequences of its use.


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

The current implementation issues JSON-LD credentials that are secured with JOSE. This is based on the [Securing Verifiable Credentials using JOSE and COSE](https://www.w3.org/TR/vc-jose-cose/#securing-with-jose) specification.
The used Issuer API also supports SD-JWT credentials.

The opensource walt.id stack that is used has no support for revocation of credentials yet. See also https://github.com/walt-id/waltid-identity/issues/991. 
It would be possible to set credential statuses by using an external status services, like:
- https://github.com/digitalcredentials/status-service-db
- https://github.com/eu-digital-identity-wallet/eudi-srv-statuslist-py
