# Arquitectura de Software Modular

## Descripción General

La plataforma está construida sobre una **arquitectura completamente modular**, cuya responsabilidad principal es proporcionar un servidor de autorización basado en **OAuth 2.0** y **OpenID Connect**. Toda funcionalidad adicional se implementa como un módulo independiente, permitiendo que el núcleo permanezca ligero, mantenible y enfocado exclusivamente en la autenticación y autorización.

A diferencia de los sistemas modulares tradicionales, que comparten un único árbol de dependencias (`composer.json` y `vendor`) para toda la aplicación, en esta arquitectura **cada módulo está completamente aislado**.

Cada módulo posee:

- Su propio código fuente.
- Su propio `composer.json`.
- Sus propias dependencias.
- Su propio ciclo de versiones.
- Su propio proceso de actualización.

Esto permite instalar, actualizar o eliminar módulos sin modificar el núcleo de la aplicación.

---

# Arquitectura General

```text
                    OAuth2 Passport Server
                           (Core)
                               │
         ┌─────────────────────┴─────────────────────┐
         │                                           │
  Laravel Runtime                               Elymod
         │                                           │
         │                             Utiliza Elymod App
         │                           como plantilla del módulo
         │
         ├───────────────────────────────────────────────┐
         │                                               │
     Módulo A                                       Módulo B
     ├── composer.json                              ├── composer.json
     ├── vendor/                                    ├── vendor/
     ├── elyscope                                   ├── elyscope
     └── dependencias aisladas                      └── dependencias aisladas
```

---

# Componentes Principales

## OAuth2 Passport Server

OAuth2 Passport Server constituye el **núcleo** de la plataforma.

Su propósito principal es actuar como un **Servidor de Autorización OAuth 2.0** y un **Proveedor OpenID Connect**, proporcionando además toda la infraestructura necesaria para permitir la integración de módulos.

### Características principales

- Servidor de autorización OAuth 2.0
- Proveedor OpenID Connect
- Administración de usuarios
- Registro de auditoría (Logs)
- Sistema de configuración dinámica
- Limitación de solicitudes (Rate Limiting)
- Soporte para Content Security Policy (CSP)
- Integración con Laravel Horizon y soporte para CSP
- Descubrimiento automático de módulos
- Carga dinámica de servicios

---

# Modelo de Autorización

En lugar de implementar un sistema RBAC tradicional, la plataforma introduce un modelo de autorización más flexible basado en cuatro conceptos:

- Grupos (Groups)
- Servicios (Services)
- Roles (Roles)
- Scopes

```
Grupo
   │
   └── Servicio
          │
          └── Rol
                │
                ▼
             Scope (GSR)
```

## Grupos

Los grupos representan las principales categorías de acceso del sistema.

OAuth2 Passport Server incorpora los siguientes grupos por defecto:

- `administrator`
- `settings`
- `developer`
- `member`
- `commerce`
- `enterprise`
- `reseller`

Cada grupo define un contexto donde los módulos pueden registrar sus propios servicios.

Los desarrolladores también pueden crear grupos adicionales según las necesidades de su aplicación.

---

## Servicios

Los servicios representan un recurso o funcionalidad dentro de un grupo.

Cada servicio debe tener un nombre único únicamente dentro del grupo al que pertenece.

Por ejemplo:

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

El mismo nombre de servicio puede existir en grupos diferentes sin generar conflictos.

Por ejemplo:

```
administrator
└── settings

developer
└── settings
```

Como ambos pertenecen a grupos distintos, representan recursos diferentes.

---

## Roles

Los roles representan las acciones disponibles sobre un servicio.

Algunos ejemplos son:

- view
- create
- update
- delete
- export

Los roles son completamente dinámicos.

Los módulos pueden registrar nuevos roles cuando sea necesario, permitiendo extender el sistema de permisos sin modificar el núcleo.

---

## Scopes (GSR)

Cuando un rol es asignado a un servicio dentro de un grupo, el sistema genera un Scope único.

Internamente, un Scope se identifica mediante la combinación:

```
Grupo → Servicio → Rol
```

Ejemplos:

```
administrator:users:view
administrator:users:create
administrator:modules:install

settings:general:update
settings:mail:update

commerce:vpn:create
commerce:vpn:delete
```

Los Scopes son los permisos que finalmente se asignan a los usuarios.

Cuando un usuario posee un Scope, está autorizado para ejecutar la acción correspondiente sobre el recurso protegido.

# Laravel Runtime

Laravel Runtime es una capa de abstracción construida sobre Artisan.

En lugar de generar recursos dentro de la aplicación principal, Laravel Runtime crea automáticamente dichos recursos dentro del módulo correspondiente.

Entre los recursos soportados se encuentran:

- Controladores
- Modelos
- Policies
- Requests
- Migraciones
- Comandos
- Resources

Laravel Runtime se instala una única vez dentro de OAuth2 Passport Server y actúa como el entorno de desarrollo utilizado por todos los módulos.

---

# Elymod

Elymod es el componente encargado de crear nuevos módulos.

