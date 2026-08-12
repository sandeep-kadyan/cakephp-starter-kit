<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Service\EnvironmentService;
use Cake\Datasource\FactoryLocator;
use Cake\Log\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * ActivityTracker middleware
 */
class ActivityTrackerMiddleware implements MiddlewareInterface
{
    /**
     * Process method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $identity = $request->getAttribute('identity');
        if ($identity && isset($identity->id)) {
            // Check if user still exists in DB
            $usersTable = FactoryLocator::get('Table')->get('Users');
            $user = $usersTable->find()->where(['id' => $identity->id])->first();
            if (!$user) {
                // User no longer exists, clear session and redirect
                $session = $request->getAttribute('session');
                $session->destroy();
            }
        }

        // Do not store activities for JSON/AJAX requests (they are table data fetches)
        $acceptHeader = $request->getHeaderLine('Accept');
        if (strpos($acceptHeader, 'application/json') !== false) {
            return $handler->handle($request);
        }

        // Track activity for the authenticated user so each user has their own feed
        $userId = $identity?->getIdentifier();
        if ($userId) {
            try {
                $env = new EnvironmentService($request);
                $activities = FactoryLocator::get('Table')->get('activities');
                $activity = $activities->newEntity([
                    'user_id' => $userId,
                    'url' => $env->getRequestUri(),
                    'browser' => $env->getBrowser(),
                    'os' => $env->getOs(),
                    'device' => $env->getDevice(),
                    'ip_address' => $env->getRemoteAddr(),
                    'location' => 'delhi', //gethostbyaddr($ip)
                    'user_agent' => $env->getUserAgent(),
                ]);
                $activities->save($activity);
            } catch (\Throwable $e) {
                // Activity tracking must never break the request
                Log::error('Failed to track activity: ' . $e->getMessage());
            }
        }

        return $handler->handle($request);
    }
}
