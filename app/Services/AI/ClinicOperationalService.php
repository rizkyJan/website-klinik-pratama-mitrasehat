<?php

namespace App\Services\AI;

use App\Models\AiKnowledge;
use App\Models\Announcement;
use App\Models\ClinicSetting;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Menjawab fakta operasional klinik secara deterministik dari database/config.
 * Tidak memakai Qwen untuk menentukan jam, tanggal, nomor kontak, atau prosedur resmi.
 */
class ClinicOperationalService
{
    public function __construct(
        private readonly ClinicDateResolver $dates,
        private readonly ClinicQueryAnalyzer $queryAnalyzer,
    ) {
    }

    /**
     * @return array{text:string,sources:array<int,string>,classification:string}|null
     */
    public function answer(string $intent, string $question): ?array
    {
        return match ($intent) {
            ClinicIntentRouter::CLINIC_HOURS => $this->clinicHours($question),
            ClinicIntentRouter::HOLIDAY_HOURS => $this->holidayHours($question),
            ClinicIntentRouter::MOBILE_JKN => $this->mobileJkn(),
            ClinicIntentRouter::CONTACT => $this->contact(),
            ClinicIntentRouter::LOCATION => $this->location(),
            ClinicIntentRouter::ANNOUNCEMENT => $this->announcements($question),
            ClinicIntentRouter::SERVICE_HOURS => $this->serviceHours($question),
            default => null,
        };
    }

    private function clinicHours(string $question): array
    {
        $now = $this->dates->now();
        $target = $this->dates->resolve($question, $now);

        if ($target === null) {
            $text = "Jam pelayanan Klinik Mitra Sehat\n\n"
                ."• Senin–Sabtu: 24 jam\n"
                ."• Minggu: 07.00–21.00 WIB";

            $note = $this->announcementNoteFor(null, ['jam', 'pelayanan', 'buka', 'tutup', 'libur']);
            if ($note !== null) {
                $text .= "\n\nCatatan pengumuman:\n{$note}";
            }

            return $this->result($text, ['Jam Pelayanan Klinik', ...($note ? ['Pengumuman'] : [])], 'clinic_hours');
        }

        $day = $this->dates->dayName($target);
        $date = $this->dates->dateLabel($target);
        $relative = $this->dates->relativeLabel($target, $now);
        $hours = $this->hoursForDay($day);

        $text = "Jam pelayanan Klinik Mitra Sehat {$relative}\n{$day}, {$date}\n\n"
            ."• Klinik: {$hours}";

        if ($target->isSameDay($now)) {
            $text .= "\n• Waktu sekarang: {$now->format('H.i')} WIB";
            $status = $this->openStatus($day, $hours, $now);
            if ($status !== null) {
                $text .= "\n• Status: {$status}";
            }
        }

        $note = $this->announcementNoteFor($target, ['jam', 'pelayanan', 'buka', 'tutup', 'libur', 'perubahan']);
        if ($note !== null) {
            $text .= "\n\nCatatan pengumuman:\n{$note}";
        }

        $text .= "\n\nJika yang Anda maksud jadwal dokter, tulis misalnya: “jadwal dokter besok”.";

        return $this->result($text, ['Jam Pelayanan Klinik', ...($note ? ['Pengumuman'] : [])], 'clinic_hours');
    }


