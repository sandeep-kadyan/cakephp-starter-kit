<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AuthRequest $authRequest
 */
$this->assign('title', __('Add Auth Request'));
$this->assign('pageHeader.description', __('Create a new authentication request.'));
$this->assign('pageHeader.icon', 'mail');
$this->start('pageHeader.actions');
echo $this->Html->link(__('List Auth Requests'), ['action' => 'index'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
$this->end();
?>
<div>
    <?= $this->Form->create($authRequest) ?>
    <fieldset>
        <legend><?= __('Add Auth Request') ?></legend>
        <?php
            echo $this->Form->control('username');
            echo $this->Form->control('email');
            echo $this->Form->control('phone');
            echo $this->Form->control('verification_token');
            echo $this->Form->control('expires');
            echo $this->Form->control('verified_at', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
