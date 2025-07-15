<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\MagicLoginForm;
use App\Form\VerifyMagicLoginForm;
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
     * @return \Cake\Http\Response|null Redirects to target on success, null otherwise.
     */
    public function login()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/dashboard';
            if (!$target) {
                $target = ['controller' => 'Pages', 'action' => 'dashboard'];
            }

            return $this->redirect($target);
        }

        // Initialize magic form
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
                        __('A magic link has been sent to your email. It will be valid for only 5 minutes. Please check your inbox.'),
                    );

                    return $this->redirect(['controller' => 'Users', 'action' => 'verify']);
                } else {
                    $this->Flash->error(__('Could not create magic login request.'));
                }
            }
        }

        $this->set(compact('magicLoginForm'));
        $this->viewBuilder()
            ->setLayout('auth')
            ->setTemplate(Configure::read('Setting.auth.login', 'magic_login'))
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
        $this->Authentication->logout();

        return $this->redirect(['controller' => 'Pages', 'action' => 'display', 'welcome']);
    }
}
