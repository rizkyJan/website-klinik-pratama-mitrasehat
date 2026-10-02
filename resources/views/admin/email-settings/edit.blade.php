@extends('admin.layout')
@section('title', 'Pengaturan Email')

@section('content')
<style>
    .g-wrap{max-width:760px;margin:0 auto}.g-head{margin-bottom:18px}.g-title{margin:0;color:#1f2937;font-size:22px;font-weight:800}.g-sub{margin:5px 0 0;color:#6b7280;font-size:13px;line-height:1.6}.g-card{overflow:hidden;border:1px solid #e4e9e5;border-radius:18px;background:#fff;box-shadow:0 5px 22px rgba(25,72,45,.05)}.g-card-head{padding:18px 20px;border-bottom:1px solid #edf1ee;background:#fbfcfb}.g-card-head h2{margin:0;color:#263f31;font-size:16px;font-weight:800}.g-body{padding:22px}.g-status{display:flex;align-items:center;gap:14px;padding:17px;border:1px solid;border-radius:14px}.g-status.ok{background:#f2faf4;border-color:#bfdfc8}.g-status.off{background:#fff9ed;border-color:#ead7aa}.g-status-icon{display:flex;align-items:center;justify-content:center;flex:0 0 42px;width:42px;height:42px;border-radius:50%;font-size:20px;font-weight:900}.g-status.ok .g-status-icon{background:#dff1e4;color:#17613d}.g-status.off .g-status-icon{background:#f8ebc9;color:#805b13}.g-status-title{color:#263f31;font-size:13px;font-weight:800}.g-status-text{margin-top:3px;color:#69776e;font-size:11px;line-height:1.55}.g-account{display:flex;align-items:center;gap:13px;margin-top:16px;padding:16px;border:1px solid #e4e9e5;border-radius:14px}.g-avatar{display:flex;align-items:center;justify-content:center;flex:0 0 44px;width:44px;height:44px;border-radius:50%;background:#f2f5f3;color:#1a5d3a;font-size:15px;font-weight:900}.g-name{color:#273c30;font-size:13px;font-weight:800}.g-email{margin-top:2px;color:#758178;font-size:11px}.g-actions{display:flex;flex-wrap:wrap;gap:9px;margin-top:18px}.g-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:43px;padding:0 16px;border-radius:11px;text-decoration:none;font-size:11px;font-weight:800;cursor:pointer}.g-btn.google{border:1px solid #d8ded9;background:#fff;color:#33443a;box-shadow:0 1px 3px rgba(0,0,0,.05)}.g-btn.google:hover{background:#f8faf8}.g-btn.secondary{border:1px solid #bed4c4;background:#fff;color:#17613d}.g-btn.danger{border:1px solid #efc7c7;background:#fff7f7;color:#b42318}.g-note{margin-top:16px;padding:13px 14px;border-radius:12px;background:#f6f8f6;color:#6d7971;font-size:10.5px;line-height:1.6}.g-note strong{color:#394b40}.g-setup{margin-bottom:16px;padding:14px 16px;border:1px solid #f0caca;border-radius:13px;background:#fff6f6;color:#9a2f2f;font-size:11px;line-height:1.6}.g-code{display:block;margin-top:6px;padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #f0dede;color:#6d3333;word-break:break-all;font-family:Consolas,monospace;font-size:10px}.g-last-error{margin-top:14px;padding:12px 14px;border-radius:11px;background:#fff5f5;border:1px solid #f0caca;color:#9f2d2d;font-size:10px;line-height:1.5;word-break:break-word}.g-google-logo{width:18px;height:18px}@media(max-width:620px){.g-actions{display:grid}.g-btn{width:100%}.g-status,.g-account{align-items:flex-start}}
</style>

<div class="g-wrap">
    <div class="g-head">
        <h1 class="g-title">Pengaturan Email</h1>
        <p class="g-sub">Hubungkan akun Google klinik untuk membalas Kritik, Saran &amp; Apresiasi langsung dari dashboard.</p>
    </div>

    @if(! $googleConfigured)
        <div class="g-setup">
            <strong>Google OAuth belum dikonfigurasi oleh pengelola sistem.</strong><br>
            Setelah Client ID dan Client Secret Google ditambahkan ke <code>.env</code>, admin cukup menekan tombol <strong>Hubungkan dengan Google</strong> di halaman ini.
            <span class="g-code">Redirect URI: {{ $redirectUri }}</span>
        </div>
    @endif

    <section class="g-card">
        <div class="g-card-head"><h2>Email Balasan Kritik &amp; Saran</h2></div>
        <div class="g-body">
            @if($setting && $setting->isConnected())
                <div class="g-status ok">
                    <div class="g-status-icon">✓</div>
                    <div>
                        <div class="g-status-title">Email tersambung</div>
                        <div class="g-status-text">Akun Google ini siap digunakan untuk mengirim balasan dari website.</div>
                    </div>
                </div>

                <div class="g-account">
                    <div class="g-avatar">{{ strtoupper(substr($setting->sender_name ?: $setting->email, 0, 1)) }}</div>
                    <div>
                        <div class="g-name">{{ $setting->sender_name ?: 'Akun Google' }}</div>
                        <div class="g-email">{{ $setting->email }}</div>
                    </div>
                </div>

                <div class="g-actions">
                    <form method="POST" action="{{ route('admin.email-settings.test') }}">
                        @csrf
                        <button class="g-btn secondary" type="submit">Kirim Email Percobaan</button>
                    </form>

                    <a class="g-btn google" href="{{ route('admin.email-settings.google.connect') }}">
                        <svg class="g-google-logo" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.06H12v3.9h5.38a4.6 4.6 0 0 1-2 3.02v2.53h3.24c1.9-1.75 2.98-4.33 2.98-7.39Z"/><path fill="#34A853" d="M12 22c2.7 0 4.97-.9 6.63-2.38l-3.24-2.53c-.9.6-2.05.96-3.39.96-2.61 0-4.82-1.76-5.61-4.13H3.04v2.61A10 10 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.39 13.92A6.02 6.02 0 0 1 6.08 12c0-.67.11-1.32.31-1.92V7.47H3.04A10 10 0 0 0 2 12c0 1.61.39 3.13 1.04 4.53l3.35-2.61Z"/><path fill="#EA4335" d="M12 5.95c1.47 0 2.79.51 3.83 1.5l2.87-2.87A9.64 9.64 0 0 0 12 2a10 10 0 0 0-8.96 5.47l3.35 2.61C7.18 7.71 9.39 5.95 12 5.95Z"/></svg>
                        Ganti Akun Google
                    </a>

                    <form method="POST" action="{{ route('admin.email-settings.disconnect') }}" onsubmit="return confirm('Putuskan akun Google dari website? Admin tidak dapat mengirim balasan sampai akun dihubungkan lagi.')">
                        @csrf
                        @method('DELETE')
                        <button class="g-btn danger" type="submit">Putuskan Sambungan</button>
                    </form>
                </div>

                <div class="g-note">
                    Website hanya meminta izin yang diperlukan untuk <strong>mengirim email</strong>. Password akun Google tidak disimpan di aplikasi.
                    @if($setting->last_tested_at)
                        <br>Terakhir diuji: {{ $setting->last_tested_at->format('d/m/Y H:i') }}
                    @endif
                </div>
            @else
                <div class="g-status off">
                    <div class="g-status-icon">!</div>
                    <div>
                        <div class="g-status-title">Email belum tersambung</div>
                        <div class="g-status-text">Hubungkan akun Google klinik agar admin dapat membalas pesan melalui email.</div>
                    </div>
                </div>

                <div class="g-actions">
                    @if($googleConfigured)
                        <a class="g-btn google" href="{{ route('admin.email-settings.google.connect') }}">
                            <svg class="g-google-logo" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.06H12v3.9h5.38a4.6 4.6 0 0 1-2 3.02v2.53h3.24c1.9-1.75 2.98-4.33 2.98-7.39Z"/><path fill="#34A853" d="M12 22c2.7 0 4.97-.9 6.63-2.38l-3.24-2.53c-.9.6-2.05.96-3.39.96-2.61 0-4.82-1.76-5.61-4.13H3.04v2.61A10 10 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.39 13.92A6.02 6.02 0 0 1 6.08 12c0-.67.11-1.32.31-1.92V7.47H3.04A10 10 0 0 0 2 12c0 1.61.39 3.13 1.04 4.53l3.35-2.61Z"/><path fill="#EA4335" d="M12 5.95c1.47 0 2.79.51 3.83 1.5l2.87-2.87A9.64 9.64 0 0 0 12 2a10 10 0 0 0-8.96 5.47l3.35 2.61C7.18 7.71 9.39 5.95 12 5.95Z"/></svg>
                            Hubungkan dengan Google
                        </a>
                    @else
                        <span class="g-btn google" style="opacity:.55;cursor:not-allowed">Hubungkan dengan Google</span>
                    @endif
                </div>

                <div class="g-note">
                    Admin tidak perlu mengisi SMTP, port, username, App Password, atau password Gmail. Cukup pilih akun Google dan berikan izin pengiriman email.
                </div>
            @endif

            @if($setting?->last_error)
                <div class="g-last-error"><strong>Koneksi terakhir bermasalah:</strong><br>{{ $setting->last_error }}<br><br>Klik <strong>Hubungkan dengan Google</strong> / <strong>Ganti Akun Google</strong> untuk memperbarui sambungan.</div>
            @endif
        </div>
    </section>
</div>
@endsection
