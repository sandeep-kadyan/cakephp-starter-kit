<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\AuthComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\AuthComponent Test Case
 */
class AuthComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\AuthComponent
     */
    protected $Auth;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Auth = new AuthComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Auth);

        parent::tearDown();
    }
}
