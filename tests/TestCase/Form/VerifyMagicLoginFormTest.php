<?php
declare(strict_types=1);

namespace App\Test\TestCase\Form;

use App\Form\VerifyMagicLoginForm;
use Cake\TestSuite\TestCase;

/**
 * App\Form\VerifyMagicLoginForm Test Case
 */
class VerifyMagicLoginFormTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Form\VerifyMagicLoginForm
     */
    protected $VerifyMagicLogin;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->VerifyMagicLogin = new VerifyMagicLoginForm();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->VerifyMagicLogin);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Form\VerifyMagicLoginForm::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
