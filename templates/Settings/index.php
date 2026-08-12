<?php
/**
 * Settings - unified page.
 *
 * Two-column layout: a left submenu lists every section; the right column
 * renders the form for the active section (selected via the `section` query
 * param). All section GET/POST handling is done by SettingsController::index()
 * plus the per-section POST actions that redirect back here.
 *
 * @var \App\View\AppView $this
 * @var array<string, array{title: string, icon: string, description: string}> $sections
 * @var string $activeSection
 * @var \App\Model\Entity\User $user
 * @var string $currentTheme
 * @var list<string> $themeDefaults
 * @var int $currentPageSize
 * @var list<int> $allowed
 * @var array{email: bool, database: bool} $prefs
 * @var iterable $active
 * @var iterable $history
 * @var iterable $methods
 * @var \App\Utility\Totp $totp
 * @var bool $isEnabled
 * @var string|null $pendingSecret
 */
$this->assign('title', __('Settings'));
$this->assign('pageHeader.description', __('Manage your profile, appearance, security, and preferences.'));
$this->assign('pageHeader.icon', 'settings');
?>
<div class="flex flex-col lg:flex-row gap-6">
    <aside class="lg:w-64 shrink-0">
        <?= $this->element('settings/tabs') ?>
    </aside>

    <div class="flex-1 min-w-0 max-w-3xl space-y-6">
        <?php if ($activeSection === 'profile') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['profile']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['profile']['description']) ?></p>
            </div>
            <div>
                <?= $this->Form->create($user, ['url' => ['action' => 'profile']]) ?>
                <fieldset>
                    <legend class="sr-only"><?= __('Profile') ?></legend>
                    <?= $this->Form->control('name') ?>
                    <?= $this->Form->control('username') ?>
                    <?= $this->Form->control('email') ?>
                </fieldset>
                <div class="mt-4">
                    <?= $this->Form->button(__('Save Profile'), ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>

        <?php elseif ($activeSection === 'appearance') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['appearance']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['appearance']['description']) ?></p>
            </div>
            <div>
                <?= $this->Form->create(null, ['url' => ['action' => 'appearance']]) ?>
                <fieldset>
                    <legend class="text-base font-semibold mb-4"><?= __('Theme') ?></legend>
                    <div class="grid grid-cols-3 gap-3">
                        <?php foreach ($themeDefaults as $theme) : ?>
                            <?php
                            $isChecked = $currentTheme === $theme;
                            $icons = ['light' => 'sun', 'dark' => 'moon', 'system' => 'monitor'];
                            ?>
                            <label class="cursor-pointer">
                                <input type="radio" name="theme" value="<?= h($theme) ?>" class="peer sr-only" <?= $isChecked ? 'checked' : '' ?>>
                                <div class="flex flex-col items-center gap-2 p-4 text-center text-sm font-medium text-muted-foreground transition-colors duration-200 peer-checked:border-primary peer-checked:bg-accent peer-checked:text-foreground">
                                    <i data-lucide="<?= h($icons[$theme]) ?>" class="h-5 w-5"></i>
                                    <span><?= h(ucfirst($theme)) ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <div class="mt-4">
                    <?= $this->Form->button(__('Save Appearance'), ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>

        <?php elseif ($activeSection === 'payments') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['payments']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['payments']['description']) ?></p>
            </div>
            <div>
                <h2 class="text-base font-semibold mb-4"><?= __('Saved methods') ?></h2>
                <?php if (count($methods) === 0) : ?>
                    <p class="text-sm text-muted-foreground"><?= __('No payment methods saved yet.') ?></p>
                <?php else : ?>
                    <ul class="divide-y divide-border">
                        <?php foreach ($methods as $method) : ?>
                            <li class="flex items-center justify-between gap-4 py-3">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="credit-card" class="h-5 w-5 text-muted-foreground"></i>
                                    <div>
                                        <p class="text-sm font-medium"><?= h($method->provider) ?></p>
                                        <p class="text-xs text-muted-foreground"><?= h($method->identifier) ?></p>
                                    </div>
                                </div>
                                <?= $this->Form->postLink(
                                    __('Remove'),
                                    ['action' => 'deletePayment', $method->id],
                                    ['class' => 'inline-flex items-center px-3 py-1.5 rounded-lg border border-border text-xs font-medium text-muted-foreground hover:bg-accent', 'confirm' => __('Remove this payment method?')],
                                ) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="mt-6">
                <h2 class="text-base font-semibold mb-4"><?= __('Add a method') ?></h2>
                <?= $this->Form->create(null, ['url' => ['action' => 'payments']]) ?>
                <fieldset>
                    <legend class="sr-only"><?= __('Add payment method') ?></legend>
                    <?= $this->Form->control('provider', ['label' => __('Provider'), 'placeholder' => __('e.g. Stripe, PayPal')]) ?>
                    <?= $this->Form->control('identifier', ['label' => __('Identifier'), 'placeholder' => __('e.g. card •••• 4242')]) ?>
                    <?= $this->Form->control('details', ['label' => __('Details'), 'type' => 'textarea', 'rows' => 2]) ?>
                    <?= $this->Form->control('is_default', ['label' => __('Set as default'), 'type' => 'checkbox']) ?>
                </fieldset>
                <div class="mt-4">
                    <?= $this->Form->button(__('Save method'), ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>

        <?php elseif ($activeSection === 'pagination') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['pagination']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['pagination']['description']) ?></p>
            </div>
            <div>
                <?= $this->Form->create(null, ['url' => ['action' => 'pagination']]) ?>
                <fieldset>
                    <legend class="text-base font-semibold mb-4"><?= __('Default page size') ?></legend>
                    <?= $this->Form->control('page_size', [
                        'label' => __('Rows per page'),
                        'type' => 'select',
                        'options' => array_combine($allowed, $allowed),
                        'value' => $currentPageSize,
                        'empty' => false,
                    ]) ?>
                    <p class="mt-2 text-sm text-muted-foreground"><?= __('Applied to all tables when you are signed in.') ?></p>
                </fieldset>
                <div class="mt-4">
                    <?= $this->Form->button(__('Save Pagination'), ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>

        <?php elseif ($activeSection === 'notifications') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['notifications']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['notifications']['description']) ?></p>
            </div>
            <div>
                <?= $this->Form->create(null, ['url' => ['action' => 'notifications']]) ?>
                <fieldset>
                    <legend class="text-base font-semibold mb-4"><?= __('Notification channels') ?></legend>
                    <?= $this->Form->control('email', ['label' => __('Email notifications'), 'type' => 'checkbox', 'checked' => $prefs['email']]) ?>
                    <?= $this->Form->control('database', ['label' => __('In-app (database) notifications'), 'type' => 'checkbox', 'checked' => $prefs['database']]) ?>
                </fieldset>
                <div class="mt-4">
                    <?= $this->Form->button(__('Save Notifications'), ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>

        <?php elseif ($activeSection === 'password') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['password']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['password']['description']) ?></p>
            </div>
            <div>
                <?= $this->Form->create(null, ['url' => ['action' => 'password']]) ?>
                <fieldset>
                    <legend class="sr-only"><?= __('Change password') ?></legend>
                    <?= $this->Form->control('current_password', ['type' => 'password', 'label' => __('Current password')]) ?>
                    <?= $this->Form->control('password', ['type' => 'password', 'label' => __('New password')]) ?>
                    <?= $this->Form->control('confirm_password', ['type' => 'password', 'label' => __('Confirm new password')]) ?>
                </fieldset>
                <div class="mt-4">
                    <?= $this->Form->button(__('Change Password'), ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>

        <?php elseif ($activeSection === 'twoFactor') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['twoFactor']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['twoFactor']['description']) ?></p>
            </div>
            <div>
                <?php if ($isEnabled) : ?>
                    <div class="flex items-center gap-2 rounded-lg border border-border bg-accent p-4 text-sm">
                        <i data-lucide="shield-check" class="h-5 w-5 text-primary"></i>
                        <span><?= __('Two-factor authentication is enabled.') ?></span>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <?= $this->Form->postLink(__('Regenerate recovery codes'), ['action' => 'regenerateRecoveryCodes'], ['class' => 'inline-flex items-center px-4 py-2 rounded-lg border border-border text-sm font-medium text-muted-foreground hover:bg-accent']) ?>
                        <?= $this->Form->postLink(__('Disable 2FA'), ['action' => 'disableTwoFactor'], ['class' => 'inline-flex items-center px-4 py-2 rounded-lg border border-destructive text-sm font-medium text-destructive hover:bg-destructive hover:text-destructive-foreground', 'confirm' => __('Are you sure you want to disable two-factor authentication?')]) ?>
                    </div>
                <?php elseif ($pendingSecret) : ?>
                    <p class="text-sm text-muted-foreground mb-4"><?= __('Add the account to your authenticator app using the details below, then enter the 6-digit code to confirm.') ?></p>
                    <div class="mb-4 space-y-3">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wide text-muted-foreground"><?= __('Secret key') ?></span>
                            <code class="block rounded-lg border border-border bg-accent px-3 py-2 text-sm"><?= h($pendingSecret) ?></code>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wide text-muted-foreground"><?= __('Account / Issuer') ?></span>
                            <code class="block rounded-lg border border-border bg-accent px-3 py-2 text-sm">CakePHP:<?= h($user->email) ?></code>
                        </div>
                    </div>
                    <?= $this->Form->create(null, ['url' => ['action' => 'confirmTwoFactor']]) ?>
                    <fieldset>
                        <legend class="sr-only"><?= __('Verify code') ?></legend>
                        <?= $this->Form->control('code', ['label' => __('Verification code'), 'placeholder' => '123456', 'maxlength' => 6]) ?>
                    </fieldset>
                    <div class="mt-4">
                        <?= $this->Form->button(__('Verify & Enable'), ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']) ?>
                    </div>
                    <?= $this->Form->end() ?>
                <?php else : ?>
                    <p class="text-sm text-muted-foreground"><?= __('Two-factor authentication is currently disabled. Enable it to protect your account with a one-time code from an authenticator app.') ?></p>
                    <div class="mt-4">
                        <?= $this->Form->postLink(__('Enable 2FA'), ['action' => 'enableTwoFactor'], ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90']) ?>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($activeSection === 'sessions') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['sessions']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['sessions']['description']) ?></p>
            </div>
            <div>
                <h2 class="text-base font-semibold mb-4"><?= __('Active sessions') ?></h2>
                <?php
                $currentSessionId = $this->request->getSession()->id();
                ?>
                <?php if (count($active) === 0) : ?>
                    <p class="text-sm text-muted-foreground"><?= __('No active sessions found.') ?></p>
                <?php else : ?>
                    <ul class="divide-y divide-border">
                        <?php foreach ($active as $session) : ?>
                            <li class="flex items-center justify-between gap-4 py-3">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="monitor" class="h-5 w-5 text-muted-foreground"></i>
                                    <div>
                                        <p class="text-sm font-medium">
                                            <?= h($session->device ?: __('Unknown device')) ?>
                                            <?php if ($session->session_id === $currentSessionId) : ?>
                                                <span class="ml-2 text-xs font-medium text-primary"><?= __('Current') ?></span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="text-xs text-muted-foreground"><?= h($session->ip_address ?: '') ?> · <?= h($session->browser ?: '') ?></p>
                                    </div>
                                </div>
                                <?php if ($session->session_id !== $currentSessionId) : ?>
                                    <?= $this->Form->postLink(__('Revoke'), ['action' => 'revokeSession', $session->id], ['class' => 'inline-flex items-center px-3 py-1.5 rounded-lg border border-border text-xs font-medium text-muted-foreground hover:bg-accent', 'confirm' => __('Revoke this session?')]) ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="mt-6">
                <h2 class="text-base font-semibold mb-4"><?= __('Recent activity') ?></h2>
                <?php if (count($history) === 0) : ?>
                    <p class="text-sm text-muted-foreground"><?= __('No recent sessions recorded.') ?></p>
                <?php else : ?>
                    <ul class="divide-y divide-border">
                        <?php foreach ($history as $entry) : ?>
                            <li class="flex items-center justify-between gap-4 py-3">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="history" class="h-5 w-5 text-muted-foreground"></i>
                                    <div>
                                        <p class="text-sm font-medium"><?= h($entry->device ?: __('Unknown device')) ?></p>
                                        <p class="text-xs text-muted-foreground"><?= h($entry->ip_address ?: '') ?> · <?= h($entry->browser ?: '') ?></p>
                                    </div>
                                </div>
                                <span class="text-xs text-muted-foreground"><?= $entry->created ? h($entry->created->format('Y-m-d H:i')) : '' ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <div class="mt-6">
                    <?= $this->Form->postLink(__('Sign out all other sessions'), ['action' => 'revokeOtherSessions'], ['class' => 'inline-flex items-center px-4 py-2 rounded-lg border border-border text-sm font-medium text-muted-foreground hover:bg-accent', 'confirm' => __('Sign out of all other devices?')]) ?>
                </div>
            </div>

        <?php elseif ($activeSection === 'account') : ?>
            <div>
                <h1 class="text-2xl font-semibold"><?= h($sections['account']['title']) ?></h1>
                <p class="text-sm text-muted-foreground"><?= h($sections['account']['description']) ?></p>
            </div>
            <div>
                <h2 class="text-base font-semibold mb-4"><?= __('Export your data') ?></h2>
                <p class="text-sm text-muted-foreground mb-4"><?= __('Download a copy of all your personal data.') ?></p>
                <?= $this->Html->link(__('Download my data'), ['action' => 'exportData'], ['class' => 'inline-flex items-center px-4 py-2 rounded-lg border border-border text-sm font-medium text-muted-foreground hover:bg-accent']) ?>
            </div>
            <div class="mt-6">
                <h2 class="text-base font-semibold mb-4 text-destructive"><?= __('Delete account') ?></h2>
                <p class="text-sm text-muted-foreground mb-4"><?= __('Permanently delete your account and all associated data. This action cannot be undone.') ?></p>
                <?= $this->Form->create(null, ['url' => ['action' => 'deleteAccount']]) ?>
                <fieldset>
                    <legend class="sr-only"><?= __('Delete account') ?></legend>
                    <?= $this->Form->control('password', ['type' => 'password', 'label' => __('Confirm your password'), 'placeholder' => __('Your password')]) ?>
                </fieldset>
                <div class="mt-4">
                    <?= $this->Form->button(__('Delete Account'), ['class' => 'inline-flex items-center px-4 py-2 rounded-lg bg-destructive text-destructive-foreground text-sm font-medium hover:bg-destructive/90', 'confirm' => __('This will permanently delete your account. Continue?')]) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>

        <?php endif; ?>
    </div>
</div>
