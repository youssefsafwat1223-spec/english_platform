<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class TwoFactorAuthService
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public function generateSecret(int $length = 32): string
    {
        $secret = '';
        $maxIndex = strlen(self::BASE32_ALPHABET) - 1;

        for ($index = 0; $index < $length; $index++) {
            $secret .= self::BASE32_ALPHABET[random_int(0, $maxIndex)];
        }

        return $secret;
    }

    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];

        for ($index = 0; $index < $count; $index++) {
            $codes[] = Str::upper(Str::random(5) . '-' . Str::random(5));
        }

        return $codes;
    }

    public function provisioningUri(User $user, string $secret, ?string $issuer = null): string
    {
        $issuer ??= config('app.name', 'Simple English');

        return sprintf(
            'otpauth://totp/%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
            rawurlencode($issuer . ':' . $user->email),
            $secret,
            rawurlencode($issuer)
        );
    }

    public function currentCode(string $secret, ?int $timestamp = null): string
    {
        return $this->codeForCounter(
            $secret,
            (int) floor(($timestamp ?? time()) / 30)
        );
    }

    public function verifyCode(string $secret, string $code, int $window = 1, ?int $timestamp = null): bool
    {
        $normalizedCode = preg_replace('/\s+/', '', $code) ?? '';

        if (!preg_match('/^\d{6}$/', $normalizedCode)) {
            return false;
        }

        $counter = (int) floor(($timestamp ?? time()) / 30);

        for ($offset = -$window; $offset <= $window; $offset++) {
            if (hash_equals($this->codeForCounter($secret, $counter + $offset), $normalizedCode)) {
                return true;
            }
        }

        return false;
    }

    public function normalizeRecoveryCode(string $code): string
    {
        return Str::upper(str_replace([' ', '-'], '', trim($code)));
    }

    protected function codeForCounter(string $secret, int $counter): string
    {
        $binarySecret = $this->base32Decode($secret);
        $hash = hash_hmac('sha1', pack('NN', 0, $counter), $binarySecret, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $value = unpack('N', substr($hash, $offset, 4))[1] & 0x7FFFFFFF;

        return str_pad((string) ($value % 1000000), 6, '0', STR_PAD_LEFT);
    }

    protected function base32Decode(string $secret): string
    {
        $clean = preg_replace('/[^A-Z2-7]/', '', Str::upper($secret)) ?? '';
        $bits = '';

        foreach (str_split($clean) as $character) {
            $position = strpos(self::BASE32_ALPHABET, $character);

            if ($position === false) {
                continue;
            }

            $bits .= str_pad(decbin($position), 5, '0', STR_PAD_LEFT);
        }

        $binary = '';

        foreach (str_split($bits, 8) as $chunk) {
            if (strlen($chunk) === 8) {
                $binary .= chr(bindec($chunk));
            }
        }

        return $binary;
    }
}
