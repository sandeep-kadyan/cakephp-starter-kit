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
}" :class="sidebarMini ? 'sidebar-mini' : ''">
    <aside
        :class="sidebarMini ? 'w-[64px]' : 'w-64'"
        class="bg-white border-r border-neutral-200 dark:border-neutral-700 dark:bg-transparent p-3 justify-center z-99 hidden lg:flex"
        x-show="!mobileMenuOpen || sidebarMini"
        @keydown.window.escape="mobileMenuOpen = false"
        @mouseenter="handleSidebarMouseEnter()"
    >
        <?= $this->element('menu/sidebar') ?>
    </aside>
    <!-- Mobile sidebar overlay -->
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 bg-black bg-opacity-40 flex lg:hidden" x-transition @click="mobileMenuOpen = false">
        <aside class="w-64 bg-neutral-100 dark:bg-neutral-800 h-full shadow-lg p-3 flex flex-col" @click.stop>
            <?= $this->element('menu/sidebar') ?>
        </aside>
    </div>
    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class="py-3 px-6 flex items-center justify-between align-middle border-b border-neutral-200 dark:border-neutral-700">
            <button class="lg:hidden mr-2 rounded-md flex items-center align-middle h-9" @click="mobileMenuOpen = true">
                <span class="material-icons dark:text-white hover:text-black text-2xl">chrome_reader_mode</span>
            </button>
            <div class="hidden lg:flex items-center align-middle">
                <button class="lg:flex items-center align-middle"  x-cloak @click="toggleSidebar()">
                    <svg :class="sidebarMini ? 'rotate-180' : 'rotate-0'" class="transition-transform duration-300 dark:text-white hover:text-black shrink-0 text-2xl" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M15 3v18"></path><path d="m10 15-3-3 3-3"></path></svg>
                </button>
                <div class="bg-border border border-neutral-600 shrink-0 h-3 ml-2"></div>
                <?= $this->element('base/breadcrumbs') ?>
            </div>
            <div class="flex items-center align-middle gap-2 lg:gap-4">
                <?= $this->element('base/theme') ?>
                <?= $this->element('base/notifications') ?>
                <?= $this->element('menu/profile') ?>
            </div>
        </header>
        <main class="flex-1 p-6 overflow-y-auto">
        <div class="lg:hidden pb-6">
            <?= $this->element('base/breadcrumbs') ?>
        </div>
            <?= $this->fetch('content') ?>
        </main>
    </div>
</div>
