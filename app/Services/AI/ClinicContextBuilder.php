<?php

namespace App\Services\AI;

use App\Models\AiKnowledge;
use App\Models\Announcement;
use App\Models\ClinicSetting;
use App\Models\Doctor;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Support\Collection;

class ClinicContextBuilder
{
    public function __construct(private readonly ClinicQueryAnalyzer $analyzer)
    {
    }

    /**
     * @return array{
     *   context:string,
     *   relevant:bool,
     *   sources:array<int,string>,
     *   confidence:string,
     *   primary_answer:?string,
     *   primary_type:?string,
     *   matched_topic:?string
     * }
     */
    public function build(string $question): array
    {
        $parts = [];
        $sources = [];
        $relevant = false;
        $confidence = 'none';
        $primaryAnswer = null;
        $primaryType = null;
        $matchedTopic = null;

        // Pengetahuan admin adalah sumber utama. Retrieval hanya memakai judul + keywords,
        // bukan isi jawaban, agar kata generik di konten tidak menimbulkan false-positive.
        $knowledge = $this->rankCollection(
            AiKnowledge::query()->where('is_active', true)->orderBy('sort_order')->get(),
            $question,
            fn (AiKnowledge $item) => $item->title.' '.$item->keywords
        )->take(5);

        if ($knowledge->isNotEmpty()) {
            $relevant = true;
            $confidence = 'high';
            $top = $knowledge->first();
            $primaryAnswer = trim((string) $top->content);
            $primaryType = 'knowledge';
            $matchedTopic = (string) $top->title;

            $parts[] = "PENGETAHUAN AI KLINIK (SUMBER UTAMA; jangan menambah fakta di luar teks ini):\n".$knowledge
                ->map(fn (AiKnowledge $item) => "- [{$item->category}] {$item->title}: {$item->content}")
                ->implode("\n");
            $sources[] = 'Pengetahuan AI';
        }

        // FAQ dicari dari pertanyaannya, bukan jawabannya. Ini mencegah jawaban umum ikut cocok.
        $faq = $this->rankCollection(
            Faq::query()->where('is_active', true)->orderBy('sort_order')->get(),
            $question,
            fn (Faq $item) => $item->question
        )->take(3);

        if ($faq->isNotEmpty()) {
            $relevant = true;
            $confidence = $confidence === 'high' ? 'high' : 'medium';
            $parts[] = "FAQ WEBSITE (fakta resmi):\n".$faq
                ->map(fn (Faq $item) => "- {$item->question} Jawaban: {$item->answer}")
                ->implode("\n");
            $sources[] = 'FAQ';

            if ($primaryAnswer === null) {
                $top = $faq->first();
                $primaryAnswer = trim((string) $top->answer);
                $primaryType = 'faq';
                $matchedTopic = (string) $top->question;
            }
        }

        // Untuk permintaan daftar layanan secara umum, tampilkan daftar aktif tanpa perlu entity token.
        if ($this->analyzer->isBroadServiceListIntent($question)) {
            $services = Service::query()->where('is_active', true)->orderBy('sort_order')->take(12)->get();
            if ($services->isNotEmpty()) {
                $relevant = true;
                $confidence = 'high';
                $serviceText = $services
                    ->map(fn (Service $item) => "• {$item->name}".(filled($item->description) ? ": {$item->description}" : ''))
                    ->implode("
");
                $parts[] = "DAFTAR LAYANAN AKTIF WEBSITE:
".$serviceText;
                $sources[] = 'Layanan';

                $primaryAnswer = "Layanan yang tercatat aktif di website Klinik Mitra Sehat:

".$serviceText;
                $primaryType = 'service_list';
                $matchedTopic = 'Daftar Layanan Klinik';
            }
        } else {
            // Untuk pertanyaan ketersediaan layanan tertentu, sebuah layanan hanya boleh dianggap
            // cocok bila nama/deskripsinya mempunyai token bermakna yang sama dengan pertanyaan.
            $services = $this->rankCollection(
                Service::query()->where('is_active', true)->orderBy('sort_order')->get(),
                $question,
                fn (Service $item) => $item->name.' '.$item->description.' '.$item->detail
            )->take(4);

            if ($services->isNotEmpty()) {
                $relevant = true;
                $confidence = $confidence === 'high' ? 'high' : 'medium';
                $parts[] = "LAYANAN WEBSITE (fakta resmi; jangan menganggap layanan lain tersedia):\n".$services
                    ->map(fn (Service $item) => "- {$item->name}: {$item->description}")
                    ->implode("\n");
                $sources[] = 'Layanan';

                if ($primaryAnswer === null) {
                    $top = $services->first();
                    $description = trim((string) $top->description);
                    $primaryAnswer = 'Ya, '.trim((string) $top->name).' tercatat sebagai layanan aktif di Klinik Mitra Sehat.'
                        .($description !== '' ? ' '.$description : '');
                    $primaryType = 'service';
                    $matchedTopic = (string) $top->name;
                }
            }
        }

        // Jadwal dokter adalah data dinamis. Hanya aktif bila pertanyaannya memang meminta dokter/jadwal/poli.
        if ($this->matchesAny($question, ['dokter', 'jadwal dokter', 'praktik dokter', 'praktek dokter', 'dokter praktik', 'dokter praktek'])) {
            $doctors = Doctor::query()
                ->where('is_active', true)
                ->with('schedules')
                ->orderBy('sort_order')
                ->get();

            if ($doctors->isNotEmpty()) {
                $relevant = true;
                $confidence = $confidence === 'none' ? 'medium' : $confidence;
                $doctorText = $doctors->map(function (Doctor $doctor) {
                    $schedules = $doctor->schedules->map(function ($schedule) {
                        if ($schedule->is_off) {
                            return "{$schedule->day}: LIBUR";
                        }

                        $start = $schedule->start_time ? substr((string) $schedule->start_time, 0, 5) : '-';
                        $end = $schedule->end_time ? substr((string) $schedule->end_time, 0, 5) : '-';
                        return "{$schedule->day}: {$start}-{$end}";
                    })->implode('; ');

                    return "- {$doctor->name} ({$doctor->specialization})".($schedules ? ": {$schedules}" : '');
                })->implode("\n");

                $parts[] = "JADWAL DOKTER DARI DATABASE WEBSITE:\n{$doctorText}";
                $sources[] = 'Jadwal Dokter';
            }
        }

        // Pengumuman aktif hanya ditambahkan untuk pertanyaan jadwal/libur/pengumuman.
        if ($this->matchesAny($question, ['pengumuman', 'libur', 'tutup', 'perubahan jadwal', 'perubahan jam', 'jam pelayanan'])) {
            $announcements = Announcement::visible()->take(3)->get();

            if ($announcements->isNotEmpty()) {
                $relevant = true;
                $confidence = $confidence === 'none' ? 'medium' : $confidence;
                $parts[] = "PENGUMUMAN AKTIF (paling baru dan mengalahkan jadwal rutin bila bertentangan):\n".$announcements
                    ->map(fn (Announcement $item) => "- {$item->title}: {$item->content}")
                    ->implode("\n");
                $sources[] = 'Pengumuman';
            }
        }

        if ($this->matchesAny($question, ['alamat', 'lokasi', 'kontak', 'nomor', 'whatsapp', 'wa', 'telepon', 'instagram', 'tiktok', 'maps'])) {
            $settings = [
                'Nama' => ClinicSetting::get('clinic_name', 'Klinik Pratama Mitra Sehat'),
                'Alamat' => ClinicSetting::get('clinic_address'),
                'WhatsApp' => ClinicSetting::get('clinic_whatsapp'),
                'Telepon' => ClinicSetting::get('clinic_phone'),
                'Instagram' => ClinicSetting::get('clinic_instagram'),
                'TikTok' => ClinicSetting::get('clinic_tiktok'),
            ];

            $settings = array_filter($settings, fn ($value) => filled($value));
            if ($settings) {
                $relevant = true;
                $confidence = $confidence === 'none' ? 'medium' : $confidence;
                $parts[] = "PROFIL/KONTAK KLINIK:\n".collect($settings)
                    ->map(fn ($value, $key) => "- {$key}: {$value}")
                    ->implode("\n");
                $sources[] = 'Profil Klinik';
            }
        }

        return [
            'context' => implode("\n\n", $parts),
            'relevant' => $relevant,
            'sources' => array_values(array_unique($sources)),
            'confidence' => $confidence,
            'primary_answer' => $primaryAnswer,
            'primary_type' => $primaryType,
            'matched_topic' => $matchedTopic,
        ];
    }

    private function rankCollection(Collection $items, string $question, callable $haystackResolver): Collection
    {
        if ($this->analyzer->meaningfulTokens($question) === []) {
            return collect();
        }

        return $items
            ->map(function ($item) use ($question, $haystackResolver) {
                $source = (string) $haystackResolver($item);
                $evidence = $this->analyzer->matchEvidence($question, $source);

                return [
                    'item' => $item,
                    'score' => $evidence['score'],
                    'strong' => $this->analyzer->isStrongMatch($question, $source),
                ];
            })
            // Sangat konservatif: satu kata umum tidak cukup untuk menjawab fakta klinik.
            ->filter(fn (array $row) => $row['strong'] === true)
            ->sortByDesc('score')
            ->pluck('item')
            ->values();
    }

    private function matchesAny(string $question, array $terms): bool
    {
        $text = $this->analyzer->normalize($question);
        foreach ($terms as $term) {
            if (str_contains($text, $this->analyzer->normalize($term))) {
                return true;
            }
        }

        return false;
    }
}
