<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Activity> $activities
 */
$this->assign('title', __('Activities'));
$this->assign('pageHeader.description', __('Track user activity across the application.'));
$this->assign('pageHeader.icon', 'activity');
$this->start('pageHeader.actions');
echo $this->Html->link(__('New Activity'), ['action' => 'add'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']);
$this->end();
?>
<div>
    <div class="overflow-x-auto rounded-lg border border-border bg-card">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('id') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('user_id') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('url') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('browser') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('os') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('device') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('ip_address') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('location') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('created') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('modified') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($activities as $activity): ?>
                <tr class="hover:bg-accent/50">
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($activity->id) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= $activity->hasValue('user') ? $this->Html->link($activity->user->name, ['controller' => 'Users', 'action' => 'view', $activity->user->id], ['class' => 'text-primary hover:text-primary/80']) : '' ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->url) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->browser) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->os) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->device) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->ip_address) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->location) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($activity->created) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($activity->modified) ?></td>
                    <td class="px-4 py-3 text-sm whitespace-nowrap">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $activity->id], ['class' => 'text-primary hover:text-primary/80']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $activity->id], ['class' => 'ml-3 text-primary hover:text-primary/80']) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $activity->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $activity->id),
                                'class' => 'ml-3 text-destructive hover:text-destructive/80',
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-sm text-muted-foreground"><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
        <ul class="flex list-none items-center gap-1">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
    </div>
</div>
