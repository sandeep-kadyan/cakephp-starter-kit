<?php
declare(strict_types=1);

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Utility\Text;
use Migrations\BaseSeed;

/**
 * Users seed.
 */
class UsersSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $hasher = new DefaultPasswordHasher();

        $data = [
            [
                'id' => Text::uuid(),
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => $hasher->hash('password'),
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('users')->insert($data)->save();
    }
}
