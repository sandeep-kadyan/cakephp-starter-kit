<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Utility\Inflector;
use Cake\View\Helper;

/**
 * AjaxTableHelper provides utilities to render interactive, responsive AjaxTables in CakePHP views.
 *
 * This helper generates the HTML and JavaScript configuration for Alpine.js-powered AjaxTables,
 * supporting features like searching, sorting, pagination, column toggling, exporting, and responsive layouts.
 *
 * Use Cases:
 * - Quickly render a paginated, searchable, and sortable table for any model in your CakePHP application.
 * - Add export, column toggle, and responsive features to admin or dashboard tables with minimal code.
 * - Integrate with AJAX/JSON endpoints for dynamic data loading and server-side processing.
 * - Provide a consistent, modern UI for CRUD index pages across your application.
 *
 * How to Use:
 * 1. Ensure the AjaxTableHelper is loaded in your controller or globally in your AppView.
 * 2. In your template (e.g., `templates/Users/index.php`), call `$this->AjaxTable->render($this->request, [...])` with your desired options.
 * 3. Customize columns, features, and endpoints as needed for your use case.
 *
 * Example:
 * ```php
 * // In your template (e.g., templates/Users/index.php)
 * echo $this->AjaxTable->render($this->request, [
 *     'columns' => [
 *         ['field' => 'id', 'title' => 'ID', 'sortable' => true],
 *         ['field' => 'username', 'title' => 'Username', 'searchable' => true],
 *         ['field' => 'email', 'title' => 'Email'],
 *         ['field' => 'created', 'title' => 'Created', 'type' => 'date'],
 *         ['field' => 'status', 'title' => 'Status', 'sortable' => true, 'searchable' => true],
 *     ],
 *     'mainColumnCount' => 3, // Show first 3 columns in main table, rest as expandable
 *     'exportable' => false, // Hide export button
 *     'pageSize' => 25, // Default page size
 *     'showActions' => true, // Show actions column
 *     'apiUrl' => '/users/index.json', // Custom API endpoint (optional)
 * ]);
 * ```
 *
 * @package App\View\Helper
 */
class AjaxTableHelper extends Helper
{
    /**
     * Default configuration for the AjaxTable.
     *
     * @var array<string, mixed> Default options for rendering the AjaxTable.
     */
    protected array $_defaultConfig = [
        'class' => 'w-full',
        'searchable' => true,
        'sortable' => true,
        'showActions' => true,
        'responsive' => true,
        'exportable' => false,
        'showColumnToggle' => true,
        'pageSize' => 10,
        'pageSizeOptions' => [10, 15, 25, 50, 100],
    ];

    /**
     * Helpers used by this helper.
     *
     * @var array<string, mixed> List of helpers used by AjaxTableHelper.
     */
    protected array $helpers = ['Html', 'Form', 'Url'];

