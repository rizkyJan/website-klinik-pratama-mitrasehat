<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('id')->get();

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'maps_url' => ['nullable', 'url', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama cabang wajib diisi.',
            'maps_url.url' => 'Link Google Maps harus berupa URL yang valid.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
            'description.max' => 'Deskripsi singkat maksimal 1000 karakter.',
        ]);

        if ($request->hasFile('photo')) {
            $storedPath = $request->file('photo')->store('branches', 'public');
            $validated['photo'] = 'storage/' . $storedPath;
        }

        Branch::create($validated);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'maps_url' => ['nullable', 'url', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama cabang wajib diisi.',
            'maps_url.url' => 'Link Google Maps harus berupa URL yang valid.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
            'description.max' => 'Deskripsi singkat maksimal 1000 karakter.',
        ]);

        $oldPhoto = null;

        if ($request->hasFile('photo')) {
            $oldPhoto = $branch->photo;
            $storedPath = $request->file('photo')->store('branches', 'public');
            $validated['photo'] = 'storage/' . $storedPath;
        }

        $branch->update($validated);

        if ($oldPhoto) {
            $this->deleteUploadedPhoto($oldPhoto);
        }

        return redirect()
            ->route('admin.branches.edit', $branch)
            ->with('success', 'Data cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        $this->deleteUploadedPhoto($branch->photo);
        $branch->delete();

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }

    private function deleteUploadedPhoto(?string $photo): void
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
