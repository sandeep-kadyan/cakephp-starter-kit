<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sessions Model
 *
 * @method \App\Model\Entity\Session newEmptyEntity()
 * @method \App\Model\Entity\Session newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Session> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Session get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Session findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Session patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Session> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Session|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Session saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Session>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Session>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Session>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Session> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Session>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Session>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Session>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Session> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SessionsTable extends Table
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

        $this->setTable('sessions');
        $this->setDisplayField('id');
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
            ->allowEmptyString('data');

        $validator
            ->nonNegativeInteger('expires')
            ->allowEmptyString('expires');

        return $validator;
    }
}
