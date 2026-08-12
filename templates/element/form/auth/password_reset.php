<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Password reset');
?>
<div class="w-full max-w-md mx-auto">
    <?= $this->Form->create($user ?? null, ['class' => 'space-y-5']) ?>
    <?php if (!empty($token)): ?>
        <?= $this->Form->hidden('token', ['value' => $token]) ?>
    <?php endif; ?>
    <h1 class="text-2xl font-bold text-center">Reset your password</h1>
    <fieldset class="mt-0">
        <legend class="text-muted-foreground text-balance text-center mb-4">
            <?= __(sprintf('Choose a new password for your %s account.', Configure::read('App.name', 'CakePHP'))) ?>
        </legend>
        <?= $this->element('form/auth/password', [
            'name' => 'password',
            'label' => 'New password',
        ]) ?>
        <?= $this->element('form/auth/password', [
            'name' => 'confirm_password',
            'label' => 'Confirm new password',
        ]) ?>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Reset Password'), [
            'class' => 'w-full py-2 px-4 bg-primary text-primary-foreground font-semibold rounded-lg shadow hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
    <p class="mt-4 text-center text-sm text-muted-foreground">
        <?= __('Remembered your password?') ?>
        <?= $this->Html->link(__('Back to login'), [
            'controller' => 'Users',
            'action' => 'login',
        ], ['class' => 'font-semibold text-foreground hover:underline']) ?>
    </p>
</div>
