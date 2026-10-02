<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmailConnectionTestMail;
use App\Models\EmailSetting;
use App\Services\ClinicMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class EmailSettingController extends Controller
{
    public function edit(): View
    {
        $setting = EmailSetting::current();
        $googleConfigured = $this->googleConfigured();
        $redirectUri = $this->redirectUri();

        return view('admin.email-settings.edit', compact('setting', 'googleConfigured', 'redirectUri'));
    }

    public function connect(Request $request): RedirectResponse
    {
        if (! $this->googleConfigured()) {
            return redirect()
                ->route('admin.email-settings.edit')
                ->with('error', 'Google OAuth belum dikonfigurasi. Isi GOOGLE_OAUTH_CLIENT_ID dan GOOGLE_OAUTH_CLIENT_SECRET di file .env terlebih dahulu.');
        }

        $state = Str::random(64);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('services.google_oauth.client_id'),
            'redirect_uri' => $this->redirectUri(),
            'response_type' => 'code',
            'scope' => implode(' ', [
                'openid',
                'email',
                'profile',
                'https://www.googleapis.com/auth/gmail.send',
            ]),
            'access_type' => 'offline',
            'prompt' => 'consent select_account',
            'include_granted_scopes' => 'true',
            'state' => $state,
        ], '', '&', PHP_QUERY_RFC3986);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function callback(Request $request, ClinicMailService $mailService): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()
                ->route('admin.email-settings.edit')
                ->with('error', 'Penyambungan akun Google dibatalkan atau tidak diizinkan.');
        }

        $expectedState = (string) $request->session()->pull('google_oauth_state', '');
        $receivedState = (string) $request->query('state', '');

        if ($expectedState === '' || ! hash_equals($expectedState, $receivedState)) {
            return redirect()
                ->route('admin.email-settings.edit')
                ->with('error', 'Sesi penyambungan Google tidak valid atau sudah kedaluwarsa. Silakan coba Hubungkan dengan Google lagi.');
        }

        $code = (string) $request->query('code', '');

        if ($code === '') {
            return redirect()
                ->route('admin.email-settings.edit')
                ->with('error', 'Google tidak mengirim kode otorisasi. Silakan coba lagi.');
        }

        try {
            $tokenResponse = Http::asForm()
                ->acceptJson()
                ->timeout(20)
                ->post('https://oauth2.googleapis.com/token', [
                    'code' => $code,
                    'client_id' => config('services.google_oauth.client_id'),
                    'client_secret' => config('services.google_oauth.client_secret'),
                    'redirect_uri' => $this->redirectUri(),
                    'grant_type' => 'authorization_code',
                ]);

            if (! $tokenResponse->successful()) {
                $message = data_get($tokenResponse->json(), 'error_description')
                    ?: data_get($tokenResponse->json(), 'error')
                    ?: $tokenResponse->body();
                throw new RuntimeException('Gagal menukar kode Google: '.mb_substr((string) $message, 0, 1000));
            }

            $tokens = $tokenResponse->json();
            $accessToken = (string) ($tokens['access_token'] ?? '');

            if ($accessToken === '') {
                throw new RuntimeException('Google tidak mengembalikan access token.');
            }

            $profileResponse = Http::withToken($accessToken)
                ->acceptJson()
                ->timeout(20)
                ->get('https://openidconnect.googleapis.com/v1/userinfo');

            if (! $profileResponse->successful()) {
                throw new RuntimeException('Tidak dapat mengambil profil akun Google yang dipilih.');
            }

            $profile = $profileResponse->json();
            $email = (string) ($profile['email'] ?? '');
            $name = trim((string) ($profile['name'] ?? '')) ?: 'Klinik Mitra Sehat';

            if ($email === '') {
                throw new RuntimeException('Alamat email akun Google tidak ditemukan.');
            }

            $setting = EmailSetting::current() ?: new EmailSetting();
            $refreshToken = (string) ($tokens['refresh_token'] ?? '');

            if ($refreshToken === '') {
                $refreshToken = (string) ($setting->google_refresh_token ?? '');
            }

            if ($refreshToken === '') {
                throw new RuntimeException('Google tidak memberikan refresh token. Putuskan akses aplikasi di akun Google lalu hubungkan kembali.');
            }

            // Kolom SMTP lama tetap diisi placeholder agar migration lama tidak perlu diubah.
            $setting->fill([
                'provider' => 'google',
                'sender_name' => $name,
                'email' => $email,
                'google_id' => (string) ($profile['sub'] ?? ''),
                'smtp_host' => 'gmail.googleapis.com',
                'smtp_port' => 443,
                'smtp_scheme' => 'smtps',
                'smtp_username' => $email,
                'smtp_password' => 'google-oauth',
                'google_access_token' => $accessToken,
                'google_refresh_token' => $refreshToken,
                'google_token_expires_at' => now()->addSeconds(max(60, (int) ($tokens['expires_in'] ?? 3600))),
                'google_scopes' => (string) ($tokens['scope'] ?? 'https://www.googleapis.com/auth/gmail.send'),
                'connected_at' => now(),
                'last_tested_at' => now(),
                'last_error' => null,
                'configured_by' => auth()->id(),
            ]);
            $setting->save();

            // Verifikasi nyata: kirim email percobaan ke akun yang baru disambungkan.
            $mailService->send($setting, $setting->email, new EmailConnectionTestMail($setting));

            $setting->update([
                'connected_at' => now(),
                'last_tested_at' => now(),
                'last_error' => null,
            ]);

            return redirect()
                ->route('admin.email-settings.edit')
                ->with('success', 'Akun Google '.$setting->email.' berhasil tersambung. Email percobaan juga sudah dikirim.');
        } catch (Throwable $e) {
            report($e);

            $setting = EmailSetting::current();
            if ($setting) {
                $setting->update([
                    'connected_at' => null,
                    'last_tested_at' => now(),
                    'last_error' => mb_substr($e->getMessage(), 0, 1500),
                ]);
            }

            return redirect()
                ->route('admin.email-settings.edit')
                ->with('error', 'Akun Google belum berhasil tersambung. '.$e->getMessage());
        }
    }

    public function test(ClinicMailService $mailService): RedirectResponse
    {
        $setting = EmailSetting::current();

        if (! $setting || ! $setting->isConnected()) {
            return redirect()
                ->route('admin.email-settings.edit')
                ->with('error', 'Belum ada akun Google yang tersambung.');
        }

        try {
            $mailService->send($setting, $setting->email, new EmailConnectionTestMail($setting));

            $setting->update([
                'last_tested_at' => now(),
                'last_error' => null,
                'connected_at' => $setting->connected_at ?: now(),
            ]);

            return redirect()
                ->route('admin.email-settings.edit')
                ->with('success', 'Email percobaan berhasil dikirim ke '.$setting->email.'.');
        } catch (Throwable $e) {
            report($e);

            $setting->update([
                'last_tested_at' => now(),
                'last_error' => mb_substr($e->getMessage(), 0, 1500),
            ]);

            return redirect()
                ->route('admin.email-settings.edit')
                ->with('error', 'Email percobaan gagal dikirim. Coba Hubungkan Ulang dengan Google.');
        }
    }

    public function disconnect(): RedirectResponse
    {
        $setting = EmailSetting::current();

        if ($setting) {
            $token = $setting->google_refresh_token ?: $setting->google_access_token;

            if (filled($token)) {
                try {
                    Http::asForm()
                        ->timeout(10)
                        ->post('https://oauth2.googleapis.com/revoke', ['token' => $token]);
                } catch (Throwable $e) {
                    report($e);
                }
            }

            $setting->delete();
        }

        return redirect()
            ->route('admin.email-settings.edit')
            ->with('success', 'Sambungan akun Google telah diputus dari website.');
    }

    private function googleConfigured(): bool
    {
        return filled(config('services.google_oauth.client_id'))
            && filled(config('services.google_oauth.client_secret'));
    }

    private function redirectUri(): string
    {
        return (string) (config('services.google_oauth.redirect_uri') ?: route('admin.email-settings.google.callback'));
    }
}
