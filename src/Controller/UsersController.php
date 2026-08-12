<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\MagicLoginForm;
use App\Form\VerifyMagicLoginForm;
use App\Utility\Totp;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\I18n\DateTime;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication AuthenticationComponent
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index(): void
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        if ($id == null) {
            $result = $this->Authentication->getResult();

            if ($result && !$result->isValid()) {
                $this->Flash->error(__('No user found. Please try to login again.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }

            $user = $this->request->getAttribute('identity');
            $id = $user->id;
        }

        $user = $this->Users->get($id, contain: ['Activities']);
        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Handles user login. Redirects authenticated users and shows error on failure.
     *
     * The behaviour depends on the configured login type (`Setting.auth.login`):
     * - `login` (default): username/email + password via the password identifier.
     * - `magic_login`: email-only form that sends a magic link.
     * - `social`: social login provider buttons.
     *
     * When a user with 2FA enabled authenticates with a password, login is not
     * completed yet; they are sent to the OTP verification step first.
     *
     * @return \Cake\Http\Response|null Redirects to target on success, null otherwise.
     */
    public function login()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $user = $this->request->getAttribute('identity');
            if ($user && $user->get('two_factor_secret') && $user->get('two_factor_confirmed_at') !== null) {
                // Password was correct but the user has 2FA enabled. Do not
                // finalize login; hand off to the OTP verification step.
                $this->request->getSession()->write('Auth.pending_2fa_user_id', $user->get('id'));
                $this->Authentication->logout();

                return $this->redirect(['controller' => 'Users', 'action' => 'verifyOtp']);
            }

            $target = $this->Authentication->getLoginRedirect() ?? ['controller' => 'Pages', 'action' => 'dashboard'];

            $this->recordLogin($user);

            return $this->redirect($target);
        }

        $loginType = Configure::read('Setting.auth.login', 'login');
        $template = 'login';

        if ($loginType === 'magic_login') {
            // Email-only magic link login.
            $magicLoginForm = new MagicLoginForm();

            if ($this->request->is('post')) {
                $data = $this->request->getData();
                if ($magicLoginForm->validate($data)) {
                    $authRequests = $this->fetchTable('AuthRequests');
                    $data['verification_token'] = bin2hex(random_bytes(16));
                    $data['expires'] = DateTime::now()->addMinutes(10);
                    $authRequest = $authRequests->newEmptyEntity();
                    $authRequest = $authRequests->patchEntity($authRequest, $data);

                    if ($authRequests->save($authRequest)) {
                        $this->Flash->success(
                            __('A magic link has been sent to your email. It will be valid for only 10 minutes. Please check your inbox.'),
                        );

                        return $this->redirect(['controller' => 'Users', 'action' => 'verify']);
                    }
                    $this->Flash->error(__('Could not create magic login request.'));
                }
            }

            $this->set(compact('magicLoginForm'));
            $template = 'magic_login';
        } elseif ($loginType === 'social') {
            // Social login provider buttons.
            $this->set('socialProviders', Configure::read('Setting.auth.social', []));
            $template = 'social_login';
        } else {
            // Default: username/email + password login.
            $this->set('socialProviders', Configure::read('Setting.auth.social', []));

            if ($this->request->is('post')) {
                $this->Flash->error(__('Invalid username or password. Please try again.'));
            }
            $template = 'login';
        }

        $this->viewBuilder()
            ->setLayout('auth')
            ->setTemplate($template)
            ->setTemplatePath('element/form/auth/');

        return null;
    }

    /**
     * Handles the 2FA (TOTP) verification step after a successful password login.
     *
     * Requires a pending 2FA login initiated by `login()`. Verifies the code
     * against the user's `two_factor_secret` and only then sets the identity.
     *
     * @return \Cake\Http\Response|null Redirects to dashboard on success, renders form otherwise.
     */
    public function verifyOtp()
    {
        $userId = $this->request->getSession()->read('Auth.pending_2fa_user_id');
        if (!$userId) {
            $this->Flash->error(__('Your session has expired. Please log in again.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $user = $this->Users->findById($userId)->first();
        if (!$user || !$user->get('two_factor_secret') || $user->get('two_factor_confirmed_at') === null) {
            $this->request->getSession()->delete('Auth.pending_2fa_user_id');
            $this->Flash->error(__('Your session has expired. Please log in again.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        if ($this->request->is('post')) {
            $code = (string)$this->request->getData('code');
            $totp = new Totp();

            if ($totp->verify($code, $user->get('two_factor_secret'))) {
                $this->request->getSession()->delete('Auth.pending_2fa_user_id');
                $this->Authentication->setIdentity($user);
                $this->Flash->success(__('You are now logged in!'));
                $this->recordLogin($user);

                return $this->redirect(['controller' => 'Pages', 'action' => 'dashboard']);
            }

            $this->Flash->error(__('Invalid verification code. Please try again.'));
        }

        $this->set(compact('user'));
        $this->viewBuilder()
            ->setLayout('auth')
            ->setTemplate('otp')
            ->setTemplatePath('element/form/auth/');

        return null;
    }

    /**
     * Handles user registration. Creates a new user account and redirects to login.
     *
     * @return \Cake\Http\Response|null Redirects to login on success, renders form otherwise.
     */
    public function register()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data);

            $password = $data['password'] ?? '';
            $confirmPassword = $data['confirm_password'] ?? '';
            if ($password !== $confirmPassword) {
                $user->setError('confirm_password', [__('Passwords do not match.')]);
            }

            if (!$user->getErrors() && $this->Users->save($user)) {
                $this->Flash->success(__('Your account has been created. Please log in.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
            $this->Flash->error(__('Unable to create your account. Please review the errors below and try again.'));
        }
        $this->set(compact('user'));
        $this->viewBuilder()
            ->setLayout('auth')
            ->setTemplate('register')
            ->setTemplatePath('element/form/auth/');
    }

    /**
     * Handles the forgot password request. Sends a reset link email if the account exists.
     *
     * @return \Cake\Http\Response|null Redirects to login after sending the link.
     */
    public function forgotPassword()
    {
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');

            $user = $this->Users->findByEmail($email)->first();
            if ($user) {
                $authRequests = $this->fetchTable('AuthRequests');
                $data = [
                    'email' => $email,
                    'verification_token' => bin2hex(random_bytes(16)),
                    'expires' => DateTime::now()->addMinutes(30),
                ];
                $authRequest = $authRequests->newEmptyEntity();
                $authRequest = $authRequests->patchEntity($authRequest, $data);

                if ($authRequests->save($authRequest, ['email_template' => 'password_reset'])) {
                    $this->Flash->success(__('If an account exists with that email, a password reset link has been sent.'));
                } else {
                    $this->Flash->error(__('Unable to send the password reset link. Please try again.'));
                }
            } else {
                // Always show the same message to avoid leaking which emails are registered.
                $this->Flash->success(__('If an account exists with that email, a password reset link has been sent.'));
            }

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $this->viewBuilder()
            ->setLayout('auth')
            ->setTemplate('forgot_password')
            ->setTemplatePath('element/form/auth/');
    }

    /**
     * Handles password reset via a token from the reset email.
     *
     * @param string|null $token The verification token from the reset link.
     * @return \Cake\Http\Response|null Redirects to login on success, renders form otherwise.
     */
    public function resetPassword(?string $token = null)
    {
        $authRequests = $this->fetchTable('AuthRequests');

        if (!$token) {
            $token = $this->request->getQuery('token');
        }
        if (!$token && $this->request->is('post')) {
            $token = $this->request->getData('token');
        }

        $authRequest = null;
        if ($token) {
            $authRequest = $authRequests->find()
                ->where([
                    'verification_token' => $token,
                    'verified_at IS' => null,
                    'expires >=' => DateTime::now(),
                ])
                ->orderBy(['created' => 'DESC'])
                ->first();
        }

        if (!$authRequest) {
            $this->Flash->error(__('Invalid or expired password reset token. Please request a new reset link.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'forgotPassword']);
        }

        $user = $this->Users->findByEmail($authRequest->email)->first();
        if (!$user) {
            $this->Flash->error(__('No account found for that email address.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'forgotPassword']);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $password = $data['password'] ?? '';
            $confirmPassword = $data['confirm_password'] ?? '';

            $user = $this->Users->patchEntity($user, ['password' => $password]);
            if ($password !== $confirmPassword) {
                $user->setError('confirm_password', [__('Passwords do not match.')]);
            }

            if (!$user->getErrors() && $this->Users->save($user)) {
                $authRequest->verified_at = DateTime::now();
                $authRequests->save($authRequest);

                $this->Flash->success(__('Your password has been reset. Please log in with your new password.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
            $this->Flash->error(__('Unable to reset your password. Please review the errors below and try again.'));
        }

        $this->set(compact('user', 'token', 'authRequest'));
        $this->viewBuilder()
            ->setLayout('auth')
            ->setTemplate('password_reset')
            ->setTemplatePath('element/form/auth/');
    }

    /**
     * Handles magic login verification via GET or POST token.
     *
     * - Accepts a token via GET or POST.
     * - Validates the token and checks for expiration and prior use.
     * - If valid, marks the request as verified, creates a user if needed, logs in the user, and redirects to dashboard.
     * - Handles all error cases with user feedback and appropriate redirects.
     * - If no token is provided or verification fails, shows the verification form again.
     *
     * @return \Cake\Http\Response|null Redirects on success or error, renders form otherwise.
     */
    public function verify()
    {
        $authRequests = $this->fetchTable('AuthRequests');
        $token = $this->request->getQuery('token');

        // If POST, override token with submitted value and check for missing token
        if ($this->request->is('post')) {
            $token = $this->request->getData('token');
            if (!$token) {
                $this->Flash->error(__('No verification token provided. Please try to login again.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
        }

        if ($token) {
            // Find a valid, unverified, unexpired auth request for this token
            $authRequest = $authRequests->find()
                ->where([
                    'verification_token' => $token,
                    'verified_at IS' => null,
                    'expires >=' => DateTime::now(),
                ])
                ->orderBy(['created' => 'DESC'])
                ->first();
            
            if (!$authRequest) {
                // No valid request found (expired, already used, or invalid token)
                $this->Flash->error(__('Invalid or expired verification token. Please request a new magic link.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }

            if ($authRequest->verification_token === $token) {
                // Mark the auth request as verified
                $authRequest->verified_at = DateTime::now();
                if (!$authRequests->save($authRequest)) {
                    $this->Flash->error(__('Could not mark the login request as verified. Please try again.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                }

                // Try to find an existing user by email or username
                $user = $this->Users->find()
                    ->where([
                        'OR' => [
                            'username' => $authRequest->email,
                            'email' => $authRequest->email,
                        ],
                    ])
                    ->first();
                if (!$user) {
                    // If user does not exist, create a new one from email
                    $email = $authRequest->email;
                    $username = $email;
                    $name = $email;
                    if ($email && strpos($email, '@') !== false) {
                        $username = substr($email, 0, strpos($email, '@'));
                        $name = ucfirst($username);
                    }
                    $userData = [
                        'username' => $username,
                        'email' => $email,
                        'name' => $name,
                        'password' => bin2hex(random_bytes(8)),
                        'last_active_at' => DateTime::now(),
                    ];
                    $user = $this->Users->newEntity($userData);
                    if (!$this->Users->save($user)) {
                        // User creation failed
                        $this->Flash->error(__('Unable to create user. Please contact support.'));
                        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                    }
                }

                // Update last_active_at for the user
                $user = $this->Users->patchEntity($user, ['last_active_at' => DateTime::now()]);
                if (!$this->Users->save($user)) {
                    $this->Flash->error(__('Unable to update user activity. Please try again.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                }

                // Log in the user and clean up the auth request
                $this->Authentication->setIdentity($user);
                $authRequests->delete($authRequest);
                $this->Flash->success(__('You are now logged in!'));
                $this->recordLogin($user);

                return $this->redirect(['controller' => 'Pages', 'action' => 'dashboard']);
            } else {
                // Token mismatch (should not happen if query is correct)
                $this->Flash->error(__('Verification token mismatch. Please request a new magic link.'));
            }
        }

        // If no token or verification failed, show the verification form again
        $verifyMagicLoginForm = new VerifyMagicLoginForm();
        $this->set(compact('verifyMagicLoginForm'));
        $this->viewBuilder()
            ->setLayout('auth')
            ->setTemplate(Configure::read('Setting.auth.verify', 'verify_magic_login'))
            ->setTemplatePath('element/form/auth/');
    }

    /**
     * Logs out the current user and redirects to the login page.
     *
     * @return \Cake\Http\Response Redirect response to login page.
     */
    public function logout(): Response
    {
        $identity = $this->request->getAttribute('identity');
        if ($identity) {
            $this->recordLogout($identity);
        }

        $this->Authentication->logout();

        return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'welcome']);
    }

    /**
     * Record a login event for session history tracking.
     */
    protected function recordLogin(object $user): void
    {
        try {
            $sessions = $this->fetchTable('UserSessions');
            $sessions->recordLogin($user->get('id'), $this->request->getSession()->id());
        } catch (\Throwable $e) {
            // Session tracking must never block login
        }
    }

    /**
     * Record that the user's session was closed on logout.
     */
    protected function recordLogout(object $identity): void
    {
        try {
            $sessions = $this->fetchTable('UserSessions');
            $sessionId = $this->request->getSession()->id();
            $sessions->logout($sessionId, $identity->getIdentifier());
        } catch (\Throwable $e) {
        }
    }
}
