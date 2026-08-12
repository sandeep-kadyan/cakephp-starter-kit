<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', __('Add User'));
$this->assign('pageHeader.description', __('Create a new user account.'));
$this->assign('pageHeader.icon', 'user-plus');
$this->start('pageHeader.actions');
echo $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
$this->end();
?>
<div>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('username');
            echo $this->Form->control('email');
            echo $this->Form->control('password');
            echo $this->Form->control('email_verified_at', ['empty' => true]);
            echo $this->Form->control('remember_me');
            echo $this->Form->control('two_factor_secret');
            echo $this->Form->control('two_factor_recovery_codes');
            echo $this->Form->control('two_factor_confirmed_at', ['empty' => true]);
            echo $this->Form->control('last_active_at', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
