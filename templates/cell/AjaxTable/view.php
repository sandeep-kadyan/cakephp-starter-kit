<?php

/**
 * AjaxTable Cell View Template
 *
 * @var \App\View\AppView $this
 * @var \App\View\Helper\AjaxTableHelper $this->AjaxTable
 *
 * -----------------------------------------------------------------------------
 * Description:
 * This template renders a detailed view for a single record using the AjaxTable
 * cell. It is designed for use with the AjaxTableHelper and supports dynamic,
 * responsive, and customizable record views in CakePHP applications.
 *
 * -----------------------------------------------------------------------------
 * Use Cases:
 * - Display a detailed view of a record in a modal, drawer, or dedicated page.
 * - Provide a consistent, customizable UI for viewing entity details via AJAX.
 * - Integrate with AjaxTableHelper for seamless CRUD and association support.
 *
 * -----------------------------------------------------------------------------
 * Example Usage:
 *
 * // In your controller or cell action:
 * $this->set('options', [
 *     'data' => $entity, // The record/entity to display
 *     'columns' => $columns, // Columns config (field => meta)
 *     'associations' => $associations, // (optional) Related models
 * ]);
 *
 * // In your template or via AJAX:
 * echo $this->cell('AjaxTable::view', [
 *     'controller' => 'Users',
 *     'options' => [
 *         'data' => $user,
 *         'columns' => $columns,
 *         'associations' => ['Posts', 'Comments'],
 *     ]
 * ]);
 *
 * -----------------------------------------------------------------------------
 * How to Override:
 * - Copy this file to your app's templates/cell/AjaxTable/view.php.
 * - Customize the markup, layout, or logic as needed for your project.
 * - You can use $this->AjaxTable->view(...) for a helper-driven layout, or
 *   write your own markup using $data, $columns, and $associations.
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
<?= $this->AjaxTable->view($controller, $plugin, $options) ?>