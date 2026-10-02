<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('schedules')
            ->orderBy('sort_order')
            ->orderBy('specialization')
            ->orderBy('name')
            ->get();

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
            'sort_order.integer' => 'Urutan tampil harus berupa angka.',
            'sort_order.min' => 'Urutan tampil tidak boleh kurang dari 0.',
        ]);

        $data = [
            'name' => $validated['name'],
            'specialization' => $validated['specialization'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('photo')) {
            $storedPath = $request->file('photo')->store('doctors', 'public');
            $data['photo'] = 'storage/' . $storedPath;
        }

        Doctor::create($data);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load('schedules');

        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 10 MB.',
            'sort_order.integer' => 'Urutan tampil harus berupa angka.',
            'sort_order.min' => 'Urutan tampil tidak boleh kurang dari 0.',
        ]);

        $data = [
            'name' => $validated['name'],
            'specialization' => $validated['specialization'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ];

        $oldPhoto = null;

        if ($request->hasFile('photo')) {
            $oldPhoto = $doctor->photo;
            $storedPath = $request->file('photo')->store('doctors', 'public');
            $data['photo'] = 'storage/' . $storedPath;
        }

        $doctor->update($data);

        if ($oldPhoto) {
            $this->deleteUploadedPhoto($oldPhoto);
        }

        return redirect()
            ->route('admin.doctors.edit', $doctor)
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor)
    {
        $this->deleteUploadedPhoto($doctor->photo);
        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Dokter berhasil dihapus.');
    }

    public function storeSchedule(Request $request, Doctor $doctor)
    {
        $isOff = $request->boolean('is_off');

        $validated = $request->validate([
            'day' => [
                'required',
                Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']),
            ],
            'start_time' => [
                Rule::requiredIf(! $isOff),
                'nullable',
                'date_format:H:i',
            ],
            'end_time' => [
                Rule::requiredIf(! $isOff),
                'nullable',
                'date_format:H:i',
                'after:start_time',
            ],
            'is_off' => ['nullable', 'boolean'],
        ], [
            'start_time.required' => 'Jam mulai wajib diisi jika dokter tidak libur.',
            'end_time.required' => 'Jam selesai wajib diisi jika dokter tidak libur.',
            'end_time.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
        ]);

        $scheduleData = [
            'day' => $validated['day'],
            'start_time' => $isOff ? null : $validated['start_time'],
            'end_time' => $isOff ? null : $validated['end_time'],
            'is_off' => $isOff,
        ];

        $doctor->schedules()->updateOrCreate(
            ['day' => $scheduleData['day']],
            $scheduleData
        );

        return back()->with('success', 'Jadwal dokter berhasil disimpan.');
    }

    public function destroySchedule(Doctor $doctor, DoctorSchedule $schedule)
    {
        abort_unless($schedule->doctor_id === $doctor->id, 404);

        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
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
