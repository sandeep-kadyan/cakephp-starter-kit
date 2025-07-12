# ViteHelper Documentation

The `ViteHelper` manages the integration between CakePHP and Vite.js for asset management. It handles both development (HMR) and production modes, with automatic fallback to built assets when the dev server is not available.

## Features

- Automatic detection of Vite dev server (HMR) or production assets
- Loads JS and CSS entrypoints with correct paths and hashes
- Works with Vite's manifest for production builds
- Simple integration in layouts and templates

## Use Cases

- Modern asset pipeline for CakePHP apps
- Hot module replacement (HMR) during development
- Cache-busted assets in production

## How to Use

1. Ensure `ViteHelper` is loaded in your `AppView` or controller.
2. In your layout or template, call:

   ```php
   <?= $this->Vite->assets(['js/app.js', 'css/app.css']) ?>
   ```

3. Configure build path in the helper or use the default `/dist`.

## Example

```php
// In your layout (e.g., templates/layout/default.php)
<?= $this->Vite->assets(['js/app.js', 'css/app.css']) ?>
```

## Configuration

- Build path: Default is `/dist`, can be customized in the helper config
- Dev server: Defaults to `localhost:5173`
