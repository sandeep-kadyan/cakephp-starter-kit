<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="md:hidden">
    <button type="button" aria-label="Open menu" class="mr-3 rounded-lg p-1 dark:text-white focus:outline-none focus:bg-neutral-100 dark:focus:bg-white/20 dark:focus:text-white flex items-center justify-center align-middle">
        <span class="material-icons">menu</span>
    </button>
</div>
<?= $this->Html->link(
    $this->Html->image('cake-icon.svg', ['alt' => 'CakePHP', 'class' => 'max-w-[25px] invert dark:invert-0']) . '<span class="ml-2 text-2xl font-bold hidden lg:block dark:text-white">CakePHP</span>',
    '/',
    [
        'class' => 'flex items-center gap-2',
        'escape' => false,
    ]
) ?>
