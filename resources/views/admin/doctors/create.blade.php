@extends('admin.layout')
@section('title', 'Tambah Dokter')

@section('content')
<style>
    .kms-form-page, .kms-form-page * { box-sizing:border-box; }
    .kms-form-page { max-width:1080px; margin:0 auto; color:#111827; }
    .kms-back { display:inline-flex; align-items:center; gap:6px; color:#1a5d3a; font-size:14px; font-weight:650; text-decoration:none; margin-bottom:14px; }
    .kms-head { margin-bottom:22px; }
    .kms-eyebrow { margin:0 0 5px; color:#1a5d3a; font-size:12px; font-weight:750; text-transform:uppercase; letter-spacing:.12em; }
    .kms-title { margin:0; font-size:28px; line-height:1.2; font-weight:800; }
    .kms-subtitle { margin:7px 0 0; color:#6b7280; font-size:14px; }
    .kms-alert { padding:14px 16px; border-radius:12px; background:#fff1f2; border:1px solid #fecdd3; color:#b91c1c; font-size:13px; margin-bottom:18px; }
    .kms-alert strong { display:block; margin-bottom:5px; }
    .kms-alert ul { margin:0; padding-left:18px; }
    .kms-grid { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:20px; align-items:start; }
    .kms-card { background:#fff; border:1px solid #edf0f2; border-radius:16px; padding:22px; box-shadow:0 3px 14px rgba(17,24,39,.05); }
    .kms-card h2 { margin:0; font-size:17px; font-weight:800; }
    .kms-card-note { margin:5px 0 18px; color:#6b7280; font-size:12px; }
    .kms-field { margin-bottom:17px; }
    .kms-field:last-child { margin-bottom:0; }
    .kms-label { display:block; margin-bottom:7px; color:#374151; font-size:13px; font-weight:700; }
    .kms-required { color:#dc2626; }
    .kms-input { width:100%; min-height:43px; border:1px solid #d1d5db; border-radius:11px; background:#fff; padding:9px 12px; font-size:14px; color:#111827; outline:none; transition:.15s ease; }
    .kms-input:focus { border-color:#1a5d3a; box-shadow:0 0 0 3px rgba(26,93,58,.10); }
    .kms-two { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .kms-help { margin:6px 0 0; color:#9ca3af; font-size:11px; }
    .kms-toggle-row { min-height:70px; border:1px solid #e5e7eb; border-radius:11px; padding:11px 12px; display:flex; align-items:center; justify-content:space-between; gap:14px; }
    .kms-toggle-title { margin:0; font-size:13px; font-weight:700; color:#374151; }
    .kms-toggle-note { margin:3px 0 0; font-size:11px; color:#9ca3af; }
    .kms-switch { position:relative; width:44px; height:24px; flex:0 0 44px; }
    .kms-switch input { position:absolute; opacity:0; pointer-events:none; }
    .kms-switch-ui { position:absolute; inset:0; background:#d1d5db; border-radius:999px; transition:.2s; cursor:pointer; }
    .kms-switch-ui:after { content:""; position:absolute; width:20px; height:20px; left:2px; top:2px; background:#fff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,.2); transition:.2s; }
    .kms-switch input:checked + .kms-switch-ui { background:#1a5d3a; }
    .kms-switch input:checked + .kms-switch-ui:after { transform:translateX(20px); }
    .kms-photo-box { width:100%; max-width:230px; aspect-ratio:4 / 5; margin:16px auto 0; overflow:hidden; border:1px dashed #cbd5e1; border-radius:16px; background:#f8fafc; display:flex; align-items:center; justify-content:center; }
    .kms-photo-box img { width:100%; height:100%; object-fit:cover; object-position:top; display:none; }
    .kms-photo-placeholder { padding:18px; text-align:center; color:#94a3b8; font-size:12px; }
    .kms-file-btn { width:100%; margin-top:14px; min-height:42px; border:1px solid #bbf7d0; border-radius:11px; background:#ecfdf3; color:#166534; font-size:13px; font-weight:750; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .kms-file-name { margin:7px 0 0; text-align:center; color:#9ca3af; font-size:11px; word-break:break-word; }
    .kms-error { margin:6px 0 0; color:#dc2626; font-size:11px; }
    .kms-actions { margin-top:20px; display:flex; justify-content:flex-end; gap:10px; }
    .kms-btn { min-height:42px; padding:10px 16px; border-radius:11px; border:0; font-size:13px; font-weight:750; text-decoration:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; }
    .kms-btn-secondary { border:1px solid #d1d5db; background:#fff; color:#4b5563; }
    .kms-btn-primary { background:#1a5d3a; color:#fff; }
    .kms-btn-primary:hover { background:#154a2e; }
    @media (max-width: 900px) { .kms-grid { grid-template-columns:1fr; } .kms-photo-box { max-width:280px; } }
    @media (max-width: 600px) { .kms-title { font-size:24px; } .kms-two { grid-template-columns:1fr; } .kms-actions { flex-direction:column-reverse; } .kms-actions .kms-btn { width:100%; } .kms-card { padding:17px; } }
</style>

<div class="kms-form-page">
    <a href="{{ route('admin.doctors.index') }}" class="kms-back">&larr; Kembali</a>

    <div class="kms-head">
        <p class="kms-eyebrow">Manajemen Dokter</p>
        <h1 class="kms-title">Tambah Dokter</h1>
        <p class="kms-subtitle">Tambahkan profil dokter yang nantinya dapat langsung ditampilkan di website.</p>
    </div>

    @if($errors->any())
        <div class="kms-alert">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data" id="doctorCreateForm">
        @csrf
        <div class="kms-grid">
            <section class="kms-card">
                <h2>Informasi Dokter</h2>
                <p class="kms-card-note">Isi identitas dasar dan pengaturan tampil dokter.</p>

                <div class="kms-field">
                    <label for="name" class="kms-label">Nama Dokter <span class="kms-required">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="kms-input" placeholder="Contoh: drg. Anna Permadani">
                    @error('name')<p class="kms-error">{{ $message }}</p>@enderror
                </div>

                <div class="kms-field">
                    <label for="specialization" class="kms-label">Spesialisasi <span class="kms-required">*</span></label>
                    <input id="specialization" type="text" name="specialization" value="{{ old('specialization') }}" required class="kms-input" placeholder="Contoh: Dokter Gigi">
                    @error('specialization')<p class="kms-error">{{ $message }}</p>@enderror
                </div>

                <div class="kms-two">
                    <div class="kms-field">
                        <label for="sort_order" class="kms-label">Urutan Tampil</label>
                        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" max="9999" class="kms-input">
                        <p class="kms-help">Angka lebih kecil tampil lebih dulu.</p>
                        @error('sort_order')<p class="kms-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="kms-field">
                        <span class="kms-label">Status Website</span>
                        <div class="kms-toggle-row">
                            <div>
                                <p class="kms-toggle-title">Tampilkan dokter</p>
                                <p class="kms-toggle-note">Bisa dimatikan sementara.</p>
                            </div>
                            <label class="kms-switch" aria-label="Tampilkan dokter di website">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                <span class="kms-switch-ui"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="kms-card">
                <h2>Foto Dokter</h2>
                <p class="kms-card-note">JPG, JPEG, PNG, atau WebP. Maksimal 10 MB.</p>

                <div class="kms-photo-box">
                    <img id="photoPreview" src="" alt="Preview foto dokter" style="display:none;">
                    <div id="photoPlaceholder" class="kms-photo-placeholder">Preview foto akan muncul di sini.</div>
                </div>

                <label for="photo" class="kms-file-btn">Pilih Foto dari Folder</label>
                <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" style="display:none;">
                <p id="photoFileName" class="kms-file-name">Belum ada foto dipilih</p>
                @error('photo')<p class="kms-error" style="text-align:center;">{{ $message }}</p>@enderror

                <x-image-adjuster
                    input="photo"
                    previews="photoPreview"
                    placeholders="photoPlaceholder"
                    filename="photoFileName"
                    title="Atur Foto Dokter"
                    :aspectw="4"
                    :aspecth="5"
                    :outputw="1000"
                    :outputh="1250"
                    :maxmb="10"
                />
            </aside>
        </div>

        <div class="kms-actions">
            <a href="{{ route('admin.doctors.index') }}" class="kms-btn kms-btn-secondary">Batal</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Dokter</button>
        </div>
    </form>
</div>

@endsection
