<?php
declare(strict_types=1);

namespace App\Event;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Event listener for handling bulk delete operations in controllers.
 *
 * Listens for the 'Controller.bulkDelete' event and performs bulk deletion of records
 * for the associated model, with special handling for user self-deletion prevention.
 *
 * @package App\Event
 */
class BulkDeleteListener implements EventListenerInterface
{
    /**
     * Returns a list of events this listener is implementing.
     *
     * @return array<string, string> Associative array of event name => handler method.
     */
    public function implementedEvents(): array
    {
        // Register the bulk delete event and its handler
        return [
            'Controller.bulkDelete' => 'onBulkDelete',
        ];
    }

    /**
     * Handles the bulk delete event, performing deletion of multiple records.
     *
     * @param \Cake\Event\EventInterface $event The event object containing context and subject.
     * @return void
     */
    public function onBulkDelete(EventInterface $event): void
    {
        // Get the controller that triggered the event
        $controller = $event->getSubject();
        $request = $controller->getRequest();
        $ids = $request->getData('ids');
        $plugin = $request->getParam('plugin');
        $flash = $request->getAttribute('flash');

        // Only allow POST or JSON requests for bulk delete
        if (!$request->is('post') && (!$request->is('json'))) {
            return;
        }

        // Determine the model class, including plugin prefix if present
        $modelClass = $controller->getName();
        if ($plugin) {
            $modelClass = $plugin . '.' . $modelClass;
        }

        // Get the table instance for the model
        $table = TableRegistry::getTableLocator()->get($modelClass);

        // Prevent users from deleting themselves in bulk delete
        if ($modelClass === 'Users' || $modelClass === 'App.Users') {
            $identity = $request->getAttribute('identity');
            if ($identity && isset($identity->id)) {
                $ids = array_filter($ids, function ($id) use ($identity) {
                    return $id != $identity->id;
                });
            }
        }

        // Ensure the BulkDelete behavior is attached to the table
        if (!$table->hasBehavior('BulkDelete')) {
            $table->addBehavior('BulkDelete');
        }

        if ($table->hasBehavior('BulkDelete')) {
            try {
                // Perform the bulk delete operation
                $result = $table->behaviors()->get('BulkDelete')->bulkDelete($ids);
                $response = ['status' => 'Ok', 'code' => 200, 'result' => $result];
                $controller->set($response);
                $controller->viewBuilder()->setOption('serialize', array_keys($response));
                $controller->viewBuilder()->setClassName('Json');

                $flash->success(sprintf('Selected records has been delete successfully.'));
            } catch (Exception $e) {
                // Handle errors and show error message
                $flash->error($e->getMessage());
            }
        } else {
            // If BulkDelete behavior is not available, show error
            $flash->error(sprintf('Selected records could not delete.'));
        }
    }
}
