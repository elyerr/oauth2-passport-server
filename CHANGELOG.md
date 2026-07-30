<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog], and this project adheres to Semantic Versioning.

## Unrelease

- Fixed commands to install and update modules
- Add upgrade guide file

---

## [v9.0.2]

- Updated composer project dependencies
- Removed unused configuration
- Updated filesystem configuration
- Updated hashing configuration
- Refactored default settings keys
- Enabled storage settings
- Updated module creation to use the latest stable Elymod version
- Removed the `clean:tmp-files` command
- Added support for module- and host-scoped configurations through the `is_module` option. Configurations are registered as module-scoped by default.
- Support for aws s3 added
- Fixed boolean value support in settings.

---

## [v9.0.1]

### Added

- Added the `extractTranslationsFields()` helper for extracting translated input fields.
- Added the `UniqueTranslation` validation rule for validating translated fields.
- Added the `getMorphClassIdentifier()` method to the `Translatable` contract to resolve the configured morph tag or fallback class name.
- Added a migration to remove obsolete module tables.
- Add release version automation script

### Changed

- Improved module repository update functionality.
- Updated the project documentation.

### Fixed

- Fixed translation synchronization (`syncTranslations`) behavior.
- Fixed polymorphic (morph) class support.
- Fixed the `module:update` command.
- Update trasnlation support on HasTranslation Trait and UniqueTranslation rule.

### Removed

- Removed the unused `module:migration` command.

---

## [v9.0.0]

> **Note:** The removed components are being extracted from the core to make the framework more modular and maintainable. They will be available as independent modules, allowing optional installation, easier maintenance, and a smaller core.

- Removed the **Transaction** and **Partner** core modules.
- Removed payment helpers.
- Removed the following administrator service: **refunds**, **plan**, **transactions**, and **partner**.
- Removed the **partner** group from the reseller service.
- Removed the configuration keys: `core.partner`, `core.transaction`, `rate_limit.partner`, `rate_limit.partner`, `rate_limit.ecommerce` , `module_enabled`, `billing`, `stripe`.
- Composer package removed paquete `stripe/stripe-php` , `stevebauman/purify` and `spatie/laravel-sitemap`
- Moved all exception handling to bootstrap/app.php and removed the RenderException class
- Removed pages, seo, lang, sitemap funtionalities.
- The following developers services removed : `pages`, `seo`,
- Add support for route overriding
- Removed lang, pages,seo and layouts resources
- Remove File resurces ( service, repositories and directories)
- Rename pages table to content_pages
- Remove unsed npm packages
- Add custom cache driver support for rate limit with fallback handling
- Update log user interface
- Support allowed email domains for user registration
- Implement multilingual support with localized routing, language switching, and translation infrastructure
- Documantion updated

### refactor: introduce JSON-based module registry

- replace database module registry with modules.json
- refactor ModuleRepository around manifest storage
- update all module commands to use the new registry
- remove direct dependency on the modules table
- register and discover modules through the registry service
- stop scanning third-party directories for installed modules
- add deprecated module:migration command to migrate existing database records to modules.json

### refactor: redesign module management

- replace database module registry with module.json manifests
- add module manager service
- update module repository
- add module management controller
- implement GUI for managing modules
- add new coomand `module:migration` to migrate modules to modules.json from database table

### Refactor deployment scripts

- Consolidated deployment scripts into environment-specific executables:
    - Merged `deploy-dev.sh` into `dev`:
        - Deploy: `./dev --deploy`
        - Stop containers: `./dev --stop`
        - Open a root shell: `./dev --root bash`
        - Open a user shell: `./dev bash`

    - Merged `deploy-staging.sh` into `staging`:
        - Deploy: `./staging --deploy`
        - Stop containers: `./staging --stop`
        - Open a root shell: `./staging --root bash`
        - Open a user shell: `./staging bash`

    - Merged `deploy-prod.sh` into `production`:
        - Deploy: `./production --deploy`
        - Stop containers: `./production --stop`
        - Open a root shell: `./production --root bash`
        - Open a user shell: `./production bash`

- Renamed deployment templates:
    - `docker-compose.yml` → `template.yaml`
    - `docker-compose-dev.yml` → `template-dev.yaml`

---

## [v8.0.1]

- Replace orphaned VueDatePicker with native date input
- Correct module configuration registration and improve config_module helper
- Correct settings.php file discovery and creation
- Remove command settings:migrate-config
- Remove old settings resources
- Fix atomic settings updates in production

---

## [v8.0.0]

### Changed

- Updated Horizon to version 5.1.2.
- Updated API Response to version 2.0.0.
- Improved the development environment deployment script.
- Enhanced Docker helper scripts with root and stop support.
- Added Elyscope support for Elymod modules.
- Added Laravel Runtime to support Elymod module development.
- Migrate to Laravel 13.x from Laravel 12.x
    - migrate application bootstrap to Laravel 13 structure
