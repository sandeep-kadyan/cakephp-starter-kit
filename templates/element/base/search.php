<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="relative hidden md:flex items-center" x-cloak>
    <div class="flex items-center rounded-lg bg-muted border border-input focus-within:ring-1 focus-within:ring-ring">
        <span class="flex items-center relative w-full">
            <input class="w-full rounded-lg h-9 pl-3 pr-2 text-sm text-foreground bg-transparent focus:outline-none placeholder:text-muted-foreground" type="text" name="search" id="search" placeholder="<?= __('Search') ?>">
            <button type="button" class="flex items-center p-2 text-muted-foreground hover:text-foreground" aria-label="<?= __('Search') ?>">
                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" class="text-xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                </svg>
            </button>
        </span>
    </div>
</div>
