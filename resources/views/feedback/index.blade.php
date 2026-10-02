@extends('layouts.app')
@section('pageTitle', 'Kritik, Saran & Apresiasi')

@section('content')
<style>
    .feedback-page {
        min-height: 72vh;
        padding: 46px 0 72px;
        background:
            radial-gradient(circle at top left, rgba(229, 244, 233, .78), transparent 30%),
            linear-gradient(180deg, #fffdf8 0%, #fbf7e9 100%);
        color: #405247;
    }
    .feedback-wrap { width: min(1080px, calc(100% - 48px)); margin: 0 auto; }
    .feedback-head { max-width: 760px; margin-bottom: 26px; }
    .feedback-kicker {
        display: inline-flex; align-items: center; gap: 8px; padding: 7px 12px;
        border-radius: 999px; background: #eaf6ed; color: #17613d;
        font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
    }
    .feedback-kicker::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: #1a7448; }
    .feedback-title { margin: 12px 0 0; color: #174a30; font-size: clamp(32px, 4vw, 48px); line-height: 1.08; letter-spacing: -.035em; font-weight: 800; }
    .feedback-subtitle { margin: 12px 0 0; color: #718077; font-size: 14px; line-height: 1.75; }
    .feedback-grid { display: grid; grid-template-columns: .78fr 1.22fr; gap: 22px; align-items: start; }
    .feedback-card { border: 1px solid #dfe8e1; border-radius: 24px; background: rgba(255,255,255,.96); box-shadow: 0 14px 38px rgba(32,75,49,.07); }
    .feedback-info { padding: 27px; }
    .feedback-info h2 { margin: 0; color: #174a30; font-size: 20px; font-weight: 800; }
    .feedback-info p { margin: 9px 0 0; color: #718077; font-size: 13px; line-height: 1.7; }
    .privacy-box { margin-top: 20px; padding: 16px; border-radius: 16px; border: 1px solid #dce9df; background: #f5faf6; }
    .privacy-title { margin: 0 0 6px; color: #17613d; font-size: 12px; font-weight: 800; }
    .privacy-text { margin: 0 !important; font-size: 12px !important; line-height: 1.65 !important; }
    .type-list { display: grid; gap: 9px; margin-top: 20px; }
    .type-item { padding: 12px 13px; border: 1px solid #e4eae5; border-radius: 14px; background: #fff; }
    .type-item strong { display: block; color: #31483a; font-size: 12px; }
    .type-item span { display: block; margin-top: 3px; color: #829087; font-size: 11px; line-height: 1.5; }
    .feedback-form-card { padding: 28px; }
    .feedback-success { margin-bottom: 18px; padding: 14px 16px; border: 1px solid #bfe0c8; border-radius: 14px; background: #eef9f1; color: #17613d; font-size: 13px; line-height: 1.6; }
    .feedback-errors { margin-bottom: 18px; padding: 14px 16px; border: 1px solid #f0c8c8; border-radius: 14px; background: #fff3f3; color: #a23434; font-size: 12px; }
    .feedback-errors ul { margin: 6px 0 0 18px; padding: 0; }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 15px; }
    .form-group { display: grid; gap: 7px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { color: #405247; font-size: 12px; font-weight: 800; }
    .form-label small { color: #8a968e; font-size: 10px; font-weight: 500; }
    .form-control {
        width: 100%; min-height: 46px; border: 1px solid #d9e3dc; border-radius: 12px; background: #fff;
        padding: 11px 13px; color: #33443a; font: inherit; font-size: 13px; outline: none; transition: .2s ease;
    }
    .form-control:focus { border-color: #68a57d; box-shadow: 0 0 0 3px rgba(70,139,93,.10); }
    textarea.form-control { min-height: 155px; resize: vertical; line-height: 1.65; }
    .form-hint { color: #8a968e; font-size: 10px; line-height: 1.45; }
    .feedback-submit {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 48px;
        padding: 0 22px; border: 0; border-radius: 13px; background: #1a5d3a; color: #fff;
        font-size: 13px; font-weight: 800; cursor: pointer; transition: .2s ease;
    }
    .feedback-submit:hover { background: #14492f; transform: translateY(-1px); box-shadow: 0 10px 22px rgba(23,97,61,.18); }
    .feedback-submit svg { width: 17px; height: 17px; }
    @media (max-width: 860px) { .feedback-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px) {
        .feedback-page { padding: 30px 0 48px; }
        .feedback-wrap { width: calc(100% - 24px); }
        .feedback-card { border-radius: 19px; }
        .feedback-info, .feedback-form-card { padding: 19px; }
        .form-grid { grid-template-columns: 1fr; }
        .form-group.full { grid-column: auto; }
        .feedback-submit { width: 100%; }
    }
</style>

<section class="feedback-page">
    <div class="feedback-wrap">
        <header class="feedback-head">
            <div class="feedback-kicker">Suara Anda Berarti</div>
            <h1 class="feedback-title">Kritik, Saran &amp; Apresiasi</h1>
            <p class="feedback-subtitle">
                Sampaikan pengalaman, masukan, maupun apresiasi untuk membantu Klinik Mitra Sehat terus meningkatkan pelayanan.
                Pesan yang Anda kirim hanya dapat dibaca oleh admin klinik.
            </p>
        </header>

        <div class="feedback-grid">
            <aside class="feedback-card feedback-info">
                <h2>Masukan Anda kami jaga</h2>
                <p>Gunakan formulir ini untuk menyampaikan hal yang ingin Anda sampaikan langsung kepada pengelola klinik.</p>

                <div class="privacy-box">
                    <p class="privacy-title">Privasi pesan</p>
                    <p class="privacy-text">Pesan tidak ditampilkan ke publik. Nama, email, dan isi masukan hanya tersedia di dashboard admin klinik untuk keperluan tindak lanjut.</p>
                </div>

                <div class="type-list">
                    <div class="type-item"><strong>Kritik</strong><span>Sampaikan hal yang menurut Anda perlu diperbaiki.</span></div>
                    <div class="type-item"><strong>Saran</strong><span>Berikan ide untuk meningkatkan pelayanan klinik.</span></div>
                    <div class="type-item"><strong>Apresiasi</strong><span>Sampaikan pengalaman positif atau penghargaan kepada tim.</span></div>
                    <div class="type-item"><strong>Lainnya</strong><span>Untuk pesan lain yang masih berkaitan dengan pelayanan.</span></div>
                </div>
            </aside>

            <section class="feedback-card feedback-form-card">
                @if(session('feedback_success'))
                    <div class="feedback-success">{{ session('feedback_success') }}</div>
                @endif

                @if($errors->any())
                    <div class="feedback-errors">
                        <strong>Mohon periksa kembali isian Anda.</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('feedback.store') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="name">Nama</label>
                            <input class="form-control" id="name" name="name" type="text" maxlength="120" value="{{ old('name') }}" autocomplete="name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="email" maxlength="190" value="{{ old('email') }}" autocomplete="email" required>
                            <div class="form-hint">Balasan admin akan dikirim ke alamat ini.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="type">Jenis Masukan</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Pilih jenis</option>
                                <option value="kritik" @selected(old('type') === 'kritik')>Kritik</option>
                                <option value="saran" @selected(old('type') === 'saran')>Saran</option>
                                <option value="apresiasi" @selected(old('type') === 'apresiasi')>Apresiasi</option>
                                <option value="lainnya" @selected(old('type') === 'lainnya')>Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="subject">Judul <small>(opsional)</small></label>
                            <input class="form-control" id="subject" name="subject" type="text" maxlength="180" value="{{ old('subject') }}" placeholder="Contoh: Waktu tunggu pendaftaran">
                        </div>

                        <div class="form-group full">
                            <label class="form-label" for="message">Pesan</label>
                            <textarea class="form-control" id="message" name="message" maxlength="5000" required placeholder="Tuliskan kritik, saran, apresiasi, atau masukan Anda...">{{ old('message') }}</textarea>
                            <div class="form-hint">Maksimal 5.000 karakter. Hindari mencantumkan data medis atau informasi pribadi yang tidak diperlukan.</div>
                        </div>

                        <div class="form-group full">
                            <button class="feedback-submit" type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7Z"/></svg>
                                Kirim Masukan
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</section>
@endsection
