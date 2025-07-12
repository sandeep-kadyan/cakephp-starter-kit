<?php
declare(strict_types=1);

namespace App\Test\TestCase\Form;

use App\Form\VerifyForm;
use Cake\TestSuite\TestCase;

/**
 * App\Form\VerifyForm Test Case
 */
class VerifyFormTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Form\VerifyForm
     */
    protected $Verify;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->Verify = new VerifyForm();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Verify);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Form\VerifyForm::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
