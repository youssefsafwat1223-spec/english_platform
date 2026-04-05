<?php

namespace App\Services;

class PhoneNumberService
{
    public function normalize(?string $phone): ?string
    {
        if (!is_string($phone) || trim($phone) === '') {
            return null;
        }

        $cleaned = preg_replace('/[^\d+]/', '', $phone);

        if (!$cleaned) {
            return null;
        }

        if (str_starts_with($cleaned, '00')) {
            $cleaned = '+' . substr($cleaned, 2);
        } elseif (!str_starts_with($cleaned, '+')) {
            $digits = preg_replace('/\D/', '', $cleaned);

            if ($digits === '') {
                return null;
            }

            if (str_starts_with($digits, '0') && strlen($digits) >= 10 && strlen($digits) <= 11) {
                $cleaned = '+2' . $digits;
            } else {
                $cleaned = '+' . ltrim($digits, '0');
            }
        }

        $digits = preg_replace('/\D/', '', $cleaned);

        if (strlen($digits) < 8 || strlen($digits) > 15) {
            return null;
        }

        return '+' . $digits;
    }

    public function exampleMessage(string $locale = 'en'): string
    {
        return $locale === 'ar'
            ? 'يرجى إدخال رقم هاتف صحيح مع كود الدولة، مثل +9665XXXXXXXX أو +2010XXXXXXX.'
            : 'Please enter a valid phone number with country code, such as +9665XXXXXXXX or +2010XXXXXXX.';
    }
}