- Migrated settings storage from database to dedicated file-based configuration.
- Added SettingRepository for settings management.
- Refactored settings services to use repository injection.
- Replaced static settings methods with instance-based methods.
- Added command to migrate legacy settings from database to file storage.
- Removed configuration translation/transformation layer.
- Simplified settings loading and persistence workflow.
- Refactored Core and Third-Party Service Providers.
- Standardized service provider lifecycle following Laravel conventions.
- Moved configuration registration and merging logic to provider registration phase.
- Improved dynamic module discovery and provider registration.
- Reorganized module resource loading (routes, views, translations, and migrations).
- improve OpenID Connect and OAuth2 compatibility
- Merge pages and sitemap, replace policy editor with Blade layouts, and update layouts
- Removed unused policy keys `policies_of_cookies`, `policies_of_privacy`, `terms_and_condition`

---

## [v7.0.0]

### Added

- Added Vite support for Elymod modules.
- Added `@module_vite` directive for loading module assets compiled with Vite.
- Added support for module-specific Vite manifests and build directories.
- Added support for running Laravel Mix and Vite modules side by side during migration.
- Added support for Elymod v2 module generation through the `module:make` command.
    - Added `--driver` option to select the asset environment when creating modules.
    - Added support for both `vite` and `mix` drivers.
    - Vite is now the default driver when no `--driver` option is provided.
    - Added compatibility checks for Elymod v2 templates and scaffolding.

### Changed

- Migrated the host application asset pipeline from Laravel Mix to Vite.
- Updated frontend tooling, build scripts, and development workflow to use Vite.
- Updated project documentation and examples to reflect the Vite workflow.
- Updated `module:make` to generate modules using Elymod v2 templates.
    - Improved module creation workflow to support multiple asset build environments.
    - Refactored module stubs into dedicated `vite` and `mix` template directories.
- Updated docker environment for vite support

### Deprecated

- Laravel Mix is no longer used for compiling host application assets.
- Webpack-based host build configuration is deprecated in favor of Vite.

### Removed

- Removed Laravel Mix build configuration and tooling from the host application.
- Removed host-specific Webpack compilation workflow.

### Migration Notes for Elymod v1.x Modules

Modules created with Elymod v1.x are not fully compatible with the Elymod v2.x development environment out of the box. To continue using Laravel Mix without migrating to Vite, the following updates are required:

- Update all frontend dependencies in `package.json` to the versions provided in the latest Elymod `stubs/mix` template.
- Remove the deprecated Inertia package from the `require-dev` section of `composer.json`.
- Add the new `build-fix.js` script included in `stubs/mix`.
- Adjust the existing `webpack.mix.js` configuration to match the current Elymod Mix environment and register the `build-fix.js` plugin.
- The `build-fix.js` plugin rewrites asset URLs generated inside CSS files, ensuring correct handling of fonts, icons, and other third-party assets referenced through `url(...)`.
- Replace the module `app/Providers/ServiceProvider.php` with the updated version included in the latest Elymod stubs.
- Review and update any asset-related configuration files to align with the current Elymod Mix environment.

After applying these changes, existing Elymod v1.x modules can continue using Laravel Mix and should work correctly with OAuth2 Passport Server v7+ without requiring a migration to Vite.

### Compatibility Notes

- Supported Elymod versions: v2.0.0+.
- Legacy modules created with Elymod v1.x must be migrated to the updated Laravel Mix environment.
- To migrate, update the module dependencies and replace the asset build configuration with the files provided in `stubs/mix`.
- Once updated, legacy modules can continue running on OAuth2 Passport Server v7+ without requiring a full migration to Vite.

---

## [v6.1.4]

### Added

- Improved the `module:make` command with Elymod version selection support.
    - Latest release discovery from Packagist.
    - Interactive version picker.
    - Custom version installation.
    - `--elymod-version` option support.
    - Compatibility with existing stable and dev workflows.

- Improved interactivity of module commands.
    - Optimized option selection in commands, allowing only "yes" or "no" inputs where applicable.
    - Removed unnecessary selection lists to simplify user experience.
    - Minor fixes and prompt clarity improvements.

- Added root access support to container execution scripts.
    - Introduced the `--root` option for executing commands as the root user inside containers.
    - Simplified container access workflow by removing the need for dedicated root-specific scripts.

### Changed

- Updated container execution documentation.
    - Replaced references to root-specific scripts with the new `--root` option.
    - Added usage examples and improved command descriptions.

- Updated Docker image build process.
    - Replaced `npm install` with `npm ci` in staging and production images.
    - Improved build reproducibility and dependency consistency across environments.

