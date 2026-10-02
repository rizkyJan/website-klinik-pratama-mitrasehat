<?php

namespace App\Services\AI;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

class ClinicDateResolver
{
    private const DAYS = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

    private const MONTHS = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function now(): CarbonImmutable
    {
        return CarbonImmutable::now('Asia/Jakarta');
    }

    public function resolve(string $question, ?CarbonImmutable $now = null): ?CarbonImmutable
    {
        $now ??= $this->now();
        $text = $this->normalize($question);

        if ($this->containsAny($text, ['hari ini', 'sekarang', 'saat ini'])) {
            return $now->startOfDay();
        }

        if (str_contains($text, 'lusa')) {
            return $now->addDays(2)->startOfDay();
        }

        if (str_contains($text, 'besok')) {
            return $now->addDay()->startOfDay();
        }

        foreach (self::DAYS as $iso => $name) {
            $needle = $this->normalize($name);
            if (preg_match('/\b'.preg_quote($needle, '/').'\b/u', $text) === 1) {
                $delta = ($iso - $now->dayOfWeekIso + 7) % 7;
                return $now->addDays($delta)->startOfDay();
            }
        }

        return null;
    }

    public function dayName(CarbonImmutable $date): string
    {
        return self::DAYS[$date->dayOfWeekIso] ?? $date->translatedFormat('l');
    }

    public function dateLabel(CarbonImmutable $date): string
    {
        $month = self::MONTHS[(int) $date->format('n')] ?? $date->format('m');
        return ((int) $date->format('j')).' '.$month.' '.$date->format('Y');
    }

    public function relativeLabel(CarbonImmutable $date, ?CarbonImmutable $now = null): string
    {
        $now ??= $this->now();

        if ($date->isSameDay($now)) {
            return 'hari ini';
        }

        if ($date->isSameDay($now->addDay())) {
            return 'besok';
        }

        if ($date->isSameDay($now->addDays(2))) {
            return 'lusa';
        }

        return $this->dayName($date);
    }

    private function containsAny(string $text, array $terms): bool
    {
        foreach ($terms as $term) {
            if (str_contains($text, $term)) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $text): string
    {
        $text = Str::lower(Str::ascii($text));
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        return preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);
    }
}
