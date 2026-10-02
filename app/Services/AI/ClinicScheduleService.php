<?php

namespace App\Services\AI;

use App\Models\Announcement;
use App\Models\Doctor;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Menjawab pertanyaan jadwal dokter secara deterministik dari database.
 * Qwen tidak dipakai untuk menentukan hari, jam, atau dokter yang praktik.
 */
class ClinicScheduleService
{
    private const DAYS = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

    private const MONTHS = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function shouldHandle(string $question): bool
    {
        $text = $this->normalize($question);

        $mentionsDoctor = $this->containsAny($text, [
            'dokter', 'dr ', 'drg ', 'jadwal', 'praktik', 'praktek', 'poli',
        ]);

        if (! $mentionsDoctor) {
            return false;
        }

        return $this->containsAny($text, [
            'jadwal', 'hari ini', 'sekarang', 'besok', 'lusa',
            'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu',
            'praktik', 'praktek', 'siapa dokter', 'dokter siapa', 'dokter yang',
        ]);
    }

    /**
     * @return array{text:string,sources:array<int,string>}|null
     */
    public function answer(string $question): ?array
    {
        if (! $this->shouldHandle($question)) {
            return null;
        }

        $now = CarbonImmutable::now('Asia/Jakarta');
        $text = $this->normalize($question);
        $targetDate = $this->resolveTargetDate($text, $now);
        $isNowIntent = $this->containsAny($text, ['sekarang', 'saat ini', 'lagi praktik', 'sedang praktik']);

        $doctors = Doctor::query()
            ->where('is_active', true)
            ->with('schedules')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $doctors = $this->filterDoctors($doctors, $text);

        if ($targetDate === null) {
            return [
                'text' => $this->weeklyAnswer($doctors),
                'sources' => ['Jadwal Dokter'],
            ];
        }

        return [
            'text' => $this->dailyAnswer($doctors, $targetDate, $now, $isNowIntent),
            'sources' => ['Jadwal Dokter'],
        ];
    }

    private function dailyAnswer(Collection $doctors, CarbonImmutable $date, CarbonImmutable $now, bool $isNowIntent): string
    {
        $dayName = self::DAYS[$date->dayOfWeekIso] ?? '';
        $dateLabel = $this->dateLabel($date);
        $isToday = $date->isSameDay($now);

        $scheduled = collect();

        foreach ($doctors as $doctor) {
            $schedule = $doctor->schedules->first(function ($item) use ($dayName) {
                return $this->normalize((string) $item->day) === $this->normalize($dayName);
            });

            if (! $schedule || $schedule->is_off || blank($schedule->start_time) || blank($schedule->end_time)) {
                continue;
            }

            $start = $this->timeText((string) $schedule->start_time);
            $end = $this->timeText((string) $schedule->end_time);

            $scheduled->push([
                'doctor' => $doctor,
                'start' => $start,
                'end' => $end,
                'start_minutes' => $this->minutes((string) $schedule->start_time),
                'end_minutes' => $this->minutes((string) $schedule->end_time),
            ]);
        }

        $scheduled = $scheduled->sortBy('start_minutes')->values();

        $header = ($isToday ? 'Jadwal dokter hari ini' : 'Jadwal dokter '.$dayName)
            ."\n{$dayName}, {$dateLabel}";

        if ($scheduled->isEmpty()) {
            $scope = $this->scopeLabel($doctors);
            $text = $header."\n\nBelum ada jadwal {$scope} yang tercatat untuk hari tersebut.";
            $note = $this->relevantAnnouncementNote();
            return $note ? $text."\n\n{$note}" : $text;
        }

        $currentMinutes = ((int) $now->format('H')) * 60 + (int) $now->format('i');

        if ($isNowIntent && $isToday) {
            $activeNow = $scheduled->filter(fn ($row) => $currentMinutes >= $row['start_minutes'] && $currentMinutes < $row['end_minutes'])->values();

            if ($activeNow->isEmpty()) {
                $future = $scheduled->first(fn ($row) => $row['start_minutes'] > $currentMinutes);
                if ($future) {
                    return "Dokter yang praktik sekarang\n{$dayName}, {$dateLabel} • {$now->format('H.i')} WIB\n\n"
                        ."Saat ini belum ada dokter dalam jam praktik. Jadwal berikutnya:\n"
                        .$this->formatRows(collect([$future]))
                        .$this->announcementSuffix();
                }

                return "Dokter yang praktik sekarang\n{$dayName}, {$dateLabel} • {$now->format('H.i')} WIB\n\n"
                    ."Jadwal praktik dokter hari ini sudah selesai."
                    .$this->announcementSuffix();
            }

            return "Dokter yang praktik sekarang\n{$dayName}, {$dateLabel} • {$now->format('H.i')} WIB\n\n"
                .$this->formatRows($activeNow)
                .$this->announcementSuffix();
        }

        $text = $header."\n\nDokter yang praktik:\n".$this->formatRows($scheduled);

        if ($isToday) {
            $activeNow = $scheduled->filter(fn ($row) => $currentMinutes >= $row['start_minutes'] && $currentMinutes < $row['end_minutes']);
            $future = $scheduled->first(fn ($row) => $row['start_minutes'] > $currentMinutes);

            if ($activeNow->isNotEmpty()) {
                $names = $activeNow->pluck('doctor.name')->implode(', ');
                $text .= "\n\nStatus saat ini ({$now->format('H.i')} WIB): {$names} sedang dalam jam praktik.";
            } elseif ($future) {
                $text .= "\n\nStatus saat ini ({$now->format('H.i')} WIB): belum masuk jam praktik. Jadwal berikutnya mulai pukul {$future['start']} WIB.";
            } else {
                $text .= "\n\nStatus saat ini ({$now->format('H.i')} WIB): jadwal praktik hari ini sudah selesai.";
            }
        }

        return $text.$this->announcementSuffix();
    }

