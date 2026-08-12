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

- **Theme token system**: Introduced a CSS-variable theme token system so the entire UI (backgrounds, text, cards, popovers, borders, inputs, focus rings, and shadows) is driven by variables in `webroot/css/app.css` only.
  - `tailwind.config.js` now maps semantic colors (`background`, `foreground`, `card`, `popover`, `primary`, `secondary`, `muted`, `accent`, `destructive`, `border`, `input`, `ring`, `sidebar`) to CSS variables as RGB triplets, enabling opacity modifiers; `borderRadius` and `boxShadow` are also theme-driven.
  - Light and dark themes flip via the `.dark` class — `dark:` color variants were removed from all templates.
  - Replaced all hardcoded `neutral-*`/`gray-*`/`stone-*`/black/white colors with theme tokens across layouts, elements, helpers (Menu, AjaxTable, Toast), auth pages, and CRUD templates.
  - Converted bake-style CRUD templates (`Activities`, `AuthRequests`, `Users`) to token-based Tailwind markup (tables, forms, detail views, and action buttons); added token-styled paginator and sort templates in `src/View/AppView.php`.
  - Tokenized `config/form.php` form templates (inputs, buttons, selects, textareas, radio, labels, errors) and the form `errorClass`; removed the stale `neutral-*` classes and bootstrap `text-danger`.
  - Tokenized `webroot/js/ajaxtable.js` (rows, dropdowns, checkboxes, headers) and replaced the custom `.spinner` class in `webroot/js/app.js` with Tailwind's `animate-spin`.
  - Restricted the Tailwind content globs to `webroot/js` and `webroot/css` so build artifacts under `dist/` are not scanned for class names.

- **Modern Welcome Page**: Redesigned `templates/Pages/welcome.php` with a SaaS-style landing page featuring a hero section with logo mockup, quick action buttons, system status grid, and a feature highlights section with three cards (Auth & Security, SaaS Dashboard, Dev Tools).

- **Default Layout Header & Footer**: Extracted header and footer into reusable element files (`templates/element/base/header.php` and `templates/element/base/footer.php`) for maintainability. Updated the `default` layout to use these elements.
- **Destructive Color Theme**: Replaced violet accent color with `--destructive` (red/orange) theme colors throughout header, footer, hero section, and status cards.
- **Modern Footer**: Redesigned the footer element with a SaaS-style layout featuring a prominent brand section with social icons, organized sitemap columns (Product, Resources, Company, Legal), and a bottom bar with copyright and CakePHP version info.

### Fixed

- **Login redirect**: Fixed `DashboardController could not be found` after login. The login fallback target was the string `/dashboard`, which CakePHP tried to route to a nonexistent `DashboardController`. Changed to `['controller' => 'Pages', 'action' => 'dashboard']` which correctly renders the Pages dashboard template.
- **Header login state**: Fixed the header always showing the "Log In" button even for logged-in users. The `isset($this->Identity)` check never matched because CakePHP helpers are exposed via the magic `__get` method and `View` implements no `__isset()`. Removed the `isset()` check and use `$this->Identity->isLoggedIn()` directly, which safely returns `false` when no identity exists.
- **Hero bento grid**: Redesigned the hero's right-side quick stats into a glassmorphism bento grid with transparent backgrounds (backdrop-blur tiles), featuring a large logo tile alongside stat tiles for Features, Ready, 2FA Auth, JWT, and Dark Mode.

### SEO

- **Full SEO setup**: Added a reusable `base/seo` element rendered in `default`, `app`, and `auth` layouts with:
  - Meta description, author, keywords, robots, theme-color, and canonical URL
  - Open Graph tags (site_name, type, title, description, url, image, locale)
  - Twitter Card tags (summary_large_image, title, description, image, site)
  - JSON-LD structured data (WebSite + WebPage + Organization + author Person schema)
  - Automatic `noindex, nofollow` for authenticated/private pages
  - Per-view overrides via `title`, `seo.description`, `seo.image`, and `seo.type` view blocks
- Added site identity settings (site name, author, keywords, share image, Twitter handle, organization) under `Setting.seo` in `config/setting.php`.
- Welcome page now sets a proper SEO title and description.
