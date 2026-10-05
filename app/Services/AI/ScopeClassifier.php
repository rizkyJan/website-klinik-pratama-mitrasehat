<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

/**
 * Memisahkan tiga domain utama chatbot:
 * - clinic       : fakta/kebijakan/operasional Klinik Mitra Sehat;
 * - health       : edukasi dan keluhan kesehatan umum;
 * - out_of_scope : selain klinik/kesehatan.
 *
 * Aturan penting: pertanyaan operasional klinik diprioritaskan sebelum istilah kesehatan.
 * Contoh "stok obat amoxicillin" adalah fakta klinik, bukan edukasi obat.
 */
class ScopeClassifier
{
    private array $outOfScopeTerms = [
        'coding', 'ngoding', 'laravel', 'php', 'javascript', 'python', 'programming', 'source code',
        'desain', 'design', 'poster', 'logo', 'edit foto', 'buat gambar', 'ppt', 'powerpoint',
        'excel', 'politik', 'presiden', 'pemilu', 'matematika', 'fisika', 'skripsi', 'game',
        'film', 'lagu', 'puisi', 'cerpen', 'sistem kasir',
    ];

    /** Frasa yang hampir pasti meminta fakta operasional klinik. */
    private array $clinicOperationalTerms = [
        // Identitas dan operasional klinik
        'klinik mitra sehat', 'mitra sehat', 'klinik buka', 'klinik tutup', 'jam operasional',
        'jam pelayanan', 'jam buka', 'buka jam', 'alamat klinik', 'lokasi klinik',
        'kontak klinik', 'nomor whatsapp', 'nomor wa', 'nomor telepon',

        // Jadwal/tenaga
        'jadwal dokter', 'dokter yang praktik', 'dokter yang praktek', 'dokter siapa',
        'siapa dokter', 'dokter jaga', 'petugas pendaftaran', 'petugas yang jaga', 'petugas jaga',

        // Pendaftaran/antrean/kebijakan
        'pendaftaran', 'daftar pasien', 'mobile jkn', 'jkn mobile', 'telehealth', 'antrean', 'antrian',
        'booking', 'nomor antrean', 'nomor antrian', 'telat datang', 'terlambat datang',
        'hangus', 'tanpa ktp', 'kartu bpjs', 'rujukan', 'rumah sakit pilihan', 'rs pilihan',
        'memilih dokter', 'pilih dokter',

        // Pembayaran/biaya
        'qris', 'shopeepay', 'gopay', 'ovo', 'dana', 'pembayaran', 'biaya administrasi', 'tarif klinik',
        'berapa biaya', 'berapa harga',

        // Farmasi/stok
        'stok obat', 'stok vaksin', 'stok amoxicillin', 'persediaan obat', 'persediaan vaksin',
        'apotek lain', 'tebus resep', 'resep ditebus', 'obat habis', 'farmasi klinik', 'obat prb',

        // Layanan/fasilitas spesifik
        'ambulans', 'ambulance', 'menjemput pasien', 'jemput pasien', 'home care', 'homecare',
        'kunjungan rumah', 'nebulizer di rumah', 'dipanggil ke rumah', 'panggil ke rumah',
        'surat keterangan sehat', 'surat sehat', 'surat keterangan sakit', 'surat sakit',
        'buta warna', 'calon pengantin', 'catin', 'suntik vitamin', 'tes laboratorium',

        // Kanal komunikasi sebagai kebijakan, bukan sekadar minta nomor
        'lewat whatsapp', 'melalui whatsapp', 'via whatsapp', 'dikirim whatsapp', 'dikirim lewat whatsapp',

        // Hari libur/jam khusus
        'lebaran', 'idul fitri', 'idul adha', 'tanggal merah', 'libur nasional', 'natal', 'tahun baru',
    ];

