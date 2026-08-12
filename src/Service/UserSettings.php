<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Table\UserSettingsTable;
use Cake\Datasource\FactoryLocator;

/**
 * Small helper around the user_settings key/value store.
 */
class UserSettings
{
    protected UserSettingsTable $table;

    public function __construct()
    {
        $this->table = FactoryLocator::get('Table')->get('UserSettings');
    }

    /**
     * Get a setting value for the given user, with a fallback default.
     */
    public function get(string $userId, string $key, mixed $default = null): mixed
    {
        $value = $this->table->getValue($userId, $key);
        if ($value === null) {
            return $default;
        }

        return $value;
    }

    /**
     * Set a setting value for the given user.
     */
    public function set(string $userId, string $key, mixed $value): bool
    {
        return $this->table->setValue($userId, $key, $value);
    }

    /**
     * Set several settings at once.
     *
     * @param array<string, mixed> $values
     */
    public function setMany(string $userId, array $values): bool
    {
        $ok = true;
        foreach ($values as $key => $value) {
            $ok = $this->set($userId, (string)$key, $value) && $ok;
        }

        return $ok;
    }
}
