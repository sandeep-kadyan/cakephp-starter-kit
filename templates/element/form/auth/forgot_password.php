<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Forgot password');
?>
<div class="w-full max-w-md mx-auto">
    <?= $this->Form->create(null, ['class' => 'space-y-5']) ?>
    <h1 class="text-2xl font-bold text-center">Forgot your password?</h1>
    <fieldset class="mt-0">
        <legend class="text-muted-foreground text-balance text-center mb-4">
            <?= __(sprintf('Enter your email below and we will send you a link to reset your %s account password.', Configure::read('App.name', 'CakePHP'))) ?>
        </legend>
        <div class="mb-4">
            <?= $this->Form->control('email', [
                'placeholder' => 'example@abc.com',
                'label' => ['class' => 'block text-sm font-medium text-foreground mb-1'],
                'class' => 'block w-full px-4 py-2 border border-input rounded-lg focus:ring-2 focus:ring-ring focus:outline-none'
            ]) ?>
        </div>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Send reset link'), [
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
