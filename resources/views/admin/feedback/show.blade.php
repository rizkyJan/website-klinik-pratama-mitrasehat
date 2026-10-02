@extends('admin.layout')
@section('title', 'Detail Masukan')

@section('content')
<style>
    .s-back{display:inline-flex;margin-bottom:14px;color:#17613d;text-decoration:none;font-size:12px;font-weight:800}.s-grid{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(320px,.9fr);gap:18px;align-items:start}.s-card{border:1px solid #e5e7eb;border-radius:16px;background:#fff;box-shadow:0 4px 16px rgba(30,60,42,.04);overflow:hidden}.s-card-head{padding:17px 19px;border-bottom:1px solid #edf0ee;background:#fafcfb}.s-card-head h2{margin:0;color:#263f31;font-size:16px;font-weight:800}.s-body{padding:19px}.s-meta{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-bottom:18px}.s-meta-item{padding:11px 12px;border-radius:11px;background:#f7f9f7}.s-meta-label{display:block;color:#8a968e;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.06em}.s-meta-value{display:block;margin-top:4px;color:#34483b;font-size:12px;font-weight:700;word-break:break-word}.s-message{padding:16px;border-radius:13px;border:1px solid #e0e7e2;background:#fcfdfc;color:#405247;line-height:1.75;white-space:pre-line;font-size:13px}.s-subject{margin:0 0 8px;color:#244331;font-size:15px;font-weight:800}.s-replies{display:grid;gap:11px;margin-top:16px}.s-reply{padding:14px;border-radius:13px;border:1px solid #dfe9e1;background:#f5faf6}.s-reply-meta{display:flex;justify-content:space-between;gap:10px;margin-bottom:7px;color:#718077;font-size:10px}.s-reply-text{white-space:pre-line;color:#35483c;line-height:1.65;font-size:12px}.s-status{display:inline-flex;padding:5px 8px;border-radius:999px;background:#edf8f0;color:#17713e;font-size:10px;font-weight:800}.s-alert{padding:13px 14px;border-radius:12px;margin-bottom:14px;font-size:12px;line-height:1.55}.s-alert.bad{background:#fff8e8;border:1px solid #ecd8a9;color:#7a5311}.s-alert.ok{background:#eff9f1;border:1px solid #c7e4ce;color:#17613d}.s-alert a{color:inherit;font-weight:800}.s-label{display:block;margin-bottom:7px;color:#405247;font-size:11px;font-weight:800}.s-textarea{width:100%;min-height:175px;padding:12px 13px;border:1px solid #d5ded8;border-radius:12px;resize:vertical;outline:none;font:inherit;font-size:12px;line-height:1.6}.s-textarea:focus{border-color:#1a5d3a;box-shadow:0 0 0 3px rgba(26,93,58,.08)}.s-btn{width:100%;min-height:44px;margin-top:10px;border:0;border-radius:11px;background:#1a5d3a;color:#fff;font-size:12px;font-weight:800;cursor:pointer}.s-btn:disabled{cursor:not-allowed;background:#9aa8a0}.s-delete{width:100%;min-height:42px;margin-top:10px;border:1px solid #efc7c7;border-radius:11px;background:#fff5f5;color:#b42318;font-size:11px;font-weight:800;cursor:pointer}.s-errors{margin-bottom:12px;color:#b42318;font-size:11px}.s-recipient{margin-bottom:12px;color:#6f7d74;font-size:11px}.s-recipient strong{color:#34483b}@media(max-width:900px){.s-grid{grid-template-columns:1fr}}@media(max-width:560px){.s-meta{grid-template-columns:1fr}}
</style>

<a class="s-back" href="{{ route('admin.feedback.index') }}">← Kembali ke daftar</a>

<div class="s-grid">
    <section class="s-card">
        <div class="s-card-head"><h2>Detail {{ $feedback->type_label }}</h2></div>
        <div class="s-body">
            <div class="s-meta">
                <div class="s-meta-item"><span class="s-meta-label">Nama</span><span class="s-meta-value">{{ $feedback->name }}</span></div>
                <div class="s-meta-item"><span class="s-meta-label">Email</span><span class="s-meta-value">{{ $feedback->email }}</span></div>
                <div class="s-meta-item"><span class="s-meta-label">Jenis</span><span class="s-meta-value">{{ $feedback->type_label }}</span></div>
                <div class="s-meta-item"><span class="s-meta-label">Dikirim</span><span class="s-meta-value">{{ $feedback->created_at->format('d/m/Y H:i') }}</span></div>
            </div>

            <h3 class="s-subject">{{ $feedback->subject ?: 'Tanpa judul' }}</h3>
            <div class="s-message">{{ $feedback->message }}</div>

            @if($feedback->replies->count())
                <div class="s-replies">
                    @foreach($feedback->replies as $reply)
                        <div class="s-reply">
                            <div class="s-reply-meta">
                                <span>Dibalas oleh {{ $reply->user?->name ?: 'Admin' }} dari {{ $reply->sender_email }}</span>
                                <span>{{ $reply->sent_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="s-reply-text">{{ $reply->message }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <aside>
        <section class="s-card">
            <div class="s-card-head"><h2>Balas melalui Email</h2></div>
            <div class="s-body">
                @if($emailSetting && $emailSetting->isConnected())
                    <div class="s-alert ok">Email pengirim aktif: <strong>{{ $emailSetting->sender_name }} &lt;{{ $emailSetting->email }}&gt;</strong></div>
                @else
                    <div class="s-alert bad">Email klinik belum tersambung. <a href="{{ route('admin.email-settings.edit') }}">Hubungkan email terlebih dahulu.</a></div>
                @endif

                @if($errors->any())
                    <div class="s-errors">{{ $errors->first() }}</div>
                @endif

                <div class="s-recipient">Balasan akan dikirim ke: <strong>{{ $feedback->email }}</strong></div>

                <form method="POST" action="{{ route('admin.feedback.reply', $feedback) }}">
                    @csrf
                    <label class="s-label" for="reply_message">Isi balasan</label>
                    <textarea class="s-textarea" id="reply_message" name="reply_message" maxlength="5000" placeholder="Tuliskan tanggapan dari Klinik Mitra Sehat..." @disabled(!($emailSetting && $emailSetting->isConnected()))>{{ old('reply_message') }}</textarea>
                    <button class="s-btn" type="submit" @disabled(!($emailSetting && $emailSetting->isConnected()))>Kirim Balasan Email</button>
                </form>
            </div>
        </section>

        <form method="POST" action="{{ route('admin.feedback.destroy', $feedback) }}" onsubmit="return confirm('Hapus pesan ini beserta riwayat balasannya?')">
            @csrf
            @method('DELETE')
            <button class="s-delete" type="submit">Hapus Pesan</button>
        </form>
    </aside>
</div>
@endsection
