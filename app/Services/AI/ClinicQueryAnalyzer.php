<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

/**
 * Menormalkan pertanyaan klinik agar variasi bahasa pasien mengarah ke topik yang sama.
 * Tidak memakai model AI sehingga hasilnya deterministik dan ringan.
 */
class ClinicQueryAnalyzer
{
    /**
     * Frasa yang berbeda tetapi mempunyai maksud operasional klinik yang sama.
     * Alias diterapkan sebelum tokenisasi.
     */
    private array $phraseAliases = [
        'pemeriksaan fungsi paru' => 'spirometri',
        'tes fungsi paru' => 'spirometri',
        'test fungsi paru' => 'spirometri',
        'cek fungsi paru' => 'spirometri',
        'cek paru' => 'spirometri',
        'tes paru' => 'spirometri',
        'test paru' => 'spirometri',

        'pembersihan karang gigi' => 'scaling',
        'bersihkan karang gigi' => 'scaling',
        'bersihin karang gigi' => 'scaling',
        'karang gigi' => 'scaling',
        'scalling' => 'scaling',
        'skeling' => 'scaling',

        'jkn mobile' => 'mobile jkn',
        'mobilejkn' => 'mobile jkn',
        'surat keterangan sehat' => 'surat sehat',
        'surat keterangan sakit' => 'surat sakit',
        'nomor wa' => 'whatsapp',
    ];

    /**
     * Kata generik yang tidak cukup untuk membuktikan sebuah fakta klinik relevan.
     * Contoh: dua dokumen sama-sama mengandung "pemeriksaan" tidak berarti topiknya sama.
     */
    private array $genericWords = [
        'yang', 'dan', 'atau', 'untuk', 'dengan', 'dari', 'pada', 'dalam', 'tentang',
        'apa', 'apakah', 'bagaimana', 'gimana', 'berapa', 'kapan', 'dimana', 'mana',
        'saya', 'aku', 'kami', 'kamu', 'anda', 'pasien', 'orang', 'mau', 'ingin', 'hendak',
        'itu', 'ini', 'disini', 'sini', 'disana', 'sana', 'di', 'ke', 'ya', 'yaa', 'dong', 'kah',
        'klinik', 'mitra', 'sehat', 'pratama',
        'ada', 'punya', 'bisa', 'boleh', 'dapat', 'melakukan', 'dilakukan', 'lakukan',
        'tersedia', 'ketersediaan', 'melayani', 'layanan', 'pelayanan', 'informasi',
        'pemeriksaan', 'periksa', 'memeriksa', 'cek', 'test', 'tes',
        'cara', 'langkah', 'prosedur', 'alur', 'melalui', 'menggunakan', 'pakai',
        'harga', 'biaya', 'berapa', 'umum', 'kesehatan',
        'kalau', 'kalo', 'mohon', 'tolong', 'ingin', 'minta', 'tidak', 'nggak', 'ngga', 'enggak', 'engga', 'gak', 'ga',
    ];

    public function normalize(string $text): string
    {
        $text = Str::lower(Str::ascii($text));
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);

        // Frasa terpanjang lebih dahulu agar alias yang spesifik tidak tertimpa alias pendek.
        $aliases = $this->phraseAliases;
        uksort($aliases, fn (string $a, string $b) => mb_strlen($b) <=> mb_strlen($a));

        foreach ($aliases as $from => $to) {
            $text = preg_replace('/\b'.preg_quote($from, '/').'\b/u', $to, $text) ?? $text;
        }

