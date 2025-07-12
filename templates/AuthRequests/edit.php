<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AuthRequest $authRequest
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $authRequest->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $authRequest->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Auth Requests'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="authRequests form content">
            <?= $this->Form->create($authRequest) ?>
            <fieldset>
                <legend><?= __('Edit Auth Request') ?></legend>
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
    </div>
</div>