Su funcionamiento es similar al generador de proyectos de Laravel, con la diferencia de que en lugar de crear una aplicación completa, genera un módulo totalmente aislado.

Sus responsabilidades incluyen:

- Creación de módulos
- Generación de la estructura de directorios
- Generación de configuraciones
- Registro del módulo
- Generación de la plantilla inicial de desarrollo

---

# Elymod App

Elymod App es la plantilla (Skeleton) utilizada por Elymod para generar nuevos módulos.

Cada módulo es creado a partir de esta plantilla.

Contiene la estructura de directorios, configuraciones, herramientas y convenciones necesarias para desarrollar módulos dentro de la plataforma.

Siempre que se genera un nuevo módulo, se utiliza la versión más reciente de Elymod App.

---

# Elyscope

Elyscope es un puente entre Composer y PHP-Scoper.

Desde la perspectiva del desarrollador, Elyscope se comporta como Composer, pero ejecuta automáticamente tareas adicionales necesarias para integrar correctamente un módulo dentro de la plataforma.

Entre sus responsabilidades se encuentran:

- Instalar dependencias
- Actualizar dependencias
- Ejecutar comandos de Composer
- Ejecutar PHP-Scoper
- Aislar las dependencias del módulo
- Preparar el módulo para su ejecución

Todos los módulos utilizan Elyscope en lugar de invocar Composer directamente.

Esto garantiza un aislamiento completo entre las dependencias de cada módulo.

---

# Módulo Content

Content es un módulo opcional para entornos de producción que incorpora capacidades de CMS y SEO a OAuth2 Passport Server.

## Características

- Administración de páginas estáticas
- Renderizado de páginas mediante Blade
- Gestión de layouts
- Modal de aceptación de cookies
- Modal de privacidad
- Personalización del encabezado
- Personalización del pie de página
- Administración de fuentes
- Administración de favicons
- Integración con Schema.org
- Personalización SEO de páginas estáticas
- Administración del archivo `robots.txt`
- Generación automática de XML Sitemap
- Soporte para indexación por motores de búsqueda

El módulo también incorpora layouts reutilizables que simplifican la creación de nuevas páginas y garantizan una experiencia consistente.

---

# ¿Por qué Content es un módulo?

Antes de la versión 9, muchas funcionalidades relacionadas con CMS estaban integradas directamente en el núcleo.

Estas funcionalidades fueron extraídas porque no formaban parte del propósito principal del servidor de autorización.

Mantener Content como un módulo independiente proporciona múltiples ventajas:

- Un núcleo más pequeño.
- Actualizaciones independientes.
- Desarrollo de nuevas funcionalidades sin afectar al núcleo.
- Mayor facilidad de mantenimiento.
- Mejor separación de responsabilidades.

De esta forma, OAuth2 Passport Server permanece enfocado únicamente en autenticación y autorización, mientras que Content puede evolucionar de forma independiente.

---

# Filosofía de la Versión 9

La versión 9 representa un rediseño completo de la arquitectura del proyecto.

Muchas funcionalidades que anteriormente formaban parte del núcleo fueron extraídas y convertidas en módulos independientes.

Este rediseño recupera la visión original del proyecto:

> OAuth2 Passport Server debe encargarse únicamente de la autenticación y autorización. Toda funcionalidad adicional debe implementarse mediante módulos independientes.

---

# Aislamiento de Dependencias

Una de las características más importantes de la plataforma es el aislamiento completo de dependencias.

A diferencia de la mayoría de soluciones modulares para Laravel, los módulos **no comparten un árbol global de dependencias**.

Una arquitectura modular tradicional suele tener la siguiente estructura:

```
Aplicación
├── composer.json
├── vendor/
├── ModuleA
├── ModuleB
└── ModuleC
```

Todos los módulos utilizan las mismas versiones de las dependencias.

Conforme la aplicación crece, también aumenta la probabilidad de conflictos entre paquetes.

En esta plataforma se adopta un enfoque completamente diferente:

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

Cada módulo mantiene su propio conjunto de dependencias.

Esto proporciona beneficios importantes:

- Versiones independientes de paquetes.
- Actualizaciones independientes.
- Menor riesgo de conflictos entre dependencias.
- Mayor portabilidad de los módulos.
- Mejor mantenibilidad a largo plazo.

---

# Principios de Diseño

La plataforma está basada en los siguientes principios:

- **Core First** — El núcleo permanece enfocado exclusivamente en autenticación y autorización.
- **100% Modular** — Toda nueva funcionalidad se implementa mediante módulos, sin modificar el núcleo.
- **Aislamiento de Dependencias** — Cada módulo administra sus propias dependencias.
- **Ciclo de Vida Independiente** — Cada módulo puede evolucionar y versionarse de forma independiente.
- **Extensibilidad** — Es posible ampliar la plataforma sin modificar el núcleo.
- **Mantenibilidad** — Un núcleo pequeño resulta más fácil de mantener, probar y evolucionar.

Esta arquitectura permite que la plataforma crezca de forma orgánica, manteniendo una separación clara de responsabilidades entre el núcleo y cada uno de los módulos instalados.
