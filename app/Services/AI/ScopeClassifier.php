<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

class ScopeClassifier
{
    private array $clinicStrongTerms = [
        'klinik', 'mitra sehat', 'jadwal', 'poli', 'bpjs', 'jkn', 'mobile jkn',
        'telehealth', 'rujuk', 'rujukan', 'farmasi', 'obat prb', 'pendaftaran', 'daftar',
        'antrean', 'antrian', 'usg', 'jam buka', 'buka jam', 'jam operasional', 'jam pelayanan', 'buka hari ini', 'buka besok', 'klinik buka', 'klinik tutup', 'layanan', 'pelayanan',
        'biaya', 'surat sehat', 'surat sakit', 'kontak', 'whatsapp', 'alamat', 'cabang',
        'pengumuman', 'promo', 'faskes', 'onsite', 'kuota', 'tersedia', 'melayani',
    ];

    private array $clinicTerms = [
        'klinik', 'mitra sehat', 'dokter', 'jadwal', 'poli', 'bpjs', 'jkn', 'mobile jkn',
        'telehealth', 'rujuk', 'rujukan', 'farmasi', 'obat prb', 'pendaftaran', 'daftar',
        'antrean', 'antrian', 'usg', 'gigi', 'scaling', 'skeling', 'karang gigi', 'kia',
        'jam buka', 'buka jam', 'jam operasional', 'jam pelayanan', 'buka hari ini', 'buka besok', 'klinik buka', 'klinik tutup', 'layanan', 'pelayanan', 'biaya', 'surat sehat',
        'surat sakit', 'laboratorium', 'lab', 'vaksin', 'nebulizer', 'kontak', 'whatsapp',
        'alamat', 'cabang', 'pengumuman', 'promo', 'faskes', 'onsite', 'online', 'kuota',
        'bidan', 'periksa di sini', 'tersedia', 'melayani', 'buta warna', 'surat keterangan',
        'fisioterapi', 'akupuntur', 'akupunktur', 'cek sehat', 'booster vitamin', 'cabut jahitan',
    ];

    private array $outOfScopeTerms = [
        'coding', 'ngoding', 'laravel', 'php', 'javascript', 'python', 'programming', 'source code',
        'desain', 'design', 'poster', 'logo', 'edit foto', 'buat gambar', 'ppt', 'powerpoint',
        'excel', 'politik', 'presiden', 'pemilu', 'matematika', 'fisika', 'skripsi', 'game',
        'film', 'lagu', 'puisi', 'cerpen', 'sistem kasir',
    ];

    /** Gejala, penyakit, pemeriksaan, dan konsep kesehatan. */
    private array $healthTerms = [
        // Gejala umum
        'sakit', 'nyeri', 'demam', 'panas', 'batuk', 'pilek', 'flu', 'mual', 'muntah',
        'diare', 'mencret', 'pusing', 'sakit kepala', 'sesak', 'napas', 'nafas', 'dada',
        'gatal', 'ruam', 'bengkak', 'luka', 'darah', 'bab', 'bak', 'kencing', 'urin',
        'haid', 'menstruasi', 'hamil', 'kehamilan', 'alergi', 'pingsan', 'kejang', 'lemas',
        'meriang', 'mriyang', 'watuk', 'mumet', 'mules', 'mulas', 'senep', 'sembelit',
        'konstipasi', 'berdebar', 'kesemutan', 'kebas', 'pegal', 'kram', 'memar', 'benjolan',
        'sariawan', 'mimisan', 'keringat dingin', 'menggigil', 'susah tidur', 'insomnia',

        // Anatomi / bagian tubuh
        'perut', 'lambung', 'ulu hati', 'gigi', 'gusi', 'mulut', 'bibir', 'lidah', 'rahang',
        'tenggorokan', 'hidung', 'telinga', 'mata', 'kulit', 'kepala', 'otak', 'leher',
        'bahu', 'lengan', 'tangan', 'jari', 'kuku', 'dada', 'jantung', 'paru', 'paru-paru',
        'hati', 'liver', 'ginjal', 'usus', 'pankreas', 'limpa', 'empedu', 'kandung kemih',
        'rahim', 'uterus', 'ovarium', 'indung telur', 'prostat', 'testis', 'payudara',
        'pinggang', 'punggung', 'tulang', 'otot', 'sendi', 'lutut', 'kaki', 'telapak kaki',
        'tumit', 'pergelangan', 'saraf', 'pembuluh darah', 'nadi', 'tulang belakang',

        // Penyakit/kondisi umum
        'maag', 'gastritis', 'asam lambung', 'gerd', 'hipertensi', 'diabetes', 'kolesterol',
        'asam urat', 'anemia', 'asma', 'tbc', 'tb paru', 'pneumonia', 'bronkitis', 'sinusitis',
        'radang', 'infeksi', 'migrain', 'vertigo', 'stroke', 'jantung koroner', 'dengue', 'dbd',
        'tifus', 'typhoid', 'cacar', 'herpes', 'jamur', 'eksim', 'dermatitis', 'wasir',
        'hemoroid', 'batu ginjal', 'isk', 'infeksi saluran kemih', 'osteoporosis', 'rematik',

        // Pemeriksaan / parameter kesehatan
        'kesehatan', 'gejala', 'keluhan', 'penyakit', 'diagnosis', 'skrining', 'screening',
        'gula darah', 'tekanan darah', 'tensi', 'hemoglobin', 'hb', 'leukosit', 'trombosit',
        'kolesterol', 'asam urat', 'spo2', 'saturasi', 'suhu tubuh', 'bmi', 'imt',
        'spirometri', 'fungsi paru', 'nebulizer', 'rontgen', 'xray', 'x-ray', 'ekg', 'eeg',
        'fisioterapi', 'akupuntur', 'akupunktur', 'laboratorium', 'usg', 'scaling', 'vaksin',
        'imunisasi', 'vitamin', 'obat', 'antibiotik', 'parasetamol', 'paracetamol',
    ];

