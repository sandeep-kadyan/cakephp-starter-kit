<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserSetting Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $key
 * @property string|null $value
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 */
class UserSetting extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'key' => true,
        'value' => true,
    ];

    protected array $_hidden = [
        'id',
    ];
}
