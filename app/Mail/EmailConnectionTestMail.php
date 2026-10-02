<?php

namespace App\Mail;

use App\Models\EmailSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailConnectionTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EmailSetting $setting)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->setting->email, $this->setting->sender_name),
            subject: 'Tes Koneksi Email - Klinik Mitra Sehat',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.connection-test',
        );
    }
}
