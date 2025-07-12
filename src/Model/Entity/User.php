<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property string $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property \Cake\I18n\DateTime|null $email_verified_at
 * @property string|null $remember_token
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property \Cake\I18n\DateTime|null $two_factor_confirmed_at
 * @property \Cake\I18n\DateTime|null $last_active_at
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Activity[] $activities
 */
class User extends Entity
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
        'name' => true,
        'username' => true,
        'email' => true,
        'password' => true,
        'email_verified_at' => true,
        'remember_token' => true,
        'two_factor_secret' => true,
        'two_factor_recovery_codes' => true,
        'two_factor_confirmed_at' => true,
        'last_active_at' => true,
        'created' => true,
        'modified' => true,
        'activities' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'password',
    ];

    /**
     * Mutator for the password field. Hashes the password before saving.
     *
     * @param string $password The plain text password to hash.
     * @return string The hashed password.
     */
    protected function _setPassword(string $password): string
    {
        $hasher = new DefaultPasswordHasher();

        return $hasher->hash($password);
    }
}
