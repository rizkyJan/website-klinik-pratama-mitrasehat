<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\AiKnowledge;
use Illuminate\Http\Request;

class KnowledgeController extends Controller
{
    public function index(Request $request)
    {
        $query = AiKnowledge::query();

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('keywords', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status')->toString() === 'active');
        }

        $knowledge = $query->orderBy('sort_order')->orderBy('title')->paginate(15)->withQueryString();
        $categories = AiKnowledge::query()->select('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.ai.knowledge.index', compact('knowledge', 'categories'));
    }

    public function create()
    {
        return view('admin.ai.knowledge.form', ['knowledge' => new AiKnowledge()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['source'] = 'manual';
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        AiKnowledge::create($data);

        return redirect()->route('admin.ai.knowledge.index')->with('success', 'Pengetahuan AI berhasil ditambahkan.');
    }

    public function edit(AiKnowledge $knowledge)
    {
        return view('admin.ai.knowledge.form', compact('knowledge'));
    }

    public function update(Request $request, AiKnowledge $knowledge)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        $knowledge->update($data);

        return redirect()->route('admin.ai.knowledge.index')->with('success', 'Pengetahuan AI berhasil diperbarui.');
    }

    public function destroy(AiKnowledge $knowledge)
    {
        $knowledge->delete();
        return redirect()->route('admin.ai.knowledge.index')->with('success', 'Pengetahuan AI berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'content' => ['required', 'string', 'max:10000'],
            'keywords' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