        return preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);
    }

    /** @return array<int,string> */
    public function meaningfulTokens(string $text): array
    {
        $normalized = $this->normalize($text);
        $tokens = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_values(array_unique(array_filter($tokens, function (string $token) {
            return mb_strlen($token) >= 3 && ! in_array($token, $this->genericWords, true);
        })));
    }

    /**
     * Kunci ringkas untuk mengelompokkan pertanyaan belum terjawab yang satu topik.
     */
    public function canonicalKey(string $text): string
    {
        $tokens = $this->meaningfulTokens($text);
        sort($tokens, SORT_STRING);

        if ($tokens === []) {
            return mb_substr($this->normalize($text), 0, 190);
        }

        return mb_substr(implode(' ', array_slice($tokens, 0, 10)), 0, 190);
    }

    public function isAvailabilityIntent(string $text): bool
    {
        $normalized = $this->normalize($text);

        foreach ([
            'ada', 'tersedia', 'melayani', 'bisa', 'boleh', 'punya',
            'dapat', 'menyediakan', 'menerima',
        ] as $term) {
            if (preg_match('/\b'.preg_quote($term, '/').'\b/u', $normalized) === 1) {
                return true;
            }
        }

        return false;
    }

    public function isProcedureIntent(string $text): bool
    {
        $normalized = $this->normalize($text);

        foreach ([
            'bagaimana', 'gimana', 'cara', 'langkah', 'prosedur', 'alur',
            'daftar', 'pendaftaran', 'mendaftar', 'melalui', 'menggunakan', 'pakai',
        ] as $term) {
            if (preg_match('/\b'.preg_quote($term, '/').'\b/u', $normalized) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Label topik ringkas untuk enrichment kesehatan umum. Alias sudah diterapkan sehingga
     * "tes fungsi paru" akan menjadi "spirometri".
     */
    public function topicLabel(string $text): string
    {
        $tokens = $this->meaningfulTokens($text);
        if ($tokens === []) {
            return '';
        }

        return implode(' ', array_slice($tokens, 0, 4));
    }

    public function isBroadServiceListIntent(string $text): bool
    {
        $normalized = $this->normalize($text);

        return (str_contains($normalized, 'layanan') || str_contains($normalized, 'pelayanan'))
            && ($this->meaningfulTokens($text) === [] || str_contains($normalized, 'apa saja'));
    }

    /**
     * Nilai kecocokan query dengan teks sumber. Minimal satu token bermakna harus cocok.
     */
    public function matchScore(string $query, string $source): int
    {
        $queryTokens = $this->meaningfulTokens($query);
        if ($queryTokens === []) {
            return 0;
        }

        $sourceNormalized = $this->normalize($source);
        $sourceTokens = $this->meaningfulTokens($source);
        $sourceSet = array_fill_keys($sourceTokens, true);
        $score = 0;
        $matched = 0;

        foreach ($queryTokens as $token) {
            if (isset($sourceSet[$token])) {
                $score += mb_strlen($token) >= 6 ? 8 : 6;
                $matched++;
                continue;
            }

            // Toleransi salah ketik kecil untuk kata yang cukup panjang.
            if (mb_strlen($token) >= 5) {
                foreach ($sourceTokens as $candidate) {
                    if (abs(mb_strlen($candidate) - mb_strlen($token)) <= 1 && levenshtein($token, $candidate) <= 1) {
                        $score += 4;
                        $matched++;
                        break;
                    }
                }
            }
        }

        if ($matched === count($queryTokens)) {
            $score += 4;
        }

        // Bonus jika bentuk kanonik utuh terdapat di sumber.
        $canonical = implode(' ', $queryTokens);
        if ($canonical !== '' && str_contains($sourceNormalized, $canonical)) {
            $score += 5;
        }

        return $score;
    }

    /**
     * Dua pertanyaan dianggap topik yang sama bila mempunyai anchor bermakna yang kuat.
     */
    public function sameTopic(string $first, string $second): bool
    {
        $a = $this->meaningfulTokens($first);
        $b = $this->meaningfulTokens($second);

        if ($a === [] || $b === []) {
            similar_text($this->normalize($first), $this->normalize($second), $percent);
            return $percent >= 78;
        }

        $intersection = array_values(array_intersect($a, $b));
        if ($intersection !== []) {
            // Satu anchor unik/panjang seperti "spirometri" sudah cukup kuat.
            foreach ($intersection as $token) {
                if (mb_strlen($token) >= 5) {
                    return true;
                }
            }
        }

        $union = array_values(array_unique([...$a, ...$b]));
        $jaccard = count($union) > 0 ? count($intersection) / count($union) : 0;

        return $jaccard >= 0.6;
    }
}
