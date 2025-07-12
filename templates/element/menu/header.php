<?php

/**
 * @var \App\View\AppView $this
 */
?>
<nav class="hidden md:flex flex-1 justify-center">
    <?= $this->cell('Menu::display', ['header']) ?>
</nav>
<div class="fixed inset-0 z-50 bg-black bg-opacity-40 hidden">
    <div class="absolute top-0 left-0 w-64 bg-white h-full shadow-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <span class="text-lg font-bold">Menu</span>
            <button type="button" aria-label="Close menu" class="p-2 rounded-full hover:bg-neutral-100 focus:outline-none flex items-center justify-center align-middle">
                <span class="material-icons">close</span>
            </button>
        </div>
        <?= $this->cell('Menu::display', ['header']) ?>
    </div>
</div>
