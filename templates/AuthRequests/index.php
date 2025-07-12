<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\AuthRequest> $authRequests
 */
?>
<div class="authRequests index content">
    <?= $this->Html->link(__('New Auth Request'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Auth Requests') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('username') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('phone') ?></th>
                    <th><?= $this->Paginator->sort('verification_token') ?></th>
                    <th><?= $this->Paginator->sort('expires') ?></th>
                    <th><?= $this->Paginator->sort('verified_at') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($authRequests as $authRequest): ?>
                <tr>
                    <td><?= h($authRequest->id) ?></td>
                    <td><?= h($authRequest->username) ?></td>
                    <td><?= h($authRequest->email) ?></td>
                    <td><?= h($authRequest->phone) ?></td>
                    <td><?= h($authRequest->verification_token) ?></td>
                    <td><?= h($authRequest->expires) ?></td>
                    <td><?= h($authRequest->verified_at) ?></td>
                    <td><?= h($authRequest->created) ?></td>
                    <td><?= h($authRequest->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $authRequest->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $authRequest->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $authRequest->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $authRequest->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>