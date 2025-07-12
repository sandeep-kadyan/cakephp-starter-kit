# CakePHP Starter Kit

A modern, extensible CakePHP 5+ starter kit for rapid application development. This kit provides a robust foundation with authentication, activity tracking, AJAX utilities, menu systems, notifications, Vite asset pipeline, and more—ready for building admin panels, SaaS backends, or any custom web application.

---

## Overview

The CakePHP Starter Kit is designed to accelerate your development with a curated set of features and best practices. It includes user authentication (with magic link/passwordless login), activity tracking, AJAX-powered tables, dynamic menus, toast notifications, fake data generation, and seamless Vite.js integration for modern asset management.

---

## Features

- **User Authentication**: Magic link login, passwordless authentication, user registration, verification, and full user management (CRUD).
- **Activity Tracking**: Middleware and models to log user activity, browser, device, and more.
- **AJAX & DataTable Utilities**: Helpers and cells for building interactive, responsive tables with search, sort, pagination, and export features.
- **Menu System**: Configurable sidebar, header, footer, and profile menus with unlimited nesting and icons.
- **Toast Notifications**: Flexible toast/flash notification system with configurable positions and display modes.
- **Vite Integration**: Modern asset pipeline with Vite.js for development (HMR) and production.
- **Fake Data Generation**: CLI command to generate fake data for any model, with schema-aware and special field handling.
- **Environment Service**: Utility for accessing and managing request environment variables.
- **Extensible Structure**: Easily add new models, controllers, helpers, and UI components.
- **Bulk Delete**: Bulk deletion support for any model.
- **Middleware**: Activity tracking, CSRF protection, error handling, and more.
- **Modern UI**: Tailwind CSS, Alpine.js, and ready-to-use layouts.

---

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & npm (for asset building with Vite)
- Supported database (MySQL, PostgreSQL, SQLite, etc.)

---

## Installation

1. **Clone the repository or use as a template:**

   ```bash
   git clone https://github.com/your-org/cakephp-starter-kit.git myapp
   cd myapp
   ```

2. **Install PHP dependencies:**

   ```bash
   composer install
   ```

3. **Install JS dependencies:**

   ```bash
   npm install
   ```

4. **Copy and configure environment files:**

   ```bash
   cp config/app_local.example.php config/app_local.php
   # Edit config/app_local.php for your DB/email settings
   ```

5. **Set writable permissions:**

   - Ensure `logs/` and `tmp/` are writable by the web server.

6. **Run database migrations:**

   ```bash
   bin/cake migrations migrate
   ```

7. **Build frontend assets:**

   ```bash
   npm run build
   # Or for development with HMR:
   npm run dev or npm run watch
   ```

8. **Start the CakePHP server:**

   ```bash
   bin/cake server -p 8765
   ```

   Visit [http://localhost:8765](http://localhost:8765) in your browser.

---

## Use Cases

- Admin dashboards and CRUD interfaces
- SaaS backends and user portals
- Rapid prototyping for new projects
- Learning CakePHP best practices

---

## Configuration

- **Environment:** `config/app_local.php` for DB, email, and environment-specific settings.
- **Menus & UI:** `config/setting.php` for menu structure, toast positions, and UI layout.
- **Assets:** Vite config in `vite.config.js` and `tailwind.config.js`.

---

## Generating Fake Data

Quickly seed your database with test data for any model:

```bash
bin/cake fake --list-models
bin/cake fake users 10
bin/cake fake users 5 --dry-run
bin/cake fake users 10 --special-fields=avatar,logo
```

See [doc/fake-data-command.md](doc/fake-data-command.md) for full details.

---

## Directory Structure

- `src/Controller/` - Main controllers (Users, Activities, AuthRequests, Pages)
- `src/Model/Table/` - Main models (Users, Activities, AuthRequests, Sessions)
- `src/View/Helper/` - UI helpers (DataTable, Menu, Toast, Vite)
- `src/View/Cell/` - UI cells (AjaxTable, DataTable, Menu)
- `src/Form/` - Authentication and verification forms
- `src/Service/` - Backend services (Environment, FakeData)
- `src/Mailer/` - Email logic (UserMailer)
- `src/Middleware/` - Middleware (ActivityTracker)
- `config/` - Configuration files
- `templates/` - View templates
- `webroot/` - Public assets

---

## Extending the Kit

- Add new models in `src/Model/Table/` and run migrations.
- Add new controllers and views for custom features.
- Use helpers and cells to build interactive UIs.
- Customize menus and layouts in `config/setting.php`.

---

## Documentation

- [Fake Data Command](doc/fake-data-command.md): Generate fake data for any model.
- [DataTable Helper](doc/datatable-helper.md): Build interactive tables.
- [Menu Helper](doc/menu-helper.md): Dynamic, nested menus.
- [Toast Helper](doc/toast-helper.md): Toast notifications.
- [Vite Helper](doc/vite-helper.md): Modern asset pipeline.
- [CakePHP Book](https://book.cakephp.org/5/en/): Official CakePHP documentation.

---

## Maintainer

[Sandeep Kadyan](https://github.com/sandeep-kadyan)

---

## License

This project is open-sourced under the [MIT license](LICENSE).
