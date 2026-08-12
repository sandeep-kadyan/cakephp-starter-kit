<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PaymentMethods Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\PaymentMethod newEmptyEntity()
 * @method \App\Model\Entity\PaymentMethod newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\PaymentMethod get(mixed $primaryKey, array|string|null $optional = null)
 * @method \App\Model\Entity\PaymentMethod|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PaymentMethodsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('payment_methods');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('provider')
            ->maxLength('provider', 64)
            ->requirePresence('provider', 'create')
            ->notEmptyString('provider')
            ->add('provider', 'inList', ['rule' => ['inList', ['stripe', 'paypal', 'manual']], 'message' => 'Invalid provider.']);

        $validator
            ->maxLength('identifier', 255)
            ->allowEmptyString('identifier');

        $validator
            ->maxLength('details', 16777215)
            ->allowEmptyString('details');

        $validator
            ->scalar('status')
            ->maxLength('status', 32)
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->add('status', 'inList', ['rule' => ['inList', ['active', 'inactive']], 'message' => 'Invalid status.']);

        $validator
            ->boolean('is_default')
            ->requirePresence('is_default', 'create')
            ->notEmptyString('is_default');

        return $validator;
    }

    /**
     * Ensure only one default payment method per user.
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add(function ($entity) {
            if (!$entity->get('is_default')) {
                return true;
            }
            $existing = $this->find()
                ->select(['id'])
                ->where([
                    'user_id' => $entity->get('user_id'),
                    'is_default' => true,
                ])
                ->where(function ($q) use ($entity) {
                    return $q->notEq('id', $entity->get('id'));
                })
                ->limit(1)
                ->count();

            return $existing === 0;
        }, 'oneDefaultPerUser', [
            'errorField' => 'is_default',
            'message' => 'You can only have one default payment method per user.',
        ]);

        return $rules;
    }
}
