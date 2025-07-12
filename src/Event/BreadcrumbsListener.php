<?php
declare(strict_types=1);

namespace App\Event;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;

/**
 * BreadcrumbsListener
 *
 * Automatically sets breadcrumbs for all controllers on beforeRender.
 */
class BreadcrumbsListener implements EventListenerInterface
{
    /**
     * Returns a list of events this listener is interested in.
     *
     * @return array<string, string> Event map
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.beforeRender' => 'onBeforeRender',
        ];
    }

    /**
     * Event handler for Controller.beforeRender.
     * Sets the 'breadcrumbs' variable for the view.
     *
     * @param \Cake\Event\EventInterface $event The event object
     * @return void
     */
    public function onBeforeRender(EventInterface $event): void
    {
        /** @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();
        /** @var \Cake\Http\ServerRequest $request */
        $request = $controller->getRequest();

        $controllerName = $controller->getName();
        // Use getParam if available, otherwise fallback to getAttribute (for CakePHP 5 compatibility)
        $action = method_exists($request, 'getParam') ? $request->getParam('action') : $request->getAttribute('action');
        $pass = method_exists($request, 'getParam') ? $request->getParam('pass') : $request->getAttribute('pass');
        $template = $controller->viewBuilder()->getTemplate();

        // Start with Home and Controller index
        $breadcrumbs = [
            [
                'title' => 'Home',
                'url' => ['controller' => 'Pages', 'action' => 'dashboard'],
            ],
            [
                'title' => __($controllerName),
                'url' => ['controller' => $controllerName, 'action' => 'index'],
            ],
        ];

        // Add action crumb if not index
        if ($action !== 'index') {
            $breadcrumbs[] = [
                'title' => __($action),
                'url' => [
                    'controller' => $controllerName,
                    'action' => $action,
                ] + (isset($pass[0]) ? [$pass[0]] : []),
            ];
        }

        // Add template crumb if template is not the same as action
        if ($template && $template !== $action) {
            $breadcrumbs[] = [
                'title' => __($template),
                'url' => null,
            ];
        } elseif (!empty($pass)) {
            // If there are pass values, use the first as the 4th crumb
            $breadcrumbs[] = [
                'title' => h($pass[0]),
                'url' => null,
            ];
        }

        // Set breadcrumbs for the view
        $controller->set('breadcrumbs', $breadcrumbs);
    }
}
