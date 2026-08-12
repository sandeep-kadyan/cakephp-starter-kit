<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserSession Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string|null $session_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $browser
 * @property string $os
 * @property string $device
 * @property string|null $location
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $last_activity
 * @property bool $is_active
 * @property \Cake\I18n\DateTime|null $expired_at
 */
class UserSession extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'session_id' => true,
        'ip_address' => true,
        'user_agent' => true,
        'browser' => true,
        'os' => true,
        'device' => true,
        'is_active' => true,
        'expired_at' => true,
    ];
}
