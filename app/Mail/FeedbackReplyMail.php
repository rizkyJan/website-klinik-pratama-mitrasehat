<?php

namespace App\Mail;

use App\Models\EmailSetting;
use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Feedback $feedback,
        public string $replyMessage,
        public EmailSetting $setting,
    ) {
    }

    public function envelope(): Envelope
    {
        $topic = $this->feedback->subject ?: $this->feedback->type_label;

        return new Envelope(
            from: new Address($this->setting->email, $this->setting->sender_name),
            replyTo: [new Address($this->setting->email, $this->setting->sender_name)],
            subject: 'Tanggapan Klinik Mitra Sehat: '.$topic,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.feedback-reply',
        );
    }
}
