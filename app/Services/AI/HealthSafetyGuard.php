<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

class HealthSafetyGuard
{
    private array $redFlags = [
        'muntah darah', 'bab hitam', 'berak hitam', 'pingsan', 'tidak sadar', 'kejang',
        'sesak berat', 'sulit bernapas', 'sulit bernafas', 'nyeri dada berat',
        'perdarahan hebat', 'darah tidak berhenti', 'lemah sebelah', 'wajah mencong',
        'bicara pelo', 'reaksi alergi berat', 'bibir bengkak dan sesak',
        'hamil perdarahan', 'perdarahan saat hamil', 'nyeri sangat hebat', 'sakit sangat hebat',
    ];

    public function detect(string $question): ?string
    {
        $text = Str::lower(Str::ascii($question));

        foreach ($this->redFlags as $flag) {
            if (str_contains($text, $flag)) {
                return $flag;
            }
        }

        return null;
    }

    public function urgentMessage(): string
    {
        return 'Keluhan yang Anda sampaikan dapat termasuk tanda bahaya dan sebaiknya diperiksa segera secara langsung. Mohon segera mencari pertolongan medis terdekat. Jika kondisi terasa berat, memburuk cepat, terjadi sesak berat, penurunan kesadaran, perdarahan hebat, atau keadaan darurat lainnya, jangan menunggu balasan chatbot.';
    }
}
