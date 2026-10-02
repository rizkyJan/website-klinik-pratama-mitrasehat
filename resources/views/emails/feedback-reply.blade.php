<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f5f7f5;font-family:Arial,sans-serif;color:#33443a;">
    <div style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #dfe8e1;border-radius:16px;overflow:hidden;">
        <div style="padding:22px 28px;background:#1a5d3a;color:#ffffff;">
            <div style="font-size:12px;opacity:.85;">Klinik Pratama Mitra Sehat</div>
            <h2 style="margin:6px 0 0;font-size:20px;">Tanggapan atas {{ $feedback->type_label }}</h2>
        </div>
        <div style="padding:28px;">
            <p style="margin:0 0 16px;line-height:1.7;">Halo <strong>{{ $feedback->name }}</strong>,</p>
            <p style="margin:0 0 20px;line-height:1.7;">
                Terima kasih telah menyampaikan {{ strtolower($feedback->type_label) }} kepada Klinik Mitra Sehat. Berikut tanggapan dari kami:
            </p>

            <div style="padding:16px 18px;border-left:4px solid #1a5d3a;background:#f3f8f4;border-radius:8px;white-space:pre-line;line-height:1.75;">{{ $replyMessage }}</div>

            <div style="margin-top:24px;padding-top:20px;border-top:1px solid #e5ebe6;">
                <div style="font-size:12px;font-weight:bold;color:#6b7b71;margin-bottom:8px;">PESAN ANDA</div>
                @if($feedback->subject)
                    <div style="font-weight:bold;margin-bottom:8px;">{{ $feedback->subject }}</div>
                @endif
                <div style="white-space:pre-line;line-height:1.7;color:#58675e;">{{ $feedback->message }}</div>
            </div>

            <p style="margin:24px 0 0;line-height:1.7;">
                Salam,<br>
                <strong>{{ $setting->sender_name }}</strong>
            </p>
        </div>
    </div>
</body>
</html>