### Fixed

- Fixed `module:delete` command.
    - Check if the module exists before attempting to delete it.

- Fixed frontend build compatibility.
    - Locked Webpack to `5.74.x` to maintain compatibility with Laravel Mix 6 and related tooling.

---

# [v6.1.3]

- Added fallback redirect for non-existent pages to improve navigation experience.

---

# [v6.1.2]

## Added

- Added `settings:upload-scopes` command
    - Dedicated command for uploading and synchronizing system scopes

- Added `settings:reset-scopes` command
    - Restore all default system scopes
    - Rebuild scope configuration from package defaults

- rename files to comply with PSR-4 autoloading standard
- rename module:services-load to module:service-upload
- fix serach for admin functionaly for OauthClientService
- Fix filters for index.vue components

---

# [v6.1.1]

- Add scope protection to reloadCache route
- Improve error handling in userCanAny middleware
- Improve authorization error messages in OAuth middlewares
- Correct scope authorization in PartnerController for admin users
- Resolve menu visibility issue caused by incorrect service keys

---

# [v6.1.0]

### Feat

- Add new command settings:drop-service to remove orphans services
- Delete option to remove orphan services to the updaload settings:roles-upload
    - delete service for group `administrator`
        - application
        - seo
        - logs
        - pages
        - lang
        - horizon,
        - terminal
    - delete service for group `developer`
        - client

### Fixed

- Fixed scope management flow when services had no existing scopes
    - Resolved error occurring while loading service data without scopes
    - Fixed issue where form was not properly reset after creating or updating a scope
    - Added dedicated service lookup function within service layer for safer retrieval
    - Moved service data rendering from scope relationship to controller layer
        - Prevents failures when no scopes exist
        - Ensures service can still be created even with empty scope state
- Fixed command module:install interactive installation flow
    - Removed manual --name option to prevent installation inconsistencies
    - Automatically extract module name from Git or Packagist source
    - Added module name normalization from source
    - Added interactive version selector with latest repository versions
    - Added support for listing latest Git tags
    - Added support for fetching Packagist package versions
    - Added manual version fallback option
    - Improved provider and protocol detection flow
    - Improved validation for existing filesystem and database modules
    - Improved interactive installation experience and configuration flow

### Changed

- Improved scope management logic for add and delete operations on service scopes
    - Service data is now consistently retrieved independently from scopes

---

# [v6.0.13]

### Added

- Introduced **cache versioning system** for scalable cache invalidation
    - Prevents mass cache deletion using Redis KEYS/SCAN
    - Enables O(1) invalidation via version increment strategy
- Added `CacheVersions` for managing domain-based cache invalidation
    - scopes
    - users
    - settings
    - config
    - broadcast
- Extended `CacheKeys` to support versioned cache keys
    - user scopes
    - api scopes
    - passport scopes
    - user groups and auth cache

### Fixed

- Fixed **scope update cache invalidation issue**
    - Scope changes were not reflected due to missing version-based cache invalidation
    - Cache keys were previously static and did not reflect updated scope state
    - Added `Cache::increment(CacheVersions::SCOPES)` after `updateOrCreate`
    - Ensured updated scopes are immediately reflected across users and API responses

### Changed

- Refactored scope caching system to use versioned keys instead of static keys
- Improved scope invalidation flow using `Cache::increment()` instead of manual deletion
- Updated cache strategy to support scalable multi-user environments (millions of users)

### Improved

- Reduced need for cache scanning or bulk deletion
- Improved performance for scope updates and permission propagation
- Better separation between user-level cache and global system cache

### Notes

- Scope updates now invalidate cache globally via version bump
- User-specific cache is still invalidated individually when required

---

# [v6.0.12]

## ✨ Features

- Updated Vue layouts
- Improved About component (Vue)
- Removed unused components
- Enhanced user interface and cleaned up unused code
- Updated role assignment command
- Updated layout menus
    - Add new layout to manage favicon
    - add new layout to manage privacy modal
- Updated pages support
    - Seo support for login, register, forgot-pasword and plans
    - Add new scope for pages service
    - Fix scope permitions for LayoutController, PageController and SeoController
    - Add default tamplate for new layouts (login, register, forgot-pasword, plans, favicon)
    - Moved SEO section from settings to pages to centralize page-related SEO management under a single section
    - llow restoring pages to default production layout (overwrites draft)
- Move transaction module API endpoints to web routes to render with Inertia instead of Axios
- Implement CAPTCHA validation on two-factor authentication challenge to prevent brute force attacks
- Update resources for (Group , Roles, services, users)
    - Migrate api route to web routes
    - Change from making requests with axios to using Inertia's useForm
    - Update links property for GroupTransformer instead of using api endpoints
