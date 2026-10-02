<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    private const ICONS = [
        'medical-cross',
        'stethoscope',
        'tooth',
        'mother-child',
        'laboratory',
        'physiotherapy',
        'acupuncture',
        'pharmacy',
        'health-check',
        'vitamin',
        'heart',
        'clinic',
    ];

    public function index()
    {
        $services = Service::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateService($request);
        $slug = Str::slug($validated['name']);

        if (Service::where('slug', $slug)->exists()) {
            throw ValidationException::withMessages([
                'name' => 'Nama layanan ini menghasilkan alamat/slug yang sudah digunakan. Gunakan nama yang berbeda.',
            ]);
        }

        Service::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'detail' => $validated['detail'] ?? null,
            'icon' => $validated['icon'] ?? 'medical-cross',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $this->validateService($request);
        $slug = Str::slug($validated['name']);

        if (
            Service::where('slug', $slug)
                ->where('id', '!=', $service->id)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'name' => 'Nama layanan ini menghasilkan alamat/slug yang sudah digunakan. Gunakan nama yang berbeda.',
            ]);
        }

        $service->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'detail' => $validated['detail'] ?? null,
            'icon' => $validated['icon'] ?? 'medical-cross',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    private function validateService(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'detail' => ['nullable', 'string', 'max:5000'],
            'icon' => ['nullable', 'string', Rule::in(self::ICONS)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama layanan wajib diisi.',
            'description.max' => 'Deskripsi singkat maksimal 500 karakter.',
            'detail.max' => 'Detail lengkap maksimal 5000 karakter.',
            'icon.in' => 'Ikon yang dipilih tidak tersedia.',
            'sort_order.integer' => 'Urutan tampil harus berupa angka.',
            'sort_order.min' => 'Urutan tampil tidak boleh kurang dari 0.',
            'sort_order.max' => 'Urutan tampil terlalu besar.',
        ]);
    }
}
