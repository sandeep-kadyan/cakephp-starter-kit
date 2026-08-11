# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Fixed

- **Authentication plugin 4.x upgrade**: Fixed `Undefined constant Authentication\Identifier\AbstractIdentifier::CREDENTIAL_USERNAME` and `Call to undefined method Authentication\AuthenticationService::loadIdentifier()`.
  - Replaced import of `Authentication\Identifier\AbstractIdentifier` with `Authentication\Identifier\PasswordIdentifier`.
  - Replaced `AbstractIdentifier::CREDENTIAL_USERNAME` / `AbstractIdentifier::CREDENTIAL_PASSWORD` with `PasswordIdentifier::CREDENTIAL_USERNAME` / `PasswordIdentifier::CREDENTIAL_PASSWORD`.
  - Removed the standalone `$service->loadIdentifier('Authentication.Password', ...)` call. In Authentication 4.x identifiers are configured within each authenticator via the `identifier` key in the authenticator config.
  - Added the password identifier configuration (including resolver and passwordHasher settings) to both the `Authentication.Form` and `Authentication.Cookie` authenticator configs.

### Added

- **Users Seed**: Added `config/Seeds/UsersSeed.php` to seed an admin user with `admin@example.com` / `password`. The password is hashed using `DefaultPasswordHasher`, matching the `User` entity's `_setPassword` mutator.

### Changed

- **Modern Welcome Page**: Redesigned `templates/Pages/welcome.php` with a SaaS-style landing page featuring a hero section with logo mockup, quick action buttons, system status grid, and a feature highlights section with three cards (Auth & Security, SaaS Dashboard, Dev Tools).

- **Default Layout Header & Footer**: Extracted header and footer into reusable element files (`templates/element/base/header.php` and `templates/element/base/footer.php`) for maintainability. Updated the `default` layout to use these elements.
- **Destructive Color Theme**: Replaced violet accent color with `--destructive` (red/orange) theme colors throughout header, footer, hero section, and status cards.
- **Modern Footer**: Redesigned the footer element with a SaaS-style layout featuring a prominent brand section with social icons, organized sitemap columns (Product, Resources, Company, Legal), and a bottom bar with copyright and CakePHP version info.

### Fixed

- **Login redirect**: Fixed `DashboardController could not be found` after login. The login fallback target was the string `/dashboard`, which CakePHP tried to route to a nonexistent `DashboardController`. Changed to `['controller' => 'Pages', 'action' => 'dashboard']` which correctly renders the Pages dashboard template.
- **Header login state**: Fixed the header always showing the "Log In" button even for logged-in users. The `isset($this->Identity)` check never matched because CakePHP helpers are exposed via the magic `__get` method and `View` implements no `__isset()`. Removed the `isset()` check and use `$this->Identity->isLoggedIn()` directly, which safely returns `false` when no identity exists.
- **Hero bento grid**: Redesigned the hero's right-side quick stats into a glassmorphism bento grid with transparent backgrounds (backdrop-blur tiles), featuring a large logo tile alongside stat tiles for Features, Ready, 2FA Auth, JWT, and Dark Mode.
