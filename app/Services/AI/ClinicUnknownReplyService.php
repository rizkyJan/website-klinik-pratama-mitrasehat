<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

/**
 * Membentuk jawaban clinic_unknown yang tetap aman (tidak mengarang fakta klinik),
 * tetapi terasa natural dan benar-benar menjawab maksud pertanyaan pengguna.
 */
class ClinicUnknownReplyService
{
    public function build(string $question): string
    {
        $cleanQuestion = $this->cleanQuestion($question);
        $normalized = $this->normalize($question);

        $opening = $cleanQuestion !== ''
            ? "Mohon maaf, untuk pertanyaan “{$cleanQuestion}”, saya belum menemukan informasi resmi yang cukup di basis pengetahuan Klinik Mitra Sehat."
            : 'Mohon maaf, saya belum menemukan informasi resmi yang cukup di basis pengetahuan Klinik Mitra Sehat untuk menjawab pertanyaan tersebut.';

        $clarification = $this->specificUncertainty($normalized);

        return implode("\n\n", array_values(array_filter([
            $opening,
            $clarification,
            'Karena belum ada data resmi, saya tidak akan menebak atau membuat asumsi mengenai layanan/kebijakan klinik.',
            'Pertanyaan ini sudah dicatat sebagai bahan ajar AI agar admin klinik dapat melengkapi jawaban resminya. Untuk kepastian saat ini, silakan menghubungi petugas Klinik Pratama Mitra Sehat.',
        ])));
    }

