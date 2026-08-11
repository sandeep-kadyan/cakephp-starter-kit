<?php

use Cake\I18n\I18n;

/**
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html lang="<?= I18n::getLocale() ?>">

<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>

    <?= $this->Html->meta('icon') ?>

    <?= $this->Vite->assets(['js/app.js', 'css/app.css']) ?>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

</head>

<body class="bg-white dark:bg-neutral-950 text-neutral-900 dark:text-neutral-100 min-h-screen flex flex-col transition-colors duration-300">
    <!-- Header -->
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
                                'class' => 'inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-sm font-medium transition-all duration-200',
                            ]
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        <?= $this->fetch('content') ?>
    </main>

    <!-- Footer -->
    <footer class="border-t border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 mt-auto">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-neutral-600 dark:text-neutral-400">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <?= $this->Html->image('cake.logo.svg', ['alt' => 'CakePHP', 'class' => 'h-6 w-auto invert dark:invert-0']) ?>
                        <span class="text-neutral-900 dark:text-neutral-100 font-bold text-lg">CakePHP SaaS</span>
                    </div>
                    <p class="text-sm text-neutral-500 dark:text-neutral-500">
                        A modern starter kit for building SaaS applications on CakePHP.
                    </p>
                </div>
                <div>
                    <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-3">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><?= $this->Html->link('Features', '/#features', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                        ]) ?></li>
                        <li><?= $this->Html->link('Pricing', '#', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                        ]) ?></li>
                        <li><?= $this->Html->link('Docs', 'https://book.cakephp.org/', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                            'target' => '_blank',
                        ]) ?></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-3">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><?= $this->Html->link('About', '#', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                        ]) ?></li>
                        <li><?= $this->Html->link('Blog', '#', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                        ]) ?></li>
                        <li><?= $this->Html->link('Careers', '#', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                        ]) ?></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-3">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><?= $this->Html->link('Privacy', '#', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                        ]) ?></li>
                        <li><?= $this->Html->link('Terms', '#', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                        ]) ?></li>
                        <li><?= $this->Html->link('Contact', '#', [
                            'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition-colors',
                        ]) ?></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-neutral-200 dark:border-neutral-800 mt-8 pt-8 text-center text-sm text-neutral-500">
                <p>&copy; <?= date('Y') ?> CakePHP SaaS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <?= $this->fetch('script') ?>
    <?= $this->Toast->render() ?>
</body>

</html>
