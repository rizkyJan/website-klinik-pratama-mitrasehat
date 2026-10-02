<?php

namespace App\Services\AI;

use App\Models\AiUnansweredQuestion;

class UnansweredQuestionService
{
    public function __construct(private readonly ClinicQueryAnalyzer $analyzer)
    {
    }

    public function record(string $question): AiUnansweredQuestion
    {
        $canonicalKey = $this->analyzer->canonicalKey($question);
        $now = now();

        $candidates = AiUnansweredQuestion::query()
            ->where('status', 'pending')
            ->latest('last_asked_at')
            ->limit(120)
            ->get();

        $match = $candidates->first(function (AiUnansweredQuestion $item) use ($question, $canonicalKey) {
            if ($item->normalized_question === $canonicalKey) {
                return true;
            }

            if ($this->analyzer->sameTopic($question, (string) $item->question)) {
                return true;
            }

            if ($item->last_question && $this->analyzer->sameTopic($question, (string) $item->last_question)) {
                return true;
            }

            return false;
        });

        if ($match) {
            $match->update([
                'last_question' => $question,
                // Naikkan canonical key agar record lama ikut mendapat normalisasi baru.
                'normalized_question' => $canonicalKey,
                'occurrences' => $match->occurrences + 1,
                'last_asked_at' => $now,
            ]);

            return $match->fresh();
        }

        return AiUnansweredQuestion::create([
            'question' => $question,
            'last_question' => $question,
            'normalized_question' => $canonicalKey,
            'occurrences' => 1,
            'status' => 'pending',
            'first_asked_at' => $now,
            'last_asked_at' => $now,
        ]);
    }

    public function normalize(string $question): string
    {
        return $this->analyzer->canonicalKey($question);
    }
}