- Add flatpickr support for VInput.vue component for date fields
- Remove unused components
    - npm : vue-sweetalert2
    - npm : @vuepic/vue-datepicker
- OAouth2 resources updted
    - Add OauthClientService
    - Update Client repository
    - Update Controllers
    - Updated user interface
    - Fixed Access token controller to manager API Keys
    - Fix vue components for access token controller for API Keys
- Partner module updated
    - fixed partner repository
    - fixed partner services
    - updated vue components
    - Add default partner commission rate setting in admin > settings > payment
    - Improve referral code validation rules and format constraints
- Sitemap (favicon management)
    - Add support to upload multiple files into public directory for seo purpose
    - Add support to list all files (images)
    - Add delete file funcionality
- Add new command to delete config keys
- Add session support
    - List all sessions
    - Delete session
    - Close all sessions when the password is updated
- Add index key for selective page indexing instead of full indexing

## Fixes

- Regenerated cached user data after enabling 2FA (Fortify)
- Fixed rate limit key generation where user identifiers were being generated incorrectly
- Fixed scope permission validation for Horizon
- implement CSP-compliant password toggle on register page
- Re-write cache clear command
- Fixed favicon default layout
- Fixed notification status for warning type

## Services Updated

### Deleted [Group: Administrator]

- application
- seo
- page
- lang

### Deleted [Group: Developer]

- client

### Added [Groups: Developer, Administrator]

- oauth2

### Added [Group: Developer]

- oauth2
- horizon
- seo
- page
- lang

---

# [v6.0.11]

## Changed

- Improved Docker environment configuration and stability

## Added

### Settings caching

- Added caching layer for settings to reduce repeated database queries
- Implemented PID-based invalidation mechanism to refresh cache on updates
- Avoided reliance on cache invalidation methods like `Cache::forget` due to environment inconsistencies
- Added Redis connection validation for default and cache configurations

### Sitemap management

- Added support for generating and managing sitemaps for public pages

---

# [v6.0.10]

- Remove child-src directive to relax Content Security Policy (CSP) restrictions
- Updated gitlab-ci.yml

---

# [v6.0.9]

- Merge host and module translation files with fallback-safe loading for local and module langs
- Centralize module public asset publishing in the host for `module:make`, `module:install`, and `module:update`
- Mirror module `assets:publish` behavior by creating `public/third-party/<module>` symlinks after scaffold, install, and update
- Add `--dev` support to `module:make` with local `../elymod` resolution first and Packagist dev fallback
- Improve `module:make` rollback to clean database records, generated module directories, and published public symlinks on failure
- Show Composer errors directly in terminal during module scaffolding failures

---

# [v6.0.8]

- Allow external API requests and form submissions via CSP (connect-src, form-action)

---

# [v6.0.7]

- fix: encode URL in base64 for sitemap deletion endpoint
- Delete passport connect controller
- Module update command added
- Module install command updated
- align OpenID identity claims with user schema
- Disable laravel passport routes for device authorization
- Updated lang

---

## [v6.0.6]

- fix: replace layout select dropdown with button group navigation
- fix: files validation after save

---

## [v6.0.5]

### Changed

- Relaxed Content Security Policy (CSP) for assets:
    - Allowed `data:` and `blob:` sources in `img-src`
    - Enabled external font loading from `fonts.bunny.net`
- Fixed Content Security Policy (CSP) restrictions interfering with the page creation form
- Remove all documentation resources
- Fix dynamic pages loader
- Add new components blade (x-layout and x-admin-layout)
- Apply new layouts for pages resources
- Create new blade component x-profile
- Add JQuery support for new page creation
- Migrate to blade from vuejs sitemamp manager
- Sitemap generator for public pages added
- add support for showing errors on draft pages
- Add job support to generate sitemap for pages
- fix profile blade component
- fix admin layout blade component
- fix jquery support for robot blade component
- Updated composer dependencies
- Updated horizon configuration file
- Lang on editor blade component fixed
- File manager support for lang directory added
- Add new volume for lang support on docker compose
- Updated user interface
- Add new services (pages and lang)
- Protect PageController and Lang controller using admin scopes

---

## [v6.0.4]

- Updated Spanish translations (es)
- Fixed email verification functionality
- Updated `x-privacy` component
- Added page generator
- Removed default landing page
- Updated queue settings
- Updated general application keys in settings
- Added new Docker volume for persistent page storage
- Refactored views
- Added filters to page generator
- Add support to manage layout for page creator
- Fix monaco editor

---

## [v6.0.3]

### Fixes

- Fixed docker deployment

---

## [v6.0.2]

### Fixes

- Fixed issues in user create and update functions.
- Removed sitemap route from meta configuration.

### Improvements

