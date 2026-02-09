# Generiek Issuer Portaal

PoC application to issue Verifiable Credentials.

This project is part of the 'Generieke Functies' project of the Ministry of Health, Welfare and Sport of the Dutch government.


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

> **Quickstart**
> 
> The easiest way is to start the example setup including the external dependencies as explained [here](#running-the-full-stack) by running the snippet below from this repository.
> This will clone all repositories, including this repository and start the example setup.
> 
> ```bash
> git clone https://github.com/minvws/gfmodules-generieke-issuer-portaal
> git clone https://github.com/minvws/gfmodules-generieke-issuer-revocatie-api
> git clone https://github.com/minvws/gfmodules-source-connector-api-private
> cd gfmodules-generieke-issuer-portaal/example-setup
>
> docker compose up -d
> ```
> 
> This will start the portaal on 'http://localhost:8564'
>


If you would like to run the generieke-issuer-portaal in laraval sail, follow the steps below.


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

### Running the full stack 
if you wish to run the whole Verifiable Credentials stack including external applications make sure you have the following repository 
available locally in the same parent directory as this one:

- [generieke-issuer-revocatie-api](https://github.com/minvws/gfmodules-generieke-issuer-revocatie-api)
- [source-connector-api](https://github.com/minvws/gfmodules-source-connector-api) 

The directory layout should be like the following example:
```bash 

- parent_dir: 
    - gfmodules-generieke-issuer-portaal
    _ gfmodules-generieke-issuer-revocatie-api
    _ gfmoudles-source-connector-api 
```


Run the stack from [example setup docker compose file](./example-setup/docker-compose.yml) using:

```bash
cd example-setup
docker compose up 
```

All configurations related to the portal and the waltid can be found in [example-setup](./example-setup/) directory.
Other related repositories configuration for revocation api and connector api can 
be updated in the repo directory itself.
Be aware, if the portaal contains a `.env` file, it's used instead of the .env.coordination file from the compose-services directory.


## Credential Signing

To sign credentials, the application uses a private key.
This is currently only for development purposes. In production, the private key should be generated with a secure algorithm and should be stored in a secure location.
The private key can be generated using the following command:

```bash
openssl ecparam -name prime256v1 -genkey -noout -out secrets/key.pem
```

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

## Contribution

As stated in the [Disclaimer](#disclaimer) this project and all associated code serve solely as documentation and
demonstration purposes to illustrate potential system communication patterns and architectures.

For that reason we will only accept contributions that fit this goal. We do appreciate any effort from the
community, but because our time is limited it is possible that your PR or issue is closed without a full justification.

If you plan to make non-trivial changes, we recommend to open an issue beforehand where we can discuss your planned changes. This increases the chance that we might be able to use your contribution (or it avoids doing work if there are reasons why we wouldn't be able to use it).

Note that all commits should be signed using a gpg key.
