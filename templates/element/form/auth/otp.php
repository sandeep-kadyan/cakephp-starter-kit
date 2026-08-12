<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Two-factor authentication');
?>
<div class="w-full max-w-md mx-auto">
    <?= $this->Form->create(null, ['class' => 'space-y-5']) ?>
    <h1 class="text-2xl font-bold text-center">Two-factor authentication</h1>
    <fieldset class="mt-0">
        <legend class="text-muted-foreground text-balance text-center mb-5">
            <?= __('Enter the 6-digit code from your authenticator app to complete login.') ?>
        </legend>
        <?= $this->Form->control('code', [
            'placeholder' => '000000',
            'label' => ['text' => 'Verification code', 'class' => 'block text-sm font-medium text-foreground mb-1'],
            'maxlength' => 6,
            'autocomplete' => 'one-time-code',
            'inputmode' => 'numeric',
            'class' => 'block w-full px-4 py-2 border border-input rounded-lg focus:ring-2 focus:ring-ring focus:outline-none tracking-widest text-center text-lg'
        ]) ?>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Verify & Login'), [
            'class' => 'w-full py-2 px-4 bg-primary text-primary-foreground font-semibold rounded-lg shadow hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
    <p class="mt-4 text-center text-sm text-muted-foreground">
        <?= $this->Html->link(__('Back to login'), [
            'controller' => 'Users',
            'action' => 'login',
        ], ['class' => 'font-semibold text-foreground hover:underline']) ?>
    </p>
    <?= $this->element('form/auth/footer') ?>
</div>
