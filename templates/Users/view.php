<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users view content">
            <h3><?= h($user->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= h($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($user->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($user->username) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Remember Me') ?></th>
                    <td><?= h($user->remember_me) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email Verified At') ?></th>
                    <td><?= h($user->email_verified_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Two Factor Confirmed At') ?></th>
                    <td><?= h($user->two_factor_confirmed_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Active At') ?></th>
                    <td><?= h($user->last_active_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($user->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($user->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Two Factor Secret') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($user->two_factor_secret)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Two Factor Recovery Codes') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($user->two_factor_recovery_codes)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Activities') ?></h4>
                <?php if (!empty($user->activities)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Url') ?></th>
                            <th><?= __('Browser') ?></th>
                            <th><?= __('Os') ?></th>
                            <th><?= __('Device') ?></th>
                            <th><?= __('Ip Address') ?></th>
                            <th><?= __('Location') ?></th>
                            <th><?= __('User Agent') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->activities as $activity) : ?>
                        <tr>
                            <td><?= h($activity->id) ?></td>
                            <td><?= h($activity->user_id) ?></td>
                            <td><?= h($activity->url) ?></td>
                            <td><?= h($activity->browser) ?></td>
                            <td><?= h($activity->os) ?></td>
                            <td><?= h($activity->device) ?></td>
                            <td><?= h($activity->ip_address) ?></td>
                            <td><?= h($activity->location) ?></td>
                            <td><?= h($activity->user_agent) ?></td>
                            <td><?= h($activity->status) ?></td>
                            <td><?= h($activity->created) ?></td>
                            <td><?= h($activity->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Activities', 'action' => 'view', $activity->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Activities', 'action' => 'edit', $activity->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Activities', 'action' => 'delete', $activity->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $activity->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>