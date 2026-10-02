<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::orderBy('id')->get();

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateGallery($request);

        $data = [
            'category' => $validated['category'],
            'caption' => $validated['caption'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            $storedPath = $request->file('photo')->store('galleries', 'public');
            $data['photo'] = 'storage/' . $storedPath;
        }

        Gallery::create($data);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $this->validateGallery($request);

        $data = [
            'category' => $validated['category'],
            'caption' => $validated['caption'] ?? null,
        ];

        $oldPhoto = null;

        if ($request->hasFile('photo')) {
            $oldPhoto = $gallery->photo;
            $storedPath = $request->file('photo')->store('galleries', 'public');
            $data['photo'] = 'storage/' . $storedPath;
        }

        $gallery->update($data);

        if ($oldPhoto) {
            $this->deleteLocalPhoto($oldPhoto);
        }

        return redirect()
            ->route('admin.galleries.edit', $gallery)
            ->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->deleteLocalPhoto($gallery->photo);
        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }

    private function validateGallery(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ], [
            'category.required' => 'Kategori galeri wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
            'caption.max' => 'Caption maksimal 1000 karakter.',
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
