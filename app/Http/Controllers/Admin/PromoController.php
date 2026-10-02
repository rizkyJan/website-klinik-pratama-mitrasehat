<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::orderBy('id')->get();

        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePromo($request);

        $data = [
            'title' => $validated['title'],
            'period' => $validated['period'],
            'price' => $validated['price'],
        ];

        if ($request->hasFile('poster')) {
            $storedPath = $request->file('poster')->store('promos', 'public');
            $data['poster'] = 'storage/' . $storedPath;
        }

        Promo::create($data);

        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $this->validatePromo($request);

        $data = [
            'title' => $validated['title'],
            'period' => $validated['period'],
            'price' => $validated['price'],
        ];

        $oldPoster = null;

        if ($request->hasFile('poster')) {
            $oldPoster = $promo->poster;
            $storedPath = $request->file('poster')->store('promos', 'public');
            $data['poster'] = 'storage/' . $storedPath;
        }

        $promo->update($data);

        if ($oldPoster) {
            $this->deleteLocalPoster($oldPoster);
        }

        return redirect()
            ->route('admin.promos.edit', $promo)
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $this->deleteLocalPoster($promo->poster);
        $promo->delete();

        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    private function validatePromo(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:150'],
            'price' => ['required', 'string', 'max:100'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'title.required' => 'Judul promo wajib diisi.',
            'period.required' => 'Periode promo wajib diisi.',
            'price.required' => 'Harga promo wajib diisi.',
            'poster.image' => 'File poster harus berupa gambar.',
            'poster.mimes' => 'Format poster harus JPG, JPEG, PNG, atau WebP.',
            'poster.max' => 'Ukuran poster maksimal 10 MB.',
        ]);
    }

    private function deleteLocalPoster(?string $poster): void
    {
        if (! $poster || ! str_starts_with($poster, 'storage/')) {
            return;
        }

        $storagePath = substr($poster, strlen('storage/'));

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }
}
