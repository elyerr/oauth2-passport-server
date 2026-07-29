# OAuth2 Passport Server

A modular **Identity and Access Management (IAM)** platform designed to centralize authentication, authorization, and access control across applications, APIs, and services.

## What It Solves

When each application manages its own authentication, permissions, and users, organizations often face duplicated accounts, inconsistent authorization rules, and increased operational overhead.

OAuth2 Passport Server centralizes this layer, allowing you to:

- Unify identity and authentication.
- Manage permissions using fine-grained scopes.
- Provide OAuth 2.0 and OpenID Connect support.
- Extend the platform through modules without modifying the core.

## Features

- OAuth 2.0 Authorization Server
- OpenID Connect Provider
- Single Sign-On (SSO) for multiple applications
- User, group, role, service, and scope management
- Modular architecture powered by Elymod
- Local, staging, and production deployment with Docker

## Quick Documentation

### English

- [User Guide](docs/en/users.md)
- [Developer Guide](docs/en/developers.md)
- [System Settings](docs/en/settings.md)
- [Install Modules](docs/en/modules/install.md)
- [Update Modules](docs/en/modules/update.md)
- [Remove Modules](docs/en/modules/delete.md)
- [Create Modules](docs/en/modules/make.md)
- [OAuth2 and Authorization Flows](docs/en/oauth2.md)
- [Scopes and Permissions](docs/en/scopes.md)
- [Production Deployment](docs/en/deploy.md)
- [Architecture](docs/architecture_en.md)

### Español

- [Guía para Usuarios](docs/es/users.md)
- [Guía para Desarrolladores](docs/es/developers.md)
- [Configuración del Sistema](docs/es/settings.md)
- [Instalación de Módulos](docs/es/modules/install.md)
- [Actualización de Módulos](docs/es/modules/update.md)
- [Eliminar Módulos](docs/es/modules/delete.md)
- [Crear Módulos](docs/es/modules/make.md)
- [OAuth2 y Flujos de Autorización](docs/es/oauth2.md)
- [Scopes y Permisos](docs/es/scopes.md)
- [Despliegue en Producción](docs/es/deploy.md)
- [Arquitectura](docs/architecture_es.md)

## Quick Start

```bash
git clone -b main --single-branch git@github.com:elyerr/oauth2-passport-server.git
cd oauth2-passport-server
cp .env.example .env
./dev --deploy
```

## Useful Commands

### Development

- `./dev --deploy`
- `./dev --stop`
- `./dev bash`

### Staging

- `./staging --deploy`
- `./staging --stop`
- `./staging bash`

### Production

- `./production --deploy`
- `./production --stop`
- `./production bash`

## In One Sentence

OAuth2 Passport Server provides a centralized identity and authorization platform, allowing every application within your ecosystem to authenticate users, authorize requests, and manage access through a single, unified service.

## Versioning

It is recommended to use stable releases (tags) and review the corresponding documentation before upgrading to a newer version.

## License

See [LICENSE.md](LICENSE.md).
