<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AuthRequest Entity
 *
 * @property string $id
 * @property string $email
 * @property string $verification_token
 * @property \Cake\I18n\DateTime $expires
 * @property \Cake\I18n\DateTime|null $verified_at
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class AuthRequest extends Entity
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
        'email' => true,
        'verification_token' => true,
        'expires' => true,
        'verified_at' => true,
        'created' => true,
        'modified' => true,
    ];
}
