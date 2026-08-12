<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class SmokeLoginTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = ['app.Users', 'app.Activities'];

    public function testLoginPageRenders(): void
    {
        $this->get('/login');
        $this->assertResponseOk();
        $this->assertResponseContains('Username or Email');
    }

    public function testVerifyOtpPageRequiresPendingLogin(): void
    {
        $this->get('/verify-otp');
        $this->assertRedirect(['controller' => 'Users', 'action' => 'login']);
    }

    public function testLoginByUsername(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->post('/login', ['email' => 'admin', 'password' => 'password']);
        $this->assertRedirect(['controller' => 'Pages', 'action' => 'dashboard']);
    }

    public function testLoginByEmail(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'password']);
        $this->assertRedirect(['controller' => 'Pages', 'action' => 'dashboard']);
    }

    public function testLoginFailsWithWrongPassword(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->post('/login', ['email' => 'admin', 'password' => 'wrongpassword']);
        $this->assertResponseOk();
        $this->assertResponseContains('Invalid username or password');
    }
}
