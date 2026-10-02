<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        return view('feedback.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'type' => ['required', 'in:kritik,saran,apresiasi,lainnya'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'type.required' => 'Silakan pilih jenis masukan.',
            'type.in' => 'Jenis masukan tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min' => 'Pesan minimal 10 karakter.',
            'message.max' => 'Pesan maksimal 5.000 karakter.',
        ]);

        Feedback::create([
            ...$validated,
            'status' => 'new',
        ]);

        return redirect()
            ->route('feedback.index')
            ->with('feedback_success', 'Terima kasih. Kritik, saran, atau apresiasi Anda sudah kami terima dan akan menjadi bahan evaluasi Klinik Mitra Sehat.');
    }
}
