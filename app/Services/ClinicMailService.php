<?php

namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ClinicMailService
{
    public function send(EmailSetting $setting, string $recipient, Mailable $mailable): void
    {
        if (! $setting->isConnected()) {
            throw new RuntimeException('Email Google belum tersambung.');
        }

        $accessToken = $this->validAccessToken($setting);
        $envelope = $mailable->envelope();
        $subject = (string) ($envelope->subject ?? 'Pesan dari Klinik Mitra Sehat');
        $html = $mailable->render();

        $raw = $this->buildRawMessage(
            senderName: $setting->sender_name ?: 'Klinik Mitra Sehat',
            senderEmail: $setting->email,
            recipient: $recipient,
            subject: $subject,
            html: $html,
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->timeout(25)
            ->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                'raw' => $raw,
            ]);

        // Access token dapat kedaluwarsa lebih cepat dari perkiraan; refresh sekali lalu coba ulang.
        if ($response->status() === 401) {
            $accessToken = $this->refreshAccessToken($setting);

            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->asJson()
                ->timeout(25)
                ->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                    'raw' => $raw,
                ]);
        }

        if (! $response->successful()) {
            $message = data_get($response->json(), 'error.message') ?: $response->body();
            throw new RuntimeException('Gmail API gagal mengirim email: '.mb_substr((string) $message, 0, 1000));
        }
    }

    public function refreshAccessToken(EmailSetting $setting): string
    {
        if (blank($setting->google_refresh_token)) {
            throw new RuntimeException('Refresh token Google tidak tersedia. Hubungkan ulang akun Google.');
        }

        $clientId = (string) config('services.google_oauth.client_id');
        $clientSecret = (string) config('services.google_oauth.client_secret');

        if ($clientId === '' || $clientSecret === '') {
            throw new RuntimeException('Google OAuth belum dikonfigurasi di .env.');
        }

        $response = Http::asForm()
            ->acceptJson()
            ->timeout(20)
            ->post('https://oauth2.googleapis.com/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $setting->google_refresh_token,
                'grant_type' => 'refresh_token',
            ]);

        if (! $response->successful()) {
            $message = data_get($response->json(), 'error_description')
                ?: data_get($response->json(), 'error')
                ?: $response->body();

            throw new RuntimeException('Gagal memperbarui token Google: '.mb_substr((string) $message, 0, 1000));
        }

        $data = $response->json();
        $accessToken = (string) ($data['access_token'] ?? '');

        if ($accessToken === '') {
            throw new RuntimeException('Google tidak mengembalikan access token baru.');
        }

        $setting->update([
            'google_access_token' => $accessToken,
            'google_token_expires_at' => now()->addSeconds(max(60, (int) ($data['expires_in'] ?? 3600))),
            'last_error' => null,
        ]);

        return $accessToken;
    }

    private function validAccessToken(EmailSetting $setting): string
    {
        $expiresAt = $setting->google_token_expires_at;

        if (
            filled($setting->google_access_token)
            && $expiresAt
            && $expiresAt->greaterThan(now()->addMinute())
        ) {
            return $setting->google_access_token;
        }

        return $this->refreshAccessToken($setting);
    }

    private function buildRawMessage(
        string $senderName,
        string $senderEmail,
        string $recipient,
        string $subject,
        string $html,
    ): string {
        $senderName = preg_replace('/[\r\n]+/', ' ', $senderName) ?: 'Klinik Mitra Sehat';
        $subject = preg_replace('/[\r\n]+/', ' ', $subject) ?: 'Pesan dari Klinik Mitra Sehat';

        if (! filter_var($senderEmail, FILTER_VALIDATE_EMAIL) || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Alamat email pengirim atau penerima tidak valid.');
        }

        $encodedName = mb_encode_mimeheader($senderName, 'UTF-8', 'B', "\r\n");
        $encodedSubject = mb_encode_mimeheader($subject, 'UTF-8', 'B', "\r\n");
        $encodedBody = chunk_split(base64_encode($html), 76, "\r\n");

        $message = implode("\r\n", [
            'From: '.$encodedName.' <'.$senderEmail.'>',
            'To: '.$recipient,
            'Reply-To: '.$senderEmail,
            'Subject: '.$encodedSubject,
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: base64',
            '',
            $encodedBody,
        ]);

        return rtrim(strtr(base64_encode($message), '+/', '-_'), '=');
    }
}
