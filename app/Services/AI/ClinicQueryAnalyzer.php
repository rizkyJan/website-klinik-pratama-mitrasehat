<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

/**
 * Analisis pertanyaan klinik secara deterministik.
 *
 * Prinsip utama:
 * - kecocokan satu kata umum tidak boleh dianggap sebagai jawaban resmi;
 * - qualifier penting (QRIS, ambulans, biaya, KTP, apotek lain, dll.) wajib ikut
 *   didukung oleh judul/keyword sumber;
 * - jika bukti tidak cukup kuat, sistem harus memilih "belum ada informasi".
 */
class ClinicQueryAnalyzer
{
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
        'wa klinik' => 'whatsapp klinik',
        'calon pengantin' => 'catin',
        'pemeriksaan calon pengantin' => 'catin',
    ];

    /** Kata umum yang tidak membuktikan topik sebuah knowledge. */
    private array $genericWords = [
        'yang', 'dan', 'atau', 'untuk', 'dengan', 'dari', 'pada', 'dalam', 'tentang',
        'apa', 'apakah', 'bagaimana', 'gimana', 'berapa', 'kapan', 'dimana', 'mana',
        'saya', 'aku', 'kami', 'kamu', 'anda', 'pasien', 'orang', 'mau', 'ingin', 'hendak',
        'itu', 'ini', 'disini', 'sini', 'disana', 'sana', 'di', 'ke', 'ya', 'yaa', 'dong', 'kah',
        'klinik', 'mitra', 'sehat', 'pratama',
        'ada', 'punya', 'bisa', 'boleh', 'dapat', 'melakukan', 'dilakukan', 'lakukan',
        'tersedia', 'ketersediaan', 'melayani', 'menerima', 'layanan', 'pelayanan', 'informasi',
        'pemeriksaan', 'periksa', 'memeriksa', 'cek', 'test', 'tes',
        'cara', 'langkah', 'prosedur', 'alur', 'melalui', 'menggunakan', 'pakai',
        'umum', 'kesehatan', 'pembayaran', 'bayar', 'kalau', 'kalo', 'mohon', 'tolong', 'minta',
        'tidak', 'nggak', 'ngga', 'enggak', 'engga', 'gak', 'ga',
    ];

    /**
     * Token ini terlalu umum untuk mengelompokkan dua pertanyaan hanya karena sama-sama muncul.
     * Contoh: "ambulans ke rumah" dan "nebulizer ke rumah" tidak boleh jadi satu topik.
     */
    private array $weakTopicAnchors = [
        'rumah', 'pasien', 'dokter', 'online', 'whatsapp', 'bpjs', 'umum', 'obat',
        'hasil', 'biaya', 'harga', 'bayar', 'pembayaran', 'surat', 'kartu', 'daftar',
        'pendaftaran', 'klinik', 'layanan', 'pelayanan', 'informasi', 'kontrol',
    ];

    /**
     * Facet kritis: bila disebut dalam pertanyaan, sumber resmi juga harus secara eksplisit
     * mendukung facet tersebut. Ini mencegah rujukan menjawab ambulans, BPJS menjawab QRIS,
     * atau info obat menjawab penebusan di apotek lain.
     *
     * @var array<string,array{query:array<int,string>,source:array<int,string>}>
     */
    private array $criticalFacets = [
        'cost' => [
            'query' => ['biaya', 'harga', 'tarif', 'administrasi'],
            'source' => ['biaya', 'harga', 'tarif', 'administrasi', 'rp'],
        ],
        'qris' => [
            'query' => ['qris'],
            'source' => ['qris'],
        ],
        'shopeepay' => [
            'query' => ['shopeepay'],
            'source' => ['shopeepay'],
        ],
        'ewallet' => [
            'query' => ['gopay', 'ovo', 'dana', 'e wallet', 'ewallet'],
            'source' => ['gopay', 'ovo', 'dana', 'e wallet', 'ewallet'],
        ],
        'ambulance' => [
            'query' => ['ambulans', 'ambulance'],
            'source' => ['ambulans', 'ambulance'],
        ],
        'home_pickup' => [
            'query' => ['menjemput', 'jemput', 'dipanggil ke rumah', 'panggil ke rumah', 'homecare', 'home care', 'kunjungan rumah'],
            'source' => ['menjemput', 'jemput', 'homecare', 'home care', 'kunjungan rumah', 'layanan rumah'],
        ],
        'outside_pharmacy' => [
            'query' => ['apotek lain', 'ditebus di apotek', 'tebus di apotek', 'tebus resep'],
            'source' => ['apotek lain', 'ditebus di apotek', 'tebus di apotek', 'tebus resep'],
        ],
        'ktp' => [
            'query' => ['ktp'],
            'source' => ['ktp'],
        ],
        'mobile_jkn' => [
            'query' => ['mobile jkn'],
            'source' => ['mobile jkn'],
        ],
        'send_result_whatsapp' => [
            'query' => ['hasil pemeriksaan dikirim', 'hasil dikirim', 'kirim hasil', 'dikirim lewat whatsapp', 'dikirim melalui whatsapp'],
            'source' => ['hasil pemeriksaan dikirim', 'hasil dikirim', 'kirim hasil', 'dikirim lewat whatsapp', 'dikirim melalui whatsapp'],
        ],
        'consult_whatsapp' => [
            'query' => ['konsultasi lewat whatsapp', 'konsultasi melalui whatsapp', 'konsultasi via whatsapp', 'konsultasi dulu lewat whatsapp'],
            'source' => ['konsultasi lewat whatsapp', 'konsultasi melalui whatsapp', 'konsultasi via whatsapp'],
        ],
        'catin' => [
            'query' => ['catin'],
            'source' => ['catin'],
        ],
        'nebulizer' => [
            'query' => ['nebulizer', 'nebulisasi'],
            'source' => ['nebulizer', 'nebulisasi'],
        ],
        'vitamin_injection' => [
            'query' => ['suntik vitamin', 'injeksi vitamin', 'booster vitamin'],
            'source' => ['suntik vitamin', 'injeksi vitamin', 'booster vitamin'],
        ],
    ];

    public function normalize(string $text): string
    {
        $text = Str::lower(Str::ascii($text));
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);

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
        foreach (['ada', 'tersedia', 'melayani', 'bisa', 'boleh', 'punya', 'dapat', 'menyediakan', 'menerima'] as $term) {
            if (preg_match('/\b'.preg_quote($term, '/').'\b/u', $normalized) === 1) {
                return true;
            }
        }
        return false;
    }

    public function isProcedureIntent(string $text): bool
    {
        $normalized = $this->normalize($text);
        foreach (['bagaimana', 'gimana', 'cara', 'langkah', 'prosedur', 'alur', 'daftar', 'pendaftaran', 'mendaftar', 'melalui', 'menggunakan', 'pakai'] as $term) {
            if (preg_match('/\b'.preg_quote($term, '/').'\b/u', $normalized) === 1) {
                return true;
            }
        }
        return false;
    }

    public function topicLabel(string $text): string
    {
        $tokens = $this->meaningfulTokens($text);
        return $tokens === [] ? '' : implode(' ', array_slice($tokens, 0, 5));
    }

    public function isBroadServiceListIntent(string $text): bool
    {
        $normalized = $this->normalize($text);
        return (str_contains($normalized, 'layanan') || str_contains($normalized, 'pelayanan'))
            && ($this->meaningfulTokens($text) === [] || str_contains($normalized, 'apa saja'));
    }

    /**
     * @return array{score:int,matched:int,query_count:int,coverage:float,matched_tokens:array<int,string>,facets_ok:bool}
     */
    public function matchEvidence(string $query, string $source): array
    {
        $queryTokens = $this->meaningfulTokens($query);
        $sourceTokens = $this->meaningfulTokens($source);
        $sourceSet = array_fill_keys($sourceTokens, true);
        $score = 0;
        $matchedTokens = [];

        foreach ($queryTokens as $token) {
            if (isset($sourceSet[$token])) {
                $score += mb_strlen($token) >= 6 ? 8 : 6;
                $matchedTokens[] = $token;
                continue;
            }

            if (mb_strlen($token) >= 5) {
                foreach ($sourceTokens as $candidate) {
                    if (abs(mb_strlen($candidate) - mb_strlen($token)) <= 1 && levenshtein($token, $candidate) <= 1) {
                        $score += 4;
                        $matchedTokens[] = $token;
                        break;
                    }
                }
            }
        }

        $matchedTokens = array_values(array_unique($matchedTokens));
        $matched = count($matchedTokens);
        $queryCount = count($queryTokens);
        $coverage = $queryCount > 0 ? $matched / $queryCount : 0.0;

        if ($queryCount > 0 && $matched === $queryCount) {
            $score += 4;
        }

        $canonical = implode(' ', $queryTokens);
        if ($canonical !== '' && str_contains($this->normalize($source), $canonical)) {
            $score += 5;
        }

        return [
            'score' => $score,
            'matched' => $matched,
            'query_count' => $queryCount,
            'coverage' => $coverage,
            'matched_tokens' => $matchedTokens,
            'facets_ok' => $this->sourceSupportsCriticalFacets($query, $source),
        ];
    }

    /**
     * Retrieval konservatif. Lebih baik menjawab "belum ada informasi" daripada
     * mengambil knowledge yang hanya kebetulan berbagi satu kata.
     */
    public function isStrongMatch(string $query, string $source): bool
    {
        $evidence = $this->matchEvidence($query, $source);
        if (! $evidence['facets_ok'] || $evidence['query_count'] === 0 || $evidence['matched'] === 0) {
            return false;
        }

        if ($evidence['query_count'] === 1) {
            $token = $evidence['matched_tokens'][0] ?? '';
            return $token !== '' && ! in_array($token, $this->weakTopicAnchors, true);
        }

        // Untuk query multi-konsep, minimal dua konsep harus cocok dan setidaknya separuh
        // maksud pertanyaan harus terwakili oleh sumber.
        return $evidence['matched'] >= 2 && $evidence['coverage'] >= 0.5;
    }

    public function matchScore(string $query, string $source): int
    {
        $evidence = $this->matchEvidence($query, $source);
        return $evidence['facets_ok'] ? $evidence['score'] : 0;
    }

    public function sameTopic(string $first, string $second): bool
    {
        $a = $this->meaningfulTokens($first);
        $b = $this->meaningfulTokens($second);

        if ($a === [] || $b === []) {
            similar_text($this->normalize($first), $this->normalize($second), $percent);
            return $percent >= 85;
        }

        $intersection = array_values(array_diff(array_intersect($a, $b), $this->weakTopicAnchors));
        if ($intersection === []) {
            return false;
        }

        // Satu anchor yang benar-benar khas cukup, mis. spirometri/scaling/nebulizer.
        if (count($intersection) === 1) {
            $token = $intersection[0];
            return mb_strlen($token) >= 5;
        }

        $aStrong = array_values(array_diff($a, $this->weakTopicAnchors));
        $bStrong = array_values(array_diff($b, $this->weakTopicAnchors));
        $union = array_values(array_unique([...$aStrong, ...$bStrong]));
        $jaccard = count($union) > 0 ? count($intersection) / count($union) : 0;

        return count($intersection) >= 2 || $jaccard >= 0.6;
    }

    private function sourceSupportsCriticalFacets(string $query, string $source): bool
    {
        $queryText = $this->normalize($query);
        $sourceText = $this->normalize($source);

        foreach ($this->criticalFacets as $facet) {
            if (! $this->containsAny($queryText, $facet['query'])) {
                continue;
            }

            if (! $this->containsAny($sourceText, $facet['source'])) {
                return false;
            }
        }

        return true;
    }

    private function containsAny(string $text, array $terms): bool
    {
        foreach ($terms as $term) {
            if (str_contains($text, $this->normalize($term))) {
                return true;
            }
        }
        return false;
    }
}
