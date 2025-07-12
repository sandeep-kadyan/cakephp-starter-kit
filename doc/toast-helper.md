# ToastHelper Documentation

The `ToastHelper` provides a flexible toast/flash notification system for CakePHP applications. It supports multiple types, positions, and display modes.

## Features

- Show toast notifications for success, error, warning, info, and custom types
- Configurable position (top/bottom, left/right/center)
- Display all at once or one-by-one (expand mode)
- Customizable via `config/setting.php`

## Use Cases

- User feedback for actions (save, delete, error, etc.)
- Alerts and notifications in admin/user dashboards
- Custom flash messages with links or actions

## How to Use

1. Ensure `ToastHelper` is loaded in your `AppView` or controller.
2. In your layout or template, call:

   ```php
   echo $this->Toast->render();
   ```

3. Configure position and display mode in `config/setting.php` under `Setting.default.toast`, `Setting.auth.toast`, or `Setting.app.toast`.

## Example

```php
// In your layout (e.g., templates/layout/default.php)
echo $this->Toast->render();
```

## Configuration

- Position: `top-left`, `top-right`, `top-center`, `bottom-left`, `bottom-right`, `bottom-center`
- Display: `all` (show all at once) or `expand` (one by one)
