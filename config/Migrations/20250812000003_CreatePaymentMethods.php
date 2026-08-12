<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePaymentMethods extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('payment_methods', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('provider', 'string', [
            'default' => null,
            'limit' => 64,
            'null' => false,
        ]);
        $table->addColumn('identifier', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'comment' => 'Opaque external reference (e.g. Stripe customer/setup id). Never store PAN here.',
        ]);
        $table->addColumn('details', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('status', 'string', [
            'default' => 'active',
            'limit' => 32,
            'null' => false,
        ]);
        $table->addColumn('is_default', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex(['user_id'], [
            'name' => 'PAYMENT_METHODS_USER',
            'unique' => false,
        ]);
        $table->addIndex(['user_id', 'is_default'], [
            'name' => 'PAYMENT_METHODS_USER_DEFAULT',
            'unique' => true,
        ]);
        $table->addForeignKey(['user_id'], 'users', ['id'], [
            'update' => 'CASCADE',
            'delete' => 'CASCADE',
        ]);
        $table->create();
    }
}
