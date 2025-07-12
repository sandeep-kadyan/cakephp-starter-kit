<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Core\Plugin;
use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\DateTime;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Exception;

/**
 * Fake Service for generating fake data dynamically based on model schema
 */
class FakeService
{
    /**
     * Default special field patterns
     */
    private const DEFAULT_SPECIAL_PATTERNS = [
        'password',
        'parent_id',
        'image',
        'file',
        '*_at', // timestamp fields
    ];

    /**
     * Data type to special field mapping
     */
    private const DATA_TYPE_SPECIAL_MAPPING = [
        'binary' => 'file',
        'blob' => 'file',
        'image' => 'file',
    ];

    /**
     * Excluded tables
     */
    private const EXCLUDED_TABLES = ['i18n', 'phinxlog', 'sessions'];

    /**
     * Check if model exists and is available
     *
     * @param string $modelName The model name (e.g., 'Users' or 'Plugin.Users')
     * @return bool True if the model Table class can be loaded, false otherwise
     */
    public function isModelAvailable(string $modelName): bool
    {
        try {
            $table = $this->getTable($modelName);

            return $table !== null;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get table instance for a model
     *
     * @param string $modelName The model name (e.g., 'Users' or 'Plugin.Users')
     * @return \Cake\ORM\Table The Table instance for the model
     */
    public function getTable(string $modelName): Table
    {
        return TableRegistry::getTableLocator()->get($modelName);
    }

    /**
     * Get available models from the application and loaded plugins that have both a database table and a Table class.
     *
     * This function dynamically discovers all tables in the default database connection (excluding EXCLUDED_TABLES),
     * and checks if a corresponding Table class exists in the application or any loaded plugin. Only models with both a
     * database table and a Table class are returned. Plugin models are returned as 'Plugin.ModelName'.
     *
     * @return array<string, string> Associative array of table name => model name (e.g., 'users' => 'Users', 'plugin_table' => 'Plugin.PluginTables')
     */
    public function getAvailableModels(): array
    {
        $models = [];
        $tableLocator = TableRegistry::getTableLocator();

        // Get all table names from the default DB connection, excluding migration/session tables
        $connection = ConnectionManager::get('default');
        $schemaCollection = $connection->getSchemaCollection();
        $allTables = array_diff($schemaCollection->listTables(), self::EXCLUDED_TABLES);

        $srcModelPath = ROOT . DS . 'src' . DS . 'Model' . DS . 'Table' . DS;
        $loadedPlugins = Plugin::loaded();

        foreach ($allTables as $tableName) {
            // Convert table name to CakePHP model class name (pluralized, camelized)
            $modelName = Inflector::camelize(Inflector::pluralize($tableName));
            $tableClass = $modelName . 'Table.php';
            $found = false;
            $modelKey = $modelName;

            // Check for Table class in app's src/Model/Table
            if (file_exists($srcModelPath . $tableClass)) {
                $found = true;
            } else {
                // Check for Table class in loaded plugins
                foreach ($loadedPlugins as $plugin) {
                    $pluginPath = Plugin::path($plugin);
                    $pluginTablePath = $pluginPath . 'src' . DS . 'Model' . DS . 'Table' . DS . $tableClass;
                    if (file_exists($pluginTablePath)) {
                        $found = true;
                        $modelKey = $plugin . '.' . $modelName;
                        break;
                    }
                }
            }

            // Try to get the Table instance to ensure the model is loadable and mapped to the correct table
            try {
                $table = $tableLocator->get($modelKey);
                $actualTableName = $table->getTable();
            } catch (Exception $e) {
                // Skip if the Table class cannot be loaded or does not map to a real table
                continue;
            }

            // Only add models that have both a Table class and a DB table
            if ($found) {
                $models[$actualTableName] = $modelKey;
            }
        }

        return $models;
    }

    /**
     * Generate fake data for a model
     *
     * @param string $modelName The model name (e.g., 'Users' or 'Plugin.Users')
     * @param int $count The number of fake entities to generate
     * @param array<string, mixed> $specialFields Custom special fields to override defaults
     * @return array<\Cake\Datasource\EntityInterface> Array of new entity objects with fake data
     */
    public function generateFakeData(string $modelName, int $count, array $specialFields = []): array
    {
        $table = $this->getTable($modelName);
        $schema = $table->getSchema();
        $entities = [];

        for ($i = 0; $i < $count; $i++) {
            $data = $this->generateFakeEntityData($modelName, $schema, $specialFields);
            $entity = $table->newEntity($data);
            $entities[] = $entity;
        }

        return $entities;
    }

    /**
     * Generate fake entity data based on model schema
     *
     * @param string $modelName The model name
     * @param \Cake\Database\Schema\TableSchemaInterface $schema The table schema
     * @param array<string, mixed> $specialFields Custom special fields
     * @return array<string, mixed> Array of fake data for one entity
     */
    private function generateFakeEntityData(string $modelName, TableSchemaInterface $schema, array $specialFields = []): array
    {
        $data = [];
        $columns = $schema->columns();

        foreach ($columns as $column) {
            $columnType = $schema->getColumnType($column);
            $columnInfo = $schema->getColumn($column);

            // Skip auto-generated fields
            if (in_array($column, ['id', 'created', 'modified'])) {
                continue;
            }

            $data[$column] = $this->generateFakeValue($modelName, $column, $columnType, $columnInfo, $specialFields);
        }

        return $data;
    }

    /**
     * Generate fake value based on column type and model
     *
     * @param string $modelName The model name
     * @param string $column The column name
     * @param string $type The column type
     * @param array<string, mixed> $info Column metadata
     * @param array<string, mixed> $specialFields Custom special fields
     * @return mixed The generated fake value
     */
    private function generateFakeValue(string $modelName, string $column, string $type, array $info, array $specialFields = []): mixed
    {
        // Check if field is special
        if ($this->isSpecialField($column, $type, $specialFields)) {
            return $this->generateSpecialFieldValue($modelName, $column, $type, $info);
        }

        // Handle based on column type
        switch ($type) {
            case 'string':
                return $this->generateStringValue($column, $info);
            case 'text':
                return $this->generateTextValue($column);
            case 'integer':
            case 'biginteger':
                return $this->generateIntegerValue($column, $info);
            case 'decimal':
            case 'float':
                return $this->generateFloatValue($column, $info);
            case 'boolean':
                return $this->generateBooleanValue($column);
            case 'datetime':
            case 'timestamp':
                return $this->generateDateTimeValue($column);
            case 'date':
                return $this->generateDateValue($column);
            case 'time':
                return $this->generateTimeValue($column);
            case 'uuid':
                return $this->generateUuidValue($column);
            case 'binary':
            case 'blob':
                return $this->generateBinaryValue($column);
            default:
                return $this->generateDefaultValue($type);
        }
    }

    /**
     * Check if field is special based on patterns and data types
     *
     * @param string $column The column name
     * @param string $type The column type
     * @param array<string, mixed> $customSpecialFields Custom special fields
     * @return bool True if the field is special, false otherwise
     */
    private function isSpecialField(string $column, string $type, array $customSpecialFields = []): bool
    {
        $allSpecialFields = array_merge(self::DEFAULT_SPECIAL_PATTERNS, $customSpecialFields);

        // Check exact matches
        if (in_array($column, $allSpecialFields)) {
            return true;
        }

        // Check pattern matches (like *_at)
        foreach ($allSpecialFields as $pattern) {
            if (str_contains($pattern, '*')) {
                $regex = str_replace('*', '.*', $pattern);
                if (preg_match('/^' . $regex . '$/', $column)) {
                    return true;
                }
            }
        }

        // Check data type special mapping
        if (isset(self::DATA_TYPE_SPECIAL_MAPPING[$type])) {
            return true;
        }

        return false;
    }

    /**
     * Generate a special field value based on model and column
     *
     * @param string $modelName The model name
     * @param string $column The column name
     * @param string $type The column type
     * @param array<string, mixed> $info Column metadata
     * @return mixed The generated special field value
     */
    private function generateSpecialFieldValue(string $modelName, string $column, string $type, array $info): mixed
    {
        // Handle password fields
        if ($column === 'password') {
            return $this->generateRandomPassword();
        }

        // Handle parent_id fields (foreign keys)
        if ($column === 'parent_id' || str_ends_with($column, '_id')) {
            return $this->generateRandomForeignKey($modelName, $column);
        }

        // Handle timestamp fields (*_at)
        if (str_ends_with($column, '_at')) {
            return $this->generateRandomDateTime();
        }

        // Handle file/image fields
        if (in_array($column, ['image', 'file']) || in_array($type, ['binary', 'blob'])) {
            return $this->generateRandomFileData($column, $type);
        }

        // Default special field handling
        return $this->generateDefaultSpecialValue($column, $type);
    }

    /**
     * Generate a fake string value for a column
     *
     * @param string $column The column name
     * @param array<string, mixed> $info Column metadata
     * @return string The generated string value
     */
    private function generateStringValue(string $column, array $info): string
    {
        $maxLength = $info['length'] ?? 255;

        // Generate appropriate content based on column name
        if (str_contains(strtolower($column), 'name') && !str_contains(strtolower($column), 'username')) {
            return $this->generateRandomName($maxLength);
        }

        if (str_contains(strtolower($column), 'email')) {
            return $this->generateRandomEmail();
        }

        if (str_contains(strtolower($column), 'username')) {
            return $this->generateRandomUsername($maxLength);
        }

        if (str_contains(strtolower($column), 'url')) {
            return $this->generateRandomUrl();
        }

        if (str_contains(strtolower($column), 'token')) {
            return $this->generateRandomToken();
        }

        if (str_contains(strtolower($column), 'secret')) {
            return base64_encode(random_bytes(32));
        }

        if (str_contains(strtolower($column), 'recovery_codes')) {
            $codes = [];
            for ($i = 0; $i < 8; $i++) {
                $codes[] = strtoupper(bin2hex(random_bytes(4)));
            }

            return json_encode($codes);
        }

        // Default string generation
        $words = ['Lorem', 'Ipsum', 'Dolor', 'Sit', 'Amet', 'Consectetur', 'Adipiscing', 'Elit'];
        $word = $words[array_rand($words)];

        return substr($word . '_' . uniqid(), 0, $maxLength);
    }

    /**
     * Generate a fake text value for a column
     *
     * @param string $column The column name
     * @return string The generated text value
     */
    private function generateTextValue(string $column): string
    {
        $loremTexts = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        ];

        return $loremTexts[array_rand($loremTexts)];
    }

    /**
     * Generate a fake integer value for a column
     *
     * @param string $column The column name
     * @param array<string, mixed> $info Column metadata
     * @return int The generated integer value
     */
    private function generateIntegerValue(string $column, array $info): int
    {
        $min = $info['unsigned'] ? 0 : -1000;
        $max = 1000;

        return rand($min, $max);
    }

    /**
     * Generate a fake float value for a column
     *
     * @param string $column The column name
     * @param array<string, mixed> $info Column metadata
     * @return float The generated float value
     */
    private function generateFloatValue(string $column, array $info): float
    {
        return round(rand(1, 100) / rand(1, 10), 2);
    }

    /**
     * Generate a fake boolean value for a column
     *
     * @param string $column The column name
     * @return bool The generated boolean value
     */
    private function generateBooleanValue(string $column): bool
    {
        return (bool)rand(0, 1);
    }

    /**
     * Generate a fake DateTime value for a column
     *
     * @param string $column The column name
     * @return \Cake\I18n\DateTime The generated DateTime value
     */
    private function generateDateTimeValue(string $column): DateTime
    {
        $timestamp = time() - rand(0, 365 * 24 * 60 * 60); // Random time in last year

        return new DateTime('@' . $timestamp);
    }

    /**
     * Generate a fake date value for a column
     *
     * @param string $column The column name
     * @return \Cake\I18n\DateTime The generated date value
     */
    private function generateDateValue(string $column): DateTime
    {
        $timestamp = time() - rand(0, 365 * 24 * 60 * 60);

        return new DateTime('@' . $timestamp);
    }

    /**
     * Generate a fake time value for a column
     *
     * @param string $column The column name
     * @return \Cake\I18n\DateTime The generated time value
     */
    private function generateTimeValue(string $column): DateTime
    {
        $hour = rand(0, 23);
        $minute = rand(0, 59);
        $second = rand(0, 59);

        return new DateTime("{$hour}:{$minute}:{$second}");
    }

    /**
     * Generate a fake UUID value for a column
     *
     * @param string $column The column name
     * @return string The generated UUID value
     */
    private function generateUuidValue(string $column): string
    {
        return Text::uuid();
    }

    /**
     * Generate a fake binary value for a column
     *
     * @param string $column The column name
     * @return string The generated binary value
     */
    private function generateBinaryValue(string $column): string
    {
        return $this->generateRandomFileData($column, 'binary');
    }

    /**
     * Generate a random name
     *
     * @param int $maxLength The maximum length
     * @return string The generated name
     */
    private function generateRandomName(int $maxLength = 255): string
    {
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Maria'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];

        $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];

        return substr($name, 0, $maxLength);
    }

