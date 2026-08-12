<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PaymentMethod Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $provider
 * @property string|null $identifier
 * @property string|null $details
 * @property string $status
 * @property bool $is_default
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 */
class PaymentMethod extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'provider' => true,
        'identifier' => true,
        'details' => true,
        'status' => true,
        'is_default' => true,
    ];

    protected array $_hidden = [
        'identifier',
        'details',
    ];

    /**
     * Safe-to-display payment method label (no sensitive data).
     */
    public function display(): string
    {
        $provider = $this->provider ?: 'Unknown';
        if ($this->provider === 'stripe' && $this->identifier) {
            return 'Stripe (' . $this->identifier . ')';
        }
        if ($this->provider === 'paypal' && $this->identifier) {
            return 'PayPal (' . $this->identifier . ')';
        }

        return $provider;
    }
}
