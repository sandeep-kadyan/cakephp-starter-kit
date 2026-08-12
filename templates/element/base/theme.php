<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="relative flex align-middle">
<button
    x-data="{
        theme: localStorage.getItem('theme') || '<?= h($defaultTheme ?? 'system') ?>',
        apply() {
            const root = document.documentElement;
            if (this.theme === 'dark') {
                root.classList.add('dark');
            } else if (this.theme === 'light') {
                root.classList.remove('dark');
            } else {
                // system: follow the OS preference
                root.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
            }
        },
        toggle() {
            this.theme = this.theme === 'light' ? 'dark' : this.theme === 'dark' ? 'system' : 'light';
            localStorage.setItem('theme', this.theme);
            this.apply();
        },
        init() {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (this.theme === 'system') this.apply();
            });
            this.apply();
        }
    }"
    x-init="init()"
    @click="toggle()"
    type="button"
    class="flex items-center rounded-full p-2 text-muted-foreground hover:bg-accent hover:text-accent-foreground relative"
    :aria-label="theme === 'light' ? 'Switch to dark mode' : theme === 'dark' ? 'Switch to system mode' : 'Switch to light mode'"
>
    <i data-lucide="moon" x-cloak x-show="theme === 'light'"></i>
    <i data-lucide="sun" x-cloak x-show="theme === 'dark'"></i>
    <i data-lucide="monitor" x-cloak x-show="theme === 'system'"></i>
</button>
</div>