    /**
     * Generate a random username
     *
     * @param int $maxLength The maximum length
     * @return string The generated username
     */
    private function generateRandomUsername(int $maxLength = 255): string
    {
        $prefixes = ['fake_user', 'fake_admin', 'fake_test', 'fake_demo', 'fake_guest'];
        $suffixes = ['123', '2024', 'test', 'demo', 'user'];

        $username = $prefixes[array_rand($prefixes)] . '_' . $suffixes[array_rand($suffixes)] . '_' . rand(1, 999);

        return substr($username, 0, $maxLength);
    }

    /**
     * Generate a random email address
     *
     * @return string The generated email address
     */
    private function generateRandomEmail(): string
    {
        $domains = ['example.com', 'test.com', 'demo.com'];
        $prefixes = ['fake_user', 'fake_test', 'fake_demo'];

        return $prefixes[array_rand($prefixes)] . rand(1, 999) . '@' . $domains[array_rand($domains)];
    }

    /**
     * Generate a random password
     *
     * @return string The generated password
     */
    private function generateRandomPassword(): string
    {
        return password_hash('password123', PASSWORD_DEFAULT);
    }

    /**
     * Generate a random token
     *
     * @return string The generated token
     */
    private function generateRandomToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Generate a random URL
     *
     * @return string The generated URL
     */
    private function generateRandomUrl(): string
    {
        $paths = ['/dashboard', '/profile', '/settings', '/users', '/activities', '/reports', '/admin'];
        $params = ['page=' . rand(1, 10), 'sort=name', 'filter=active', 'view=list'];

        $url = 'https://example.com' . $paths[array_rand($paths)];
        if (rand(0, 1)) {
            $url .= '?' . $params[array_rand($params)];
        }

        return $url;
    }

