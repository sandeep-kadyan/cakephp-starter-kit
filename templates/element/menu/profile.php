<?php

/**
 * @var \App\View\AppView $this
 */
$inSidebar = isset($inSidebar) ? (bool)$inSidebar : false;
?>
<div class="relative flex items-center <?= $inSidebar ? 'w-full' : '' ?>" x-data="{ open: false }">
    <button type="button" aria-label="User menu" @click="open = !open" class="<?= $inSidebar ? 'flex items-center gap-3 w-full p-2 rounded-md text-muted-foreground hover:bg-accent hover:text-accent-foreground focus:outline-none' : 'rounded-full w-8 h-8 bg-secondary text-secondary-foreground flex items-center justify-center hover:bg-accent hover:text-accent-foreground focus:outline-none' ?>">
        <?php $userName = $this->Identity->get('name'); ?>
        <span class="rounded-full w-8 h-8 bg-secondary <?= $inSidebar ? 'text-muted-foreground' : 'text-secondary-foreground' ?> flex items-center justify-center shrink-0">
            <span class="text-lg font-bold">
                <?= strtoupper(mb_substr($userName, 0, 1)) ?>
            </span>
        </span>
        <?php if ($inSidebar): ?>
            <span class="min-w-0 flex-1 text-left text-sm font-medium truncate" :class="sidebarMini ? 'hidden' : 'inline'"><?= h($userName) ?></span>
            <i data-lucide="chevron-up" class="text-xs text-muted-foreground transition-transform duration-300" :class="{ 'rotate-180': open, 'hidden': sidebarMini, 'inline': !sidebarMini }"></i>
        <?php endif; ?>
    </button>
    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute <?= $inSidebar ? 'bottom-full mb-2 -left-3 w-64' : 'right-0 mt-10 w-72' ?> z-50 bg-popover text-popover-foreground border border-border rounded-lg shadow-lg">
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
