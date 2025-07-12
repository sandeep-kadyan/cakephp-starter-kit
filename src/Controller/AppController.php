<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Mailer\MailerAwareTrait;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    use MailerAwareTrait;

    /**
     * Controller initialization hook.
     *
     * This method is called before every action and is used to load components and perform
     * any setup required for the controller.
     *
     * @return void
     */
    public function initialize(): void
    {
        // Call the parent initialize to ensure base setup is performed
        parent::initialize();

        // Load the flash component for session-based flash messages
        $this->loadComponent('Flash');

        // Load the authentication component for user authentication
        $this->loadComponent('Authentication.Authentication');
    }

    /**
     * beforeFilter callback.
     *
     * This method is called before every controller action. It allows certain actions
     * to be accessed without authentication (e.g., 'display', 'login', 'verify').
     *
     * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event The event instance passed to the callback.
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        // Call the parent beforeFilter to ensure any parent logic is executed
        parent::beforeFilter($event);

        // Allow unauthenticated access to the 'display', 'login', and 'verify' actions
        $this->Authentication->allowUnauthenticated(['display', 'login', 'verify']);
    }

    /**
     * beforeRender callback.
     *
     * This method is called before the view is rendered. It can be used to modify view variables
     * or perform logic that should run before rendering the response.
     *
     * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event The event instance passed to the callback.
     * @return void
     */
    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        // Get the current controller name
        $controller = $this->getName();

        // Get the current action name
        $action = $this->getRequest()->getParam('action');

        // Get the view builder instance
        $builder = $this->viewBuilder();

        // If the user is authenticated and not on the Pages controller, use the 'app' layout
        if ($this->request->getAttribute('identity') && $controller !== 'Pages') {
            $builder->setLayout('app');
        // If the action is 'login' or 'verify', use the 'auth' layout
        } elseif (in_array($action, ['login', 'verify'])) {
            $builder->setLayout('auth');
        // If the controller is 'Pages', use the 'default' layout
        } elseif ($controller == 'Pages') {
            $builder->setLayout('default');
        }

        // Set AjaxTable path
        if (in_array($action, ['index', 'view'])) {
            $this->viewBuilder()->setTemplatePath('AjaxTable');
        }
    }

    /**
     * bulkDelete action.
     *
     * Handles bulk deletion of records for the current controller's model. Expects a POST request
     * with an array of IDs to delete. Returns a JSON response with the result.
     *
     * @return \Cake\Http\Response|null JSON response with success or error message.
     */
    public function bulkDelete()
    {
        $this->getEventManager()->dispatch(
            new Event('Controller.bulkDelete', $this),
        );
    }
}
