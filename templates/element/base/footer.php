<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
?>
<footer class="border-t border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 mt-auto">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-5 lg:gap-8">
            <!-- Brand -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <?= $this->Html->image('cake.logo.svg', ['alt' => 'CakePHP', 'class' => 'h-7 w-auto invert dark:invert-0']) ?>
                    <span class="text-neutral-900 dark:text-neutral-100 font-bold text-xl">CakePHP SaaS</span>
                </div>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4 max-w-md">
                    A modern starter kit for building SaaS applications on CakePHP.
                    Get up and running with authentication, authorization, dashboards, and more.
                </p>
                <div class="flex gap-4">
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

        <!-- Bottom Bar -->
        <div class="border-t border-neutral-200 dark:border-neutral-800 mt-8 pt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-neutral-500">
            <p>&copy; <?= date('Y') ?> CakePHP SaaS. All rights reserved.</p>
            <p class="sm:text-right">
                Made with ❤️ using CakePHP <?= h(Configure::version()) ?>
            </p>
        </div>
    </div>
</footer>
