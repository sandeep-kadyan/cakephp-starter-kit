<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Verification of magic login');
?>
<div class="w-full max-w-md mx-auto">
    <?= $this->Form->create(null, ['class' => 'space-y-5']) ?>
    <h1 class="text-2xl font-bold text-center"><?= __('Have a verification token instead?') ?></h1>
    <fieldset class="mt-0">
        <legend class="text-muted-foreground text-balance text-center mb-5"><?= __('Enter your verification token below to continue') ?></legend>
        <?= $this->Form->control('token', [
            'placeholder' => 'Enter verification token',
            'label' => false,
            'class' => 'block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:outline-none'
        ]) ?>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Continue with token'), [
            'class' => 'w-full py-2 px-4 bg-black hover:bg-gray-900 text-white font-semibold rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-gray-900'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
    <?= $this->element('form/auth/footer') ?>
</div>
