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
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Event\AjaxTableListener;
use App\Event\AuthRequestListener;
use App\Event\BreadcrumbsListener;
use App\Event\BulkDeleteListener;
use App\Middleware\ActivityTrackerMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\AbstractIdentifier;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventManager;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI !== 'cli') {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false),
            );
        }

        // Attach Listener for an event
        EventManager::instance()->on(new AjaxTableListener());
        EventManager::instance()->on(new AuthRequestListener());
        EventManager::instance()->on(new BreadcrumbsListener());
        EventManager::instance()->on(new BulkDeleteListener());
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance.
            // See https://github.com/CakeDC/cakephp-cached-routing
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/5/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            ->add(new AuthenticationMiddleware($this))

            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/5/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]))

            // Add ActiveUserCheckMiddleware after authentication
            ->add(new ActivityTrackerMiddleware());

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/5/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

    /**
     * Returns a service provider instance.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();

        if ($request->getParam('prefix') == 'api') {
            // $service->loadIdentifier('Authentication.Callback', [
            //     'callback' => function($data) {
            //         // do identifier logic

            //         if ($result) {
            //             return new Result($result, Result::SUCCESS);
            //         }

            //         return new Result(
            //             null,
            //             Result::FAILURE_OTHER,
            //             ['message' => 'Removed user.']
            //         );
            //     },
            // ]);
        } else {
            // Define where users should be redirected to when they are not authenticated
            $service->setConfig([
                'unauthenticatedRedirect' => '/login',
                'queryParam' => 'redirect',
            ]);

            $fields = [
                AbstractIdentifier::CREDENTIAL_USERNAME => 'email',
                AbstractIdentifier::CREDENTIAL_PASSWORD => 'password',
            ];
            // Load the authenticators. Session should be first.
            $service->loadAuthenticator('Authentication.Session');
            $service->loadAuthenticator('Authentication.Form', [
                'fields' => $fields,
                'loginUrl' => '/login',
            ]);
            $service->loadAuthenticator('Authentication.Token', [
                'queryParam' => 'token',
                'header' => 'Authorization',
                'tokenPrefix' => 'Token',
            ]);
            $service->loadAuthenticator('Authentication.Cookie', [
                'fields' => $fields,
                'loginUrl' => '/login',
            ]);
            $service->loadIdentifier('Authentication.Password', [
                compact('fields'),
                'resolver' => [
                    'className' => 'Authentication.Orm',
                    'userModel' => 'Users',
                    'finder' => 'active', // default: 'all'
                ],
                'passwordHasher' => [
                    'className' => 'Authentication.Fallback',
                    'hashers' => [
                        'Authentication.Default',
                        [
                            'className' => 'Authentication.Legacy',
                            'hashType' => 'md5',
                        ],
                    ],
                ],
            ]);

            // $service->loadIdentifier('Authentication.Callback', [
            //     'callback' => function($data) {
            //         // do identifier logic

            //         if ($result) {
            //             return new Result($result, Result::SUCCESS);
            //         }

            //         return new Result(
            //             null,
            //             Result::FAILURE_OTHER,
            //             ['message' => 'Removed user.']
            //         );
            //     },
            // ]);
        }

        return $service;
    }
}
