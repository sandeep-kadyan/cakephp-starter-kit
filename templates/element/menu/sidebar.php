<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="flex flex-col w-full flex-1 min-h-0">
    <div class="flex items-center p-2 mb-6 transition-all duration-300 ease-in-out">
        <?= $this->Html->link(
            $this->Html->image('cake-icon.svg', ['alt' => 'CakePHP', 'class' => 'w-8 h-8 invert dark:invert-0 shrink-0']) . '<span class="flex-1 text-left text-2xl font-bold text-sidebar-foreground" :class="sidebarMini ? \'hidden\' : \'inline\'">CakePHP</span>',
            '/',
            [
                'class' => 'relative z-20 text-lg font-medium flex items-center gap-2',
                'target' => '_self',
                'rel' => 'noopener',
                'escape' => false,
            ]
        ) ?>
    </div>
    <?= $this->cell('Menu::display', ['sidebar']) ?>
    <div class="flex flex-col mt-auto relative">
        <?= $this->cell('Menu::display', ['sidebar_footer']) ?>
        <div class="mt-2 border-t border-border pt-2">
            <?= $this->element('menu/profile', ['inSidebar' => true]) ?>
        </div>
    </div>
</div>
