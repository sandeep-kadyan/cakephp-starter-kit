<?php
declare(strict_types=1);

namespace App\Utility;

use InvalidArgumentException;

/**
 * RFC 6238 TOTP implementation with base32 secrets.
 *
 * Provides generation and verification of time-based one-time passwords
 * using HMAC-SHA1, the same algorithm used by Google Authenticator and
 * other TOTP apps.
 */
class Totp
{
    /**
     * Time step in seconds (RFC 6238 default).
     *
     * @var int
     */
    protected int $period = 30;

    /**
     * Number of code digits.
     *
     * @var int
     */
    protected int $digits = 6;

    /**
     * Accepted clock drift window (number of periods before/after now).
     *
     * @var int
     */
    protected int $window = 1;

    /**
     * Constructor.
     *
     * @param array<string, mixed> $config Config with optional `period`, `digits` and `window` keys.
     */
    public function __construct(array $config = [])
    {
        $this->period = (int)($config['period'] ?? $this->period);
        $this->digits = (int)($config['digits'] ?? $this->digits);
        $this->window = (int)($config['window'] ?? $this->window);
    }

    /**
     * Generate a new random base32 secret.
     *
     * @param int $bytes Number of random bytes (default 20 => 160 bits).
     * @return string Base32 encoded secret (no padding).
     */
    public static function generateSecret(int $bytes = 20): string
    {
        return self::base32Encode(random_bytes($bytes));
    }

    /**
     * Generate the current (or a given) TOTP code for a secret.
     *
     * @param string $secret Base32 encoded secret.
     * @param int|null $timestamp Unix timestamp. Defaults to now.
     * @return string The TOTP code, zero-padded.
     */
    public function generate(string $secret, ?int $timestamp = null): string
    {
        $timestamp ??= time();
        $counter = intdiv($timestamp, $this->period);

        $key = self::base32Decode($secret);
        $hash = hash_hmac('sha1', pack('N*', 0, $counter), $key, true);

        $offset = ord($hash[strlen($hash) - 1]) & 0x0f;
        $binary = (
            ((ord($hash[$offset]) & 0x7f) << 24) |
            ((ord($hash[$offset + 1]) & 0xff) << 16) |
            ((ord($hash[$offset + 2]) & 0xff) << 8) |
            (ord($hash[$offset + 3]) & 0xff)
        );

        return str_pad((string)($binary % (10 ** $this->digits)), $this->digits, '0', STR_PAD_LEFT);
    }

    /**
     * Verify a user supplied code against a secret, allowing clock drift.
     *
     * @param string $code The code to check.
     * @param string $secret Base32 encoded secret.
     * @param int|null $timestamp Unix timestamp. Defaults to now.
     * @return bool True if the code is valid.
     */
    public function verify(string $code, string $secret, ?int $timestamp = null): bool
    {
        if ($code === '') {
            return false;
        }
        $code = trim($code);

        $timestamp ??= time();
        for ($i = -$this->window; $i <= $this->window; $i++) {
            $check = $this->generate($secret, $timestamp + ($i * $this->period));
            if (hash_equals($check, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Encode arbitrary binary data to base32 (RFC 4648, no padding).
     *
     * @param string $data Binary input.
     * @return string Base32 output.
     */
    public static function base32Encode(string $data): string
    {
        if ($data === '') {
            return '';
        }

        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $binary = '';
        foreach (str_split($data) as $char) {
            $binary .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }

        $output = '';
        foreach (str_split($binary, 5) as $chunk) {
            $output .= $alphabet[bindec(str_pad($chunk, 5, '0', STR_PAD_RIGHT))];
        }

        return $output;
    }

    /**
     * Decode a base32 string (RFC 4648) to raw bytes.
     *
     * @param string $data Base32 input (padding and lowercase are tolerated).
     * @return string Binary output.
     */
    public static function base32Decode(string $data): string
    {
        $data = strtoupper(rtrim($data, '='));
        if ($data === '') {
            return '';
        }

        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $binary = '';
        foreach (str_split($data) as $char) {
            $value = strpos($alphabet, $char);
            if ($value === false) {
                throw new InvalidArgumentException(sprintf('Invalid base32 character "%s".', $char));
            }
            $binary .= str_pad(decbin($value), 5, '0', STR_PAD_LEFT);
        }

        $output = '';
        foreach (str_split($binary, 8) as $chunk) {
            if (strlen($chunk) < 8) {
                break;
            }
            $output .= chr(bindec($chunk));
        }

        return $output;
    }
}
