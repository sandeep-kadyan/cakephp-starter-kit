<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Register');
?>
<div class="w-full max-w-md mx-auto">
    <?= $this->Form->create($user ?? null, ['class' => 'space-y-5']) ?>
    <h1 class="text-2xl font-bold text-center">Create your account</h1>
    <fieldset class="mt-0">
        <legend class="text-muted-foreground text-balance text-center mb-4"><?= __(sprintf('Register for your %s account', Configure::read('App.name', 'CakePHP'))) ?></legend>
        <div class="mb-4">
            <?= $this->Form->control('name', [
                'placeholder' => 'Your full name',
                'label' => ['class' => 'block text-sm font-medium text-foreground mb-1'],
                'class' => 'block w-full px-4 py-2 border border-input rounded-lg focus:ring-2 focus:ring-ring focus:outline-none'
            ]) ?>
        </div>
        <div class="mb-4">
            <?= $this->Form->control('username', [
                'placeholder' => 'Choose a username',
                'label' => ['class' => 'block text-sm font-medium text-foreground mb-1'],
                'class' => 'block w-full px-4 py-2 border border-input rounded-lg focus:ring-2 focus:ring-ring focus:outline-none'
            ]) ?>
        </div>
        <div class="mb-4">
            <?= $this->Form->control('email', [
                'placeholder' => 'example@abc.com',
                'label' => ['class' => 'block text-sm font-medium text-foreground mb-1'],
                'class' => 'block w-full px-4 py-2 border border-input rounded-lg focus:ring-2 focus:ring-ring focus:outline-none'
            ]) ?>
        </div>
        <?= $this->element('form/auth/password', [
            'name' => 'password',
            'label' => 'Password',
        ]) ?>
        <?= $this->element('form/auth/password', [
            'name' => 'confirm_password',
            'label' => 'Confirm password',
        ]) ?>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Create Account'), [
            'class' => 'w-full py-2 px-4 bg-primary text-primary-foreground font-semibold rounded-lg shadow hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
    <p class="mt-4 text-center text-sm text-muted-foreground">
        <?= __('Already have an account?') ?>
        <?= $this->Html->link(__('Log in'), [
            'controller' => 'Users',
            'action' => 'login',
        ], ['class' => 'font-semibold text-foreground hover:underline']) ?>
    </p>
    <?= $this->element('form/auth/footer') ?>
</div>
