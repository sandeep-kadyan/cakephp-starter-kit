<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activity $activity
 */
$this->assign('title', $activity->url);
$this->assign('pageHeader.description', __('Details for this activity record.'));
$this->assign('pageHeader.icon', 'activity');
$this->start('pageHeader.actions');
echo $this->Html->link(__('Edit Activity'), ['action' => 'edit', $activity->id], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
echo $this->Form->postLink(__('Delete Activity'), ['action' => 'delete', $activity->id], ['confirm' => __('Are you sure you want to delete # {0}?', $activity->id), 'class' => 'inline-flex items-center px-4 py-2 rounded-md border border-destructive text-sm font-medium text-destructive hover:bg-destructive hover:text-destructive-foreground']);
echo $this->Html->link(__('List Activities'), ['action' => 'index'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
echo $this->Html->link(__('New Activity'), ['action' => 'add'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']);
$this->end();
?>
<div>
    <div class="overflow-x-auto rounded-lg border border-border bg-card">
        <table class="min-w-full divide-y divide-border">
            <tbody class="divide-y divide-border">
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Id') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->id) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('User') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= $activity->hasValue('user') ? $this->Html->link($activity->user->name, ['controller' => 'Users', 'action' => 'view', $activity->user->id], ['class' => 'text-primary hover:text-primary/80']) : '' ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Url') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground break-all"><?= h($activity->url) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Browser') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->browser) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Os') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->os) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Device') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->device) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Ip Address') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->ip_address) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Location') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->location) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Created') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->created) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Modified') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($activity->modified) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground align-top"><?= __('User Agent') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground break-all"><?= h($activity->user_agent) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
