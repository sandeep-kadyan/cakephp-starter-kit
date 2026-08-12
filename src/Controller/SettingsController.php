<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use App\Service\UserSettings;
use App\Utility\Totp;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Response;
use Cake\I18n\DateTime;
use Cake\ORM\Table;

/**
 * Settings Controller
 *
 * Personal settings for the authenticated user, organized into tabs/sections:
 * profile, appearance, pagination, notifications, password, two-factor auth,
 * active sessions, account (export/delete), and payment methods.
 */
class SettingsController extends AppController
{
    /**
     * Sections shown in the settings sidebar/tabs.
     */
    public const SECTIONS = [
        'profile' => [
            'title' => 'Profile',
            'icon' => 'user',
            'description' => 'Manage your personal information and public profile.',
        ],
        'appearance' => [
            'title' => 'Appearance',
            'icon' => 'palette',
            'description' => 'Choose how the application looks.',
        ],
        'payments' => [
            'title' => 'Payment Methods',
            'icon' => 'credit-card',
            'description' => 'Manage your saved payment methods.',
        ],
        'pagination' => [
            'title' => 'Pagination',
            'icon' => 'list',
            'description' => 'Default rows per page for tables.',
        ],
        'notifications' => [
            'title' => 'Notifications',
            'icon' => 'bell',
            'description' => 'How you want to be notified.',
        ],
        'password' => [
            'title' => 'Password',
            'icon' => 'lock',
            'description' => 'Change your account password.',
        ],
        'twoFactor' => [
            'title' => 'Two-Factor Authentication',
            'icon' => 'shield-check',
            'description' => 'Add an extra layer of security with TOTP.',
        ],
        'sessions' => [
            'title' => 'Active Sessions',
            'icon' => 'monitor',
            'description' => 'Review and manage your active sessions.',
        ],
        'account' => [
            'title' => 'Account',
            'icon' => 'user-cog',
            'description' => 'Download your data or delete your account.',
        ],
    ];

    /**
     * Settings landing page (2-column layout).
     *
     * The left column shows the section submenu; the right column renders the
     * form for the active section (selected via the `section` query param).
     *
     * @return void
     */
    public function index(): void
    {
        $sections = self::SECTIONS;
        $activeSection = (string)$this->request->getQuery('section', 'profile');
        if (!isset($sections[$activeSection])) {
            $activeSection = 'profile';
        }

        $user = $this->currentUser();
        $settings = new UserSettings();

        $themeDefaults = ['light', 'dark', 'system'];
        $currentTheme = $settings->get($user->id, 'appearance.theme', 'system');

        $allowed = Configure::read('Setting.pagination.pageSizes', [10, 15, 25, 50, 100]);
        $currentPageSize = $settings->get($user->id, 'pagination.default_page_size', $allowed[0] ?? 10);

        $prefs = [
            'email' => (bool)$settings->get($user->id, 'notifications.email.enabled', true),
            'database' => (bool)$settings->get($user->id, 'notifications.database.enabled', true),
        ];

        $sessionsTable = $this->fetchTable('UserSessions');
        $active = $sessionsTable->find()
            ->where(['user_id' => $user->id, 'is_active' => true])
            ->orderByDesc('UserSessions.last_activity')
            ->all();
        $history = $sessionsTable->find()
            ->where(['user_id' => $user->id])
            ->orderByDesc('UserSessions.created')
            ->limit(20)
            ->all();

        $methods = $this->fetchTable('PaymentMethods')->find()->where(['user_id' => $user->id])->all();

        $totp = new Totp();
        $isEnabled = $user->get('two_factor_confirmed_at') !== null && $user->get('two_factor_secret') !== null;
        $pendingSecret = $activeSection === 'twoFactor'
            ? $this->request->getSession()->consume('2fa_pending_secret')
            : null;

        $this->set(compact(
            'sections',
            'activeSection',
            'user',
            'currentTheme',
            'themeDefaults',
            'currentPageSize',
            'allowed',
            'prefs',
            'active',
            'history',
            'methods',
            'totp',
            'isEnabled',
            'pendingSecret',
        ));
    }

    /**
     * Current authenticated user entity.
     *
     * @return \App\Model\Entity\User
     */
    protected function currentUser(): User
    {
        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            throw new ForbiddenException('You must be logged in.');
        }
        $user = $this->fetchTable('Users')->get($identity->getIdentifier());

