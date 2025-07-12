<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\View\Cell;

/**
 * AjaxTable cell
 */
class AjaxTableCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array<string, mixed>
     */
    protected array $_validCellOptions = ['controller', 'plugin', 'options'];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize(): void
    {
    }

    /**
     * Renders the AjaxTable index view for a given controller and plugin.
     *
     * This method prepares the columns configuration and options for the AjaxTable,
     * filtering out hidden fields and non-displayable types. It is used to render
     * a paginated, searchable, and sortable table for the specified model/controller,
     * supporting dynamic columns and custom options for the AjaxTable UI.
     *
     * @param string $controller The controller/model name (e.g., 'Users', 'Activities').
     * @param string|null $plugin The plugin name, if any.
     * @param array $options Additional options for customizing the AjaxTable output (e.g., hiddenFields, mainColumnCount).
     * @return void Sets view variables for use in the AjaxTable index template.
     */
    public function index(string $controller, ?string $plugin = null, array $options = []): void
    {
        $columns = $this->getColumns($controller, $plugin);

        // Use hiddenFields from options or default
        $hiddenFields = $options['hiddenFields'] ?? ['id', 'created', 'modified', 'password'];

        // Filter columns from the hidden fields
        $columns = array_filter($columns, function ($colConfig, $colName) use ($hiddenFields) {
            if (in_array($colName, $hiddenFields)) {
                return false;
            }
            if (in_array($colConfig['type'], ['binary', 'text'])) {
                return false;
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH);

        $options['columns'] = $columns;
        $options['defaultSortField'] = $options['defaultSortField'] ?? array_keys($columns)[0];

        $this->set(compact('controller', 'plugin', 'options'));
    }

    /**
     * View display method.
     *
     * @param string $controller The controller name (e.g., 'Users', 'Settings').
     * @param string|null $plugin The plugin name if the controller is in a plugin (optional).
     * @param string|null $options The options (optional).
     * @return void
     */
    public function view(string $controller, ?string $plugin = null, array $options = []): void
    {
        $options['columns'] = $this->getColumns($controller, $plugin);
        $options['associations'] = $this->getAssociations($controller, $plugin);

        $this->set(compact('controller', 'plugin', 'options'));
    }

    /**
     * Generate column configuration for AjaxTable based on database schema.
     *
     * This method analyzes the table schema to automatically generate column configurations
     * for the AjaxTable. It detects column types, relationships, and sets appropriate
     * defaults for sorting, searching, and rendering.
     *
     * Features:
     * - Automatic column type detection from database schema
     * - Human-readable column titles using Inflector
     * - Relationship detection for foreign keys (_id suffix)
     * - Text editor assignment for the first text column
     * - Nullable column handling with default values
     *
     * @param string $controller The controller name (e.g., 'Users', 'Settings').
     * @param string|null $plugin The plugin name if the controller is in a plugin (optional).
     * @return array<string, array<string, mixed>> Column configuration array with field names as keys.
     */
    protected function getColumns(string $controller, ?string $plugin): array
    {
        $instance = $this->instance($controller, $plugin);
        // Get the database schema for the table
        $schema = $instance->getSchema();
        // Retrieve all column names from the schema
        $schemaColumns = $schema->columns();
        $associations = $instance->associations()->keys();

        // Initialize the columns array and editor flag
        $columns = [];
        $editorSet = false; // Track if we've assigned a text editor to avoid duplicates

        // Iterate through each column in the schema to build configuration
        foreach ($schemaColumns as $column) {
            // Get the database column type (e.g., 'string', 'text', 'integer', etc.)
            $colType = $schema->getColumnType($column);

            // Build the base column configuration with common defaults
            $colConfig = [
                'field' => $column, // Database field name
                'title' => Inflector::humanize($column), // Human-readable title (e.g., 'user_id' -> 'User Id')
                'type' => $colType, // Database column type
                'sortable' => true, // Enable sorting by default
                'searchable' => true, // Enable searching by default
                'visible' => true, // Show column by default
                'render' => '', // Custom render function (empty by default)
                'default' => $schema->isNullable($column) ? '-' : '', // Default value for nullable columns
            ];

            // Assign text editor to the first text column encountered
            // This ensures at least one column has rich text editing capabilities
            if (!$editorSet && in_array($colType, ['text'])) {
                $colConfig['type'] = 'text'; // Ensure type is set to 'text' for editor
                $editorSet = true; // Mark that we've assigned an editor
            }

            // Detect belongsTo relationships by checking for '_id' suffix
            // This is a common naming convention for foreign key columns
            if (substr($column, -3) === '_id') {
                $relatedModel = substr($column, 0, -3); // Remove _id suffix
                $relatedModel = Inflector::classify($relatedModel);
                $associatedModel = $this->instance(Inflector::pluralize($relatedModel), $plugin);
                $colConfig['type'] = 'belongsTo'; // Override type to indicate relationship
                $colConfig['route'] = Router::url(['plugin' => $plugin, 'controller' => $associatedModel->getAlias(), 'action' => 'view'], true);
                $colConfig['displayField'] = $associatedModel->getDisplayField();
            }

            // Add the column configuration to the columns array
            $columns[$column] = $colConfig;
        }

        return $columns;
    }

    /**
     * Returns the association keys for a given controller and plugin.
     *
     * This method retrieves an array of association names (e.g., ['Users', 'Tags'])
     * for the specified table/controller. It is useful for building AjaxTable columns,
     * generating contain() queries, and for dynamic UI features that need to know
     * which related models are available for a given entity.
     *
     * @param string $controller The controller/model name (e.g., 'Users', 'Activities').
     * @param string|null $plugin The plugin name, if any.
     * @return array<int, string> List of association keys for the table (e.g., ['Users', 'Tags']).
     */
    public function getAssociations(string $controller, ?string $plugin): array
    {
        $instance = $this->instance($controller, $plugin);

        return $instance->associations()->keys();
    }

    /**
     * Get a table instance from the registry based on controller name and optional plugin.
     *
     * This method resolves table instances for both main application controllers and
     * plugin controllers. It handles the CakePHP naming convention where plugin tables
     * are prefixed with the plugin name followed by a dot.
     *
     * Examples:
     * - instance('Users') returns the Users table from the main app
     * - instance('Users', 'Admin') returns the Users table from the Admin plugin
     * - instance('BlogPosts', 'Blog') returns the BlogPosts table from the Blog plugin
     *
     * @param string $controller The controller name (e.g., 'Users', 'Settings').
     * @param string|null $plugin The plugin name if the controller is in a plugin (optional).
     * @return \Cake\ORM\Table The resolved table instance from the registry.
     */
    protected function instance(string $controller, ?string $plugin = null): Table
    {
        // Build the full table name using CakePHP's plugin naming convention
        // If plugin is provided, prefix the controller name with "plugin."
        // Otherwise, use the controller name as-is for main app tables
        $controller = $plugin ? $plugin . '.' . $controller : $controller;

        // Retrieve the table instance from CakePHP's table registry
        // This ensures we get the correct table instance (main app or plugin)
        return TableRegistry::getTableLocator()->get($controller);
    }
}
