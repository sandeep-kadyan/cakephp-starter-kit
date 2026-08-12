<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUserSessions extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('user_sessions', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('session_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('ip_address', 'string', [
            'default' => null,
            'limit' => 45,
            'null' => true,
        ]);
        $table->addColumn('user_agent', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('browser', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('os', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('device', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('location', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('last_activity', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('is_active', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->addColumn('expired_at', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addIndex(['user_id', 'created'], [
            'name' => 'USER_SESSIONS_USER_CREATED',
            'unique' => false,
        ]);
        $table->addIndex(['session_id'], [
            'name' => 'USER_SESSIONS_SESSION_ID',
            'unique' => false,
        ]);
        $table->addForeignKey(['user_id'], 'users', ['id'], [
            'update' => 'CASCADE',
            'delete' => 'CASCADE',
        ]);
        $table->create();
    }
}
