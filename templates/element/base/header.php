<?php

/**
 * @var \App\View\AppView $this
 */
?>
<header class="border-b border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
    <div class="container mx-auto px-6">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <?= $this->Html->link(
                    $this->Html->image('cake.logo.svg', ['alt' => 'CakePHP', 'class' => 'h-8 w-auto invert dark:invert-0']) .
                    '<span class="ml-2 text-xl font-bold text-neutral-900 dark:text-neutral-100">CakePHP SaaS</span>',
                    '/',
                    ['class' => 'flex items-center gap-2', 'escape' => false]
                ) ?>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-1">
                <?= $this->Html->link('Home', '/', [
                    'class' => 'px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors duration-200',
                ]) ?>
                <?= $this->Html->link('Features', '/#features', [
                    'class' => 'px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors duration-200',
                ]) ?>
                <?= $this->Html->link('Docs', 'https://book.cakephp.org/', [
                    'class' => 'px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors duration-200',
                    'target' => '_blank',
                ]) ?>
            </nav>

            <!-- Right Side -->
            <div class="flex items-center gap-3">
                <?= $this->element('base/theme') ?>
                <?php if (isset($this->Identity) && $this->Identity->isLoggedIn()): ?>
                    <?= $this->Html->link(
                        '<span class="material-icons text-sm mr-2">dashboard</span> Dashboard',
                        '/pages/dashboard',
                        [
                            'class' => 'hidden sm:inline-flex items-center px-4 py-2 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 rounded-lg text-sm font-medium hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors duration-200',
                            'escape' => false,
                        ]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<span class="material-icons text-sm">logout</span>',
                        ['controller' => 'Users', 'action' => 'logout'],
                        [
                            'class' => 'inline-flex items-center justify-center w-10 h-10 text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors duration-200',
                            'style' => 'border:none;cursor:pointer;',
                            'escape' => false,
                            'title' => 'Logout',
                            'confirm' => __('Are you sure you want to logout?'),
                        ]
                    ) ?>
                <?php else: ?>
                    <?= $this->Html->link(
                        'Log In',
                        '/login',
                        [
                            'class' => 'inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-all duration-200',
                        ]
                    ) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