    private function specificUncertainty(string $text): string
    {
        if ($this->containsAny($text, ['stok obat', 'stok vaksin', 'persediaan obat', 'persediaan vaksin', 'stok '])) {
            return 'Saya belum memiliki akses ke data stok klinik secara real-time, sehingga ketersediaannya saat ini belum dapat saya pastikan.';
        }

        if ($this->containsAny($text, ['jumlah pasien', 'berapa pasien', 'pasien hari ini', 'pasien sekarang'])) {
            return 'Saya belum terhubung ke data jumlah pasien secara real-time, sehingga jumlah pasien saat ini belum dapat saya pastikan.';
        }

        if ($this->containsAny($text, ['petugas pendaftaran', 'petugas jaga', 'petugas yang jaga', 'admin yang jaga'])) {
            return 'Saya belum terhubung ke data jadwal petugas secara real-time, sehingga siapa petugas yang sedang berjaga belum dapat saya pastikan.';
        }

        if ($this->containsAny($text, ['ambulans', 'ambulance', 'menjemput pasien', 'jemput pasien'])) {
            return 'Saya belum bisa memastikan apakah Klinik Mitra Sehat memiliki ambulans sendiri atau menyediakan layanan penjemputan pasien.';
        }

        if ($this->containsAny($text, ['golongan darah'])) {
            return 'Saya belum bisa memastikan apakah layanan pemeriksaan golongan darah tersedia di Klinik Mitra Sehat.';
        }

        if ($this->containsAny($text, ['qris', 'shopeepay', 'gopay', 'ovo', 'dana', 'e wallet', 'ewallet'])) {
            return 'Saya belum bisa memastikan apakah metode pembayaran tersebut diterima di Klinik Mitra Sehat.';
        }

        if ($this->containsAny($text, ['hasil pemeriksaan', 'hasil lab', 'hasil tes']) && $this->containsAny($text, ['whatsapp', ' via wa', ' lewat wa', ' melalui wa'])) {
            return 'Saya belum menemukan kebijakan resmi apakah hasil pemeriksaan dapat dikirim melalui WhatsApp.';
        }

        if ($this->containsAny($text, ['booking', 'antrean', 'antrian', 'pendaftaran', 'daftar']) && $this->containsAny($text, ['whatsapp', ' via wa', ' lewat wa', ' melalui wa'])) {
            return 'Saya belum menemukan kebijakan resmi apakah pendaftaran atau booking antrean dapat dilakukan melalui WhatsApp.';
        }

        if ($this->containsAny($text, ['konsultasi']) && $this->containsAny($text, ['whatsapp', ' via wa', ' lewat wa', ' melalui wa'])) {
            return 'Saya belum menemukan kebijakan resmi apakah konsultasi sebelum kunjungan dapat dilakukan melalui WhatsApp.';
        }

        if ($this->containsAny($text, ['tanpa ktp']) && $this->containsAny($text, ['bpjs', 'mobile jkn', 'jkn mobile'])) {
            return 'Saya belum menemukan ketentuan resmi apakah pasien BPJS tanpa KTP tetapi memiliki Mobile JKN tetap dapat dilayani.';
        }

        if ($this->containsAny($text, ['rujukan']) && $this->containsAny($text, ['rumah sakit pilihan', 'rs pilihan', 'pilih rumah sakit', 'memilih rumah sakit'])) {
            return 'Saya belum menemukan ketentuan resmi apakah pasien dapat memilih rumah sakit tujuan rujukan.';
        }

        if ($this->containsAny($text, ['pilih dokter', 'memilih dokter', 'dokter sendiri'])) {
            return 'Saya belum menemukan ketentuan resmi apakah pasien dapat memilih dokter sendiri.';
        }

        if ($this->containsAny($text, ['antrean', 'antrian']) && $this->containsAny($text, ['telat', 'terlambat', 'hangus'])) {
            return 'Saya belum menemukan ketentuan resmi mengenai status nomor antrean ketika pasien terlambat datang.';
        }

        if ($this->containsAny($text, ['resep', 'obat']) && $this->containsAny($text, ['apotek lain', 'ditebus', 'tebus'])) {
            return 'Saya belum menemukan kebijakan resmi mengenai penebusan resep atau obat di apotek lain.';
        }

        if ($this->containsAny($text, ['lebaran', 'idul fitri', 'idul adha', 'tanggal merah', 'libur nasional', 'natal', 'tahun baru'])) {
            return 'Saya belum menemukan jadwal operasional khusus untuk hari libur yang Anda tanyakan, sehingga saya belum dapat memastikan apakah klinik buka pada hari tersebut.';
        }

        if ($this->containsAny($text, ['berapa biaya', 'berapa harga', 'tarif ', 'biaya '])) {
            return 'Saya belum menemukan tarif resmi untuk layanan yang Anda tanyakan, sehingga saya belum dapat menyebutkan nominal biayanya.';
        }

        if ($this->containsAny($text, ['apakah ada layanan', 'apakah tersedia', 'melayani pemeriksaan', 'melayani layanan', 'bisa periksa', 'bisa tes', 'bisa test'])) {
            return 'Saya belum bisa memastikan apakah layanan atau pemeriksaan yang Anda tanyakan tersedia di Klinik Mitra Sehat.';
        }

        if ($this->containsAny($text, ['punya ', 'mempunyai ', 'memiliki '])) {
            return 'Saya belum bisa memastikan apakah fasilitas atau layanan yang Anda tanyakan dimiliki/tersedia di Klinik Mitra Sehat.';
        }

        if ($this->containsAny($text, ['bisa ', 'boleh ', 'dapat ', 'tetap bisa', 'tetap dapat'])) {
            return 'Saya belum menemukan ketentuan resmi yang cukup untuk memastikan apakah hal tersebut dapat dilakukan di Klinik Mitra Sehat.';
        }

        return 'Saya belum menemukan jawaban resmi yang cukup untuk memberikan kepastian atas pertanyaan tersebut.';
    }

    private function cleanQuestion(string $question): string
    {
        $question = trim(preg_replace('/\s+/u', ' ', $question) ?? $question);
        $question = trim($question, " \t\n\r\0\x0B\"'“”");

        if ($question === '') {
            return '';
        }

        return Str::limit($question, 170, '…');
    }

    private function normalize(string $text): string
    {
        $text = Str::lower(Str::ascii($text));
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        return ' '.preg_replace('/\s+/', ' ', trim($text)).' ';
    }

    private function containsAny(string $text, array $terms): bool
    {
        foreach ($terms as $term) {
            $needle = trim($this->normalize($term));
            if ($needle !== '' && str_contains($text, $needle)) {
                return true;
            }
        }

        return false;
    }
}
