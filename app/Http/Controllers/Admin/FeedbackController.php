<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FeedbackReplyMail;
use App\Models\EmailSetting;
use App\Models\Feedback;
use App\Services\ClinicMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class FeedbackController extends Controller
{
    public function index(Request $request): View
    {
        $query = Feedback::query()->latest();

        if ($request->filled('status') && in_array($request->status, ['new', 'read', 'replied'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && in_array($request->type, ['kritik', 'saran', 'apresiasi', 'lainnya'], true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery
                    ->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('subject', 'like', "%{$keyword}%")
                    ->orWhere('message', 'like', "%{$keyword}%");
            });
        }

        $feedbacks = $query->paginate(15)->withQueryString();
        $emailSetting = EmailSetting::current();

        return view('admin.feedback.index', compact('feedbacks', 'emailSetting'));
    }

    public function show(Feedback $feedback): View
    {
        if ($feedback->status === 'new') {
            $feedback->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        $feedback->load(['replies.user']);
        $emailSetting = EmailSetting::current();

        return view('admin.feedback.show', compact('feedback', 'emailSetting'));
    }

    public function reply(Request $request, Feedback $feedback, ClinicMailService $mailService): RedirectResponse
    {
        $validated = $request->validate([
            'reply_message' => ['required', 'string', 'min:3', 'max:5000'],
        ], [
            'reply_message.required' => 'Balasan wajib diisi.',
            'reply_message.min' => 'Balasan terlalu pendek.',
            'reply_message.max' => 'Balasan maksimal 5.000 karakter.',
        ]);

        $setting = EmailSetting::current();

        if (! $setting || ! $setting->isConnected()) {
            return back()
                ->withInput()
                ->with('error', 'Email klinik belum tersambung. Sambungkan dan uji email terlebih dahulu.');
        }

        try {
            $mailService->send(
                $setting,
                $feedback->email,
                new FeedbackReplyMail($feedback, $validated['reply_message'], $setting),
            );

            $feedback->replies()->create([
                'user_id' => auth()->id(),
                'message' => $validated['reply_message'],
                'sent_to' => $feedback->email,
                'sender_email' => $setting->email,
                'sender_name' => $setting->sender_name,
                'sent_at' => now(),
            ]);

            $feedback->update([
                'status' => 'replied',
                'read_at' => $feedback->read_at ?: now(),
            ]);

            return redirect()
                ->route('admin.feedback.show', $feedback)
                ->with('success', 'Balasan berhasil dikirim ke '.$feedback->email.'.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Balasan belum terkirim. Periksa koneksi internet dan Pengaturan Email, lalu coba lagi.');
        }
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