    /**
     * Generate a random foreign key value for a column
     *
     * @param string $modelName The model name
     * @param string $column The column name
     * @return string|null The generated foreign key value or null
     */
    private function generateRandomForeignKey(string $modelName, string $column): ?string
    {
        // Extract the related table name from the foreign key
        $relatedTable = str_replace('_id', '', $column);
        if ($relatedTable === 'parent') {
            // For parent_id, try to find existing records in the same table
            $relatedTable = $modelName;
        }

        // Try to get an existing record ID, or return null if none exist
        try {
            $table = TableRegistry::getTableLocator()->get($relatedTable);
            $existingRecord = $table->find()->select(['id'])->first();
            if ($existingRecord) {
                return $existingRecord->id;
            }
        } catch (Exception $e) {
            // If there's an error, return null
        }

        // Return null if no records exist or there's an error
        return null;
    }

    /**
     * Generate random file data for a column
     *
     * @param string $column The column name
     * @param string $type The column type
     * @return string The generated file data
     */
    private function generateRandomFileData(string $column, string $type): string
    {
        // For binary/blob fields, generate some dummy binary data
        if ($type === 'binary' || $type === 'blob') {
            return base64_encode(random_bytes(rand(100, 500)));
        }

        // For file/image fields, generate a fake file path or URL
        $extensions = ['jpg', 'png', 'gif', 'pdf', 'doc', 'txt'];
        $extension = $extensions[array_rand($extensions)];

        if (str_contains($column, 'image')) {
            return 'uploads/images/fake_image_' . uniqid() . '.' . $extension;
        }

        return 'uploads/files/fake_file_' . uniqid() . '.' . $extension;
    }

