<?php

/**
 * @var \App\View\AppView $this
 */
?>
<nav class="hidden md:flex flex-1 justify-center">
    <?= $this->cell('Menu::display', ['header']) ?>
</nav>
<div class="fixed inset-0 z-50 bg-foreground/50 hidden">
    <div class="absolute top-0 left-0 w-64 bg-sidebar text-sidebar-foreground h-full shadow-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <span class="text-lg font-bold">Menu</span>
            <button type="button" aria-label="Close menu" class="p-2 rounded-full hover:bg-accent hover:text-accent-foreground focus:outline-none flex items-center justify-center">
                <i data-lucide="x"></i>
            </button>
        </div>
        <?= $this->cell('Menu::display', ['header']) ?>
    </div>
</div>
