<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

/**
 * Router intent khusus fakta operasional klinik.
 *
 * Tujuan utama: pertanyaan yang jawabannya sudah ada di database/config tidak perlu
 * "dipikirkan" oleh model kecil. Ini membuat jawaban lebih akurat, cepat, konsisten,
 * dan tidak terpotong oleh batas token model.
 */
class ClinicIntentRouter
{
    public const CLINIC_HOURS = 'clinic_hours';
    public const DOCTOR_SCHEDULE = 'doctor_schedule';
    public const MOBILE_JKN = 'mobile_jkn';
    public const CONTACT = 'clinic_contact';
    public const LOCATION = 'clinic_location';
    public const ANNOUNCEMENT = 'clinic_announcement';
    public const SERVICE_HOURS = 'service_hours';
    public const SERVICE_AVAILABILITY = 'service_availability';
    public const GENERAL = 'clinic_general';

    public function detect(string $question): string
    {
        $text = $this->normalize($question);

        // Pendaftaran Mobile JKN/Telehealth harus menang atas kata "dokter/poli" yang
        // mungkin ikut muncul di kalimat prosedurnya.
        if ($this->containsAny($text, ['mobile jkn', 'jkn mobile', 'telehealth'])
            && $this->containsAny($text, ['daftar', 'pendaftaran', 'cara', 'bagaimana', 'gimana', 'masuk', 'chat'])) {
            return self::MOBILE_JKN;
        }

        // Jam buka KLINIK ≠ jadwal dokter. Prioritaskan intent ini sebelum doctor_schedule.
        if ($this->isClinicHoursQuestion($text)) {
            return self::CLINIC_HOURS;
        }

        if ($this->isServiceHoursQuestion($text)) {
            return self::SERVICE_HOURS;
        }

        if ($this->isDoctorScheduleQuestion($text)) {
            return self::DOCTOR_SCHEDULE;
        }

        if ($this->containsAny($text, ['alamat', 'lokasi', 'maps', 'google maps', 'dimana klinik', 'di mana klinik'])) {
            return self::LOCATION;
        }

        if ($this->containsAny($text, ['nomor whatsapp', 'nomor wa', 'whatsapp', 'kontak', 'telepon', 'nomor telepon'])) {
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

        // Jika secara eksplisit menyebut dokter/poli tertentu, bukan jam buka klinik umum.
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
            'dokter praktek', 'praktik dokter', 'praktek dokter', 'dr ', 'drg ',
        ]);

        if (! $doctor) {
            // "jadwal dr auliya" atau "dr auliya besok" tetap ditangani sebagai jadwal dokter.
            $doctor = preg_match('/\bdrg?\b/u', $text) === 1;
        }

        return $doctor && $this->containsAny($text, [
            'jadwal', 'praktik', 'praktek', 'hari ini', 'sekarang', 'saat ini', 'besok', 'lusa',
            'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu', 'jam', 'siapa',
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
        $text = preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);
        return $text;
    }
}
