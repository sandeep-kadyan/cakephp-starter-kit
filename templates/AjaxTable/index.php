<?php
/**
 * Ajax Table Index Page Template
 *
 * @var \App\View\AppView $this
 * @var \App\View\Helper\AjaxTableHelper $this->AjaxTable
 *
 * -----------------------------------------------------------------------------
 * Description:
 * This template renders a full-page AJAX-powered table using the AjaxTable cell
 * and AjaxTableHelper. It is designed for dynamic, responsive, and customizable
 * table views in CakePHP applications, supporting server-side pagination, search,
 * and sort.
 *
 * -----------------------------------------------------------------------------
 * Use Cases:
 * - Display a list of records with AJAX pagination, search, and sort on a full page.
 * - Provide a consistent, customizable UI for listing entities in admin or user dashboards.
 * - Integrate with AjaxTableHelper and AjaxTable cell for seamless CRUD and association support.
 *
 * -----------------------------------------------------------------------------
 * Example Usage:
 *
 * // In your controller:
 * // No special setup required unless you want to customize columns or options.
 *
 * // In your template:
 * echo $this->cell('AjaxTable::index', [
 *     'controller' => $this->getName(),
 *     'plugin' => $this->getPlugin(),
 *     'options' => [
 *         'mainColumnCount' => 4,
 *         // 'columns' => $columns, // (optional) Custom columns config
 *         // 'apiUrl' => '/users.json', // (optional) Custom API endpoint
 *         // 'actionUrl' => '/users', // (optional) Custom action URL
 *     ],
 * ]);
 *
 * -----------------------------------------------------------------------------
 * How to Override:
 * - Copy this file to your app's templates/Ajax/index.php.
 * - Customize the markup, layout, or logic as needed for your project.
 * - You can use $this->cell('AjaxTable::index', ...) for a helper-driven layout,
 *   or write your own markup using AjaxTableHelper.
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
$this->assign('title', $this->getName());
?>
<?= $this->cell('AjaxTable::index', [
    'controller' => $this->getName(),
    'plugin' => $this->getPlugin(),
    'options' => [
        'mainColumnCount' => 4,
    ],
]) ?>
