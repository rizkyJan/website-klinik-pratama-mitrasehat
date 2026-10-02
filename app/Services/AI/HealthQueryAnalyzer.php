<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

/**
 * Menganalisis intent pertanyaan kesehatan agar model kecil tidak salah jalur.
 *
 * Tiga intent utama:
 * - definition : pertanyaan pengertian/fungsi organ atau istilah kesehatan
 * - complaint  : keluhan/gejala personal yang perlu skrining awal
 * - general    : edukasi kesehatan umum (penyebab, pencegahan, nilai normal, dll.)
 */
class HealthQueryAnalyzer
{
    private array $topicAliases = [
        'ulu hati' => 'ulu_hati',
        'perut bagian atas' => 'ulu_hati',
        'perut atas' => 'ulu_hati',
        'asam lambung' => 'lambung',
        'maag' => 'lambung',
        'gastritis' => 'lambung',
        'gerd' => 'lambung',
        'fungsi paru' => 'paru',
        'paru paru' => 'paru',
        'spirometri' => 'spirometri',
        'karang gigi' => 'gigi',
        'scaling' => 'gigi',
        'skeling' => 'gigi',
        'sakit gigi' => 'gigi',
        'gusi' => 'gigi',
        'sakit kepala' => 'kepala',
        'pusing' => 'kepala',
        'tenggorokan' => 'tenggorokan',
        'sesak' => 'napas',
        'napas' => 'napas',
        'nafas' => 'napas',
        'telapak kaki' => 'kaki',
        'indung telur' => 'ovarium',
        'kandung kemih' => 'kandung_kemih',
        'tulang belakang' => 'tulang_belakang',
        'pembuluh darah' => 'pembuluh_darah',
        'tekanan darah' => 'tekanan_darah',
        'gula darah' => 'gula_darah',
        'asam urat' => 'asam_urat',
    ];

    private array $singleTopics = [
        'gigi', 'mulut', 'bibir', 'lidah', 'rahang', 'perut', 'lambung', 'jantung', 'paru',
        'hati', 'ginjal', 'otak', 'kulit', 'mata', 'telinga', 'hidung', 'tenggorokan',
        'kepala', 'leher', 'bahu', 'lengan', 'tangan', 'jari', 'kuku', 'dada', 'pinggang',
        'punggung', 'tulang', 'otot', 'sendi', 'lutut', 'kaki', 'tumit', 'pergelangan',
        'usus', 'pankreas', 'limpa', 'empedu', 'rahim', 'uterus', 'ovarium', 'prostat',
        'testis', 'payudara', 'saraf', 'darah', 'demam', 'batuk', 'pilek', 'diare', 'mual',
        'muntah', 'sembelit', 'hipertensi', 'diabetes', 'kolesterol', 'alergi', 'anemia',
        'asma', 'spirometri', 'usg', 'ekg', 'eeg', 'vaksin', 'imunisasi', 'tensi',
        'saturasi', 'hemoglobin', 'trombosit', 'leukosit', 'antibiotik', 'vitamin',
    ];

