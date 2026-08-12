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

        // Auth pages (login, register, OTP, password reset, magic login) keep the
        // original form styling. Backend CRUD add/edit forms use the compact,
        // grid-friendly templates in config/form.php.
        $isAuth = $this->request->getParam('controller') === 'Users'
            && in_array($this->request->getParam('action'), [
                'login',
                'verifyOtp',
                'register',
                'forgotPassword',
                'resetPassword',
                'verify',
            ], true);

        $this->loadHelper('Form', [
            'errorClass' => $isAuth
                ? 'p-2 h-10 w-full rounded-lg bg-background text-foreground placeholder:text-muted-foreground border border-destructive focus:outline-none focus:shadow-none focus:ring-1 focus:ring-destructive'
                : 'h-9 w-full rounded-md bg-background px-3 text-sm text-foreground placeholder:text-muted-foreground border border-destructive focus:outline-none focus:ring-1 focus:ring-destructive',
            'autoSetCustomValidity' => false,
            'templates' => $isAuth ? 'auth_form' : 'form',
        ]);
        $this->loadHelper('Paginator', [
            'templates' => [
                'nextActive' => '<li class="inline-flex"><a rel="next" href="{{url}}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-muted-foreground hover:bg-accent hover:text-accent-foreground rounded-md">{{text}}</a></li>',
                'nextDisabled' => '<li class="inline-flex"><a class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-muted-foreground opacity-40 cursor-not-allowed pointer-events-none">{{text}}</a></li>',
                'prevActive' => '<li class="inline-flex"><a rel="prev" href="{{url}}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-muted-foreground hover:bg-accent hover:text-accent-foreground rounded-md">{{text}}</a></li>',
                'prevDisabled' => '<li class="inline-flex"><a class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-muted-foreground opacity-40 cursor-not-allowed pointer-events-none">{{text}}</a></li>',
                'first' => '<li class="inline-flex"><a href="{{url}}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-muted-foreground hover:bg-accent hover:text-accent-foreground rounded-md">{{text}}</a></li>',
                'last' => '<li class="inline-flex"><a href="{{url}}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-muted-foreground hover:bg-accent hover:text-accent-foreground rounded-md">{{text}}</a></li>',
                'number' => '<li class="inline-flex"><a href="{{url}}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-muted-foreground hover:bg-accent hover:text-accent-foreground rounded-md">{{text}}</a></li>',
                'current' => '<li class="inline-flex"><a class="inline-flex items-center px-3 py-1.5 text-sm font-semibold text-primary-foreground bg-primary rounded-md">{{text}}</a></li>',
                'ellipsis' => '<li class="inline-flex"><span class="px-2 py-1.5 text-sm text-muted-foreground">&hellip;</span></li>',
                'sort' => '<a href="{{url}}" class="hover:text-foreground">{{text}}</a>',
                'sortAsc' => '<a class="text-primary" href="{{url}}">{{text}} &#9650;</a>',
                'sortDesc' => '<a class="text-primary" href="{{url}}">{{text}} &#9660;</a>',
                'sortAscLocked' => '<a class="text-primary" href="{{url}}">{{text}} &#9650;</a>',
                'sortDescLocked' => '<a class="text-primary" href="{{url}}">{{text}} &#9660;</a>',
            ],
        ]);
    }
}
