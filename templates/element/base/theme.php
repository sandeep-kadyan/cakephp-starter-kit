<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="relative flex align-middle">
<button
    x-data="{
        theme: localStorage.getItem('theme') || 'light',
        toggle() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            document.documentElement.classList[this.theme === 'dark' ? 'add' : 'remove']('dark');
        },
        init() {
            document.documentElement.classList[this.theme === 'dark' ? 'add' : 'remove']('dark');
        }
    }"
    x-init="init()"
    @click="toggle()"
    type="button"
    class="flex align-middle rounded-full z-99 relative p-2"
    :aria-label="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
>
    <span class="material-icons" x-cloak x-show="theme === 'light'">dark_mode</span>
    <span class="material-icons dark:text-white" x-cloak x-show="theme === 'dark'">light_mode</span>
</button>
</div>
