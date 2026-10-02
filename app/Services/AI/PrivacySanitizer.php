<?php

namespace App\Services\AI;

class PrivacySanitizer
{
    public function sanitize(string $text): string
    {
        $text = trim($text);

        // Email
        $text = preg_replace('/\b[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}\b/i', '[email disembunyikan]', $text) ?? $text;

        // NIK 16 digit
        $text = preg_replace('/(?<!\d)\d{16}(?!\d)/', '[NIK disembunyikan]', $text) ?? $text;

        // Nomor telepon Indonesia yang cukup panjang. Hindari mengganti jam/tanggal/angka pendek.
        $text = preg_replace('/(?<!\d)(?:\+62|62|0)8\d{8,12}(?!\d)/', '[nomor telepon disembunyikan]', $text) ?? $text;

        return $text;
    }
}
