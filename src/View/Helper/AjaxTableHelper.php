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
     * @param string|null $plugin The current request plugin.
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

        // Inherit the authenticated user's preferred default page size (if set)
        $identity = $this->getView()->getRequest()->getAttribute('identity');
        if ($identity) {
            $userSettingsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('UserSettings');
            $preferredSize = $userSettingsTable->getValue($identity->getIdentifier(), 'pagination.default_page_size');
            if ($preferredSize !== null) {
                $options['pageSize'] = (int)$preferredSize;
            }
        }

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
        $html = '<div x-data="ajaxTable()" data-api="' . $apiUrl . '" data-action="' . $actionUrl . '" data-columns="' . $columnsJson . '" data-table-id="' . $tableId . '" data-main-columns="' . $mainColumnsJson . '" data-extra-columns="' . $extraColumnsJson . '" data-has-extra-columns="' . ($hasExtraColumns ? '1' : '0') . '" data-page-size="' . (int)$options['pageSize'] . '"';

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
            $html .= '<input type="text" id="searchable" x-model="searchTerm" @input.debounce.500ms="onSearchInput()" ';
            $html .= 'placeholder="Search..." class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-ring">';
            $html .= '</div>';
        }
        // Button group: Export, Clear State, Select All, Delete
        $html .= '<div class="flex space-x-2 items-center">';

        // Select All/Visible/Delete buttons (show only if header checkbox is checked)
        // $html .= '<template x-if="allVisibleChecked" x-cloak>';
        // $html .= '<div class="flex space-x-2 items-center">';
        // $html .= '<button @click="deleteAllRecords()" class="px-2 py-1 text-destructive rounded-md hover:bg-destructive hover:text-destructive-foreground text-sm flex items-center gap-1" title="Delete All Records"><i data-lucide="trash-2"></i></button>';
        // $html .= '</div>';
        // $html .= '</template>';
        $html .= '<button x-show="selectedRows.length > 0" @click="deleteAllSelectedRecords()" class="w-9 h-9 inline-flex items-center justify-center bg-transparent text-destructive rounded-md hover:bg-destructive hover:text-destructive-foreground text-sm" title="Delete Selected"><i data-lucide="trash-2"></i></button>';
        if ($options['exportable']) {
            $html .= '<button @click="exportData(\'csv\')" class="w-9 h-9 inline-flex items-center justify-center bg-transparent text-foreground rounded-md hover:bg-accent hover:text-accent-foreground text-sm" title="Export CSV"><i data-lucide="download"></i></button>';
        }
        // History dropdown
        $html .= '<div class="relative" @click.outside="historyOpen = false">';
        $html .= '<button @click="historyOpen = !historyOpen" class="w-9 h-9 inline-flex items-center justify-center bg-transparent text-foreground rounded-md hover:bg-accent hover:text-accent-foreground text-sm" title="History"><i data-lucide="history"></i></button>';
        $html .= '<div x-show="historyOpen" x-cloak x-transition class="absolute right-0 z-50 mt-2 w-80 ajaxtable-panel rounded-md border border-border bg-popover text-popover-foreground shadow-lg focus:outline-none">';
        $html .= '<div class="p-2 border-b border-border flex items-center justify-between">';
        $html .= '<span class="text-sm font-medium">History</span>';
        $html .= '<button @click="clearHistory()" class="text-xs text-muted-foreground hover:text-foreground">Clear</button>';
        $html .= '</div>';
        $html .= '<template x-if="historyEntries.length === 0">';
        $html .= '<div class="p-3 text-sm text-muted-foreground">No actions recorded yet.</div>';
        $html .= '</template>';
        $html .= '<div x-show="historyEntries.length > 0" class="p-1">';
        $html .= '<template x-for="(entry, i) in historyEntries" :key="i">';
        $html .= '<button @click="restoreHistory(entry)" class="w-full text-left px-2 py-1.5 rounded-md text-sm hover:bg-accent hover:text-accent-foreground flex items-center justify-between gap-2">';
        $html .= '<span x-text="entry.label"></span>';
        $html .= '<span class="text-xs text-muted-foreground shrink-0" x-text="entry.timestamp"></span>';
        $html .= '</button>';
        $html .= '</template>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        // Filter dropdown
        $html .= '<div class="relative" @click.outside="filterOpen = false">';
        $html .= '<button @click="toggleFilterPanel()" class="w-9 h-9 inline-flex items-center justify-center bg-transparent text-foreground rounded-md hover:bg-accent hover:text-accent-foreground text-sm relative" title="Filter"><i data-lucide="filter"></i>';
        $html .= '<span x-show="activeFilterCount() > 0" class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-primary text-primary-foreground text-[10px] font-medium flex items-center justify-center" x-text="activeFilterCount()"></span>';
        $html .= '</button>';
        $html .= '<div x-show="filterOpen" x-cloak x-transition class="absolute right-0 z-50 mt-2 w-80 ajaxtable-panel rounded-md border border-border bg-popover text-popover-foreground shadow-lg focus:outline-none">';
        $html .= '<div class="p-2 border-b border-border"><span class="text-sm font-medium">Filters</span></div>';
        $html .= '<template x-if="filterableColumns.length === 0">';
        $html .= '<div class="p-3 text-sm text-muted-foreground">No filterable columns.</div>';
        $html .= '</template>';
        $html .= '<div x-show="filterableColumns.length > 0" class="p-3 space-y-3">';
        $html .= '<template x-for="col in filterableColumns" :key="col.field">';
        $html .= '<div>';
        $html .= '<label class="block text-xs font-medium text-muted-foreground mb-1" x-text="col.title"></label>';
        $html .= '<input type="text" class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-ring" placeholder="Filter by..." :value="filterValue(col.field)" @input="setFilterValue(col.field, $event.target.value)">';
        $html .= '</div>';
        $html .= '</template>';
        $html .= '<div class="flex justify-end gap-2 pt-1">';
        $html .= '<button @click="clearFilters()" class="px-3 py-1.5 rounded-md text-sm border border-border hover:bg-accent">Clear</button>';
        $html .= '<button @click="applyFilters()" class="px-3 py-1.5 rounded-md text-sm bg-primary text-primary-foreground hover:bg-primary/90">Apply</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        // Settings dropdown
        $html .= '<div class="relative" @click.outside="settingsOpen = false">';
        $html .= '<button @click="settingsOpen = !settingsOpen" class="w-9 h-9 inline-flex items-center justify-center bg-transparent text-foreground rounded-md hover:bg-accent hover:text-accent-foreground text-sm" title="Settings"><i data-lucide="settings"></i></button>';
        $html .= '<div x-show="settingsOpen" x-cloak x-transition class="absolute right-0 z-50 mt-2 w-80 ajaxtable-panel rounded-md border border-border bg-popover text-popover-foreground shadow-lg focus:outline-none">';
        $html .= '<div class="p-2 border-b border-border"><span class="text-sm font-medium">Settings</span></div>';
        $html .= '<div class="p-3 space-y-3">';
        $html .= '<div>';
        $html .= '<span class="block text-xs font-medium text-muted-foreground mb-1">Visible Columns</span>';
        $html .= '<div class="space-y-1">';
        $html .= '<template x-for="col in columns.filter(c => c.field !== \'actions\')" :key="col.field">';
        $html .= '<label class="flex items-center gap-2 text-sm cursor-pointer">';
        $html .= '<input type="checkbox" class="w-4 h-4 border border-input rounded accent-primary" :checked="visibleColumns.includes(col.field)" @change="toggleColumn(col.field)">';
        $html .= '<span x-text="col.title"></span>';
        $html .= '</label>';
        $html .= '</template>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div>';
        $html .= '<label for="settingsPageSize" class="block text-xs font-medium text-muted-foreground mb-1">Page Size</label>';
        $html .= '<select id="settingsPageSize" x-model="pageSize" @change="onPageSizeChange()" class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-ring">';
        foreach ($options['pageSizeOptions'] as $size) {
            $html .= '<option value="' . $size . '">' . $size . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<div class="flex justify-end gap-2 pt-1">';
        $html .= '<button @click="resetSettings()" class="px-3 py-1.5 rounded-md text-sm border border-border hover:bg-accent">Reset</button>';
        $html .= '<button @click="applyColumnVisibility()" class="px-3 py-1.5 rounded-md text-sm bg-primary text-primary-foreground hover:bg-primary/90">Apply</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<a href="' . $actionUrl . '/add" class="w-9 h-9 inline-flex items-center justify-center bg-transparent text-foreground rounded-md hover:bg-accent hover:text-accent-foreground text-sm" title="Add New"><i data-lucide="plus"></i></a>';
        $html .= '</div>';
        $html .= '</div>';

        // Table markup and Alpine.js hooks
        $html .= '<div class="block w-full overflow-auto shadow-md ring-1 ring-border md:rounded-lg relative" x-cloak>';
        $html .= '<table id="' . $tableId . '" class="min-w-full divide-y divide-border ">';
        $html .= '<thead class="bg-muted text-muted-foreground">';
        $html .= '<tr>';
        // Checkbox header
        $html .= '<th data-field="__select__" class="px-4 py-3 w-[50px]">'
            . '<label class="relative inline-flex items-center cursor-pointer">'
            . '<input name="checkbox" id="checkHead" type="checkbox" @change="toggleSelectAll($event)" x-model="allVisibleChecked"'
            . ' class="appearance-none w-4 h-4 border border-input rounded checked:bg-primary checked:border-primary focus:outline-none transition-all duration-150" />'
            . '<svg x-show="allVisibleChecked" class="absolute w-3 h-3 pointer-events-none left-0 top-0 m-0.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">'
            . '<path d="M5 13l4 4L19 7" :stroke="document.documentElement.classList.contains(\'dark\') ? \'black\' : \'white\'" />'
            . '</svg>'
            . '</label>'
            . '</th>';
        // Expand/collapse header (only if extra columns)
        if ($hasExtraColumns) {
            $html .= '<th data-field="__expand__" class="px-2 py-1 text-center w-[50px]"></th>';
        }
        // Main columns only
        foreach ($mainColumns as $key => $column) {
            $field = is_string($column) ? $column : ($column['field'] ?? $key);
            $title = Inflector::humanize(is_string($column) ? $column : ($column['title'] ?? $field));
            $sortable = is_string($column) ? true : ($column['sortable'] ?? true);
            if ($field === 'actions') {
                continue;
            }
            $html .= '<th data-field="' . addslashes($field) . '" class="px-6 py-1 text-left text-xs font-medium uppercase tracking-wider">';
            if ($sortable) {
                $html .= '<button @click="sort(\'' . addslashes($field) . '\')" class="flex items-center space-x-1 hover:text-foreground focus:outline-none">';
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
            $html .= '<th data-field="actions" class="px-6 py-1 text-right text-xs font-medium tracking-wider">Actions</th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        // Empty tbody for JS to fill, with support for checkboxes and expandable rows
        $html .= '<tbody class="bg-card text-muted-foreground divide-y divide-border min-h-24" x-ref="tbody">';
        // Alpine.js will render rows and nested rows here
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        // Pagination and loading indicator
        $html .= '<div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">';
        $html .= '<div class="text-sm text-foreground flex flex-wrap items-center gap-3">';
        // Page size selector (styling mirrors config/form.php select template)
        $html .= '<div class="flex items-center gap-2">';
        $html .= '<label for="pageSize" class="text-sm text-foreground hidden lg:flex">Page Size:</label>';
        $html .= '<select id="pageSize" x-model="pageSize" @change="onPageSizeChange()" class="h-9 rounded-md border border-input bg-background px-3 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-ring">';
        foreach ($options['pageSizeOptions'] as $size) {
            $html .= '<option value="' . $size . '">' . $size . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<span class="hidden lg:block">Showing <span x-text="startRecord"></span> to <span x-text="endRecord"></span> of <span x-text="totalRecords"></span> results</span>';
        $html .= '</div>';
        $html .= '<div class="flex items-center gap-2 text-foreground">';
        $html .= '<button @click="firstPage()" :disabled="currentPage === 1" class="h-9 px-3 inline-flex items-center justify-center gap-1 rounded-md border border-input bg-background text-sm hover:bg-accent disabled:opacity-50 disabled:cursor-not-allowed">'
            . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7M19 19l-7-7 7-7"/></svg>'
            . '<span class="hidden lg:inline">First</span>'
        . '</button>';
        $html .= '<button @click="previousPage()" :disabled="currentPage === 1" class="h-9 px-3 inline-flex items-center justify-center gap-1 rounded-md border border-input bg-background text-sm hover:bg-accent disabled:opacity-50 disabled:cursor-not-allowed">'
            . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>'
            . '<span class="hidden lg:inline">Previous</span>'
        . '</button>';
        $html .= '<span class="h-9 px-3 inline-flex items-center justify-center rounded-md border border-border bg-muted text-sm" title="Current page"><span x-text="currentPage"></span>&nbsp;of&nbsp;<span x-text="totalPages"></span></span>';
        $html .= '<button @click="nextPage()" :disabled="currentPage === totalPages" class="h-9 px-3 inline-flex items-center justify-center gap-1 rounded-md border border-input bg-background text-sm hover:bg-accent disabled:opacity-50 disabled:cursor-not-allowed">'
            . '<span class="hidden lg:inline">Next</span>'
            . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>'
        . '</button>';
        $html .= '<button @click="lastPage()" :disabled="currentPage === totalPages" class="h-9 px-3 inline-flex items-center justify-center gap-1 rounded-md border border-input bg-background text-sm hover:bg-accent disabled:opacity-50 disabled:cursor-not-allowed">'
            . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5l7 7-7 7M13 5l7 7-7 7"/></svg>'
            . '<span class="hidden lg:inline">Last</span>'
        . '</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div x-show="loading" class="flex items-center justify-center">';
        $html .= '<div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>';
        $html .= '<span class="ml-2 text-muted-foreground">Loading...</span>';
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
            $html .= '<div class="relative text-foreground">';
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
                        $html .= '<svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block text-muted-foreground hover:text-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="9" y="9" width="13" height="13" rx="2" stroke-width="2"/><rect x="3" y="3" width="13" height="13" rx="2" stroke-width="2"/></svg>';
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
                            $html .= '<div class="col-span-9">' . $this->Html->link($displayValue, $relatedUrl, ['class' => 'text-foreground hover:text-primary', 'escape' => false]) . '</div>';
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
                        $html .= '<h4 class="py-4 font-bold text-foreground">' . __('Related ' . $association) . '</h4>';
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
            $html .= '<div class="flex flex-1 items-center justify-center p-6">No data available</div>';
        }
        $html .= '</div>';

        return $html;
    }
}