- Updated user policy interface for better usability.
- Improved layouts and form structures across the system.
- Added SEO section for default pages.
- Added SEO configuration section in settings.

### Changes

- Removed editable volume and file handling.

---

## [v6.0.1]

### Improvements

- Added a scheduled task to automatically clean temporary files.
- Improved and standardized system-wide configuration settings.
- Introduced cache support to enhance overall performance.
- Simplified queue configuration by removing dedicated Horizon setup; now using default Redis settings.
- Added asset loading support for modules via `module_mix`.
- Updated the `x-editor` component for better flexibility and stability.
- Fixed support for polymorphic (`morph`) class handling.
- Implemented automatic cleanup of outdated storage files.

---

## [v6.0.0]

### Added

- Added **API support** and separated responsibilities between **backend** and **frontend** layers.
- Introduced **Laravel Scout integration** to support search indexing.
- Added a **Settings section** to simplify external service integrations and system configuration.
- Added a **Horizon configuration panel** to allow management of queues and related queue settings.
- **Feat:** Added a **Shipping Addresses management area** that allows users to store and manage delivery addresses.  
  This feature is designed to be reusable by modules related to **product sales, orders, or any other module that requires shipping or delivery information**, and includes built-in integration for associating shipping addresses with users.

### Updated

- Improved the **user interface** for better usability and consistency.

### Refactored

- Refactored modules to support API-based architecture.  
  Affected modules: `users`, `roles`, `services`, `groups`, `transactions`, and `plans`.

### Fixed

- `module:make` command now performs a **rollback if an error occurs**, preventing partial changes.
- Fixed **Composer executable detection** in module commands.

### Removed

- Removed **orphan tables and models** that were not contributing to the system.
- Cleaned up and optimized **database migrations**.
- Dropped **legacy core tables** that were migrated to modules to avoid duplication and inconsistencies.

---

## [v5.1.6]

### Added

- Introduced `module:services-loads` command to load groups, services, roles, and scopes from modules.

### Fixed

- Corrected welcome email notification.

---

## [v5.1.5]

### Added

- Added editable volume to the docker-compose file
- Deleted orphan keys (`frontend_url` and `asset_url`)
- Added module rollback support on installation failure
- Added safe module deletion with confirmation prompt
- Added automatic removal of module public symlink during uninstall
- Added module registry cleanup on uninstall
- Added compatibility metadata support via `module.json`
- Added module version awareness for future update handling
- Added improved Composer install output and error visibility

### Fixed

- Adjusted file and directory permissions
- Restored public file read access for other users
- Updated schema to HTTPS from HTTP
- Fixed environment scheme key (`APP_URL_SCHEME`)
- Fixed environment file configuration
- Fixed symlink removal logic to prevent orphaned links
- Fixed module installation rollback to restore previous state on failure

### Changed

- Updated Dockerfile to multi-stage build
- Updated Docker-related files
- Updated `.dockerignore`
- Updated SEO metadata
- Improved module install and uninstall reliability
- Improved Composer execution handling inside modules
- Improved module lifecycle handling (install, uninstall, rollback)

---

## [v5.1.4]

### Fixed

- Fixed robots.txt handling for SEO management

---

## [v5.1.3]

### Fixed

- Hide password confirmation for demo users
- Fixed an issue where module rate limits were not loaded unless settings were saved first.
- Module `rate_limit` configs are now preloaded before RouteServiceProvider initializes.

---

## [v5.1.2]

### Added

- Fully Docker-based development workflow with zero host dependencies (PHP, Node, Composer, Nginx).

- `deploy-dev.sh` script to automate local development setup, build, and startup.

- Automatic detection and injection of host UID/GID into the container to avoid permission issues.

- Local `ops` helper to execute commands inside the app container using the host user.

- Optional global `ops` alias documentation for advanced users.

- Supervisor-managed background services for development (queues, Horizon, recurring payments).

- Two-Factor Authentication (2FA) support via **Laravel Fortify**.

- Endpoints to enable, confirm, disable, and manage 2FA for authenticated users.

- Support for **TOTP verification codes** (Google Authenticator, Authy, etc.).

- Generation and secure storage of **recovery codes** for account access fallback.

- Ability to **regenerate recovery codes** using the same Fortify action.

- Vue 3 views (Composition API / `setup`) for managing 2FA state (enable, confirm, regenerate codes).

- Frontend API integration using the global Axios `$server` instance (no manual injection required).

### Changed

- **Switched project license to AGPL-3.0**.

- Updated license headers across source files to reflect AGPL licensing.

- Removed non-commercial license restrictions in favor of AGPL compliance.

- Improved development documentation with clear, step-by-step instructions for setup and usage.

- Standardized container access patterns (root vs host user) to prevent file permission problems.

