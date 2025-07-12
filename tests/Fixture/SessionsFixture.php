<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SessionsFixture
 */
class SessionsFixture extends TestFixture
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
                'id' => '',
                'data' => 'Lorem ipsum dolor sit amet',
                'expires' => 1,
                'created' => '2025-06-22 19:40:40',
                'modified' => '2025-06-22 19:40:40',
            ],
        ];
        parent::init();
    }
}
