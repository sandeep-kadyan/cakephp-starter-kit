<?php

/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->link(
    $this->Html->image('cake-icon.svg', ['alt' => 'CakePHP', 'class' => 'w-6 h-6 invert dark:invert-0 shrink-0']) . '<span class="ml-2 text-2xl font-bold hidden lg:block text-foreground">CakePHP</span>',
    '/',
    [
        'class' => 'flex items-center gap-2',
        'escape' => false,
    ]
) ?>
