<?php

/**
 * @var \App\View\AppView $this
 */
?>

<header class="px-4 lg:px-6 py-3 flex items-center justify-between bg-card border-b border-border">
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
<div class="px-4 lg:px-6 py-3 bg-background border-b border-border">
    <?= $this->element('base/breadcrumbs') ?>
</div>
<div class="flex bg-background">
    <main class="flex-1 px-4 lg:px-6 py-6 overflow-y-auto">
        <?= $this->element('base/page_header') ?>
        <?= $this->fetch('content') ?>
    </main>
</div>

