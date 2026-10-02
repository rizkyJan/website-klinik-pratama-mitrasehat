<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\AiConversation;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $conversations = AiConversation::query()
            ->withCount('messages')
            ->when($request->filled('topic'), fn ($q) => $q->where('last_topic', $request->string('topic')->toString()))
            ->orderByDesc('last_activity_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.ai.history.index', compact('conversations'));
    }

    public function show(AiConversation $conversation)
    {
        $conversation->load('messages');
        return view('admin.ai.history.show', compact('conversation'));
    }

    public function destroy(AiConversation $conversation)
    {
        $conversation->delete();
        return redirect()->route('admin.ai.history.index')->with('success', 'Riwayat percakapan berhasil dihapus.');
    }
}
