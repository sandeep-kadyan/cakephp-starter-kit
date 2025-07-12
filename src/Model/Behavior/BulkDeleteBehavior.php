<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * BulkDeleteBehavior
 *
 * Provides a method to delete multiple records by their IDs in bulk.
 * Useful for implementing bulk delete actions in admin panels or APIs.
 *
 * @package App\Model\Behavior
 */
class BulkDeleteBehavior extends Behavior
{
    /**
     * Delete multiple records by their primary key IDs.
     *
     * @param array $ids Array of primary key IDs to delete.
     * @return int|false Number of rows deleted, or false on failure or if $ids is empty.
     */
    public function bulkDelete(array $ids): int|false
    {
        if (empty($ids)) {
            return false;
        }

        return $this->_table->deleteAll(['id IN' => $ids]);
    }
}
