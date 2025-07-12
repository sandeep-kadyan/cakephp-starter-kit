<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Forgot password');
?>
<div class="w-full max-w-md mx-auto">
    <?= $this->Form->create(null, ['class' => 'space-y-5']) ?>
    <h1 class="text-2xl font-bold text-center">Log in to your account</h1>
    <fieldset class="mt-0">
        <legend class="text-muted-foreground text-balance text-center mb-4"><?= __(sprintf('Login to your %s account', Configure::read('App.name', 'CakePHP'))) ?></legend>
        <div class="mb-4">
            <?= $this->Form->control('username', [
                'placeholder' => 'username or example@abc.com',
                'label' => ['class' => 'block text-sm font-medium text-gray-700 mb-1'],
                'class' => 'block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:outline-none'
            ]) ?>
        </div>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Login'), [
            'class' => 'w-full py-2 px-4 bg-black hover:bg-gray-900 text-white font-semibold rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-gray-900'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
    <?= $this->element('form/auth/footer') ?>
</div>
