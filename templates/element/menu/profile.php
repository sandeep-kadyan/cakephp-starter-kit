<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="relative flex align-middle" x-data="{ open: false }">
    <button type="button" aria-label="User menu" @click="open = !open" class="rounded-full w-8 h-8 bg-neutral-200 dark:bg-neutral-700 flex items-center align-middle justify-center">
        <?php $userName = $this->Identity->get('name'); ?>
        <span class="text-lg font-bold text-neutral-700 dark:text-white">
            <?= strtoupper(mb_substr($userName, 0, 1)) ?>
        </span>
    </button>
    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-10 w-72 z-99 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded shadow-lg z-50">
        <div class="flex items-center gap-3 p-4 bg-neutral-100 dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700 dark:text-white">
            <div class="w-12 h-12 rounded-full bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center align-middle">
                <span class="text-2xl font-bold text-neutral-700 dark:text-white">
                    <?= strtoupper(mb_substr($userName, 0, 1)) ?>
                </span>
            </div>
            <div>
                <div class="font-semibold"><?= h($userName) ?></div>
                <div class="text-xs overflow-wrap"><?= h($this->Identity->get('email')) ?></div>
            </div>
        </div>
        <?= $this->cell('Menu::display', ['profile']) ?>
    </div>
</div>
