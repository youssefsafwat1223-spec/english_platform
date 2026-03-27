<?php

namespace App\Support;

class TextEncoding
{
    public static function repair(?string $value): ?string
    {
        if (!is_string($value) || $value === '') {
            return $value;
        }

        $current = str_replace("\u{FEFF}", '', $value);

        for ($i = 0; $i < 2; $i++) {
            if (!self::looksCorrupted($current)) {
                break;
            }

            $repaired = @mb_convert_encoding($current, 'Windows-1252', 'UTF-8');

            if (!is_string($repaired) || $repaired === '' || $repaired === $current) {
                break;
            }

            $current = $repaired;
        }

        return $current;
    }

    public static function looksCorrupted(string $value): bool
    {
        return preg_match('/(?:Ø.|Ù.|Ã.|Â.|â.|ðŸ|�)/u', $value) === 1;
    }
}
