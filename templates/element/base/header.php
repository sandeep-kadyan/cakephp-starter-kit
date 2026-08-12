<?php

/**
 * @var \App\View\AppView $this
 */
?>
<header class="bg-transparent">
    <div class="container mx-auto px-6">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <?= $this->Html->link(
                    $this->Html->image('cake.logo.svg', ['alt' => 'CakePHP', 'class' => 'h-8 w-auto invert dark:invert-0']),
                    '/',
                    ['class' => 'flex items-center gap-2', 'escape' => false]
                ) ?>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-1">
                <?= $this->Html->link('Home', '/', [
                    'class' => 'px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-colors duration-200',
                ]) ?>
                <?= $this->Html->link('Features', '/#features', [
                    'class' => 'px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-colors duration-200',
                ]) ?>
                <?= $this->Html->link('Docs', 'https://book.cakephp.org/', [
                    'class' => 'px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-colors duration-200',
                    'target' => '_blank',
                ]) ?>
            </nav>

            <!-- Right Side -->
            <div class="flex items-center gap-3">
                <?= $this->element('base/theme') ?>
                <?php if ($this->Identity->isLoggedIn()): ?>
                    <?= $this->Html->link(
                        '<i data-lucide="layout-dashboard" class="text-sm mr-2"></i> Dashboard',
                        '/pages/dashboard',
                        [
                            'class' => 'hidden sm:inline-flex items-center px-4 py-2 bg-secondary text-secondary-foreground rounded-lg text-sm font-medium hover:bg-accent hover:text-accent-foreground transition-colors duration-200',
                            'escape' => false,
                        ]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i data-lucide="log-out" class="text-sm"></i>',
                        ['controller' => 'Users', 'action' => 'logout'],
                        [
                            'class' => 'inline-flex items-center justify-center w-10 h-10 text-muted-foreground hover:text-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-colors duration-200',
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
                            'class' => 'inline-flex items-center px-4 py-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded-lg text-sm font-medium transition-all duration-200',
                        ]
                    ) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
