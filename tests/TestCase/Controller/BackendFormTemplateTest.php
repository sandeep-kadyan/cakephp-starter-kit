<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Guards that backend CRUD add/edit forms use the compact templates while
 * auth pages keep the original form styling.
 */
class BackendFormTemplateTest extends TestCase
{
    use IntegrationTestTrait;

    public array $fixtures = [
        'app.Users',
    ];

    public function testAddFormUsesCompactTemplates(): void
    {
        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->find()->first();
        $this->assertNotNull($user, 'Need a user row to authenticate with');

        $this->session(['Auth' => $user->toArray()]);
        $this->get(['controller' => 'Users', 'action' => 'add']);
        $this->assertResponseOk();
        $body = $this->_getBodyAsString();
        $this->assertStringContainsString('form-field', $body, 'compact grid wrapper missing');
        $this->assertStringNotContainsString('class="mb-4"', $body, 'old auth-style wrapper leaked into CRUD form');
        $this->assertStringContainsString('mb-2.5', $body, 'label bottom spacing missing');
        $this->assertStringContainsString('mt-4', $body, 'space above submit button missing');
    }

    public function testAuthFormsKeepOriginalTemplates(): void
    {
        $this->get(['controller' => 'Users', 'action' => 'login']);

        $this->assertResponseOk();
        $body = $this->_getBodyAsString();
        $this->assertStringContainsString('class="mb-4"', $body, 'auth form wrapper must stay unchanged');
        $this->assertStringNotContainsString('form-field', $body, 'compact grid wrapper leaked into auth form');
    }
}
