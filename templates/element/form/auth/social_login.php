<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 * @var array $socialProviders
 */
$this->assign('title', 'Login');
$providers = $socialProviders ?? [];
?>
<div class="w-full max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-center">Welcome Back</h1>
    <p class="text-muted-foreground text-balance text-center mb-5 mt-3">
        <?= __(sprintf('Log in to your %s account', Configure::read('App.name', 'CakePHP'))) ?>
    </p>

    <?php if (empty($providers)): ?>
        <div class="text-center py-6 text-muted-foreground">
            <?= __('No social login providers are configured.') ?>
            <?= $this->Html->link(__('Back to login'), [
                'controller' => 'Users',
                'action' => 'login',
            ], ['class' => 'font-semibold text-foreground hover:underline']) ?>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($providers as $provider): ?>
                <?php
                    $name = $provider['name'] ?? 'Provider';
                    $url = $provider['url'] ?? '#';
                ?>
                <?= $this->Html->link(
                    $name,
                    $url,
                    [
                        'escape' => false,
                        'class' => 'w-full flex items-center justify-center py-2 px-4 border border-input rounded-lg hover:bg-accent hover:text-accent-foreground text-foreground font-medium focus:outline-none focus:ring-2 focus:ring-ring',
                    ]
                ) ?>
            <?php endforeach; ?>
        </div>

        <p class="mt-4 text-center text-sm text-muted-foreground">
            <?= __('Or') ?>
            <?= $this->Html->link(__('log in with your username & password'), [
                'controller' => 'Users',
                'action' => 'login',
            ], ['class' => 'font-semibold text-foreground hover:underline']) ?>
        </p>
    <?php endif; ?>

    <?= $this->element('form/auth/footer') ?>
</div>