    private array $clinicTerms = [
        'klinik', 'dokter', 'jadwal', 'poli', 'bpjs', 'jkn', 'telehealth', 'rujuk', 'rujukan',
        'farmasi', 'pendaftaran', 'daftar', 'antrean', 'antrian', 'usg', 'scaling', 'karang gigi',
        'kia', 'surat sehat', 'surat sakit', 'laboratorium', 'lab', 'kontak', 'whatsapp', 'alamat',
        'cabang', 'pengumuman', 'promo', 'faskes', 'onsite', 'kuota', 'layanan', 'pelayanan',
        'bidan', 'tes buta warna', 'vaksin', 'nebulizer', 'fisioterapi', 'akupuntur', 'akupunktur',
    ];

    private array $healthTerms = [
        // Gejala
        'sakit', 'nyeri', 'demam', 'panas', 'batuk', 'pilek', 'flu', 'mual', 'muntah', 'diare',
        'mencret', 'pusing', 'sakit kepala', 'sesak', 'napas', 'nafas', 'gatal', 'ruam', 'bengkak',
        'luka', 'darah', 'bab', 'bak', 'kencing', 'urin', 'haid', 'menstruasi', 'hamil', 'kehamilan',
        'alergi', 'pingsan', 'kejang', 'lemas', 'meriang', 'mules', 'sembelit', 'berdebar',
        'kesemutan', 'kebas', 'pegal', 'kram', 'memar', 'benjolan', 'sariawan', 'mimisan',

        // Anatomi
        'perut', 'lambung', 'ulu hati', 'gigi', 'gusi', 'mulut', 'bibir', 'lidah', 'rahang',
        'tenggorokan', 'hidung', 'telinga', 'mata', 'kulit', 'kepala', 'otak', 'leher', 'bahu',
        'lengan', 'tangan', 'jari', 'kuku', 'dada', 'jantung', 'paru', 'paru-paru', 'hati', 'liver',
        'ginjal', 'usus', 'pankreas', 'limpa', 'empedu', 'kandung kemih', 'rahim', 'uterus',
        'ovarium', 'prostat', 'testis', 'payudara', 'pinggang', 'punggung', 'tulang', 'otot',
        'sendi', 'lutut', 'kaki', 'tumit', 'pergelangan', 'saraf', 'pembuluh darah',

        // Kondisi/edukasi
        'maag', 'gastritis', 'asam lambung', 'gerd', 'hipertensi', 'diabetes', 'kolesterol',
        'asam urat', 'anemia', 'asma', 'tbc', 'pneumonia', 'bronkitis', 'sinusitis', 'radang',
        'infeksi', 'migrain', 'vertigo', 'stroke', 'dbd', 'tifus', 'herpes', 'jamur', 'eksim',
        'dermatitis', 'wasir', 'batu ginjal', 'isk', 'osteoporosis', 'rematik',

        // Pemeriksaan/parameter medis sebagai edukasi umum
        'kesehatan', 'gejala', 'keluhan', 'penyakit', 'diagnosis', 'skrining', 'screening',
        'gula darah', 'tekanan darah', 'tensi', 'hemoglobin', 'trombosit', 'leukosit',
        'saturasi', 'spo2', 'suhu tubuh', 'bmi', 'imt', 'spirometri', 'ekg', 'eeg',
        'imunisasi', 'vitamin', 'antibiotik', 'parasetamol', 'paracetamol',
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

        // PRIORITAS 1: fakta/kebijakan operasional klinik. Ini harus menang dari kata seperti
        // "obat", "vaksin", "pasien", dll. agar tidak salah masuk health_general.
        if ($this->looksLikeClinicOperationalQuestion($text)) {
            return 'clinic';
        }

        // Pertanyaan yang eksplisit menyebut Klinik Mitra Sehat dianggap fakta klinik,
        // kecuali bentuknya jelas edukasi kesehatan umum.
        if ($this->containsAny($text, ['klinik mitra sehat', 'mitra sehat'])) {
            return 'clinic';
        }

        // Jadwal dokter spesifik.
        if (str_contains($text, 'dokter') && $this->containsAny($text, [
            'jadwal', 'praktik', 'praktek', 'besok', 'hari ini', 'sekarang', 'minggu', 'siapa', 'jam',
        ])) {
            return 'clinic';
        }

        // Pertanyaan ketersediaan layanan/pemeriksaan di konteks website klinik harus menjadi
        // fakta klinik, meskipun objeknya juga istilah kesehatan seperti vaksin/nebulizer/lab.
        if ($this->containsAny($text, [
            'tersedia', 'ketersediaan', 'melayani', 'menyediakan', 'menerima', 'ada layanan',
            'bisa periksa', 'bisa cek', 'bisa tes', 'bisa test', 'bisa melakukan pemeriksaan',
        ])) {
            return 'clinic';
        }

        // PRIORITAS 2: kesehatan umum/keluhan.
        if ($this->containsAny($text, $this->healthTerms) || $this->looksLikeHealthQuestion($text)) {
            return 'health';
        }

        // PRIORITAS 3: informasi klinik yang lebih umum.
        if ($this->containsAny($text, $this->clinicTerms)) {
            return 'clinic';
        }

        // Pertanyaan ketersediaan/biaya yang tidak dikenal tetap diperlakukan sebagai fakta klinik
        // agar masuk "belum tersedia", bukan dijawab bebas oleh Qwen.
        if ($this->containsAny($text, [
            'apakah ada', 'ada ', 'tersedia', 'melayani', 'menyediakan', 'menerima',
            'berapa biaya', 'berapa harga', 'bisa periksa', 'bisa cek', 'bisa tes', 'bisa test',
        ])) {
            return 'clinic';
        }

        return 'out_of_scope';
    }