    private function weeklyAnswer(Collection $doctors): string
    {
        if ($doctors->isEmpty()) {
            return 'Belum ada jadwal dokter aktif yang tercatat pada sistem.';
        }

        $lines = ['Jadwal dokter mingguan'];

        foreach (self::DAYS as $dayName) {
            $rows = collect();
            foreach ($doctors as $doctor) {
                $schedule = $doctor->schedules->first(fn ($item) => $this->normalize((string) $item->day) === $this->normalize($dayName));
                if (! $schedule || $schedule->is_off || blank($schedule->start_time) || blank($schedule->end_time)) {
                    continue;
                }

                $rows->push([
                    'doctor' => $doctor,
                    'start' => $this->timeText((string) $schedule->start_time),
                    'end' => $this->timeText((string) $schedule->end_time),
                    'start_minutes' => $this->minutes((string) $schedule->start_time),
                ]);
            }

            if ($rows->isEmpty()) {
                continue;
            }

            $lines[] = '';
            $lines[] = $dayName;
            foreach ($rows->sortBy('start_minutes') as $row) {
                $lines[] = '• '.$row['doctor']->name.' — '.$row['start'].'–'.$row['end'].' WIB';
            }
        }

        $lines[] = '';
        $lines[] = 'Jadwal dapat berubah mengikuti pengumuman terbaru dari klinik.';

        return implode("\n", $lines);
    }

    private function formatRows(Collection $rows): string
    {
        return $rows->map(function ($row) {
            $specialization = trim((string) $row['doctor']->specialization);
            $extra = $specialization !== '' ? ' ('.$specialization.')' : '';
            return '• '.$row['doctor']->name.$extra."\n  ".$row['start'].'–'.$row['end'].' WIB';
        })->implode("\n");
    }

