<?php
declare(strict_types=1);

namespace App\Test\TestCase\Form;

use App\Form\LoginForm;
use Cake\TestSuite\TestCase;

/**
 * App\Form\LoginForm Test Case
 */
class LoginFormTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Form\LoginForm
     */
    protected $Login;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->Login = new LoginForm();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Login);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Form\LoginForm::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
