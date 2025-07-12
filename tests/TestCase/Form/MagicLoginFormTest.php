<?php
declare(strict_types=1);

namespace App\Test\TestCase\Form;

use App\Form\MagicLoginForm;
use Cake\TestSuite\TestCase;

/**
 * App\Form\MagicLoginForm Test Case
 */
class MagicLoginFormTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Form\MagicLoginForm
     */
    protected $MagicLogin;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->MagicLogin = new MagicLoginForm();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MagicLogin);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Form\MagicLoginForm::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