- Simplified workflow for running Artisan, Composer, and NPM commands inside containers.

- Updated `.gitlab-ci.yml` for the new Docker-based development flow.

- Authentication flow enhanced to optionally require **2FA challenge** after successful login.

- User security logic centralized using Fortify actions instead of custom implementations.

- Improved session security by enforcing 2FA validation where applicable.

### Documentation

- Updated licensing documentation to reflect AGPL terms and obligations.

- Added detailed development guide covering Docker usage, container access, helpers, and common commands.

- Added notes explaining GitHub token prompts during `composer install`.

- Added documentation describing the complete 2FA lifecycle (enable → confirm → active → recovery).

- Documented Fortify 2FA routes (`challenge`, `confirm`, `recovery`) and their responsibilities.

- Added usage notes for recovery codes and regeneration behavior.

### Developer Experience

- Hot-reload frontend workflow using `npm run watch` inside the container.

- Safer and more predictable file ownership when editing from the host or IDE (VS Code, PhpStorm).

- Clear separation between development-only tooling and production concerns.

- Clear separation between password-protected sessions and 2FA-protected actions.

- More predictable frontend state handling for 2FA using Vue 3 Composition API.

---

## [v5.1.1]

### Improved

- Enhanced the user permission assignment interface with a clearer and more intuitive design.
- Improved the overall UI/UX for managing user permissions and roles.

### Fixed

- Fixed issues in role assignment to ensure permissions are applied correctly.
- Fixed the generation and assignment of new scopes within services to prevent inconsistencies.

## [v5.1.0]

### Features

- Added route metadata support for configuration keys
- Introduced shared configuration keys for settings management
- Added helper to centralize and manage module configuration
- Updated settings logic to resolve the main module config key automatically
- Switched routing logic to use route names instead of URLs

---

## [v5.0.9]

### Fix

- Third party service provider

---

## [v5.0.8]

### Changed

- Renamed the command **`module:create`** to **`module:make`** to align with Laravel's standard conventions.
- Updated and improved the **`module:install`** command, allowing installation of modules from **Packagist** or **Git repositories**, with automatic source detection and version selection.

### Fixed

- Fixed the **OAuth2 client creation workflow**, ensuring proper and consistent configuration.
- Fixed **module Service Providers** to correctly load and access system **configuration values** after installation.

---

## v5.0.7

### fix

- Fixed RouteServiceProvider

---

## v5.0.6

### fix

- Fixed `php artisan settings:system-start` command

---

## v5.0.5

### Feat

- Added `php artisan module:db:seed` command
- Removed eCommerce orphan seeders

---

## v5.0.4

### Fixed

- Remove orphan settings keys

---

## v5.0.3

### Fixed

- setLanguage fixed to support modules

---

## v5.0.2

### Added

- **Elymod module CLI support**
    - `module:create` — Create a new Elymod module inside the `third-party` directory.
    - `module:delete` — Delete an Elymod module and its published assets symlink.

These commands are provided by the Elymod mini-framework to streamline modular development.

---

## 🛠️ v5.0.1

### Fixed

- Fixed incorrect Notyf.js import causing build issues
- Fixed webpack.mix.js configuration for proper asset compilation

---

## 🛠️ v5.0.0

### 🔄 Changed

- Migrated frontend asset compilation from **Vite** to **Laravel Mix**.
- Refactored the asset pipeline to align with the modular core architecture.
- Renamed the **modules** directory to **third-party** to better reflect external module usage.
- Added a **public/third-party** directory to publish and serve assets from third-party modules.

### 🧹 Removed

- Removed **Ecommerce** as a core module.

### 🐳 Fixed

- Fixed and aligned **Docker configuration files** to ensure correct builds and runtime behavior.

### 🛠 Fixed

- Fixed issues in the **CodeController**.

---

## 🛠️ v4.0.7

### Added

- Modular asset discovery system: Vite now automatically detects JavaScript and SCSS entrypoints inside each module.
- Support for multiple JS/SCSS files per module (e.g., `app.js`, `admin.js`, `public.js`).
- Ability for modules to use Blade views, Vue components, or Inertia pages seamlessly.
- Fallback system that checks for module-specific assets first, then falls back to the main `resources/` directory.

### Changed

- Rewritten internal Vite helper logic to dynamically prepend module paths.
- Updated path resolution to remove absolute base paths and generate clean relative paths (e.g., `core/Ecommerce/resources/js/app.js`).
- Improved behavior of the `@vite()` directive to remain fully compatible with Laravel defaults while adding module-level resolution.

### Fixed

- Added validation to ensure only existing module asset files are included.
- Ensured stable and predictable asset resolution whether routes belong to a module or the main application.
- Fixed SEO sitemap generator

