<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Activity> $activities
 */
?>
<div class="activities index content">
    <?= $this->Html->link(__('New Activity'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Activities') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('url') ?></th>
                    <th><?= $this->Paginator->sort('browser') ?></th>
                    <th><?= $this->Paginator->sort('os') ?></th>
                    <th><?= $this->Paginator->sort('device') ?></th>
                    <th><?= $this->Paginator->sort('ip_address') ?></th>
                    <th><?= $this->Paginator->sort('location') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><?= h($activity->id) ?></td>
                    <td><?= $activity->hasValue('user') ? $this->Html->link($activity->user->name, ['controller' => 'Users', 'action' => 'view', $activity->user->id]) : '' ?></td>
                    <td><?= h($activity->url) ?></td>
                    <td><?= h($activity->browser) ?></td>
                    <td><?= h($activity->os) ?></td>
                    <td><?= h($activity->device) ?></td>
                    <td><?= h($activity->ip_address) ?></td>
                    <td><?= h($activity->location) ?></td>
                    <td><?= h($activity->created) ?></td>
                    <td><?= h($activity->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $activity->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $activity->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $activity->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $activity->id),
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