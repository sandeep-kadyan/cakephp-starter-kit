<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Activities Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\Activity newEmptyEntity()
 * @method \App\Model\Entity\Activity newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Activity> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Activity get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Activity findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Activity patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Activity> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Activity|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Activity saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Activity>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Activity>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Activity>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Activity> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Activity>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Activity>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Activity>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Activity> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ActivitiesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('activities');
        $this->setDisplayField('url');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->requirePresence('url', 'create')
            ->notEmptyString('url');

        $validator
            ->scalar('browser')
            ->maxLength('browser', 255)
            ->requirePresence('browser', 'create')
            ->notEmptyString('browser');

        $validator
            ->scalar('os')
            ->maxLength('os', 255)
            ->requirePresence('os', 'create')
            ->notEmptyString('os');

        $validator
            ->scalar('device')
            ->maxLength('device', 255)
            ->requirePresence('device', 'create')
            ->notEmptyString('device');

        $validator
            ->scalar('ip_address')
            ->maxLength('ip_address', 40)
            ->requirePresence('ip_address', 'create')
            ->notEmptyString('ip_address');

        $validator
            ->scalar('location')
            ->maxLength('location', 255)
            ->requirePresence('location', 'create')
            ->notEmptyString('location');

        $validator
            ->scalar('user_agent')
            ->requirePresence('user_agent', 'create')
            ->notEmptyString('user_agent');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
