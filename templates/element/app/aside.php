<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
?>

<div class="flex h-screen overflow-hidden" x-data="{
    mobileMenuOpen: false,
    sidebarMini: false,
    sidebarLocked: false,
    handleSidebarMouseEnter() {
        if (this.sidebarMini && !this.sidebarLocked) this.sidebarMini = false;
    },
    toggleSidebar() {
        this.sidebarMini = !this.sidebarMini;
        this.sidebarLocked = !this.sidebarMini;
    }
}">
    <aside
        :class="sidebarMini ? 'w-16' : 'w-64'"
        class="hidden lg:flex flex-col bg-sidebar text-sidebar-foreground border-r border-border p-3 transition-all duration-300 shrink-0"
        @keydown.window.escape="mobileMenuOpen = false"
        @mouseenter="handleSidebarMouseEnter()"
    >
        <?= $this->element('menu/sidebar') ?>
    </aside>
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="py-3 px-4 lg:px-6 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <button type="button" class="lg:hidden rounded-md p-2 text-muted-foreground hover:bg-accent hover:text-accent-foreground focus:outline-none" @click="mobileMenuOpen = true" aria-label="<?= __('Open menu') ?>">
                    <i data-lucide="menu" class="text-2xl"></i>
                </button>
                <button type="button" class="hidden lg:flex items-center rounded-md p-2 text-muted-foreground hover:bg-accent hover:text-accent-foreground focus:outline-none" @click="toggleSidebar()" aria-label="<?= __('Toggle sidebar') ?>">
                    <svg :class="sidebarMini ? 'rotate-180' : 'rotate-0'" class="transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M15 3v18"></path><path d="m10 15-3-3 3-3"></path></svg>
                </button>
                <div class="hidden lg:flex items-center gap-2">
                    <div class="h-3 shrink-0"></div>
                    <?= $this->element('base/breadcrumbs') ?>
                </div>
            </div>
            <div class="flex items-center gap-1 lg:gap-2">
                <?= $this->element('base/theme') ?>
                <?= $this->element('base/notifications') ?>
            </div>
        </header>
        <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
            <div class="lg:hidden pb-4">
                <?= $this->element('base/breadcrumbs') ?>
            </div>
            <?= $this->element('base/page_header') ?>
            <?= $this->fetch('content') ?>
        </main>
    </div>

    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" x-transition>
        <div class="absolute inset-0 bg-foreground/50" @click="mobileMenuOpen = false"></div>
        <aside class="absolute inset-y-0 left-0 w-64 bg-sidebar text-sidebar-foreground shadow-xl p-3 overflow-y-auto">
            <div class="flex items-center justify-end mb-2">
                <button type="button" class="rounded-md p-2 text-muted-foreground hover:bg-accent hover:text-accent-foreground focus:outline-none" @click="mobileMenuOpen = false" aria-label="<?= __('Close menu') ?>">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <?= $this->element('menu/sidebar') ?>
        </aside>
    </div>
</div>
