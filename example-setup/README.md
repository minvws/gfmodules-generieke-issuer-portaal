# Example Setup

This directory contains a complete Docker Compose setup to run the **Generiek Issuer Portaal** with all its dependencies. It is the easiest way to get the Proof of Concept stack running locally.

For general project information, see the [main README](../README.md).


## Overview

The example setup orchestrates the following services:

| Service                                                                                                            | Port                                 | Description                                               |
|--------------------------------------------------------------------------------------------------------------------|--------------------------------------|-----------------------------------------------------------|
| Generiek Issuer Portaal                                                                                            | [`8564`](http://localhost:8564)      | The main issuer portal application                        |
| [Issuer API (walt.id)](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-issuer-api)     | [`8560`](http://localhost:8560)      | API for issuing Verifiable Credentials                    |
| [Verifier API (walt.id)](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-verifier-api) | [`8561`](http://localhost:8561)      | API for verifying Verifiable Credentials                  |
| [Caddy](https://caddyserver.com/) (wallet proxy)                                                                   | [`8562`](http://localhost:8562)      | Reverse proxy serving the Dev Wallet and Wallet API       |
| [Dev Wallet (walt.id)](https://github.com/walt-id/waltid-identity/tree/main/waltid-applications/waltid-web-wallet) | [`8562`](http://localhost:8562)      | Development wallet UI for testing credentials (via Caddy) |
| [Wallet API (walt.id)](https://github.com/walt-id/waltid-identity/tree/main/waltid-services/waltid-wallet-api)     | —                                    | Backend API for the wallet (via Caddy)                    |
| [Revocation API](https://github.com/minvws/gfmodules-generieke-issuer-revocatie-api)                               | [`8565`](http://localhost:8565/docs) | API for credential revocation                             |
| [Source Connector API](https://github.com/minvws/gfmodules-source-connector-api)                                   | [`8566`](http://localhost:8566/docs) | API for connecting to data sources                        |
| [PostgreSQL](https://www.postgresql.org/)                                                                          | `8432`                               | Database for wallet and other services                    |

The **Dev Wallet** (frontend) and **Wallet API** (backend) are not directly exposed. Instead, they are served through a **Caddy** reverse proxy on port `8562`. Caddy routes frontend requests to the Dev Wallet and `/wallet-api/*` requests to the Wallet API backend. This allows both services to be accessed from a single endpoint.


## Prerequisites

1. **Docker** and **Docker Compose** installed on your system
2. **`.npmrc`** file with a **GitHub personal access token** configured in your home directory, required to install the [rijksoverheid-ui-theme](https://github.com/minvws/nl-rdo-rijksoverheid-ui-theme?tab=readme-ov-file#installation) package from the GitHub npm registry
3. **Related repositories** cloned in the same parent directory:
   - [gfmodules-generieke-issuer-revocatie-api](https://github.com/minvws/gfmodules-generieke-issuer-revocatie-api)
   - [gfmodules-source-connector-api](https://github.com/minvws/gfmodules-source-connector-api)

The expected directory layout:

```
parent_dir/
├── gfmodules-generieke-issuer-portaal/         # This repository
├── gfmodules-generieke-issuer-revocatie-api/
└── gfmodules-source-connector-api/
```


## Quick Start

From within this directory (`example-setup/`):

```bash
docker compose up -d
```

The portaal will be available at **http://localhost:8564**.



## Persistent Volumes

The setup uses Docker volumes to persist data:

| Volume | Contents |
|--------|----------|
| `postgres_data` | PostgreSQL database data |
| `generieke-issuer-portaal-data` | Portal application data, including generated `.env` and keys |

On first run, the `.env` file and cryptographic keys are automatically created and stored in the volume. On subsequent runs, these persisted files are reused, so your configuration remains consistent across container restarts.

### Resetting the Setup

To stop the containers **and remove all volumes** (this will delete all persisted data):

```bash
docker compose down -v
```


## Configuration

### Portal Configuration

The portal configuration is managed through:

- `compose-services/generieke-issuer-portaal/.env.coordination` — Environment variables for the portal

### Walt.id Services Configuration

- `compose-services/waltid/issuer-api/config/` — Issuer API configuration
- `compose-services/waltid/wallet-api/config/` — Wallet API configuration
- `compose-services/waltid/Caddyfile` — Caddy reverse proxy configuration (routes to Dev Wallet and Wallet API)


## Troubleshooting

### Services not starting

Check that all required repositories are cloned in the correct location (see [Prerequisites](#prerequisites)).

### npm authentication errors

Ensure your `~/.npmrc` file is configured correctly for the rijksoverheid-ui-theme package. See the [installation instructions](https://github.com/minvws/nl-rdo-rijksoverheid-ui-theme?tab=readme-ov-file#installation).

### Database connection issues

If services fail to connect to PostgreSQL, wait for the database to be fully initialized. Check its status with:

```bash
docker compose ps postgres
```

### Viewing logs

View logs for all services:

```bash
docker compose logs -f
```

View logs for a specific service:

```bash
docker compose logs -f generieke-issuer-portaal
```
