<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/5/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadHelper('Vite');
        $this->loadHelper('Toast');
        $this->loadHelper('Authentication.Identity');
        $this->loadHelper('Menu');
        $this->loadHelper('Form', [
            'errorClass' => 'p-2 h-10 w-full rounded-lg dark:bg-transparent dark:text-white dark:placeholder-orange-400 border border-orange-700 dark:border-orange-700 focus:outline-none focus:shadow-none focus:ring-1 focus:ring-orange dark:focus:ring-orange',
            'autoSetCustomValidity' => false,
            'templates' => 'form',
        ]);
    }
}
