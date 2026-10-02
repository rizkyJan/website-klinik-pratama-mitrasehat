<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('id')->get();

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateArticle($request);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['content'],
        ];

        if ($request->hasFile('photo')) {
            $storedPath = $request->file('photo')->store('articles', 'public');
            $data['photo'] = 'storage/' . $storedPath;
        }

        Article::create($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $this->validateArticle($request);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['content'],
        ];

        $oldPhoto = null;

        if ($request->hasFile('photo')) {
            $oldPhoto = $article->photo;
            $storedPath = $request->file('photo')->store('articles', 'public');
            $data['photo'] = 'storage/' . $storedPath;
        }

        $article->update($data);

        if ($oldPhoto) {
            $this->deleteLocalPhoto($oldPhoto);
        }

        return redirect()
            ->route('admin.articles.edit', $article)
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $this->deleteLocalPhoto($article->photo);
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }

    private function validateArticle(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'content' => ['required', 'string'],
        ], [
            'title.required' => 'Judul artikel wajib diisi.',
            'category.required' => 'Kategori artikel wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
            'content.required' => 'Konten artikel wajib diisi.',
        ]);
    }

    private function deleteLocalPhoto(?string $photo): void
    {
        if (! $photo || ! str_starts_with($photo, 'storage/')) {
            return;
        }

        $storagePath = substr($photo, strlen('storage/'));

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }
}
