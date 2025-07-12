<?php
declare(strict_types=1);

namespace App\Test\TestCase\View\Helper;

use App\View\Helper\AjaxTableHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\AjaxTableHelper Test Case
 */
class AjaxTableHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\View\Helper\AjaxTableHelper
     */
    protected $AjaxTable;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->AjaxTable = new AjaxTableHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AjaxTable);

        parent::tearDown();
    }
}
