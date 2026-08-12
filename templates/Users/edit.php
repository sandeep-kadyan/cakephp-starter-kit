<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', __('Edit User'));
$this->assign('pageHeader.description', __('Update the details for this user account.'));
$this->assign('pageHeader.icon', 'user-cog');
$this->start('pageHeader.actions');
echo $this->Form->postLink(
    __('Delete'),
    ['action' => 'delete', $user->id],
    ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'inline-flex items-center px-4 py-2 rounded-md border border-destructive text-sm font-medium text-destructive hover:bg-destructive hover:text-destructive-foreground']
);
echo $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
$this->end();
?>
<div>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
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
