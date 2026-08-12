<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', $user->name);
$this->assign('pageHeader.description', __('Account details and activity for this user.'));
$this->assign('pageHeader.icon', 'user');
$this->start('pageHeader.actions');
echo $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
echo $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'inline-flex items-center px-4 py-2 rounded-md border border-destructive text-sm font-medium text-destructive hover:bg-destructive hover:text-destructive-foreground']);
echo $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
echo $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']);
$this->end();
?>
<div>
    <div class="overflow-x-auto rounded-lg border border-border bg-card">
        <table class="min-w-full divide-y divide-border">
            <tbody class="divide-y divide-border">
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Id') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->id) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Name') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->name) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Username') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->username) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Email') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Remember Me') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->remember_me) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Email Verified At') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->email_verified_at) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Two Factor Confirmed At') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->two_factor_confirmed_at) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Last Active At') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->last_active_at) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Created') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->created) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Modified') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($user->modified) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground align-top"><?= __('Two Factor Secret') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground break-all"><?= h($user->two_factor_secret) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground align-top"><?= __('Two Factor Recovery Codes') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground break-all whitespace-pre-wrap"><?= h($user->two_factor_recovery_codes) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mt-8">
        <h4 class="mb-4 text-lg font-semibold text-foreground"><?= __('Related Activities') ?></h4>
        <?php if (!empty($user->activities)) : ?>
        <div class="overflow-x-auto rounded-lg border border-border bg-card">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Id') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('User Id') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Url') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Browser') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Os') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Device') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Ip Address') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Location') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('User Agent') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Status') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Created') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Modified') ?></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php foreach ($user->activities as $activity) : ?>
                    <tr class="hover:bg-accent/50">
                        <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($activity->id) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->user_id) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->url) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->browser) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->os) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->device) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->ip_address) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->location) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->user_agent) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->status) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($activity->created) ?></td>
                        <td class="px-4 py-3 text-sm text-foreground whitespace-nowrap"><?= h($activity->modified) ?></td>
                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                            <?= $this->Html->link(__('View'), ['controller' => 'Activities', 'action' => 'view', $activity->id], ['class' => 'text-primary hover:text-primary/80']) ?>
                            <?= $this->Html->link(__('Edit'), ['controller' => 'Activities', 'action' => 'edit', $activity->id], ['class' => 'ml-3 text-primary hover:text-primary/80']) ?>
                            <?= $this->Form->postLink(
                                __('Delete'),
                                ['controller' => 'Activities', 'action' => 'delete', $activity->id],
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
        <?php endif; ?>
    </div>
</div>
