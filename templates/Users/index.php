<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
$this->assign('title', __('Users'));
$this->assign('pageHeader.description', __('Manage the people who have access to your application.'));
$this->assign('pageHeader.icon', 'users');
$this->start('pageHeader.actions');
echo $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']);
$this->end();
?>
<div>
    <div class="overflow-x-auto rounded-lg border border-border bg-card">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('id') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('name') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('username') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('email') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('email_verified_at') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('remember_me') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('two_factor_confirmed_at') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('last_active_at') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('created') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $this->Paginator->sort('modified') ?></th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-accent/50">
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($user->id) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->name) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->username) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->email) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($user->email_verified_at) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->remember_me) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($user->two_factor_confirmed_at) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($user->last_active_at) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($user->created) ?></td>
                    <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($user->modified) ?></td>
                    <td class="px-4 py-3 text-sm whitespace-nowrap">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $user->id], ['class' => 'text-primary hover:text-primary/80']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => 'ml-3 text-primary hover:text-primary/80']) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $user->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $user->id),
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
