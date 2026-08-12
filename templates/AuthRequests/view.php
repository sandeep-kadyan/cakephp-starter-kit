<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AuthRequest $authRequest
 */
$this->assign('title', $authRequest->username);
$this->assign('pageHeader.description', __('Details for this authentication request.'));
$this->assign('pageHeader.icon', 'mail');
$this->start('pageHeader.actions');
echo $this->Html->link(__('Edit Auth Request'), ['action' => 'edit', $authRequest->id], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
echo $this->Form->postLink(__('Delete Auth Request'), ['action' => 'delete', $authRequest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $authRequest->id), 'class' => 'inline-flex items-center px-4 py-2 rounded-md border border-destructive text-sm font-medium text-destructive hover:bg-destructive hover:text-destructive-foreground']);
echo $this->Html->link(__('List Auth Requests'), ['action' => 'index'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md border border-border text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground']);
echo $this->Html->link(__('New Auth Request'), ['action' => 'add'], ['class' => 'inline-flex items-center px-4 py-2 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']);
$this->end();
?>
<div>
    <div class="overflow-x-auto rounded-lg border border-border bg-card">
        <table class="min-w-full divide-y divide-border">
            <tbody class="divide-y divide-border">
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Id') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->id) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Username') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->username) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Email') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->email) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Phone') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->phone) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Verification Token') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground break-all"><?= h($authRequest->verification_token) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Expires') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->expires) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Verified At') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->verified_at) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Created') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->created) ?></td>
                </tr>
                <tr>
                    <th class="w-48 px-4 py-3 text-left text-sm font-medium text-muted-foreground"><?= __('Modified') ?></th>
                    <td class="px-4 py-3 text-sm text-foreground"><?= h($authRequest->modified) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
