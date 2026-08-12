<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => '673eb365-b05d-4d4b-b6b0-0228a1fa6f1c',
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => '$2y$12$Hc1.36a4gnK9DpLr/Db6fORWc95Ft4a9FK8ltKPsDpp69RZWSKGzq',
                'email_verified_at' => '2025-06-22 19:40:12',
                'remember_token' => null,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'last_active_at' => '2025-06-22 19:40:12',
                'created' => '2025-06-22 19:40:12',
                'modified' => '2025-06-22 19:40:12',
            ],
        ];
        parent::init();
    }
}
