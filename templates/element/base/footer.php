<?php

/**
 * @var \App\View\AppView $this
 */
?>
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
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?></li>
                    <li><?= $this->Html->link('Pricing', '#', [
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?></li>
                    <li><?= $this->Html->link('Docs', 'https://book.cakephp.org/', [
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                        'target' => '_blank',
                    ]) ?></li>
                </ul>
            </div>
            <div>
                <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-3">Company</h4>
                <ul class="space-y-2 text-sm">
                    <li><?= $this->Html->link('About', '#', [
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?></li>
                    <li><?= $this->Html->link('Blog', '#', [
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?></li>
                    <li><?= $this->Html->link('Careers', '#', [
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?></li>
                </ul>
            </div>
            <div>
                <h4 class="text-neutral-900 dark:text-neutral-100 font-semibold mb-3">Legal</h4>
                <ul class="space-y-2 text-sm">
                    <li><?= $this->Html->link('Privacy', '#', [
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?></li>
                    <li><?= $this->Html->link('Terms', '#', [
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?></li>
                    <li><?= $this->Html->link('Contact', '#', [
                        'class' => 'text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-500 transition-colors',
                    ]) ?></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-neutral-200 dark:border-neutral-800 mt-8 pt-8 text-center text-sm text-neutral-500">
            <p>&copy; <?= date('Y') ?> CakePHP SaaS. All rights reserved.</p>
        </div>
    </div>
</footer>
