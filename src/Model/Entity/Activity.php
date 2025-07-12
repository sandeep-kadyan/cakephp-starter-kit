<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Activity Entity
 *
 * @property string $id
 * @property string|null $user_id
 * @property string $url
 * @property string $browser
 * @property string $os
 * @property string $device
 * @property string $ip_address
 * @property string $location
 * @property string $user_agent
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Activity extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'user_id' => true,
        'url' => true,
        'browser' => true,
        'os' => true,
        'device' => true,
        'ip_address' => true,
        'location' => true,
        'user_agent' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
