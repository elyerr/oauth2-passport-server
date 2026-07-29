# Modular Software Architecture

## Overview

The platform is built around a **fully modular architecture** whose primary responsibility is to provide an **OAuth 2.0** and **OpenID Connect** authorization server. Every additional capability is implemented as an independent module, allowing the core to remain lightweight, maintainable, and focused on authentication and authorization.

Unlike traditional modular systems that share a single dependency tree (`composer.json` and `vendor`) across the entire application, every module in this architecture is completely isolated.

Each module has:

- Its own source code
- Its own `composer.json`
- Its own dependencies
- Its own release cycle
- Its own update process

This allows modules to be installed, updated, or removed without modifying the core application.

---

# Overall Architecture

```text
                    OAuth2 Passport Server
                           (Core)
                               │
         ┌─────────────────────┴─────────────────────┐
         │                                           │
  Laravel Runtime                               Elymod
         │                                           │
         │                                 Uses Elymod App
         │                                 as the module skeleton
         │
         ├───────────────────────────────────────────────┐
         │                                               │
   Module A                                        Module B
   ├── composer.json                               ├── composer.json
   ├── vendor/                                     ├── vendor/
   ├── elyscope                                    ├── elyscope
   └── isolated dependencies                       └── isolated dependencies
```

---

# Core Components

## OAuth2 Passport Server

OAuth2 Passport Server is the **core** of the platform.

Its primary purpose is to provide an OAuth 2.0 Authorization Server and OpenID Connect Provider while exposing the infrastructure required for modular extensions.

### Main Features

- OAuth 2.0 Authorization Server
- OpenID Connect Provider
- User management
- Audit logs
- Dynamic configuration system
- Rate limiting
- Content Security Policy (CSP)
- Laravel Horizon integration with CSP support
- Modular service loading
- Automatic module discovery

---

# Authorization Model

Instead of implementing a traditional RBAC system, the platform introduces a more flexible authorization model based on four core concepts:

- Groups
- Services
- Roles
- Scopes

```
Group
   │
   └── Service
          │
          └── Role
                │
                ▼
             Scope (GSR)
```

## Groups

Groups represent the primary access categories within the platform.

OAuth2 Passport Server provides the following built-in groups:

- `administrator`
- `settings`
- `developer`
- `member`
- `commerce`
- `enterprise`
- `reseller`

Each group defines a context in which modules can register their own services.

Developers may also create additional groups to meet the requirements of their applications.

---

## Services

Services represent a resource or functionality within a group.

Each service name must be unique only within its own group.

For example:

```
administrator
├── users
├── modules
├── logs

settings
├── general
├── mail
├── cache

commerce
├── vpn
├── cloud
├── billing
```

The same service name may exist in different groups without creating conflicts.

For example:

```
administrator
└── settings

developer
└── settings
```

Although both services are named `settings`, they belong to different groups and therefore represent different resources.

---

## Roles

Roles define the actions that can be performed on a service.

Examples include:

- `view`
- `create`
- `update`
- `delete`
- `export`

Roles are fully dynamic.

Modules can register new roles whenever needed, allowing the authorization model to evolve without modifying the core.

---

## Scopes (GSR)

Whenever a Role is assigned to a Service within a Group, the system generates a unique **Scope**.

Internally, a Scope is identified by the combination:

```
Group → Service → Role
```

Examples:

```
administrator:users:view
administrator:users:create
administrator:modules:install

settings:general:update
settings:mail:update

commerce:vpn:create
commerce:vpn:delete
```

Scopes are the permissions ultimately assigned to users.

When a user owns a Scope, they are authorized to perform the corresponding action on the protected resource.

# Laravel Runtime

Laravel Runtime is an abstraction layer built on top of Artisan.

Instead of generating resources in the application root, Laravel Runtime automatically creates resources inside the appropriate module.

Examples include:

- Controllers
- Models
- Policies
- Requests
- Migrations
- Commands
- Resources

Laravel Runtime is installed once in OAuth2 Passport Server and serves as the development runtime for every module.

---

# Elymod

Elymod is responsible for creating new modules.

Its behavior is similar to Laravel's project generator, but instead of generating a Laravel application, it generates a fully isolated module.

Responsibilities include:

- Module scaffolding
- Directory generation
- Configuration generation
- Registration
- Development template generation

---

# Elymod App

Elymod App is the module skeleton used by Elymod.

Every module is generated from this template.

It contains the default directory structure, configuration, development tools, and conventions required by the platform.

Whenever a new module is created, the latest version of Elymod App is used.

---

# Elyscope

Elyscope is a bridge between Composer and PHP-Scoper.

From the developer's perspective, Elyscope behaves like Composer while performing additional operations automatically.

Responsibilities include:

- Installing dependencies
- Updating dependencies
- Executing Composer commands
- Running PHP-Scoper
- Isolating vendor packages
- Preparing the module for execution

Every module uses Elyscope instead of invoking Composer directly.

This guarantees complete dependency isolation.

---

# Content Module

Content is an optional production module that adds CMS and SEO capabilities to OAuth2 Passport Server.

## Features

- Static page management
- Blade-powered page rendering
- Layout management
- Cookie consent modal
- Privacy modal
- Header customization
- Footer customization
- Font management
- Favicon management
- Schema.org integration
- Static page SEO customization
- robots.txt management
- XML Sitemap generation
- Search engine indexing support

The module also provides reusable layouts to simplify page creation while maintaining a consistent user experience.

---

# Why Content is a Module

Prior to Version 9, many CMS-related features were included directly inside the core.

These features were eventually extracted because they were outside the primary purpose of the authorization server.

Keeping Content as a standalone module provides several benefits:

- Smaller core
- Independent updates
- Faster feature development
- Better maintainability
- Clear separation of responsibilities

OAuth2 Passport Server remains focused on authentication and authorization, while Content evolves independently.

---

# Version 9 Philosophy

Version 9 represents a complete architectural redesign.

Many features previously bundled with the core were removed and transformed into standalone modules.

This redesign aligns the project with its original vision:

> OAuth2 Passport Server should only be responsible for authentication and authorization. Every additional feature belongs in an independent module.

---

# Dependency Isolation

One of the platform's defining characteristics is complete dependency isolation.

Unlike most Laravel modular solutions, modules **do not share a global dependency tree**.

Traditional modular systems generally look like this:

```
Application
├── composer.json
├── vendor/
├── ModuleA
├── ModuleB
└── ModuleC
```

All modules rely on the same dependency versions.

As the application grows, dependency conflicts become increasingly difficult to manage.

This platform instead follows the architecture below:

```
OAuth2 Passport Server
│
├── ModuleA
│   ├── composer.json
│   ├── vendor/
│   └── elyscope
│
├── ModuleB
│   ├── composer.json
│   ├── vendor/
│   └── elyscope
│
└── ModuleC
    ├── composer.json
    ├── vendor/
    └── elyscope
```

Each module maintains its own dependency graph.

Benefits include:

- Independent package versions
- Independent updates
- Reduced dependency conflicts
- Better portability
- Improved long-term maintainability

---

# Design Principles

The platform is built around the following principles:

- **Core First** — The core remains focused on authentication and authorization.
- **100% Modular** — New functionality is delivered through modules instead of modifying the core.
- **Dependency Isolation** — Every module owns and manages its own dependencies.
- **Independent Lifecycle** — Modules can be released and versioned independently.
- **Extensibility** — Developers can extend the platform without changing the core.
- **Maintainability** — A smaller core is easier to test, maintain, and evolve over time.

This architecture enables the platform to scale organically while preserving a clean separation of concerns between the core application and every installed module.
