<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="relative flex items-center" x-data="{ open: false }">
    <button type="button" aria-label="User menu" @click="open = !open" class="rounded-full w-8 h-8 bg-secondary text-secondary-foreground flex items-center justify-center hover:bg-accent hover:text-accent-foreground focus:outline-none">
        <?php $userName = $this->Identity->get('name'); ?>
        <span class="text-lg font-bold">
            <?= strtoupper(mb_substr($userName, 0, 1)) ?>
        </span>
    </button>
    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-10 w-96 z-50 bg-popover text-popover-foreground border border-border rounded-lg shadow-lg">
        <div class="flex items-center gap-3 p-4 bg-muted border-b border-border">
            <div class="w-12 h-12 rounded-full bg-secondary text-secondary-foreground flex items-center justify-center">
                <span class="text-2xl font-bold">
                    <?= strtoupper(mb_substr($userName, 0, 1)) ?>
                </span>
            </div>
            <div class="min-w-0">
                <div class="font-semibold truncate"><?= h($userName) ?></div>
                <div class="text-xs text-muted-foreground break-words"><?= h($this->Identity->get('email')) ?></div>
            </div>
        </div>
        <?= $this->cell('Menu::display', ['profile']) ?>
    </div>
</div>
