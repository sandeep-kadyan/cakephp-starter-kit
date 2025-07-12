<?php
/**
 * Ajax Table View Page Template
 *
 * @var \App\View\AppView $this
 * @var \App\View\Helper\AjaxTableHelper $this->AjaxTable
 *
 * -----------------------------------------------------------------------------
 * Description:
 * This template renders a full-page AJAX-powered detail view for a single record
 * using the AjaxTable cell and AjaxTableHelper. It is designed for dynamic,
 * responsive, and customizable record views in CakePHP applications, supporting
 * association and custom column rendering.
 *
 * -----------------------------------------------------------------------------
 * Use Cases:
 * - Display a detailed view of a record with AJAX in a modal, drawer, or full page.
 * - Provide a consistent, customizable UI for viewing entity details in admin or user dashboards.
 * - Integrate with AjaxTableHelper and AjaxTable cell for seamless CRUD and association support.
 *
 * -----------------------------------------------------------------------------
 * Example Usage:
 *
 * // In your controller:
 * $this->set($entityName, $entity); // e.g., $this->set('user', $user);
 *
 * // In your template:
 * echo $this->cell('AjaxTable::view', [
 *     'controller' => $this->getName(),
 *     'plugin' => $this->getPlugin(),
 *     'options' => [
 *         'data' => $this->viewVars[$controller],
 *         // 'columns' => $columns, // (optional) Custom columns config
 *         // 'associations' => $associations, // (optional) Related models
 *     ],
 * ]);
 *
 * -----------------------------------------------------------------------------
 * How to Override:
 * - Copy this file to your app's templates/Ajax/view.php.
 * - Customize the markup, layout, or logic as needed for your project.
 * - You can use $this->cell('AjaxTable::view', ...) for a helper-driven layout,
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

use Cake\Utility\Inflector;

$controller = Inflector::singularize(Inflector::variable($this->getName()));
$entity = $this->viewVars[$controller];
?>
<?= $this->cell('AjaxTable::view', [
    'controller' => $this->getName(),
    'plugin' => $this->getPlugin(),
    'options' => [
        'data' => $this->viewVars[$controller],
    ],
]) ?>