    /**
     * Generate a default special value for a column
     *
     * @param string $column The column name
     * @param string $type The column type
     * @return mixed The generated default special value
     */
    private function generateDefaultSpecialValue(string $column, string $type): mixed
    {
        // Default handling for special fields
        switch ($type) {
            case 'string':
                return $this->generateRandomToken();
            case 'text':
                return $this->generateRandomToken();
            case 'integer':
                return rand(1, 1000);
            case 'boolean':
                return (bool)rand(0, 1);
            case 'datetime':
                return $this->generateRandomDateTime();
            default:
                return null;
        }
    }

    /**
     * Generate a random DateTime value
     *
     * @return \Cake\I18n\DateTime The generated DateTime value
     */
    private function generateRandomDateTime(): DateTime
    {
        $timestamp = time() - rand(0, 365 * 24 * 60 * 60); // Random time in last year

        return new DateTime('@' . $timestamp);
    }

    /**
     * Generate a default value for a column type
     *
     * @param string $type The column type
     * @return mixed The generated default value
     */
    private function generateDefaultValue(string $type): mixed
    {
        switch ($type) {
            case 'string':
                return 'default_value';
            case 'integer':
                return 0;
            case 'boolean':
                return false;
            case 'datetime':
                return new DateTime();
            default:
                return null;
        }
    }
}
