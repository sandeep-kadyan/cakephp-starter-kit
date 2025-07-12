<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ActivitiesFixture
 */
class ActivitiesFixture extends TestFixture
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
                'id' => 'a0996fd6-19ab-4d05-a7e0-4064eb9815b4',
                'user_id' => '765e7089-8dfb-42a9-9158-ae20a842f717',
                'url' => 'Lorem ipsum dolor sit amet',
                'browser' => 'Lorem ipsum dolor sit amet',
                'os' => 'Lorem ipsum dolor sit amet',
                'device' => 'Lorem ipsum dolor sit amet',
                'ip_address' => 'Lorem ipsum dolor sit amet',
                'location' => 'Lorem ipsum dolor sit amet',
                'user_agent' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2025-07-06 02:38:19',
                'modified' => '2025-07-06 02:38:19',
            ],
        ];
        parent::init();
    }
}
