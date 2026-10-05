<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

/**
 * Gerbang terakhir sebelum pertanyaan masuk ke model.
 *
 * Pertanyaan yang meminta fakta/kebijakan Klinik Mitra Sehat harus selalu
 * diperlakukan sebagai domain clinic, walaupun di dalamnya ada kata medis
 * seperti obat, vaksin, dokter, BPJS, pemeriksaan, dll.
 *
 * Tujuannya sederhana: fakta klinik tidak boleh pernah dijawab dari
 * pengetahuan umum Qwen. Jika data resmi tidak ada, jawab clinic_unknown.
 */
class ClinicFactGuard
{
    /**
     * Pertanyaan berikut harus dipaksa menjadi domain clinic.
     */
    public function shouldForceClinic(string $question): bool
    {
        $text = $this->normalize($question);

        if ($text === '') {
            return false;
        }

        // 1) Data real-time internal klinik.
        if ($this->containsAny($text, [
            'stok ', 'persediaan ', 'stok obat', 'stok vaksin',
            'jumlah pasien', 'berapa pasien', 'pasien hari ini', 'pasien sekarang',
            'petugas pendaftaran', 'petugas jaga', 'petugas yang jaga', 'admin yang jaga',
            'nomor antrean sekarang', 'nomor antrian sekarang', 'antrean saat ini', 'antrian saat ini',
        ])) {
            return true;
        }

        // 2) Kebijakan pembayaran / kanal pembayaran.
        if ($this->containsAny($text, [
            'qris', 'shopeepay', 'gopay', 'ovo', 'dana', 'e wallet', 'ewallet',
            'metode pembayaran', 'cara bayar', 'pembayaran lewat', 'pembayaran via',
        ])) {
            return true;
        }

        // 3) Kebijakan WhatsApp. Menyebut WhatsApp untuk suatu tindakan bukan berarti meminta nomor kontak.
        if ($this->containsAny($text, ['whatsapp', ' wa ', ' via wa', ' lewat wa', ' melalui wa'])) {
            if ($this->containsAny($text, [
                'booking', 'antrean', 'antrian', 'daftar', 'pendaftaran', 'konsultasi',
                'hasil pemeriksaan', 'hasil lab', 'hasil tes', 'dikirim', 'kirim hasil',
                'resep', 'rujukan', 'konfirmasi',
            ])) {
                return true;
            }
        }

        // 4) Kebijakan identitas/BPJS/JKN.
        if ($this->containsAny($text, ['bpjs', 'mobile jkn', 'jkn mobile'])) {
            if ($this->containsAny($text, [
                'ktp', 'tanpa ktp', 'identitas', 'dilayani', 'bisa dilayani', 'daftar',
                'antrean', 'antrian', 'telat', 'terlambat', 'hangus', 'rujukan', 'biaya',
            ])) {
                return true;
            }
        }

        // 5) Kebijakan rujukan dan pilihan dokter.
        if ($this->containsAny($text, ['rujukan', 'rumah sakit', ' rs ', 'dokter'])) {
            if ($this->containsAny($text, [
                'pilih', 'pilihan', 'memilih', 'dokter sendiri', 'rumah sakit pilihan',
                'rs pilihan', 'biaya administrasi', 'administrasi',
            ])) {
                return true;
            }
        }

        // 6) Kebijakan obat/farmasi yang spesifik ke klinik.
        if ($this->containsAny($text, ['obat', 'resep', 'farmasi', 'apotek'])) {
            if ($this->containsAny($text, [
                'stok', 'persediaan', 'habis', 'apotek lain', 'tebus', 'ditebus',
                'boleh ditebus', 'bisa ditebus', 'ambil obat', 'pengambilan obat',
            ])) {
                return true;
            }
        }

        // 7) Fasilitas/layanan yang harus bersumber dari data klinik.
        if ($this->containsAny($text, [
            'ambulans', 'ambulance', 'menjemput pasien', 'jemput pasien', 'panggil ke rumah',
            'dipanggil ke rumah', 'home care', 'homecare', 'kunjungan rumah',
            'nebulizer', 'nebulisasi', 'suntik vitamin', 'injeksi vitamin', 'booster vitamin',
            'vaksin influenza', 'vaksin tetanus', 'calon pengantin', 'catin',
        ])) {
            return true;
        }

        // 8) Aturan pemeriksaan tertentu (bukan edukasi medis umum).
        if ($this->containsAny($text, [
            'tanpa konsultasi', 'tanpa konsultasi dokter', 'tanpa puasa',
            'langsung atau harus daftar', 'bisa langsung', 'harus daftar dulu',
            'menit sebelum tutup', 'sebelum tutup',
        ])) {
            return true;
        }

        // 9) Hari libur khusus klinik.
        if ($this->containsAny($text, [
            'lebaran', 'idul fitri', 'idul adha', 'tanggal merah', 'libur nasional',
            'natal', 'tahun baru',
        ])) {
            return true;
        }

        // 10) Jika eksplisit menyebut Klinik Mitra Sehat, anggap fakta klinik kecuali
        // pertanyaannya jelas merupakan edukasi kesehatan umum (mis. "apa itu hipertensi").
        if ($this->containsAny($text, ['klinik mitra sehat', 'mitra sehat'])) {
            if (! $this->looksLikePureHealthEducation($text)) {
                return true;
            }
        }

        // 11) Pola ketersediaan/biaya layanan tanpa menyebut nama klinik tetap dianggap
        // fakta klinik karena chatbot hanya mewakili Klinik Mitra Sehat.
        if ($this->containsAny($text, [
            'apakah ada layanan', 'apakah tersedia', 'tersedia vaksin', 'tersedia pemeriksaan',
            'melayani pemeriksaan', 'melayani layanan', 'bisa periksa', 'bisa tes', 'bisa test',
            'berapa biaya', 'berapa harga', 'tarif ',
        ])) {
            return true;
        }

        return false;
    }

    private function looksLikePureHealthEducation(string $text): bool
    {
        $education = $this->containsAny($text, [
            'apa itu ', 'apa arti ', 'apa artinya ', 'pengertian ', 'definisi ',
            'apa penyebab ', 'penyebab ', 'apa gejala ', 'gejala ', 'cara mencegah ',
            'cara menjaga ', 'fungsi ', 'apa fungsi ', 'normalnya ', 'berapa normal ',
        ]);

        $clinicPolicy = $this->containsAny($text, [
            'tersedia', 'melayani', 'menyediakan', 'menerima', 'bisa dilakukan', 'bisa periksa',
            'biaya', 'harga', 'tarif', 'jadwal', 'jam', 'buka', 'tutup', 'daftar', 'pendaftaran',
            'antrean', 'antrian', 'bpjs', 'jkn', 'whatsapp', 'rujukan', 'stok', 'petugas',
        ]);

        return $education && ! $clinicPolicy;
    }

    private function normalize(string $text): string
    {
        $text = ' '.Str::lower(Str::ascii($text)).' ';
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);
        return ' '.$text.' ';
    }

    private function containsAny(string $text, array $terms): bool
    {
        foreach ($terms as $term) {
            $term = trim($this->normalize($term));
            if ($term !== '' && str_contains($text, $term)) {
                return true;
            }
        }

        return false;
    }
}
