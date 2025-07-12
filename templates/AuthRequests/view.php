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
            <?= $this->Html->link(__('Edit Auth Request'), ['action' => 'edit', $authRequest->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Auth Request'), ['action' => 'delete', $authRequest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $authRequest->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Auth Requests'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Auth Request'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="authRequests view content">
            <h3><?= h($authRequest->username) ?></h3>
            <table>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= h($authRequest->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($authRequest->username) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($authRequest->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($authRequest->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Verification Token') ?></th>
                    <td><?= h($authRequest->verification_token) ?></td>
                </tr>
                <tr>
                    <th><?= __('Expires') ?></th>
                    <td><?= h($authRequest->expires) ?></td>
                </tr>
                <tr>
                    <th><?= __('Verified At') ?></th>
                    <td><?= h($authRequest->verified_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($authRequest->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($authRequest->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>