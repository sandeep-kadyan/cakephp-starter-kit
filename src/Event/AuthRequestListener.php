<?php
declare(strict_types=1);

namespace App\Event;

use App\Model\Entity\AuthRequest;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Mailer\Mailer;

class AuthRequestListener implements EventListenerInterface
{
    /**
     * Returns a list of events this listener is interested in.
     *
     * @return array<string, string|array>
     */
    public function implementedEvents(): array
    {
        return [
            'Model.afterSave' => 'afterSave',
        ];
    }

    /**
     * Handle afterSave event for AuthRequestsTable.
     *
     * @param \Cake\Event\EventInterface $event The event object.
     * @param \Cake\Datasource\EntityInterface $entity The entity being saved.
     * @param ArrayObject $options Additional options.
     * @return void
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // Only handle AuthRequest entities and only on creation
        if ($entity instanceof AuthRequest && $entity->isNew()) {
            $mailer = new Mailer();
            $mailer
                ->setTo($entity->email)
                ->setSubject('Your Magic Login Link')
                ->setEmailFormat('both')
                ->setViewVars(['authRequest' => $entity])
                ->viewBuilder()
                    ->setTemplate('magic_link');

            $mailer->deliver();
        }
    }
}
