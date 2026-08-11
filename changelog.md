# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Fixed

- **Authentication plugin 4.x upgrade**: Fixed `Undefined constant Authentication\Identifier\AbstractIdentifier::CREDENTIAL_USERNAME` and `Call to undefined method Authentication\AuthenticationService::loadIdentifier()`.
  - Replaced import of `Authentication\Identifier\AbstractIdentifier` with `Authentication\Identifier\PasswordIdentifier`.
  - Replaced `AbstractIdentifier::CREDENTIAL_USERNAME` / `AbstractIdentifier::CREDENTIAL_PASSWORD` with `PasswordIdentifier::CREDENTIAL_USERNAME` / `PasswordIdentifier::CREDENTIAL_PASSWORD`.
  - Removed the standalone `$service->loadIdentifier('Authentication.Password', ...)` call. In Authentication 4.x identifiers are configured within each authenticator via the `identifier` key in the authenticator config.
  - Added the password identifier configuration (including resolver and passwordHasher settings) to both the `Authentication.Form` and `Authentication.Cookie` authenticator configs.
