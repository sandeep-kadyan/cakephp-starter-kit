<?php

/**
 * @var \App\View\AppView $this
 */
?>
<p class="mt-6 text-xs text-gray-500 text-center">
    <?= __('By clicking continue, you agree to our') ?>
    <?= $this->Html->link(__('Terms of Service'), '#', ['class' => 'underline hover:text-gray-600']) ?>
    <?= __('and') ?>
    <?= $this->Html->link(__('Privacy Policy'), '#', ['class' => 'underline hover:text-gray-600']) ?>.
</p>