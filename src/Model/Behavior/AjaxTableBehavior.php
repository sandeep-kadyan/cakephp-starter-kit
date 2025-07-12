<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\ORM\Behavior;

/**
 * AjaxTableBehavior
 *
 * This behavior provides support for processing AJAX-based table data requests, including
 * searching, sorting, pagination, and caching of results for improved performance.
 *
 * Caching can be enabled or disabled via the Setting.ajaxTableCache config value.
 * Cache is automatically invalidated when a new entry is added to the table.
 *
 * @package App\Model\Behavior
 */
class AjaxTableBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'defaultOrder' => ['id' => 'DESC'],
        'searchableFields' => [],
        'pageSize' => 10,
        'page' => 1,
    ];

    /**
     * Process AjaxTable request and return formatted response.
     *
     * @param array<string, mixed> $params The request parameters for AjaxTable (search, sort, page, etc.)
     * @return array<string, mixed> The formatted response for AjaxTable
     */
    public function processAjaxTable(array $params): array
    {
        // Merge provided params with default config
        $config = $this->getConfig();
        $params = array_merge($config, $params);
        $page = (int)$params['page'];
        $pageSize = (int)$params['pageSize'];
        $searchableFields = $params['searchableFields'];
        $search = $params['search'] ?? '';

        // Check if caching is enabled in config
        $cacheEnabled = Configure::read('Setting.ajaxTableCache') ?? false;

        // Generate a cache key based on table name and params
        $cacheKey = 'ajaxtable_' . $this->_table->getAlias() . '_' . md5(json_encode($params));
        $cacheConfig = 'default';

        // Try to read from cache if enabled
        if ($cacheEnabled) {
            $cached = Cache::read($cacheKey, $cacheConfig) ?? [];
            if ($cached) {
                // Return cached result if available
                return $cached;
            }
        }

        // Build the query for the table
        $query = $this->_table->find();
        $associations = $this->_table->associations()->keys();

        // Add associations if we have related models
        if (!empty($associations)) {
            $query->contain($associations);
        }

        // Get the table schema and use all columns if no searchable fields specified
        $schema = $this->_table->getSchema();
        $searchableFields = $searchableFields ?: $schema->columns();

        // Apply search conditions if search term is provided
        if (!empty($search) && !empty($searchableFields)) {
            $searchConditions = [];
            foreach ($searchableFields as $field) {
                $searchConditions[] = [$field . ' LIKE' => '%' . $search . '%'];
            }
            if (!empty($searchConditions)) {
                $query->where(['OR' => $searchConditions]);
            }
        }

        // Apply sorting if specified, otherwise use default order
        if (isset($params['sort']) && isset($params['direction'])) {
            $orderField = $params['sort'];
            $orderDirection = $params['direction'];
            // Always qualify with table alias to avoid ambiguity
            $query->orderBy([$this->_table->getAlias() . '.' . $orderField => $orderDirection]);
        } else {
            // Apply default ordering, also qualified
            $query->orderBy([$this->_table->getAlias() . '.id' => 'DESC']);
        }

        // Calculate pagination offsets
        if (isset($params['page']) && isset($params['pageSize'])) {
            $params['start'] = ($params['page'] - 1) * $params['pageSize'];
            $params['length'] = $params['pageSize'];
        }

        // Get total records before pagination
        $totalRecords = $query->count();

        // Apply pagination
        $start = (int)($params['start'] ?? 0);
        $length = (int)($params['length'] ?? $config['pageSize']);
        $totalPages = (int)ceil($totalRecords / $pageSize);
        $startRecord = $totalRecords > 0 ? (($page - 1) * $pageSize) + 1 : 0;
        $endRecord = min($page * $pageSize, $totalRecords);

        $query->limit($length)->offset($start);

        // Execute query and fetch results
        $data = $query->all()->toArray();

        // Prepare the result array
        $result = [
            'draw' => (int)($params['draw'] ?? 1),
            'totalRecords' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'pageSize' => $pageSize,
            'results' => $data,
        ];

        // Write to cache if enabled
        if ($cacheEnabled) {
            Cache::write($cacheKey, $result, $cacheConfig);
        }

        return $result;
    }

    /**
     * Get AjaxTable configuration for a table.
     *
     * @param array<int, string> $searchableFields Fields that can be searched
     * @param array<int, string> $sortableFields Fields that can be sorted (indexed by column position)
     * @param array<string, string> $defaultOrder Default ordering
     * @return array<string, mixed> AjaxTable configuration array
     */
    public function getAjaxTableConfig(array $searchableFields = [], array $sortableFields = [], array $defaultOrder = []): array
    {
        // Return configuration for AjaxTable
        return [
            'searchableFields' => $searchableFields,
            'sortableFields' => $sortableFields,
            'defaultOrder' => $defaultOrder ?: ['id' => 'DESC'],
        ];
    }
}
