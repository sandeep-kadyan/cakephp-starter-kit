<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserSettings Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\UserSetting newEmptyEntity()
 * @method \App\Model\Entity\UserSetting newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UserSetting> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserSetting get(mixed $primaryKey, array|string|null $optional = null)
 * @method \App\Model\Entity\UserSetting|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UserSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserSettingsTable extends Table
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

        $this->setTable('user_settings');
        $this->setDisplayField('key');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('key')
            ->maxLength('key', 128)
            ->requirePresence('key', 'create')
            ->notEmptyString('key');

        $validator
            ->maxLength('value', 16777215)
            ->allowEmptyString('value');

        return $validator;
    }

    /**
     * Returns the value for a given setting key for a user.
     */
    public function getValue(string $userId, string $key): mixed
    {
        $row = $this->find()
            ->select(['value'])
            ->where(['user_id' => $userId, 'key' => $key])
            ->limit(1)
            ->disableHydration()
            ->all()
            ->first();

        if ($row === null) {
            return null;
        }

        return $this->decodeValue((string)($row['value'] ?? ''));
    }

    /**
     * Set (create or update) a setting value for a user.
     */
    public function setValue(string $userId, string $key, mixed $value): bool
    {
        $entity = $this->find()
            ->where(['user_id' => $userId, 'key' => $key])
            ->limit(1)
            ->all()
            ->first();

        $encoded = $this->encodeValue($value);

        if ($entity) {
            $entity->set(['value' => $encoded], ['guard' => false]);
        } else {
            $entity = $this->newEntity([
                'user_id' => $userId,
                'key' => $key,
                'value' => $encoded,
            ]);
        }

        return $this->save($entity) !== false;
    }

    /**
     * Decode a stored value (JSON scalars/arrays supported).
     */
    protected function decodeValue(string $raw): mixed
    {
        if ($raw === '') {
            return null;
        }
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $raw;
    }

    /**
     * Encode a value for storage. Booleans/scalars/integers are JSON-encoded
     * so they round-trip cleanly.
     */
    protected function encodeValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }
        if (is_string($value)) {
            // Store plain strings directly; JSON-decode will fall back to raw.
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $value : $value;
        }

        return json_encode($value);
    }
}
