<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AuthRequestsFixture
 */
class AuthRequestsFixture extends TestFixture
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
                'id' => 'fbfadc12-96cb-4976-a4a9-c65b9f986dc5',
                'email' => 'Lorem ipsum dolor sit amet',
                'verification_token' => 'Lorem ipsum dolor sit amet',
                'expires' => '2025-06-24 20:15:54',
                'verified_at' => '2025-06-24 20:15:54',
                'created' => '2025-06-24 20:15:54',
                'modified' => '2025-06-24 20:15:54',
            ],
        ];
        parent::init();
    }
}
