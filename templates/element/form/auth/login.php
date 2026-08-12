<?php

use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Login');
?>
<div class="w-full max-w-md mx-auto">
    <?= $this->Form->create(null, ['class' => 'space-y-5']) ?>
    <h1 class="text-2xl font-bold text-center">Log in to your account</h1>
    <fieldset class="mt-0">
        <legend class="text-muted-foreground text-balance text-center mb-4"><?= __(sprintf('Login to your %s account', Configure::read('App.name', 'CakePHP'))) ?></legend>
        <div class="mb-4">
            <?= $this->Form->control('email', [
                'placeholder' => 'username or email@example.com',
                'label' => ['text' => 'Username or Email', 'class' => 'block text-sm font-medium text-foreground mb-1'],
                'class' => 'block w-full px-4 py-2 border border-input rounded-lg focus:ring-2 focus:ring-ring focus:outline-none'
            ]) ?>
        </div>
        <?= $this->element('form/auth/password', [
            'name' => 'password',
            'label' => 'Password',
        ]) ?>
        <div class="mb-4 flex items-center justify-between">
            <label class="flex items-center">
                <?= $this->Form->checkbox('remember_me', [
                    'label' => false,
                    'class' => 'h-4 w-4 accent-primary border-primary rounded'
                ]) ?>
                <span class="ml-2 text-sm text-muted-foreground"><?= __('Remember me') ?></span>
            </label>
            <?= $this->Html->link(__('Forgot password?'), [
                'controller' => 'Users',
                'action' => 'forgotPassword',
            ], ['class' => 'text-sm font-medium text-muted-foreground hover:text-foreground']) ?>
        </div>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Login'), [
            'class' => 'w-full py-2 px-4 bg-primary text-primary-foreground font-semibold rounded-lg shadow hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
    <?php if (!empty($socialProviders)): ?>
        <div class="relative my-5">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t border-border"></span>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="bg-background px-2 text-muted-foreground"><?= __('or continue with') ?></span>
            </div>
        </div>
        <div class="space-y-3">
            <?php foreach ($socialProviders as $provider): ?>
                <?= $this->Html->link(
                    $provider['name'] ?? 'Provider',
                    $provider['url'] ?? '#',
                    [
                        'class' => 'w-full flex items-center justify-center py-2 px-4 border border-input rounded-lg hover:bg-accent hover:text-accent-foreground text-foreground font-medium focus:outline-none focus:ring-2 focus:ring-ring',
                    ]
                ) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <p class="mt-4 text-center text-sm text-muted-foreground">
        <?= __('Don\'t have an account?') ?>
        <?= $this->Html->link(__('Register'), [
            'controller' => 'Users',
            'action' => 'register',
        ], ['class' => 'font-semibold text-foreground hover:underline']) ?>
    </p>
    <?= $this->element('form/auth/footer') ?>
</div>