    /**
     * Render a AjaxTable with Alpine.js configuration and markup.
     *
     * @param string $controller The current request controller.
     * @param string $plugin The current request plugin.
     * @param array<string, mixed> $options Additional options for customizing the AjaxTable output.
     * @return string The generated HTML for the AjaxTable.
     */
    public function render(string $controller, ?string $plugin = null, array $options = []): string
    {
        // Merge default config with any custom options
        $options = array_merge($this->getConfig(), $options);
        $tableId = $options['tableId'] ?? 'ajaxtable-' . strtolower($controller) . '-index';

        // Columns configuration for the AjaxTable
        $columns = $options['columns'];

        // Build the API URL for fetching data (default to current controller/action)
        $apiUrl = $options['apiUrl'] ?? $this->Url->build([
            'plugin' => $plugin,
            'controller' => $controller,
            'action' => 'index',
            '_ext' => 'json',
        ]);

        // Build the action URL for row actions (default to current controller)
        $actionUrl = $options['actionUrl'] ?? $this->Url->build([
            'plugin' => $plugin,
            'controller' => $controller,
        ]);

        // Split columns into main (fit) and extra (nested)
        $mainColumnCount = $options['mainColumnCount'] ?? 4; // Number of columns to show in main table
        $columnKeys = array_keys($columns);
        $mainColumnKeys = array_slice($columnKeys, 0, $mainColumnCount);
        $extraColumnKeys = array_slice($columnKeys, $mainColumnCount);

        $mainColumns = array_map(function ($key) use ($columns) {
            $col = $columns[$key];

            return is_string($col) ? $key : ($col['field'] ?? $key);
        }, $mainColumnKeys);

        $extraColumns = array_map(function ($key) use ($columns) {
            $col = $columns[$key];

            return is_string($col) ? $key : ($col['field'] ?? $key);
        }, $extraColumnKeys);

        // Create a column map for JavaScript configuration
        $columnMap = [];
        foreach ($columns as $key => $column) {
            $field = is_string($column) ? $column : ($column['field'] ?? $key);
            $columnMap[$field] = [
                'field' => $field,
                'title' => is_string($column) ? $column : ($column['title'] ?? $field),
                'sortable' => is_string($column) ? true : ($column['sortable'] ?? true),
                'searchable' => is_string($column) ? true : ($column['searchable'] ?? true),
                'visible' => true, // Always visible in column map, but only mainColumns shown in main row
                'render' => is_string($column) ? null : ($column['render'] ?? null),
                'type' => is_string($column) ? true : ($column['type'] ?? true),
                'default' => is_string($column) ? ($column['null'] ? $column['default'] : '-') : ($column['default'] ?? '-'),
                'exportable' => is_string($column) ? true : ($column['exportable'] ?? true),
                'route' => is_string($column) ? true : ($column['route'] ?? ''),
                'displayField' => is_string($column) ? true : ($column['displayField'] ?? ''),
            ];
        }

        // Encode column configuration for use in HTML attributes
        $columnsJson = htmlspecialchars(json_encode($columnMap), ENT_QUOTES, 'UTF-8');
        $mainColumnsJson = htmlspecialchars(json_encode($mainColumns), ENT_QUOTES, 'UTF-8');
        $extraColumnsJson = htmlspecialchars(json_encode($extraColumns), ENT_QUOTES, 'UTF-8');

        $hasExtraColumns = count($extraColumns) > 0;
        // Responsive wrapper for Alpine.js AjaxTable
        $html = '<div x-data="ajaxTable()" data-api="' . $apiUrl . '" data-action="' . $actionUrl . '" data-columns="' . $columnsJson . '" data-table-id="' . $tableId . '" data-main-columns="' . $mainColumnsJson . '" data-extra-columns="' . $extraColumnsJson . '" data-has-extra-columns="' . ($hasExtraColumns ? '1' : '0') . '"';

        // Add default sort field/direction if provided
        if (!empty($options['defaultSortField'])) {
            $html .= ' data-default-sort-field="' . htmlspecialchars($options['defaultSortField'], ENT_QUOTES, 'UTF-8') . '"';
        }
        if (!empty($options['defaultSortDirection'])) {
            $html .= ' data-default-sort-direction="' . htmlspecialchars($options['defaultSortDirection'], ENT_QUOTES, 'UTF-8') . '"';
        }
        $html .= '>';

        $html .= '<div class="flex flex-col md:flex-row md:flex-1 gap-6 justify-between mb-6 items-center">';
        // Search box
        if ($options['searchable']) {
            $html .= '<div class="max-w-md w-full">';
            $html .= '<input type="text" id="searchable" x-model="searchTerm" @input.debounce.500ms="loadData()" ';
            $html .= 'placeholder="Search..." class="px-3 py-2 h-9 w-full rounded-lg dark:bg-transparent dark:text-white border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white">';
            $html .= '</div>';
        }
        // Button group: Export, Clear State, Select All, Delete
        $html .= '<div class="flex space-x-2 items-center">';

        // Select All/Visible/Delete buttons (show only if header checkbox is checked)
        // $html .= '<template x-if="allVisibleChecked" x-cloak>';
        // $html .= '<div class="flex space-x-2 items-center">';
        // $html .= '<button @click="deleteAllRecords()" class="px-2 py-1 text-red-700 rounded-md hover:bg-red-700 hover:text-neutral-100 text-sm flex align-middle items-center gap-1" title="Delete All Records"><span class="material-icons">delete_outline</span></button>';
        // $html .= '</div>';
        // $html .= '</template>';
        $html .= '<button x-show="selectedRows.length > 0" @click="deleteAllSelectedRecords()" class="px-2 py-1 text-red-700 rounded-md hover:bg-red-700 hover:text-neutral-100 text-sm flex align-middle items-center gap-1" title="Delete Selected Records"><span class="material-icons">delete_sweep</span></button>';
        if ($options['exportable']) {
            $html .= '<button @click="exportData(\'csv\')" class="px-2 py-1 bg-transparent text-black dark:text-white dark:hover:bg-white/20 rounded-md hover:bg-neutral-200 text-sm"><span class="material-icons flex items-center align-middle">file_download</span></button>';
        }
        $html .= '<button @click="clearState()" class="px-2 py-1 bg-transparent text-black dark:text-white dark:hover:bg-white/20 rounded-md hover:bg-neutral-200 text-sm"><span class="material-icons flex items-center align-middle">restore_page</span></button>';
        $html .= '<button @click="clearState()" class="px-2 py-1 bg-transparent text-black dark:text-white dark:hover:bg-white/20 rounded-md hover:bg-neutral-200 text-sm"><span class="material-icons flex items-center align-middle">filter_list</span></button>';
        $html .= '<button @click="clearState()" class="px-2 py-1 bg-transparent text-black dark:text-white dark:hover:bg-white/20 rounded-md hover:bg-neutral-200 text-sm"><span class="material-icons flex items-center align-middle">settings</span></button>';
        $html .= '<a href="' . $actionUrl . '/add" class="px-2 py-1 bg-neutral-100 text-black rounded-md hover:bg-neutral-200 dark:text-white dark:bg-white/20 text-sm flex align-middle items-center gap-1"><span class="material-icons">add</span>New</a>';
        $html .= '</div>';
        $html .= '</div>';

        // Table markup and Alpine.js hooks
        $html .= '<div class="block w-full overflow-auto shadow ring-1 ring-black dark:ring-neutral-700 ring-opacity-5 md:rounded-lg relative" x-cloak>';
        $html .= '<table id="' . $tableId . '" class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700 ">';
        $html .= '<thead class="bg-neutral-50 text-neutral-500 dark:bg-white/20 dark:text-white dark:hover:text-white">';
        $html .= '<tr>';
        // Checkbox header
        $html .= '<th class="px-4 py-3 w-[50px]">'
            . '<label class="relative inline-flex items-center cursor-pointer">'
            . '<input name="checkbox" id="checkHead" type="checkbox" @change="toggleSelectAll($event)" x-model="allVisibleChecked"'
            . ' class="appearance-none w-4 h-4 border border-neutral-300 rounded checked:bg-black dark:checked:bg-white checked:border-black dark:checked:border-white focus:outline-none transition-all duration-150 align-middle" />'
            . '<svg x-show="allVisibleChecked" class="absolute w-3 h-3 pointer-events-none left-0 top-0 m-0.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">'
            . '<path d="M5 13l4 4L19 7" :stroke="document.documentElement.classList.contains(\'dark\') ? \'black\' : \'white\'" />'
            . '</svg>'
            . '</label>'
            . '</th>';
        // Expand/collapse header (only if extra columns)
        if ($hasExtraColumns) {
            $html .= '<th class="px-2 py-1 text-center w-[50px]"></th>';
        }
        // Main columns only
        foreach ($mainColumns as $key => $column) {
            $field = is_string($column) ? $column : ($column['field'] ?? $key);
            $title = Inflector::humanize(is_string($column) ? $column : ($column['title'] ?? $field));
            $sortable = is_string($column) ? true : ($column['sortable'] ?? true);
            if ($field === 'actions') {
                continue;
            }
            $html .= '<th class="px-6 py-1 text-left text-xs font-medium uppercase tracking-wider">';
            if ($sortable) {
                $html .= '<button @click="sort(\'' . addslashes($field) . '\')" class="flex items-center space-x-1 hover:text-neutral-700 dark:hover:text-neutral-100 focus:outline-none">';
                $html .= '<span>' . h($title) . '</span>';
                $html .= '<span x-show="sortField === \'' . addslashes($field) . '\'" x-text="sortDirection === \'asc\' ? \'↑\' : \'↓\'" class="ml-1"></span>';
                $html .= '</button>';
            } else {
                $html .= h($title);
            }
            $html .= '</th>';
        }
        // Actions column header (always last, always visible)
        if ($options['showActions']) {
            $html .= '<th class="px-6 py-1 text-right text-xs font-medium tracking-wider">Actions</th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        // Empty tbody for JS to fill, with support for checkboxes and expandable rows
        $html .= '<tbody class="bg-white text-neutral-500 dark:bg-transparent dark:text-white divide-y divide-neutral-200 dark:divide-neutral-700" x-ref="tbody" style="min-height:100px;">';
        // Alpine.js will render rows and nested rows here
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        // Pagination and loading indicator remain as before
        $html .= '<div class="mt-4 flex items-center justify-between">';
        $html .= '<div class="text-sm text-neutral-800 dark:text-white">';
        $html .= '<div class="flex items-center justify-start gap-1">';
        // Page size selector
        $html .= '<div class="flex items-center lg:space-x-2">';
        $html .= '<label for="pageSize" class="text-sm text-neutral-800 dark:text-white hidden lg:flex">Page Size:</label>';
        $html .= '<select id="pageSize" x-model="pageSize" @change="onPageSizeChange()" class="px-2 py-1 h-8 border border-neutral-200 dark:border-neutral-100 dark:bg-white/20 rounded-md text-sm">';
        foreach ($options['pageSizeOptions'] as $size) {
            $html .= '<option value="' . $size . '">' . $size . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<span class="hidden lg:block">Showing <span x-text="startRecord"></span> to <span x-text="endRecord"></span> of <span x-text="totalRecords"></span> results</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="flex align-middle items-center space-x-2 dark:text-white">';
        $html .= '<button @click="firstPage()" :disabled="currentPage === 1" class="px-3 py-1 border border-neutral-200 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-neutral-50 dark:bg-white/20 flex items-center justify-center">'
            . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7M19 19l-7-7 7-7"/></svg>'
            . '<span class="hidden lg:inline ml-1">First</span>'
        . '</button>';
        $html .= '<button @click="previousPage()" :disabled="currentPage === 1" class="px-3 py-1 border border-neutral-200 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-neutral-50 dark:bg-white/20 flex items-center justify-center">'
            . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>'
            . '<span class="hidden lg:inline ml-1">Previous</span>'
        . '</button>';
        $html .= '<span class="text-sm text-neutral-800 dark:text-neutral-100"><span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>';
        $html .= '<button @click="nextPage()" :disabled="currentPage === totalPages" class="px-3 py-1 border border-neutral-200 text-neutral-800 dark:text-neutral-100 dark:border-neutral-800 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-neutral-50 dark:bg-white/20 flex items-center justify-center">'
            . '<span class="hidden lg:inline ml-1">Next</span>'
            . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>'
        . '</button>';
        $html .= '<button @click="lastPage()" :disabled="currentPage === totalPages" class="px-3 py-1 border border-neutral-200 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-neutral-50 dark:bg-white/20 flex items-center justify-center">'
            . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5l7 7-7 7M13 5l7 7-7 7"/></svg>'
            . '<span class="hidden lg:inline ml-1">Last</span>'
        . '</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div x-show="loading" class="flex items-center justify-center">';
        $html .= '<div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-neutral-800 dark:border-white"></div>';
        $html .= '<span class="ml-2 text-neutral-500 dark:text-white">Loading...</span>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render a detailed view of a single record (row) with all columns and associations.
     *
     * @param string $controller The current request controller.
     * @param string $plugin The current request plugin.
     * @param array<string, mixed> $options Additional options for customizing the AjaxTable output.
     * @return string Rendered HTML for the record view
     */
    public function view(string $controller, ?string $plugin = null, array $options = []): string
    {
        $data = $options['data'];
        $columns = $options['columns'];
        $associations = $options['associations'];

        $html = '<div>';
        if ($data) {
            $html .= '<div class="relative dark:text-white">';
            foreach ($columns as $field => $column) {
                $label = __(Inflector::humanize($field));
                $value = $data->$field;
                switch ($column['type']) {
                    case 'uuid':
                        $uuid = h($value);
                        // Alpine.js for copy feedback
                        $html .= '<div class="grid grid-cols-12 gap-2 items-center py-2">';
                        $html .= '<div class="col-span-3 text-left">' . $label . '</div>';
                        $html .= '<div class="col-span-9 flex items-center gap-2" x-data="{ copied: false }">';
                        $html .= '<span class="font-mono select-all">' . $uuid . '</span>';
                        $html .= '<button type="button" class="ml-2 copy-uuid-btn" title="Copy UUID" @click="navigator.clipboard.writeText(\'' . $uuid . '\'); copied = true; setTimeout(() => copied = false, 1500)" >';
                        // Copy icon
                        $html .= '<svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block text-neutral-500 hover:text-black dark:hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="9" y="9" width="13" height="13" rx="2" stroke-width="2"/><rect x="3" y="3" width="13" height="13" rx="2" stroke-width="2"/></svg>';
                        // Check icon
                        $html .= '<svg x-show="copied" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                        $html .= '</button>';
                        $html .= '</div>';
                        $html .= '</div>';
                        break;
                    case 'binary':
                        $alt = h($data->title ?? $data->name ?? '');
                        $html .= '<div class="grid grid-cols-10 gap-4 items-center py-2">';
                        $html .= '<div class="col-span-7 text-left">' . $label . '</div>';
                        $html .= '<div class="col-span-3 flex justify-end">' . $this->Html->image($value, ['alt' => $alt, 'class' => 'max-h-24 max-w-full rounded shadow']) . '</div>';
                        $html .= '</div>';
                        break;
                    case 'string':
                        $html .= '<div class="grid grid-cols-12 gap-2 items-center py-2">';
                        $html .= '<div class="col-span-3 text-left">' . $label . '</div>';
                        $html .= '<div class="col-span-9">' . h($value) . '</div>';
                        $html .= '</div>';
                        break;
                    case 'boolean':
                        $html .= '<div class="grid grid-cols-12 gap-2 items-center py-2">';
                        $html .= '<div class="col-span-3 text-left">' . $label . '</div>';
                        $html .= '<div class="col-span-9">' . h($value ? 'Yes' : 'No') . '</div>';
                        $html .= '</div>';
                        break;
                    case 'datetime':
                    case 'date':
                    case 'time':
                    case 'timestamp':
                        $html .= '<div class="grid grid-cols-12 gap-2 items-center py-2">';
                        $html .= '<div class="col-span-3 text-left">' . $label . '</div>';
                        $html .= '<div class="col-span-9">' . h($value) . '</div>';
                        $html .= '</div>';
                        break;
                    case 'integer':
                    case 'float':
                    case 'decimal':
                    case 'biginteger':
                    case 'smallinteger':
                    case 'tinyinteger':
                        $html .= '<div class="grid grid-cols-12 gap-2 items-center py-2">';
                        $html .= '<div class="col-span-3 text-left">' . $label . '</div>';
                        $html .= '<div class="col-span-9">';
                        if ($value === null) {
                            $html .= h('-');
                        } else {
                            $html .= $this->getView()->Number->format($value);
                        }
                        $html .= '</div>';
                        $html .= '</div>';
                        break;
                    case 'text':
                        $html .= '<div class="grid grid-cols-12 gap-2 items-center py-2">';
                        $html .= '<div class="col-span-3 text-left">' . $label . '</div>';
                        $html .= '<div class="col-span-9"><p>' . $this->getView()->Text->autoParagraph(h($value)) . '</p></div>';
                        $html .= '</div>';
                        break;
                    case 'belongsTo':
                        // Handle foreign key relationships in view
                        $relatedModel = substr($field, 0, -3); // Remove _id suffix
                        $relatedModel = Inflector::singularize($relatedModel);
                        $relatedData = $data->$relatedModel ?? null;

                        if ($relatedData && $relatedData->id) {
                            // Get display field or fallback to id
                            $displayField = $column['displayField'];
                            $displayValue = $relatedData->$displayField ?? $relatedData->title ?? $relatedData->username ?? $relatedData->email ?? $relatedData->id;
                            $relatedUrl = $column['route'] . '/' . $relatedData->id;

                            $html .= '<div class="grid grid-cols-12 gap-2 items-center py-2">';
                            $html .= '<div class="col-span-3 text-left">' . $label . '</div>';
                            $html .= '<div class="col-span-9">' . $this->Html->link($displayValue, $relatedUrl, ['class' => 'text-neutral-800 hover:text-neutral-900 dark:text-neutral-100 dark:hover:text-neutral-200', 'escape' => false]) . '</div>';
                            $html .= '</div>';
                        } else {
                            $html .= '<div class="grid grid-cols-12 gap-2 items-center py-2">';
                            $html .= '<div class="col-span-3 text-left">' . $label . '</div>';
                            $html .= '<div class="col-span-9">' . h($value ?? '-') . '</div>';
                            $html .= '</div>';
                        }
                        break;
                }
            }
            $html .= '</div>';
            if (!empty($associations)) {
                foreach ($associations as $association) {
                    $associated = Inflector::underscore($association);
                    if (!empty($data->$associated)) {
                        $html .= '<div class="related">';
                        $html .= '<h4 class="py-4 font-bold dark:text-white">' . __('Related ' . $association) . '</h4>';
                        $html .= $this->getView()->cell('AjaxTable::index', [
                            'controller' => $association,
                            'options' => [
                                'mainColumnCount' => 3,
                            ],
                        ]);
                        $html .= '</div>';
                    }
                }
            }
        } else {
            $html .= '<div class="flex flex-1 align-middle justify-center p-6">No data available</div>';
        }
        $html .= '</div>';

        return $html;
    }
}
