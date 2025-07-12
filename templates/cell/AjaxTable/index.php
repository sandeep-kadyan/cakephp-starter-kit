<?php
/**
 * AjaxTable Cell Index Template
 *
 * @var \App\View\AppView $this
 * @var \App\View\Helper\AjaxTableHelper $this->AjaxTable
 *
 * -----------------------------------------------------------------------------
 * Description:
 * This template renders a paginated, searchable, and sortable table using the
 * AjaxTable cell and AjaxTableHelper. It is designed for dynamic, responsive,
 * and customizable table views in CakePHP applications, supporting AJAX and
 * server-side processing.
 *
 * -----------------------------------------------------------------------------
 * Use Cases:
 * - Display a list of records with pagination, search, and sort in a modal,
 *   drawer, or dedicated page.
 * - Provide a consistent, customizable UI for listing entities via AJAX.
 * - Integrate with AjaxTableHelper for seamless CRUD and association support.
 *
 * -----------------------------------------------------------------------------
 * Example Usage:
 *
 * // In your controller or cell action:
 * $this->set('options', [
 *     'columns' => $columns, // Columns config (field => meta)
 *     'mainColumnCount' => 4, // (optional) Number of main columns
 *     'apiUrl' => '/users.json', // (optional) Custom API endpoint
 *     'actionUrl' => '/users', // (optional) Custom action URL
 * ]);
 *
 * // In your template or via AJAX:
 * echo $this->cell('AjaxTable::index', [
 *     'controller' => 'Users',
 *     'options' => [
 *         'columns' => $columns,
 *         'mainColumnCount' => 4,
 *         'apiUrl' => '/users.json',
 *         'actionUrl' => '/users',
 *     ]
 * ]);
 *
 * -----------------------------------------------------------------------------
 * How to Override:
 * - Copy this file to your app's templates/cell/AjaxTable/index.php.
 * - Customize the markup, layout, or logic as needed for your project.
 * - You can use $this->AjaxTable->render(...) for a helper-driven layout, or
 *   write your own markup using $columns and options.
 *
 * -----------------------------------------------------------------------------
 * Ajax Table Documentation:
 *
 * The AjaxTable cell and AjaxTableHelper provide a flexible, modern way to
 * render tables and record views in CakePHP with Alpine.js-powered interactivity.
 *
 * Features:
 * - Server-side pagination, search, and sort
 * - Responsive, mobile-friendly UI
 * - Customizable columns and field renderers
 * - Association/related data support
 * - Easy override via template or helper
 *
 * Best Practices:
 * - Use the AjaxTableHelper for consistent table and view rendering.
 * - Override this template for project-specific needs (branding, layout, etc).
 * - Pass all required data via the 'options' array for maximum flexibility.
 * - Use Alpine.js for lightweight, reactive UI enhancements.
 *
 * For more, see your app's AjaxTableHelper and AjaxTable cell documentation.
 * -----------------------------------------------------------------------------
 */
?>
<?= $this->AjaxTable->render($controller, $plugin, $options) ?>