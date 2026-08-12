<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUserSettings extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('user_settings', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('key', 'string', [
            'default' => null,
            'limit' => 128,
            'null' => false,
        ]);
        $table->addColumn('value', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex(['user_id', 'key'], [
            'name' => 'USER_SETTINGS_USER_KEY',
            'unique' => true,
        ]);
        $table->addForeignKey(['user_id'], 'users', ['id'], [
            'update' => 'CASCADE',
            'delete' => 'CASCADE',
        ]);
        $table->create();
    }
}
