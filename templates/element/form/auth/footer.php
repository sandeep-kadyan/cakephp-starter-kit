<?php

/**
 * @var \App\View\AppView $this
 */
?>
<p class="mt-6 text-xs text-muted-foreground text-center">
    <?= __('By clicking continue, you agree to our') ?>
    <?= $this->Html->link(__('Terms of Service'), '#', ['class' => 'underline hover:text-muted-foreground']) ?>
    <?= __('and') ?>
    <?= $this->Html->link(__('Privacy Policy'), '#', ['class' => 'underline hover:text-muted-foreground']) ?>.
</p>