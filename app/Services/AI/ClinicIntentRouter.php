<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

/**
 * Router deterministik untuk fakta Klinik Mitra Sehat.
 * Fakta operasional tidak boleh ditentukan oleh Qwen.
 */
class ClinicIntentRouter
{
    public const CLINIC_HOURS = 'clinic_hours';
    public const HOLIDAY_HOURS = 'holiday_hours';
    public const DOCTOR_SCHEDULE = 'doctor_schedule';
    public const MOBILE_JKN = 'mobile_jkn';
    public const CONTACT = 'clinic_contact';
    public const LOCATION = 'clinic_location';
    public const ANNOUNCEMENT = 'clinic_announcement';
    public const SERVICE_HOURS = 'service_hours';
    public const SERVICE_AVAILABILITY = 'service_availability';
    public const REALTIME_DATA = 'realtime_data';
    public const GENERAL = 'clinic_general';

    public function detect(string $question): string
    {
        $text = $this->normalize($question);

        if ($this->isMobileJknProcedure($text)) {
            return self::MOBILE_JKN;
        }

        // Jadwal libur khusus tidak boleh dijawab memakai jam rutin bila belum ada pengumuman.
        if ($this->isHolidayHoursQuestion($text)) {
            return self::HOLIDAY_HOURS;
        }

        // Jam buka KLINIK ≠ jadwal dokter.
        if ($this->isClinicHoursQuestion($text)) {
            return self::CLINIC_HOURS;
        }

        if ($this->isServiceHoursQuestion($text)) {
            return self::SERVICE_HOURS;
        }

        if ($this->isDoctorScheduleQuestion($text)) {
            return self::DOCTOR_SCHEDULE;
        }

        // Data real-time yang belum terhubung ke SIMRS/farmasi/SDM tidak boleh ditebak.
        if ($this->isRealtimeQuestion($text)) {
            return self::REALTIME_DATA;
        }

        if ($this->containsAny($text, ['alamat', 'lokasi', 'maps', 'google maps', 'dimana klinik', 'di mana klinik'])) {
            return self::LOCATION;
        }

        // Menyebut kata WhatsApp tidak otomatis berarti user meminta nomor kontak.
        if ($this->isContactRequest($text)) {
            return self::CONTACT;
        }

        if ($this->containsAny($text, ['pengumuman', 'info terbaru', 'informasi terbaru', 'ada perubahan', 'libur klinik', 'klinik libur'])) {
            return self::ANNOUNCEMENT;
        }

        if ($this->looksLikeAvailabilityQuestion($text)) {
            return self::SERVICE_AVAILABILITY;
        }

        return self::GENERAL;
    }

    private function isMobileJknProcedure(string $text): bool
    {
        return $this->containsAny($text, ['mobile jkn', 'jkn mobile', 'telehealth'])
            && $this->containsAny($text, ['daftar', 'pendaftaran', 'cara', 'bagaimana', 'gimana', 'masuk', 'chat']);
    }

    private function isHolidayHoursQuestion(string $text): bool
    {
        $holiday = $this->containsAny($text, [
            'lebaran', 'idul fitri', 'idul adha', 'tanggal merah', 'libur nasional',
            'hari libur', 'natal', 'tahun baru',
        ]);

        if (! $holiday) {
            return false;
        }

        return $this->containsAny($text, [
            'buka', 'tutup', 'jam', 'pelayanan', 'layanan', 'praktik', 'praktek', 'dokter', 'klinik',
        ]);
    }

    private function isClinicHoursQuestion(string $text): bool
    {
        $mentionsHours = $this->containsAny($text, [
            'jam buka', 'buka jam', 'jam kerja', 'jam operasional', 'jam pelayanan klinik',
            'jam layanan klinik', 'klinik buka', 'klinik tutup', 'buka besok',
            'buka hari ini', 'buka minggu', 'buka senin', 'buka selasa',
            'buka rabu', 'buka kamis', 'buka jumat', 'buka sabtu',
        ]);

        if (! $mentionsHours) {
            return false;
        }

        if ($this->containsAny($text, ['jadwal dokter', 'dokter siapa', 'dokter yang', 'praktik dokter', 'praktek dokter'])) {
            return false;
        }

        if ($this->containsAny($text, ['poli gigi', 'poli umum', 'poli kia', 'usg', 'pelayanan online'])) {
            return false;
        }

        return true;
    }

    private function isServiceHoursQuestion(string $text): bool
    {
        if ($this->containsAny($text, ['dokter', 'drg ', 'dr ']) || preg_match('/\bdrg?\b/u', $text) === 1) {
            return false;
        }

        $service = $this->containsAny($text, ['poli gigi', 'poli umum', 'poli kia', 'usg', 'pelayanan online', 'layanan online']);
        $time = $this->containsAny($text, ['jam', 'jadwal', 'buka', 'tutup', 'hari ini', 'besok', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);

        return $service && $time;
    }

    private function isDoctorScheduleQuestion(string $text): bool
    {
        $doctor = $this->containsAny($text, [
            'jadwal dokter', 'dokter siapa', 'siapa dokter', 'dokter yang', 'dokter praktik',
            'dokter praktek', 'praktik dokter', 'praktek dokter', 'dokter jaga', 'dokter', 'dr ', 'drg ',
        ]);

        if (! $doctor) {
            $doctor = preg_match('/\bdrg?\b/u', $text) === 1;
        }

        return $doctor && $this->containsAny($text, [
            'jadwal', 'praktik', 'praktek', 'hari ini', 'sekarang', 'saat ini', 'besok', 'lusa',
            'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu', 'jam', 'siapa', 'jaga',
        ]);
    }

    private function isRealtimeQuestion(string $text): bool
    {
        if ($this->containsAny($text, ['stok ', 'persediaan '])) {
            return true;
        }

        if ($this->containsAny($text, ['jumlah pasien', 'berapa pasien', 'pasien hari ini', 'pasien sekarang'])) {
            return true;
        }

        if ($this->containsAny($text, ['petugas pendaftaran', 'petugas yang jaga', 'petugas jaga', 'admin yang jaga'])) {
            return true;
        }

        if ($this->containsAny($text, ['nomor antrean sekarang', 'nomor antrian sekarang', 'antrean saat ini', 'antrian saat ini'])) {
            return true;
        }

        return false;
    }

    private function isContactRequest(string $text): bool
    {
        return $this->containsAny($text, [
            'nomor whatsapp', 'nomor wa', 'wa klinik berapa', 'whatsapp klinik berapa',
            'nomor telepon', 'telepon klinik', 'kontak klinik', 'cara menghubungi klinik',
            'hubungi klinik',
        ]);
    }

    private function looksLikeAvailabilityQuestion(string $text): bool
    {
        return $this->containsAny($text, [
            'apakah ada', 'ada ', 'tersedia', 'melayani', 'menyediakan', 'bisa periksa',
            'bisa cek', 'bisa tes', 'bisa test', 'bisa melakukan', 'punya layanan',
        ]);
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
