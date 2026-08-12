<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activity $activity
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
$this->assign('title', __('Add Activity'));
$this->assign('pageHeader.description', __('Create a new activity record.'));
$this->assign('pageHeader.icon', 'activity');
$this->start('pageHeader.actions');
echo $this->Html->link(__('List Activities'), ['action' => 'index'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
$this->end();
?>
<div>
    <?= $this->Form->create($activity) ?>
    <fieldset>
        <legend><?= __('Add Activity') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
            echo $this->Form->control('url');
            echo $this->Form->control('browser');
            echo $this->Form->control('os');
            echo $this->Form->control('device');
            echo $this->Form->control('ip_address');
            echo $this->Form->control('location');
            echo $this->Form->control('user_agent');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