    public function normalize(string $text): string
    {
        $text = Str::lower(Str::ascii($text));
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        return preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);
    }

    public function intent(string $question): string
    {
        if ($this->isDefinitionIntent($question)) {
            return 'definition';
        }

        if ($this->isComplaintIntent($question)) {
            return 'complaint';
        }

        return 'general';
    }

    public function isDefinitionIntent(string $question): bool
    {
        $text = $this->normalize($question);
        if ($text === '') {
            return false;
        }

        $patterns = [
            '/^(apa\s+itu|apa\s+arti|apa\s+artinya|apa\s+pengertian|apa\s+maksud(?:nya)?|pengertian|definisi)\b/u',
            '/^(jelaskan|terangkan)\b/u',
            '/^(apa\s+fungsi|fungsi\s+dari|fungsi|apa\s+gunanya|gunanya)\b/u',
            '/\b(itu\s+apa|artinya\s+apa)\s*$/u',
            // Bahasa percakapan singkat: "kaki adalah", "jantung adalah?"
            '/^[a-z0-9\s]{1,60}\s+adalah\s*$/u',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text) === 1) {
                return true;
            }
        }

        return false;
    }

    public function isComplaintIntent(string $question): bool
    {
        $text = $this->normalize($question);
        if ($text === '' || $this->isDefinitionIntent($question)) {
            return false;
        }

        // Pertanyaan edukasi umum jangan dipaksa masuk format skrining keluhan.
        foreach ([
            'apa penyebab', 'penyebab ', 'apa gejala', 'gejala ', 'cara mencegah', 'pencegahan',
            'cara menjaga', 'cara merawat', 'bagaimana kerja', 'bagaimana cara kerja', 'normalnya',
            'berapa normal', 'berapa kadar normal', 'apa manfaat', 'kenapa bisa terjadi',
            'apakah normal jika', 'apakah bisa menyebabkan', 'apa risikonya', 'faktor risiko',
        ] as $generalMarker) {
            if (str_contains($text, $generalMarker)) {
                return false;
            }
        }

        $personalMarkers = [
            'saya ', 'aku ', 'saya merasa', 'saya mengalami', 'aku merasa', 'aku mengalami',
            'anak saya', 'ibu saya', 'bapak saya', 'ayah saya', 'suami saya', 'istri saya',
        ];

        $complaintMarkers = [
            'sakit', 'nyeri', 'terasa', 'perih', 'panas', 'bengkak', 'gatal', 'merah', 'ruam',
            'pusing', 'sesak', 'mual', 'muntah', 'demam', 'batuk', 'pilek', 'diare', 'mencret',
            'lemas', 'kebas', 'kesemutan', 'kram', 'pegal', 'luka', 'berdarah', 'keluar darah',
            'berdebar', 'sariawan', 'mimisan', 'susah tidur', 'tidak bisa tidur', 'jatuh',
            'terkilir', 'memar', 'benjolan', 'susah menelan', 'sulit menelan', 'anyang anyangan',
        ];

        $hasComplaint = $this->containsAny($text, $complaintMarkers);
        if (! $hasComplaint) {
            return false;
        }

        // "kaki sakit", "mual sejak pagi", dst. tetap dianggap keluhan walau tanpa kata saya.
        if ($this->containsAny($text, $personalMarkers)) {
            return true;
        }

        return mb_strlen($text) <= 180;
    }

    public function isLikelyFollowUp(string $question): bool
    {
        $text = $this->normalize($question);

        if ($this->isDefinitionIntent($question)) {
            return false;
        }

        foreach ([
            'iya', 'ya', 'tidak', 'nggak', 'ngga', 'enggak', 'ga', 'gak',
            'di bagian', 'bagian tengah', 'bagian kanan', 'bagian kiri', 'kanan', 'kiri', 'tengah',
            'sejak ', 'dari tadi', 'dari kemarin', 'rasanya ', 'terasa ', 'kadang ',
            'setelah makan', 'sebelum makan', 'kalau telat makan', 'kalau makan',
            'mual', 'muntah', 'demam', 'tidak demam', 'nggak demam', 'berdarah',
            'bengkak', 'gatal', 'sesak', 'sakit kalau', 'lebih sakit', 'makin sakit',
            'sudah ', 'sekitar ', 'kurang lebih ', 'hari ini', 'kemarin',
        ] as $pattern) {
            if ($text === $pattern || str_starts_with($text, $pattern) || str_contains($text, $pattern)) {
                return true;
            }
        }

        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        return count($words) <= 8 && $this->topicKey($question) === '';
    }

    /**
     * Follow-up edukasi umum seperti "terus fungsinya?", "bahaya tidak?", "cara mencegahnya?".
     */
    public function isContextualFollowUp(string $question): bool
    {
        $text = $this->normalize($question);
        if ($text === '') {
            return false;
        }

        foreach ([
            'terus', 'lalu', 'kalau begitu', 'kalau itu', 'bagaimana itu', 'kenapa begitu',
            'fungsinya', 'penyebabnya', 'gejalanya', 'risikonya', 'bahaya tidak', 'berbahaya tidak',
            'cara mencegahnya', 'cara mengatasinya', 'cara merawatnya', 'normal tidak', 'apakah normal',
            'contohnya', 'maksudnya', 'jadi bagaimana', 'jadi gimana',
        ] as $marker) {
            if ($text === $marker || str_starts_with($text, $marker) || str_contains($text, $marker)) {
                return true;
            }
        }

        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        return count($words) <= 6 && $this->topicKey($question) === '';
    }

    public function topicKey(string $question): string
    {
        $text = $this->normalize($question);

        $aliases = $this->topicAliases;
        uksort($aliases, fn (string $a, string $b) => mb_strlen($b) <=> mb_strlen($a));
        foreach ($aliases as $phrase => $key) {
            if (preg_match('/\b'.preg_quote($phrase, '/').'\b/u', $text) === 1) {
                return $key;
            }
        }

        foreach ($this->singleTopics as $topic) {
            if (preg_match('/\b'.preg_quote($topic, '/').'\b/u', $text) === 1) {
                return $topic;
            }
        }

        return '';
    }

    public function sameTopic(string $first, string $second): bool
    {
        $a = $this->topicKey($first);
        $b = $this->topicKey($second);

        return $a !== '' && $b !== '' && $a === $b;
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
}