### Improved

- Project structure is now fully modularized while maintaining a unified workflow.
- Modules can now reuse main application components without additional configuration.

---

## 🛠️ v4.0.6

### Fixes

- Added support for selecting the currency in the eCommerce dashboard.

### Performance & UI Improvements

- Simplified CSS rules and reduced heavy layout operations on small screens.
- Removed non-essential shadows, blurs, and transitions that caused frame drops on mobile.
- Improved responsive behavior for charts and dashboard components.
- Ensured smoother scrolling and faster interactions on low-end devices.
- Improve design for eCommerce

### Development

- Enabled `dotenv` usage inside Vite config.
- `VITE_HOST` is now used to dynamically define the dev server host.
- Change applies exclusively to the development environment.

---

## 🛠️ v4.0.5

- switch to custom Horizon fork with full CSP support

## 🛠️ v4.0.4

### Added

- New File Service to centralize and manage all file operations.
- Support to manage refunds
- Service refunds added
- New role (review) added
- Add Auto login support after account creation
- Updated settings (Payment, Security)

### Improved

- Image upload performance for products and categories by using temporary storage and internal file moves.
- Overall file handling flow for faster processing and reduced timeouts.

### Fixed

- Middleware restriction to block non-GET requests for demo users.
- Fixed language auto-detection for demo users
- Fixed Middleware to check demo user
- Fixed Content Security Policy(CSP)
- updated eCommerce components
- Fixed menu ordering inconsistencies.
- Moved Manage Partner into the Administrator menu.
- Improved hierarchy and clarity of the navigation structure.
- Ensured correct visibility and placement of admin-level menu items.

## 🛠️ v4.0.3

- fix user scope for seo management

## 🛠️ v4.0.2

### Added

- Added support for sitemaps.
- Added SEO management section.
- Added support to edit `robots.txt`.
- Added real-time backup generation for `robots.txt` and `sitemap.xml`.
- Added automatic restoration of backed-up SEO files on every Docker container restart or when running `php artisan settings:system-start`.
- Added Docker volumes for data persistence:
    - `sitemaps:/var/www/public/sitemaps`
    - `cache:/var/www/storage/framework/cache`
    - `logs:/var/www/storage/logs`
- Added new `deploy-latest` option for deploying the `latest` image tag.
- Added Content Security Policy exceptions for Horizon, Monaco Editor, and Jodit Editor.

### Improved

- Improved password update validation request.
- Updated language files.
- Updated lang resources.

### Fixed

- Fixed password update form.
- Fixed role translation issues.
- Fixed incorrect key handling for menu rendering in Vue layouts.

---

## 🛠️ v4.0.1

- Fixed filter for plan search.
- Added new module for users to view transactions.
- Refactored eCommerce module layers to delegate responsibilities more clearly.
- Updated lang
- Add support for language (English, Spanish)
- Updated dark mode support

## 🛠️ v4.0.0

- Added support for module creation
- Introduced Artisan command for module installation
- Added license headers to source files
- Declared `SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project`
- Documented `/modules` license exception and third-party ownership terms in [LICENSE.md](./LICENSE.md)
- Clarified that modules are supported but remain subject to their individual licenses
- Improved price input: now accepts decimal format for readability (still stored internally as integer)
- Disabled trial functionality (feature not yet available)
- Renamed **"Client registered"** to **"Secret client"** for confidential client handling
- Added core modules
- Improved user interface
- Enhanced rate limit implementation
- Improved polymorphism support
- Introduced e-commerce module
- Migrated to Vite.js from Laravel Mix
- Changed Transaction to polymorphic table
- Added Tags support for polymorphic relation instead of model location
- Added support for Spanish language
- Added support for demo user
- Integrated **queue-based event dispatching** to ensure reliable and asynchronous processing
- Added **automatic retry mechanism** for failed webhook deliveries
- Optimized **background jobs with queues**, improving payment and transaction performance
- Added **foundational support for external service registration**, enabling integration with third-party apps (e.g., Nextcloud)
- docker configuration updated
- fixed responsive design

## 🚀 [v3.0.4]

- Removed orphaned fields to improve data integrity
- Fixed docker deployment
- 🔒 Changed license to custom non-commercial license

---

## 🚀 [v3.0.3]

- Improved Docker deployment configuration for enhanced security and consistency.
- Fixed cache issue affecting system settings persistence.

## 🚀 [v3.0.2]

### 🐛 Fixes

- Resolved issue where the OpenID Connect endpoint returned static user claims. Now returns dynamic, authenticated user data.

## 🚀 [v3.0.1]

### 👥 Users

-🔧 Fix and properly define the relationship with the Partner model (belongsTo, hasOne, etc.), ensuring it aligns with the business logic.

