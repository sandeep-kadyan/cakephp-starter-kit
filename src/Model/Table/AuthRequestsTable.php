<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AuthRequests Model
 *
 * @method \App\Model\Entity\AuthRequest newEmptyEntity()
 * @method \App\Model\Entity\AuthRequest newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\AuthRequest> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AuthRequest get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\AuthRequest findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\AuthRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\AuthRequest> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AuthRequest|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\AuthRequest saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\AuthRequest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AuthRequest>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AuthRequest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AuthRequest> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AuthRequest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AuthRequest>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AuthRequest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AuthRequest> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AuthRequestsTable extends Table
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

        $this->setTable('auth_requests');
        $this->setDisplayField('email');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('verification_token')
            ->maxLength('verification_token', 255)
            ->requirePresence('verification_token', 'create')
            ->notEmptyString('verification_token');

        $validator
            ->dateTime('expires')
            ->requirePresence('expires', 'create')
            ->notEmptyDateTime('expires');

        $validator
            ->dateTime('verified_at')
            ->allowEmptyDateTime('verified_at');

        return $validator;
    }
}
