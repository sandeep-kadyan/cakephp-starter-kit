<?php
declare(strict_types=1);

namespace App\Event;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Http\ServerRequest;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Event listener for handling AjaxTable integration in controllers and views.
 *
 * Listens for events to automatically inject AjaxTable helpers and process AJAX/JSON requests
 * for AjaxTable data, supporting dynamic column configuration and model resolution.
 *
 * @package App\Event
 */
class AjaxTableListener implements EventListenerInterface
{
    /**
     * Returns a list of events this listener is interested in.
     *
     * @return array<string, string|array> Associative array of event name => handler method.
     */
    public function implementedEvents(): array
    {
        // Register the events and their handler methods
        return [
            'Controller.startup' => 'startup',
        ];
    }

    /**
     * Handle controller startup event to process AjaxTable JSON POST requests.
     *
     * @param \Cake\Event\EventInterface $event The event object.
     * @return \Cake\Event\EventInterface The event, possibly modified.
     */
    public function startup(EventInterface $event): EventInterface
    {
        // Get the controller instance from the event
        $controller = $event->getSubject();
        $request = $controller->getRequest();

        // Check if this is a JSON POST request for AjaxTable data
        if ($request->is('post') && $request->is('json') && $request->getParam('action') == 'index') {
            try {
                // Process and return AjaxTable data
                $response = $this->getAjaxTableData($request);
                $controller->set($response);
                $controller->viewBuilder()->setOption('serialize', array_keys($response));
                $controller->viewBuilder()->setClassName('Json');
            } catch (Exception $e) {
                // Handle errors and show error message
                $controller->Flash->error($e->getMessage());
            }
        }

        return $event;
    }

    /**
     * Get AjaxTable data for an AJAX/JSON request.
     *
     * @param \Cake\Http\ServerRequest $request The request object containing AjaxTable parameters.
     * @return array<string, mixed> The processed AjaxTable response data.
     */
    private function getAjaxTableData(ServerRequest $request): array
    {
        // Get the model instance for the request
        $model = $this->getModel($request);

        // Use the AjaxTable behavior to process the request data
        $response = $model->behaviors()->get('AjaxTable')->processAjaxTable($request->getData());

        return $response;
    }

    /**
     * Get model instance from request parameters (controller/plugin).
     *
     * @param \Cake\Http\ServerRequest $request The request object.
     * @return \Cake\ORM\Table The resolved model table instance.
     */
    private function getModel(ServerRequest $request): Table
    {
        $plugin = $request->getParam('plugin');
        $controller = $request->getParam('controller');

        // Build the model name, including plugin prefix if present
        $controller = $plugin ? $plugin . '.' . $controller : $controller;

        // Fetch the table instance and ensure AjaxTable behavior is attached
        $model = TableRegistry::getTableLocator()->get($controller);
        $model->addBehavior('AjaxTable');

        return $model;
    }
}
