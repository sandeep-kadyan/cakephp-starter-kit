<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\AuthRequest> $authRequests
 */
$this->assign('title', __('Auth Requests'));
$this->assign('pageHeader.description', __('Review magic login and password reset requests.'));
$this->assign('pageHeader.icon', 'mail');
$this->start('pageHeader.actions');
echo $this->Html->link(__('New Auth Request'), ['action' => 'add'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']);
$this->end();
?>
<div>
    <div class="overflow-x-auto rounded-lg border border-border bg-card">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('id') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('username') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('email') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('phone') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('verification_token') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('expires') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('verified_at') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('created') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('modified') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($authRequests as $authRequest): ?>
                <tr class="hover:bg-accent/50">
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($authRequest->id) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->username) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->email) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->phone) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->verification_token) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($authRequest->expires) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($authRequest->verified_at) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($authRequest->created) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($authRequest->modified) ?></td>
                    <td class="px-4 py-3 text-sm whitespace-nowrap">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $authRequest->id], ['class' => 'text-primary hover:text-primary/80']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $authRequest->id], ['class' => 'ml-3 text-primary hover:text-primary/80']) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $authRequest->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $authRequest->id),
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
