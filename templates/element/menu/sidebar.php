<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div :class="sidebarMini ? 'items-center' : ''" class="flex flex-col w-64">
    <div class="flex items-center p-2 mb-6 transition-all duration-300 ease-in-out ">
        <?= $this->Html->link(
            $this->Html->image('cake-icon.svg', ['alt' => 'CakePHP', 'class' => 'max-w-[30px] invert dark:invert-0']) . '<span class="flex-1 text-left sidebar-text text-2xl font-bold dark:text-white">CakePHP</span>',
            '/',
            [
                'class' => 'relative z-20 text-lg font-medium flex justify-center items-center gap-2',
                'target' => '_self',
                'rel' => 'noopener',
                'escape' => false,
            ]
        ) ?>
    </div>
    <?= $this->cell('Menu::display', ['sidebar']) ?>
    <div class="flex flex-col mt-auto relative">
        <?= $this->cell('Menu::display', ['sidebar_footer']) ?>
    </div>
</div>