    public function isHealthRelated(string $question): bool
    {
        $text = $this->normalize($question);
        return $this->containsAny($text, $this->healthTerms) || $this->looksLikeHealthQuestion($text);
    }

    private function looksLikeClinicOperationalQuestion(string $text): bool
    {
        if ($this->containsAny($text, $this->clinicOperationalTerms)) {
            return true;
        }

        // Pola real-time/kebijakan klinik yang tidak selalu menyebut kata "klinik".
        if ($this->containsAny($text, ['stok ', 'persediaan ', 'jumlah pasien', 'berapa pasien'])) {
            return true;
        }

        if ($this->containsAny($text, ['booking', 'antrean', 'antrian'])
            && $this->containsAny($text, ['whatsapp', 'wa', 'mobile jkn', 'telat', 'terlambat', 'hangus', 'nomor'])) {
            return true;
        }

        if ($this->containsAny($text, ['hasil pemeriksaan', 'hasil lab', 'hasil tes'])
            && $this->containsAny($text, ['whatsapp', 'wa', 'dikirim', 'kirim'])) {
            return true;
        }

        if ($this->containsAny($text, ['resep', 'obat'])
            && $this->containsAny($text, ['apotek lain', 'stok', 'persediaan', 'habis', 'tebus'])) {
            return true;
        }

        if ($this->containsAny($text, ['bpjs', 'mobile jkn'])
            && $this->containsAny($text, ['ktp', 'dilayani', 'daftar', 'antrean', 'antrian'])) {
            return true;
        }

        if ($this->containsAny($text, ['rujukan'])
            && $this->containsAny($text, ['rumah sakit', 'rs ', 'pilih', 'pilihan', 'biaya', 'administrasi'])) {
            return true;
        }

        return false;
    }

    private function looksLikeHealthQuestion(string $text): bool
    {
        $medicalMarkers = [
            'apa itu', 'apa fungsi', 'fungsi dari', 'kenapa sakit', 'kenapa terasa', 'normalnya',
            'apakah normal', 'bahaya tidak', 'berbahaya tidak', 'cara merawat', 'cara mengatasi',
            'penyebab', 'gejala', 'obat untuk', 'harus periksa', 'kapan ke dokter',
        ];

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
        $text = Str::lower(Str::ascii($text));
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        return preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);
    }
}
