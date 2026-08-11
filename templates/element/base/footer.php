<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
?>
<footer class="border-t border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 mt-auto">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-6 lg:gap-8">
            <!-- Brand -->
            <div class="lg:col-span-2 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <?= $this->Html->image('cake-logo.png', [
                            'alt' => 'CakePHP',
                            'class' => 'h-10 w-auto',
                        ]) ?>
                        <span class="text-neutral-900 dark:text-neutral-100 font-bold text-3xl tracking-tight">CAKEPHP</span>
                    </div>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-5 max-w-md">
                        A modern starter kit for building SaaS applications on CakePHP.
                        Get up and running with authentication, authorization, dashboards, and more.
                    </p>
                </div>
                <div class="flex gap-3">
                    <?= $this->Html->link(
                        '<span class="material-icons text-sm">x</span>',
                        '#',
                        [
                            'class' => 'flex items-center justify-center w-10 h-10 text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-all duration-200',
                            'escape' => false,
                            'title' => 'X',
                        ]
                    ) ?>
                    <?= $this->Html->link(
                        '<span class="material-icons text-sm">facebook</span>',
                        '#',
                        [
                            'class' => 'flex items-center justify-center w-10 h-10 text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-all duration-200',
                            'escape' => false,
                            'title' => 'Facebook',
                        ]
                    ) ?>
                    <?= $this->Html->link(
                        '<span class="material-icons text-sm">linkedin</span>',
                        '#',
                        [
                            'class' => 'flex items-center justify-center w-10 h-10 text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-all duration-200',
                            'escape' => false,
                            'title' => 'LinkedIn',
                        ]
                    ) ?>
                    <?= $this->Html->link(
                        '<span class="material-icons text-sm">github</span>',
                        '#',
                        [
                            'class' => 'flex items-center justify-center w-10 h-10 text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-all duration-200',
                            'escape' => false,
                            'title' => 'GitHub',
                        ]
                    ) ?>
                </div>
            </div>

            <!-- Product -->
            <div>
                <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-4 text-sm uppercase tracking-wider">Product</h4>
                <ul class="space-y-3">
                    <?= $this->Html->link('Features', '/#features', [
                        'class' => 'text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?>
                    <?= $this->Html->link('Pricing', '#', [
                        'class' => 'text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Changelog</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Roadmap</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Enterprise</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-4 text-sm uppercase tracking-wider">Resources</h4>
                <ul class="space-y-3">
                    <?= $this->Html->link('Documentation', 'https://book.cakephp.org/', [
                        'class' => 'text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                        'target' => '_blank',
                    ]) ?>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Blog</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Community</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Support</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">API Reference</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-4 text-sm uppercase tracking-wider">Company</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">About</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Careers</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Partners</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-4 text-sm uppercase tracking-wider">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Cookie Policy</a></li>
                    <li><a href="#" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">Security</a></li>
                </ul>
            </div>
        </div>

        <!-- Big Text Logo -->
        <div class="-mx-6 sm:-mx-0 relative mb-4 overflow-hidden -z-10">
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <span class="text-neutral-100 dark:text-neutral-950/5 select-none font-black text-[6rem] sm:text-[8rem] md:text-[10rem] lg:text-[12rem] leading-none tracking-tighter opacity-10">
                    CAKEPHP
                </span>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-neutral-200 dark:border-neutral-800 mt-8 pt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-neutral-500">
            <div class="flex items-center gap-6 text-xs text-neutral-500 dark:text-neutral-500">
                <p>&copy; <?= date('Y') ?> CakePHP SaaS. All rights reserved.</p>
                <?php $loadTime = round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2); ?>
                <?php $memUsage = round(memory_get_usage() / 1024 / 1024, 2); ?>
                <span class="flex items-center gap-1">
                    <span class="material-icons text-xs">speed</span>
                    Speed: <?= $loadTime ?>ms
                </span>
                <span class="flex items-center gap-1">
                    <span class="material-icons text-xs">memory</span>
                    Memory: <?= $memUsage ?>MB
                </span>
            </div>
            <p class="sm:text-right">
                Made with &hearts; using CakePHP <?= h(Configure::version()) ?>
            </p>
        </div>
    </div>
</footer>
