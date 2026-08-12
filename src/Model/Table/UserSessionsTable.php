<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserSessions Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\UserSession newEmptyEntity()
 * @method \App\Model\Entity\UserSession newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UserSession> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserSession get(mixed $primaryKey, array|string|null $optional = null)
 * @method \App\Model\Entity\UserSession|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserSessionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('user_sessions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', ['updated' => false]);

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

        return $validator;
    }

    /**
     * Record a login event for the given user.
     */
    public function recordLogin(string $userId, ?string $sessionId = null): Entity
    {
        $row = $this->newEntity([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'ip_address' => $this->getEnv('REMOTE_ADDR'),
            'user_agent' => $this->getEnv('HTTP_USER_AGENT'),
            'browser' => $this->getBrowser(),
            'os' => $this->getOs(),
            'device' => $this->getDevice(),
            'is_active' => true,
        ]);
        $this->save($row);

        return $row;
    }

    /**
     * Mark a session (or all sessions) inactive.
     */
    public function logout(?string $sessionId = null, ?string $userId = null): void
    {
        $q = $this->updateAll(
            ['is_active' => false, 'expired_at' => gmdate('Y-m-d H:i:s')],
            is_null($sessionId) ? ['user_id' => $userId, 'is_active' => true]
                    : ['session_id' => $sessionId]
        );
    }

    protected function getEnv(string $key): ?string
    {
        return $_SERVER[$key] ?? null;
    }

    protected function getBrowser(): string
    {
        $ua = (string)$_SERVER['HTTP_USER_AGENT'];
        if ($ua === '') {
            return 'Unknown';
        }
        if (stripos($ua, 'Edg') !== false) {
            return 'Edge';
        }
        if (preg_match('/Opera|OPR/', $ua)) {
            return 'Opera';
        }
        if (stripos($ua, 'Chrome') !== false) {
            return 'Chrome';
        }
        if (stripos($ua, 'Firefox') !== false) {
            return 'Firefox';
        }
        if (stripos($ua, 'Safari') !== false) {
            return 'Safari';
        }

        return 'Unknown';
    }

    protected function getOs(): string
    {
        $ua = (string)$_SERVER['HTTP_USER_AGENT'];
        if ($ua === '') {
            return 'Unknown';
        }
        if (preg_match('/windows nt/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/macintosh|mac os x/i', $ua)) {
            return 'Mac OS';
        }
        if (preg_match('/android/i', $ua)) {
            return 'Android';
        }
        if (preg_match('/iphone|ipad|ipod/i', $ua)) {
            return 'iOS';
        }
        if (preg_match('/linux/i', $ua)) {
            return 'Linux';
        }

        return 'Unknown';
    }

    protected function getDevice(): string
    {
        $ua = (string)$_SERVER['HTTP_USER_AGENT'];
        if ($ua === '') {
            return 'Unknown';
        }
        if (preg_match('/tablet|ipad/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/mobile/i', $ua)) {
            return 'Mobile';
        }
        if (preg_match('/windows|macintosh|linux/i', $ua)) {
            return 'Desktop';
        }

        return 'Unknown';
    }
}