    private function filterDoctors(Collection $doctors, string $text): Collection
    {
        if (str_contains($text, 'gigi') || str_contains($text, 'drg')) {
            $filtered = $doctors->filter(fn ($doctor) => str_contains($this->normalize((string) $doctor->specialization), 'gigi'))->values();
            if ($filtered->isNotEmpty()) {
                return $filtered;
            }
        }

        if (str_contains($text, 'umum')) {
            $filtered = $doctors->filter(fn ($doctor) => str_contains($this->normalize((string) $doctor->specialization), 'umum'))->values();
            if ($filtered->isNotEmpty()) {
                return $filtered;
            }
        }

        // Jika nama dokter disebut, batasi ke dokter tersebut. Token nama pendek seperti "dr" diabaikan.
        foreach ($doctors as $doctor) {
            $nameTokens = preg_split('/\s+/', $this->normalize((string) $doctor->name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $nameTokens = array_values(array_filter($nameTokens, fn ($token) => mb_strlen($token) >= 4 && ! in_array($token, ['dokter'], true)));
            foreach ($nameTokens as $token) {
                if (preg_match('/\b'.preg_quote($token, '/').'\b/u', $text) === 1) {
                    return $doctors->filter(fn ($item) => $item->id === $doctor->id)->values();
                }
            }
        }

        return $doctors;
    }

    private function resolveTargetDate(string $text, CarbonImmutable $now): ?CarbonImmutable
    {
        if ($this->containsAny($text, ['hari ini', 'sekarang', 'saat ini'])) {
            return $now->startOfDay();
        }

        if (str_contains($text, 'besok')) {
            return $now->addDay()->startOfDay();
        }

        if (str_contains($text, 'lusa')) {
            return $now->addDays(2)->startOfDay();
        }

        foreach (self::DAYS as $iso => $name) {
            if (preg_match('/\b'.preg_quote($this->normalize($name), '/').'\b/u', $text) === 1) {
                $delta = ($iso - $now->dayOfWeekIso + 7) % 7;
                return $now->addDays($delta)->startOfDay();
            }
        }

        return null;
    }

    private function relevantAnnouncementNote(): ?string
    {
        $items = Announcement::visible()->take(5)->get()->filter(function ($item) {
            $text = $this->normalize((string) $item->title.' '.(string) $item->content);
            return $this->containsAny($text, ['dokter', 'jadwal', 'poli', 'libur', 'tutup', 'jam pelayanan', 'jam layanan']);
        })->take(2);

        if ($items->isEmpty()) {
            return null;
        }

        return 'Catatan pengumuman aktif: '.$items->map(fn ($item) => trim((string) $item->title))->implode('; ').'.';
    }

    private function announcementSuffix(): string
    {
        $note = $this->relevantAnnouncementNote();
        return $note ? "\n\n{$note}" : '';
    }

    private function scopeLabel(Collection $doctors): string
    {
        if ($doctors->isEmpty()) {
            return 'dokter';
        }

        $specs = $doctors->pluck('specialization')->filter()->unique()->values();
        if ($specs->count() === 1) {
            return Str::lower((string) $specs->first());
        }

        return 'dokter';
    }

    private function dateLabel(CarbonImmutable $date): string
    {
        $month = self::MONTHS[(int) $date->format('n')] ?? $date->format('m');
        return ((int) $date->format('j')).' '.$month.' '.$date->format('Y');
    }

    private function timeText(string $time): string
    {
        $time = trim($time);
        $time = str_replace(':', '.', $time);
        if (preg_match('/^(\d{1,2})\.(\d{2})/', $time, $match)) {
            return str_pad($match[1], 2, '0', STR_PAD_LEFT).'.'.$match[2];
        }
        return $time;
    }

    private function minutes(string $time): int
    {
        $time = str_replace('.', ':', trim($time));
        if (preg_match('/^(\d{1,2}):(\d{2})/', $time, $match)) {
            return ((int) $match[1]) * 60 + (int) $match[2];
        }
        return 0;
    }

    private function normalize(string $text): string
    {
        $text = Str::lower(Str::ascii($text));
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        return preg_replace('/\s+/', ' ', trim($text)) ?? trim($text);
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
