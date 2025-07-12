# AjaxTableHelper Documentation

The `AjaxTableHelper` provides utilities to render interactive, responsive AjaxTables in CakePHP views, powered by Alpine.js. It supports searching, sorting, pagination, column toggling, exporting, and responsive layouts.

## Features

- Render paginated, searchable, and sortable tables for any model
- Export data (CSV) - `Not implemented`
- Column toggle and responsive layouts - `Not implemented`
- AJAX/JSON endpoints for dynamic data loading
- Consistent, modern UI for CRUD index pages

## Use Cases

- Admin dashboards and CRUD interfaces
- User, activity, or custom entity listings
- Any page requiring advanced table features with minimal code

## How to Use

1. Ensure `AjaxTableHelper` is loaded in your controller or globally in `AppView`.
2. In your template (e.g., `templates/Users/index.php`), call:

   ```php
   echo $this->AjaxTable->render($this->request, [
       'columns' => [
           ['field' => 'id', 'title' => 'ID', 'sortable' => true],
           ['field' => 'username', 'title' => 'Username', 'searchable' => true],
           ['field' => 'email', 'title' => 'Email'],
           ['field' => 'created', 'title' => 'Created', 'type' => 'date'],
           ['field' => 'status', 'title' => 'Status', 'sortable' => true, 'searchable' => true],
       ],
       'mainColumnCount' => 3, // Show first 3 columns in main table, rest as expandable
       'exportable' => false, // Hide export button
       'pageSize' => 25, // Default page size
       'showActions' => true, // Show actions column
       'apiUrl' => '/users/index.json', // Custom API endpoint (optional)
   ]);
   ```

## Configuration Options

- `columns`: Array of column definitions (field, title, type, sortable, searchable, etc.)
- `mainColumnCount`: Number of columns to show in the main table
- `exportable`: Show/hide export button
- `pageSize`: Default page size
- `showActions`: Show actions column
- `apiUrl`: Custom API endpoint for AJAX loading

## Extending

- Customize columns and features as needed
- Integrate with AjaxTableCell for advanced AJAX tables