        return $user;
    }

    /**
     * Profile: edit name, username, email.
     */
    public function profile(): ?Response
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
            return $this->redirect(['action' => 'index', '?' => ['section' => 'profile']]);
        }

        $user = $this->currentUser();
        $user = $this->fetchTable('Users')->patchEntity($user, $this->request->getData(), [
            'fieldList' => ['name', 'username', 'email'],
        ]);
        if ($this->fetchTable('Users')->save($user)) {
            $this->Flash->success(__('Your profile has been updated.'));
        } else {
            $this->Flash->error(__('Could not update profile. Please try again.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['section' => 'profile']]);
    }

    /**
     * Appearance: theme preference (light/dark/system) + density.
     */
    public function appearance(): ?Response
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
            return $this->redirect(['action' => 'index', '?' => ['section' => 'appearance']]);
        }

        $user = $this->currentUser();
        $settings = new UserSettings();
        $key = 'appearance.theme';
        $defaults = ['light', 'dark', 'system'];

        $theme = (string)$this->request->getData('theme');
        if (!in_array($theme, $defaults, true)) {
            $theme = 'system';
        }
        $settings->set($user->id, $key, $theme);
        $this->Flash->success(__('Appearance settings saved.'));

        return $this->redirect(['action' => 'index', '?' => ['section' => 'appearance']]);
    }

    /**
     * Pagination: default page size.
     */
    public function pagination(): ?Response
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
            return $this->redirect(['action' => 'index', '?' => ['section' => 'pagination']]);
        }

        $user = $this->currentUser();
        $settings = new UserSettings();
        $key = 'pagination.default_page_size';
        $allowed = Configure::read('Setting.pagination.pageSizes', [10, 15, 25, 50, 100]);

        $size = (int)$this->request->getData('page_size');
        if (!in_array($size, $allowed, true)) {
            $size = 10;
        }
        $settings->set($user->id, $key, $size);
        $this->Flash->success(__('Pagination settings saved.'));

        return $this->redirect(['action' => 'index', '?' => ['section' => 'pagination']]);
    }

    /**
     * Notifications toggles.
     */
    public function notifications(): ?Response
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
            return $this->redirect(['action' => 'index', '?' => ['section' => 'notifications']]);
        }

        $user = $this->currentUser();
        $settings = new UserSettings();
        $keys = ['notifications.email.enabled', 'notifications.database.enabled'];
        $data = $this->request->getData();
        $settings->set($user->id, $keys[0], !empty($data['email']));
        $settings->set($user->id, $keys[1], !empty($data['database']));
        $this->Flash->success(__('Notification preferences saved.'));

        return $this->redirect(['action' => 'index', '?' => ['section' => 'notifications']]);
    }

    /**
     * Change password (current + new + confirm).
     */
    public function password(): ?Response
    {
        if (!$this->request->is(['patch', 'post', 'put'])) {
            return $this->redirect(['action' => 'index', '?' => ['section' => 'password']]);
        }

        $user = $this->currentUser();
        $data = $this->request->getData();
        $hasher = new DefaultPasswordHasher();

        $current = (string)($data['current_password'] ?? '');
        $new = (string)($data['password'] ?? '');
        $confirm = (string)($data['confirm_password'] ?? '');

        if (!$hasher->check($current, $user->get('password'))) {
            $this->Flash->error(__('Current password is incorrect.'));
        } elseif (strlen($new) < 8) {
            $this->Flash->error(__('New password must be at least 8 characters.'));
        } elseif ($new !== $confirm) {
            $this->Flash->error(__('New passwords do not match.'));
        } else {
            $user = $this->fetchTable('Users')->patchEntity($user, ['password' => $new]);
            if ($this->fetchTable('Users')->save($user)) {
                $this->Flash->success(__('Your password has been changed.'));
            } else {
                $this->Flash->error(__('Unable to save the new password.'));
            }
        }

        return $this->redirect(['action' => 'index', '?' => ['section' => 'password']]);
    }

    /**
     * Two-factor authentication management.
     *
     * GET shows status + (if a secret is pending verification) the QR/code form.
     * POST `enable` generates a pending secret (stored only in session).
     * POST `confirm` verifies the code then activates 2FA.
     * POST `disable` turns 2FA off.
     * POST `regenerate` issues fresh recovery codes.
     */
    public function twoFactor(): ?Response
    {
        return $this->redirect(['action' => 'index', '?' => ['section' => 'twoFactor']]);
    }

    /**
     * Start enabling 2FA: generate a pending secret stored only in the session.
     */
    public function enableTwoFactor(): ?Response
    {
        $secret = Totp::generateSecret();
        $this->request->getSession()->write('2fa_pending_secret', $secret);

        return $this->redirect(['action' => 'index', '?' => ['section' => 'twoFactor']]);
    }

    /**
     * Confirm and activate 2FA using the code from the authenticator app.
     */
    public function confirmTwoFactor(): ?Response
    {
        $user = $this->currentUser();
        $totp = new Totp();
        $session = $this->request->getSession();
        $secret = $session->consume('2fa_pending_secret');

        $code = (string)$this->request->getData('code');
        if ($secret && $totp->verify($code, $secret)) {
            $recipients = str_shuffle(str_repeat('0123456789ABCDEFGHJKLMNPQRSTUVWXYZ', 5));
            $codes = [];
            for ($i = 0; $i < 8; $i++) {
                $codes[] = substr($recipients, $i * 10, 10);
            }
            $this->fetchTable('Users')->updateAll(
                [
                    'two_factor_secret' => $secret,
                    'two_factor_recovery_codes' => json_encode(array_values($codes)),
                    'two_factor_confirmed_at' => new DateTime(),
                ],
                ['id' => $user->id],
            );
            $this->Flash->success(__('Two-factor authentication has been enabled.'));
        } else {
            $this->Flash->error(__('Invalid verification code.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['section' => 'twoFactor']]);
    }

    /**
     * Disable two-factor authentication for the current user.
     */
    public function disableTwoFactor(): ?Response
    {
        $user = $this->currentUser();
        $this->fetchTable('Users')->updateAll(
            [
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ],
            ['id' => $user->id],
        );
        $this->request->getSession()->write('2fa_pending_secret', null);
        $this->Flash->success(__('Two-factor authentication has been disabled.'));

        return $this->redirect(['action' => 'index', '?' => ['section' => 'twoFactor']]);
    }

    /**
     * Issue a fresh set of recovery codes (only when 2FA is enabled).
     */
    public function regenerateRecoveryCodes(): ?Response
    {
        $user = $this->currentUser();
        // Only regenerate if 2FA is actually enabled with a secret.
        if ($user->get('two_factor_confirmed_at') === null || !$user->get('two_factor_secret')) {
            $this->Flash->error(__('Two-factor authentication is not enabled.'));

            return $this->redirect(['action' => 'index', '?' => ['section' => 'twoFactor']]);
        }

        $recipients = str_shuffle(str_repeat('0123456789ABCDEFGHJKLMNPQRSTUVWXYZ', 5));
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = substr($recipients, $i * 10, 10);
        }
        $this->fetchTable('Users')->updateAll(
            ['two_factor_recovery_codes' => json_encode(array_values($codes))],
            ['id' => $user->id],
        );
        $this->Flash->success(__('New recovery codes have been generated.'));

        return $this->redirect(['action' => 'index', '?' => ['section' => 'twoFactor']]);
    }

    /**
     * Active sessions + history for the current user.
     */
    public function sessions(): ?Response
    {
        return $this->redirect(['action' => 'index', '?' => ['section' => 'sessions']]);
    }

    /**
     * Revoke a single active session by id.
     *
     * @param string $id The session id to revoke.
     */
    public function revokeSession(string $id): ?Response
    {
        $user = $this->currentUser();
        $this->fetchTable('UserSessions')->updateAll(
            ['is_active' => false, 'expired_at' => new DateTime()],
            ['id' => $id, 'user_id' => $user->id],
        );
        $this->Flash->success(__('Session has been revoked.'));

        return $this->redirect(['action' => 'index', '?' => ['section' => 'sessions']]);
    }

    /**
     * Sign out all of the user's sessions except the current one.
     */
    public function revokeOtherSessions(): ?Response
    {
        $user = $this->currentUser();
        $currentSessionId = $this->request->getSession()->id();
        $this->fetchTable('UserSessions')->updateAll(
            ['is_active' => false, 'expired_at' => new DateTime()],
            ['user_id' => $user->id, 'is_active' => true, 'session_id !=' => $currentSessionId],
        );
        $this->Flash->success(__('All other sessions have been signed out.'));

        return $this->redirect(['action' => 'index', '?' => ['section' => 'sessions']]);
    }

    /**
     * Account: export data or delete the account.
     */
    public function account(): ?Response
    {
        return $this->redirect(['action' => 'index', '?' => ['section' => 'account']]);
    }

    /**
     * Download all of the current user's data as JSON.
     */
    public function exportData(): ?Response
    {
        $user = $this->currentUser();
        $users = $this->fetchTable('Users');
        $activities = $this->fetchTable('Activities');
        $sessions = $this->fetchTable('UserSessions');
        $settings = $this->fetchTable('UserSettings');
        $payments = $this->fetchTable('PaymentMethods');

        $payload = [
            'user' => $users->get($user->id)->toArray(),
            'activities' => $this->exportRows($activities, $user->id),
            'sessions' => $this->exportRows($sessions, $user->id),
            'settings' => $this->exportRows($settings, $user->id),
            'payment_methods' => $this->exportRows($payments, $user->id),
            'exported_at' => (string)new DateTime(),
        ];

        $body = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $filename = 'my-data-' . $user->id . '-' . gmdate('Y-m-d') . '.json';

        return $this->getResponse()
            ->withType('application/json')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->withStringBody($body);
    }

    /**
     * Fetch all rows for a user from the given table as plain arrays.
     *
     * @param \Cake\ORM\Table $table The table to query.
     * @param string $userId The owning user id.
     * @return array<array<string, mixed>>
     */
    private function exportRows(Table $table, string $userId): array
    {
        return $table->find()
            ->where(['user_id' => $userId])
            ->enableHydration(false)
            ->all()
            ->toList();
    }

    /**
     * Delete the current user's account (requires password confirmation).
     */
    public function deleteAccount(): ?Response
    {
        $user = $this->currentUser();
        $data = $this->request->getData();

        $hasher = new DefaultPasswordHasher();
        if (!$hasher->check((string)($data['password'] ?? ''), $user->get('password'))) {
            $this->Flash->error(__('Password confirmation is incorrect.'));

            return $this->redirect(['action' => 'index', '?' => ['section' => 'account']]);
        }

        $this->fetchTable('Users')->getConnection()->transactionalClosure(function () use ($user): void {
            // Clear 2FA / sessions / settings for the user's data privacy on deletion.
            $this->fetchTable('UserSettings')->deleteAll(['user_id' => $user->id]);
            $this->fetchTable('UserSessions')->deleteAll(['user_id' => $user->id]);
            $this->fetchTable('PaymentMethods')->deleteAll(['user_id' => $user->id]);
            $this->fetchTable('Activities')->deleteAll(['user_id' => $user->id]);
            // Optionally soft-delete here. Hard-delete is chosen for GDPR "right to be forgotten".
            $this->fetchTable('Users')->delete($user);
        });

        $this->Authentication->logout();
        $this->Flash->success(__('Your account and all associated data have been deleted.'));

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    /**
     * Payment methods list + add.
     *
     * NOTE: This is a placeholder scaffold. No card data (PAN) is ever stored.
     * It stores only an external provider reference (e.g. a Stripe customer id)
     * once a real gateway integration is wired in.
     */
    public function payments(): ?Response
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'index', '?' => ['section' => 'payments']]);
        }

        $user = $this->currentUser();
        $paymentsTable = $this->fetchTable('PaymentMethods');
        $entity = $paymentsTable->newEntity([
            'user_id' => $user->id,
            'provider' => (string)$this->request->getData('provider'),
            'identifier' => (string)$this->request->getData('identifier'),
            'details' => (string)$this->request->getData('details'),
            'is_default' => !empty($this->request->getData('is_default')),
        ]);
        if ($paymentsTable->save($entity)) {
            $this->Flash->success(__('Payment method saved.'));
        } else {
            $this->Flash->error(__('Could not save the payment method.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['section' => 'payments']]);
    }

    /**
     * Remove a saved payment method by id.
     *
     * @param string $id The payment method id to remove.
     */
    public function deletePayment(string $id): ?Response
    {
        $user = $this->currentUser();
        $this->fetchTable('PaymentMethods')->deleteAll(['id' => $id, 'user_id' => $user->id]);
        $this->Flash->success(__('Payment method removed.'));

        return $this->redirect(['action' => 'index', '?' => ['section' => 'payments']]);
    }
}