## 🚀 [v3.0.0]

### 🗄️ Database

- 🏷️ Prefixed all table names with `ops_`
- ⚙️ Optimized migration structure and logic
- ❌ Removed `tax` fields from the `transactions` table
- ❌ Removed `stripe_customer_id` from the `users` table
- 🆕 Added new migration to manage multiple payment providers

### 💳 Payment

- 🛠️ Fixed support for multiple payment providers
- 🔒 Fixed issue with forced activation of payment methods

### 👥 Users

- 🐞 Fixed partner registration with referral code

### 🧩 Middleware

- 🔁 Fixed redirect logic for unauthenticated (guest) users

### 🔐 Login

- 🧭 Fixed redirect behavior after first failed login attempt

### 🧰 Services

- 🔄 Moved `Menu` class from `Models` to `Services`
- 🧹 Refactored `Settings` to follow separation of concerns and moved to `Services`

## [v2.0.2]

### ⚡️ Cache

- Added cache support for **user scopes**, **menus**, and **configurations**.
- The section **Admin → Settings → Cache** allows manual management of cached keys.
- Automatic cache invalidation implemented when scopes expire or related data changes.

### 👮‍♂️ isAdmin (Fix)

- Fixed `isAdmin` logic to prevent false positives when a user does not belong to an admin group.
- Now strictly checks against valid groups assigned to the authenticated user.

### 👥 User Groups (Refactor)

- Unified logic for retrieving all user groups, combining both:
    - Directly assigned groups (with or without services).
    - Groups linked through active scopes and services.

### 🚦 Rate Limiting

- Implemented rate limiting on critical routes to improve security.
- Added configurable rate limit settings in **Admin → Settings → Security**.

### 🔐 OAuth2

- Fixed an issue with updating OAuth2 clients in the client management interface.

### 📄 Log Viewer

- Added an integrated **log viewer** accessible from the admin panel.
- Enables direct viewing of application logs without accessing the server manually.

### ⚙️ Setting (Fix)

- Moved `SCHEMA_HTTPS` from dynamic settings to the environment file for better consistency.
- Fixed deployment issues when running in environments without HTTPS (i.e., HTTP-only setups).

---

## [v2.0.1]

### OAuth2 Enhancements

- Enhance OAuth2 token validation middleware to strictly verify token integrity and associated client existence, preventing 500 errors when tokens remain active but related clients have been deleted or are missing.

---

## [v2.0.0]

### Framework & Package Upgrades

- Laravel upgraded from v10 to v12.
- Laravel Passport upgraded from v10 to v13.

### OAuth2 Enhancements

- Added internal grant type for trusted applications.
- Implemented OpenID Connect support.

### User Notifications

- Added notification UI with read/unread tabs.
- Notifications mark as read before opening.
- Badge indicators and modern design using Quasar.

### User Payment

- Added new functionality to enable or disable recurring payment for the user

### Account Validation

- Fixed logic related to registration and email verification.

### Developer Route Controls

- Ability to dynamically enable/disable developer features for users.

### Removed Features

- Log Viewer removed from admin panel.

---

## [v1.0.0]

### 👤 Users

- **Dashboard**: User overview panel.
- **Profile**: Edit personal information.
- **Password**: Change password functionality.
- **Two-Factor Authentication (2FA)**: Security feature for login.
- **Subscriptions**: Manage active user subscriptions.
- **Store**: Access to service or product purchases.
- **Developers**:
    - **Applications**: Manage registered apps.
    - **API Functionality**: Access and manage API keys and usage.
- **Partner Portal**:
    - Accessible only to users with "partner" status.
    - Partners benefit from services sold to users who register or purchase using their referral link.
    - Access is granted after the partner has completed at least one purchase.

---

### 🛠️ Admin Panel

- **User Management**: View, edit, or deactivate user accounts.
- **Group Management**: Organize users into groups.
- **Role Management**: define service roles.
- **Service Management**: Create and manage available services.
- **Client Management**: Manage application integrations (OAuth clients).
- **Broadcast Management**: Configure real-time channels.
- **Plan Management**: Create and manage subscription plans.
- **Transaction Management**: View and manage user payments.
- **Command Terminal**: Execute system-level artisan commands.
- **Settings**:
    - **General**: General application configuration.
    - **Session**: App session management.
    - **Payment**: Configure payment providers (stripe, offline).
    - **Email**: Manage email service settings.
    - **Routes**: Manage internal app routes.
    - **Redis**: Redis connection configuration.
    - **Cache**: Cache system settings _(currently inactive)_.
    - **Queue**: Queue service configuration.
    - **Filesystem**: Storage driver configuration.
    - **Security**: Configure CAPTCHA and other security-related settings.
    - **Logs**: Log viewer interface.
