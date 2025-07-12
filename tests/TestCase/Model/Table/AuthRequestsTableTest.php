<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AuthRequestsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AuthRequestsTable Test Case
 */
class AuthRequestsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AuthRequestsTable
     */
    protected $AuthRequests;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.AuthRequests',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AuthRequests') ? [] : ['className' => AuthRequestsTable::class];
        $this->AuthRequests = $this->getTableLocator()->get('AuthRequests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AuthRequests);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AuthRequestsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
