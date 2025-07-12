<?php

/**
 * @var \App\View\AppView $this
 */
?>

<header class="px-6 py-3 flex items-center justify-between bg-white dark:bg-neutral-800">
    <div class="flex items-center gap-5">
        <?= $this->element('base/header_logo') ?>
        <?= $this->element('menu/header') ?>
    </div>
    <div class="flex items-center gap-0 md:gap-2 lg:gap-4">
        <?= $this->element('base/theme') ?>
        <?= $this->element('base/notifications') ?>
        <?= $this->element('base/search') ?>
        <?= $this->element('menu/profile') ?>
    </div>
</header>
<div class="p-6">
    <?= $this->element('base/breadcrumbs') ?>
</div>
<div class="flex">
    <main class="flex-1 px-6 pb-6 overflow-y-auto">
        <?= $this->fetch('content') ?>
    </main>
</div>