    private function holidayHours(string $question): ?array
    {
        $target = $this->dates->resolve($question, $this->dates->now());
        $normalized = $this->queryAnalyzer->normalize($question);

        $keywords = ['lebaran', 'idul fitri', 'idul adha', 'tanggal merah', 'libur nasional', 'natal', 'tahun baru', 'libur', 'tutup', 'buka'];
        $items = $this->announcementItemsFor($target)
            ->filter(function (Announcement $item) use ($keywords, $normalized) {
                $text = $this->queryAnalyzer->normalize((string) $item->title.' '.(string) $item->content);

                $hasHolidaySignal = false;
                foreach ($keywords as $keyword) {
                    if (str_contains($text, $this->queryAnalyzer->normalize($keyword))) {
                        $hasHolidaySignal = true;
                        break;
                    }
                }

                if (! $hasHolidaySignal) {
                    return false;
                }

                // Bila pertanyaan menyebut hari raya tertentu, pengumuman juga harus menyebutnya.
                foreach (['lebaran', 'idul fitri', 'idul adha', 'natal', 'tahun baru'] as $specific) {
                    if (str_contains($normalized, $specific) && ! str_contains($text, $specific)) {
                        return false;
                    }
                }

                return true;
            })
            ->take(3);

        if ($items->isEmpty()) {
            return null;
        }

        $lines = ['Informasi jadwal khusus/libur Klinik Mitra Sehat'];
        foreach ($items as $item) {
            $lines[] = '';
            $lines[] = '• '.trim((string) $item->title);
            $content = trim((string) $item->content);
            if ($content !== '') {
                $lines[] = '  '.$content;
            }
        }

        return $this->result(implode("\n", $lines), ['Pengumuman'], 'clinic_holiday_hours');
    }

    private function mobileJkn(): array
    {
        $knowledge = AiKnowledge::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('title', 'like', '%Mobile JKN%')
                    ->orWhere('keywords', 'like', '%mobile jkn%')
                    ->orWhere('keywords', 'like', '%telehealth%');
            })
            ->orderByRaw("CASE WHEN title LIKE '%Mobile JKN%' THEN 0 ELSE 1 END")
            ->orderBy('sort_order')
            ->first();

        if ($knowledge && filled($knowledge->content)) {
            return $this->result(
                $this->formatMobileJknContent((string) $knowledge->content),
                ['Pengetahuan AI: '.$knowledge->title],
                'clinic_mobile_jkn'
            );
        }

        $fallback = <<<'TEXT'
Pendaftaran online melalui Mobile JKN menggunakan menu TELEHEALTH.

Langkah-langkah:
1. Login ke aplikasi Mobile JKN menggunakan akun pasien yang akan diperiksa.
2. Pilih menu TELEHEALTH.
3. Pilih Poli Umum (Dokter) yang aktif.
4. Pilih Chat lalu isi kondisi kesehatan.
5. Setelah masuk kolom chat, kirim data yang diminta petugas sesuai prosedur klinik.
6. Setelah berhasil masuk kolom chat, konfirmasikan melalui WhatsApp Klinik Mitra Sehat.

Tautan:
• Download Mobile JKN: https://bit.ly/AplikasiJKNMobile
• Tutorial Mobile JKN: https://intip.in/VideoTutorialMJKN

