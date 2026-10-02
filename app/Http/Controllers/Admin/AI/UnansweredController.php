<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\AiKnowledge;
use App\Models\AiUnansweredQuestion;
use App\Services\AI\ClinicQueryAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnansweredController extends Controller
{
    public function __construct(private readonly ClinicQueryAnalyzer $queryAnalyzer)
    {
    }

    public function index(Request $request)
    {
        $status = $request->string('status', 'pending')->toString();
        $questions = AiUnansweredQuestion::query()
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->orderByDesc('occurrences')
            ->orderByDesc('last_asked_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.ai.unanswered.index', compact('questions', 'status'));
    }

    public function show(AiUnansweredQuestion $unanswered)
    {
        return view('admin.ai.unanswered.show', compact('unanswered'));
    }

    public function teach(Request $request, AiUnansweredQuestion $unanswered)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'content' => ['required', 'string', 'max:10000'],
            'keywords' => ['nullable', 'string', 'max:2000'],
        ]);

        // Simpan juga variasi pertanyaan yang sudah pernah muncul agar kalimat berbeda
        // tetap mengarah ke pengetahuan yang sama.
        $autoKeywords = collect([
            $data['keywords'] ?? null,
            $unanswered->question,
            $unanswered->last_question,
            $this->queryAnalyzer->canonicalKey((string) $unanswered->question),
            $unanswered->last_question ? $this->queryAnalyzer->canonicalKey((string) $unanswered->last_question) : null,
        ])->filter()->unique()->implode("\n");

        $data['keywords'] = $autoKeywords;

        DB::transaction(function () use ($data, $unanswered) {
            $knowledge = AiKnowledge::create([
                ...$data,
                'source' => 'unanswered_question',
                'is_active' => true,
                'sort_order' => 0,
            ]);

            $unanswered->update([
                'status' => 'answered',
                'knowledge_id' => $knowledge->id,
            ]);
        });

        return redirect()->route('admin.ai.unanswered.index')->with('success', 'Jawaban disimpan ke Pengetahuan AI. Pertanyaan serupa sekarang dapat menggunakan informasi ini.');
    }

    public function ignore(AiUnansweredQuestion $unanswered)
    {
        $unanswered->update(['status' => 'ignored']);
        return redirect()->route('admin.ai.unanswered.index')->with('success', 'Pertanyaan ditandai sebagai diabaikan.');
    }

    public function reopen(AiUnansweredQuestion $unanswered)
    {
        $unanswered->update(['status' => 'pending']);
        return redirect()->route('admin.ai.unanswered.index')->with('success', 'Pertanyaan dikembalikan ke daftar belum terjawab.');
    }
}