    private array $greetings = [
        'halo', 'hai', 'hi', 'hello', 'pagi', 'siang', 'sore', 'malam', 'assalamualaikum',
        'tes', 'test', 'permisi',
    ];

    public function classify(string $question): string
    {
        $text = $this->normalize($question);

        if ($this->isGreeting($text)) {
            return 'greeting';
        }

        if ($this->containsAny($text, $this->outOfScopeTerms)) {
            return 'out_of_scope';
        }

        // Fakta operasional Klinik Mitra Sehat diprioritaskan daripada pengetahuan kesehatan umum.
        if ($this->containsAny($text, $this->clinicStrongTerms)) {
            return 'clinic';
        }

        if (str_contains($text, 'dokter') && $this->containsAny($text, ['jadwal', 'praktik', 'praktek', 'besok', 'hari ini', 'siapa', 'jam'])) {
            return 'clinic';
        }

        // Kamus kesehatan diperluas agar anatomi sederhana seperti kaki/mulut tetap masuk domain kesehatan.
        if ($this->containsAny($text, $this->healthTerms)) {
            return 'health';
        }

        // Pola pertanyaan kesehatan yang umum, dipakai sebagai pengaman untuk bahasa sehari-hari.
        if ($this->looksLikeHealthQuestion($text)) {
            return 'health';
        }

        if ($this->containsAny($text, $this->clinicTerms)) {
            return 'clinic';
        }

        // Pertanyaan ketersediaan layanan yang belum dikenal tetap dicatat sebagai pertanyaan klinik.
        if ($this->containsAny($text, ['apakah ada', 'ada ', 'bisa ', 'tersedia', 'melayani', 'berapa biaya', 'berapa harga'])) {
            return 'clinic';
        }

        return 'out_of_scope';
    }

    public function isHealthRelated(string $question): bool
    {
        $text = $this->normalize($question);
        return $this->containsAny($text, $this->healthTerms) || $this->looksLikeHealthQuestion($text);
    }

    private function looksLikeHealthQuestion(string $text): bool
    {
        $medicalMarkers = [
            'apa itu', 'apa fungsi', 'fungsi dari', 'kenapa sakit', 'kenapa terasa', 'normalnya',
            'apakah normal', 'bahaya tidak', 'berbahaya tidak', 'cara merawat', 'cara mengatasi',
            'penyebab', 'gejala', 'obat untuk', 'harus periksa', 'kapan ke dokter',
        ];

        // Marker saja tidak cukup; pertanyaan tetap harus mengandung istilah kesehatan yang dikenal.
        return $this->containsAny($text, $medicalMarkers) && $this->containsAny($text, $this->healthTerms);
    }

    private function isGreeting(string $text): bool
    {
        $trimmed = trim($text, " .,!?:;\t\n\r\0\x0B");
        if (mb_strlen($trimmed) > 40) {
            return false;
        }

        foreach ($this->greetings as $greeting) {
            if ($trimmed === $greeting || str_starts_with($trimmed, $greeting.' ')) {
                return true;
            }
        }

        return false;
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
        return Str::lower(Str::ascii($text));
    }
}