Catatan:
• Pendaftaran online tidak dilayani melalui WhatsApp; WhatsApp digunakan untuk konfirmasi sesuai alur pelayanan.
• Jangan mengirim data pribadi melalui chatbot Asisten Klinik ini.
TEXT;

        return $this->result($fallback, ['Prosedur Mobile JKN'], 'clinic_mobile_jkn');
    }

    private function contact(): array
    {
        $whatsapp = trim((string) ClinicSetting::get('clinic_whatsapp', '0813-1170-9726'));
        $phone = trim((string) ClinicSetting::get('clinic_phone', '0271-592374'));
        $instagram = trim((string) ClinicSetting::get('clinic_instagram', ''));

        $lines = ['Kontak Klinik Mitra Sehat'];
        if ($whatsapp !== '') {
            $lines[] = '';
            $lines[] = '• WhatsApp: '.$whatsapp;
        }
        if ($phone !== '') {
            $lines[] = '• Telepon: '.$phone;
        }
        if ($instagram !== '') {
            $lines[] = '• Instagram: '.$instagram;
        }

        return $this->result(implode("\n", $lines), ['Profil Klinik'], 'clinic_contact');
    }

    private function location(): array
    {
        $address = trim((string) ClinicSetting::get(
            'clinic_address',
            'Jl. Veteran No.70, Ngabeyan, Jetis, Kec. Sukoharjo, Kabupaten Sukoharjo, Jawa Tengah 57511'
        ));

        $text = "Lokasi Klinik Mitra Sehat\n\n• {$address}";
        return $this->result($text, ['Profil Klinik'], 'clinic_location');
    }

    private function announcements(string $question): array
    {
        $target = $this->dates->resolve($question, $this->dates->now());
        $items = $this->announcementItemsFor($target)->take(5);

        if ($items->isEmpty()) {
            $label = $target ? ' untuk '.$this->dates->dayName($target).', '.$this->dates->dateLabel($target) : '';
            return $this->result('Saat ini belum ada pengumuman aktif'.$label.'.', ['Pengumuman'], 'clinic_announcement');
        }

        $lines = [$target
            ? 'Pengumuman Klinik Mitra Sehat untuk '.$this->dates->dayName($target).', '.$this->dates->dateLabel($target)
            : 'Pengumuman Klinik Mitra Sehat'];

        foreach ($items as $item) {
            $lines[] = '';
            $lines[] = '• '.$item->title;
            $lines[] = '  '.trim((string) $item->content);
        }

        return $this->result(implode("\n", $lines), ['Pengumuman'], 'clinic_announcement');
    }

    private function serviceHours(string $question): ?array
    {
        $text = $this->queryAnalyzer->normalize($question);
        $topic = match (true) {
            str_contains($text, 'poli gigi') || str_contains($text, 'gigi') => 'gigi',
            str_contains($text, 'usg') => 'usg',
            str_contains($text, 'poli kia') || str_contains($text, 'kia') => 'kia',
            str_contains($text, 'pelayanan online') || str_contains($text, 'layanan online') => 'online',
            str_contains($text, 'poli umum') => 'umum',
            default => null,
        };

        if ($topic === null) {
            return null;
        }

        $knowledge = AiKnowledge::query()
            ->where('is_active', true)
            ->get()
            ->map(function (AiKnowledge $item) use ($question) {
                $source = trim((string) $item->title.' '.(string) $item->keywords);
                return ['item' => $item, 'score' => $this->queryAnalyzer->matchScore($question, $source)];
            })
            ->filter(fn (array $row) => $row['score'] >= 6)
            ->sortByDesc('score')
            ->pluck('item')
            ->first();

        if (! $knowledge) {
            // Fallback khusus dari informasi resmi yang sudah ada pada knowledge awal.
            $fallback = match ($topic) {
                'gigi' => "Jadwal pelayanan Poli Gigi\n\n• Senin–Jumat: 08.00–12.00 WIB\n• Sabtu: 13.00–17.00 WIB\n• Tanggal merah/libur nasional: tutup\n• Kuota pemeriksaan maksimal 15 orang per hari.",
                'usg' => "Jadwal pelayanan USG\n\n• Senin dan Kamis: 15.30–18.00 WIB\n• Selasa: 15.30–18.00 WIB\n• Pendaftaran mengikuti ketentuan reservasi klinik dan ketersediaan kuota.",
                'kia' => "Jadwal pelayanan Poli KIA\n\n• Senin–Sabtu\n• Shift pagi: 07.00–13.00 WIB\n• Shift siang: 14.00–21.00 WIB",
                'online' => "Pelayanan online\n\n• Senin–Minggu: 08.00–20.00 WIB",
                'umum' => "Jadwal pelayanan Poli Umum\n\n• Senin–Sabtu: 24 jam\n• Minggu: 07.00–21.00 WIB",
            };

            return $this->result($fallback, ['Jadwal Pelayanan Klinik'], 'clinic_service_hours');
        }

        return $this->result(
            $this->formatKnowledgeAsReadable((string) $knowledge->content, (string) $knowledge->title),
            ['Pengetahuan AI: '.$knowledge->title],
            'clinic_service_hours'
        );
    }

    private function hoursForDay(string $day): string
    {
        $key = 'clinic_hours_'.Str::lower(Str::ascii($day));
        $default = Str::lower($day) === 'minggu' ? '07.00–21.00 WIB' : '24 jam';
        $value = trim((string) ClinicSetting::get($key, $default));
        return $value !== '' ? $value : $default;
    }

    private function openStatus(string $day, string $hours, CarbonImmutable $now): ?string
    {
        if (str_contains(Str::lower($hours), '24')) {
            return 'buka 24 jam';
        }

        if (preg_match('/(\d{1,2})[.:](\d{2})\s*[–-]\s*(\d{1,2})[.:](\d{2})/u', $hours, $m) !== 1) {
            return null;
        }

        $start = ((int) $m[1]) * 60 + (int) $m[2];
        $end = ((int) $m[3]) * 60 + (int) $m[4];
        $current = ((int) $now->format('H')) * 60 + (int) $now->format('i');

        return ($current >= $start && $current < $end)
            ? 'sedang buka'
            : 'di luar jam pelayanan';
    }

    private function announcementNoteFor(?CarbonImmutable $target, array $keywords): ?string
    {
        $items = $this->announcementItemsFor($target)
            ->filter(function (Announcement $item) use ($keywords) {
                $text = Str::lower(Str::ascii((string) $item->title.' '.(string) $item->content));
                foreach ($keywords as $keyword) {
                    if (str_contains($text, Str::lower(Str::ascii($keyword)))) {
                        return true;
                    }
                }
                return false;
            })
            ->take(2);

        if ($items->isEmpty()) {
            return null;
        }

        return $items->map(fn (Announcement $item) => '• '.$item->title.': '.trim((string) $item->content))->implode("\n");
    }

    private function announcementItemsFor(?CarbonImmutable $target): Collection
    {
        $date = ($target ?? $this->dates->now())->toDateString();

        return Announcement::query()
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('end_date')->orWhereDate('end_date', '>=', $date);
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();
    }

    private function formatMobileJknContent(string $content): string
    {
        $normalized = preg_replace('/\s+/', ' ', trim($content)) ?? trim($content);

        // Knowledge lama berbentuk satu paragraf; susun ulang secara deterministik supaya enak dibaca.
        if (str_contains(Str::lower($normalized), 'telehealth')) {
            return <<<'TEXT'
Pendaftaran online melalui Mobile JKN menggunakan menu TELEHEALTH.

Langkah-langkah:
1. Login ke aplikasi Mobile JKN menggunakan akun pasien yang akan diperiksa.
2. Pilih menu TELEHEALTH.
3. Pilih Poli Umum (Dokter) yang aktif.
4. Pilih Chat, lalu isi kondisi kesehatan.
5. Setelah masuk kolom chat, kirim data yang diminta sesuai prosedur klinik:
   • Nama
   • NIK
   • Keluhan
   • Alergi Obat
   • Berat Badan
6. Setelah berhasil masuk kolom chat, konfirmasikan melalui WhatsApp Klinik Mitra Sehat.

Tautan:
• Download Mobile JKN: https://bit.ly/AplikasiJKNMobile
• Tutorial Mobile JKN: https://intip.in/VideoTutorialMJKN

Catatan:
• Pendaftaran online tidak dilayani melalui WhatsApp. WhatsApp digunakan untuk konfirmasi sesuai alur pelayanan.
• Untuk keamanan, jangan kirim NIK atau data pribadi melalui chatbot Asisten Klinik ini.
TEXT;
        }

        return $this->formatKnowledgeAsReadable($content, 'Pendaftaran Mobile JKN');
    }

    private function formatKnowledgeAsReadable(string $content, string $title): string
    {
        $content = trim($content);
        if ($content === '') {
            return $title;
        }

        // Jangan mengubah fakta; hanya pecah kalimat agar tidak menjadi tembok teks.
        $sentences = preg_split('/(?<=[.!?])\s+/u', $content, -1, PREG_SPLIT_NO_EMPTY) ?: [$content];
        if (count($sentences) <= 2) {
            return $content;
        }

        $lines = [$title, ''];
        foreach ($sentences as $sentence) {
            $lines[] = '• '.trim($sentence);
        }

        return implode("\n", $lines);
    }

    private function result(string $text, array $sources, string $classification): array
    {
        return [
            'text' => trim($text),
            'sources' => array_values(array_unique($sources)),
            'classification' => $classification,
        ];
    }
}
