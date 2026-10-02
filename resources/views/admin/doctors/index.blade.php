@extends('admin.layout')
@section('title', 'Kelola Dokter')

@section('content')
<style>
    .kms-doctor-page, .kms-doctor-page * { box-sizing: border-box; }
    .kms-doctor-page { width: 100%; color: #111827; }
    .kms-doctor-head { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:22px; }
    .kms-doctor-eyebrow { margin:0 0 5px; color:#1a5d3a; font-size:12px; line-height:1.4; font-weight:700; text-transform:uppercase; letter-spacing:.12em; }
    .kms-doctor-title { margin:0; font-size:28px; line-height:1.2; font-weight:800; color:#111827; }
    .kms-doctor-subtitle { margin:7px 0 0; color:#6b7280; font-size:14px; }
    .kms-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:42px; padding:10px 16px; border:0; border-radius:11px; font-size:14px; font-weight:700; text-decoration:none; cursor:pointer; transition:.2s ease; white-space:nowrap; }
    .kms-btn-primary { background:#1a5d3a; color:#fff; }
    .kms-btn-primary:hover { background:#154a2e; }
    .kms-btn-edit { background:#ecfdf3; color:#166534; border:1px solid #bbf7d0; }
    .kms-btn-edit:hover { background:#dcfce7; }
    .kms-btn-delete { background:#fff1f2; color:#dc2626; border:1px solid #fecdd3; }
    .kms-btn-delete:hover { background:#ffe4e6; }
    .kms-plus { width:18px; height:18px; display:block; flex:0 0 18px; }
    .kms-stats { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; margin-bottom:20px; }
    .kms-stat { background:#fff; border:1px solid #eef0f2; border-radius:16px; padding:16px 18px; box-shadow:0 2px 10px rgba(17,24,39,.04); }
    .kms-stat-label { margin:0; color:#6b7280; font-size:12px; font-weight:600; }
    .kms-stat-value { margin:5px 0 0; font-size:26px; font-weight:800; color:#111827; }
    .kms-stat-value.green { color:#1a5d3a; }
    .kms-table-card { overflow:hidden; background:#fff; border:1px solid #eef0f2; border-radius:16px; box-shadow:0 3px 14px rgba(17,24,39,.05); }
    .kms-table-wrap { overflow-x:auto; width:100%; }
    .kms-table { width:100%; min-width:840px; border-collapse:collapse; font-size:14px; }
    .kms-table th { padding:14px 18px; background:#f8fafc; color:#64748b; font-size:11px; text-transform:uppercase; letter-spacing:.05em; text-align:left; border-bottom:1px solid #edf0f2; }
    .kms-table th.center, .kms-table td.center { text-align:center; }
    .kms-table th.right, .kms-table td.right { text-align:right; }
    .kms-table td { padding:14px 18px; border-bottom:1px solid #f0f2f4; vertical-align:middle; }
    .kms-table tbody tr:last-child td { border-bottom:0; }
    .kms-table tbody tr:hover { background:#fafcfa; }
    .kms-doctor-info { display:flex; align-items:center; gap:12px; min-width:230px; }
    .kms-avatar { width:50px; height:50px; flex:0 0 50px; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb; background:#effaf3; display:flex; align-items:center; justify-content:center; }
    .kms-avatar img { width:100%; height:100%; object-fit:cover; object-position:top; display:block; }
    .kms-avatar-fallback { color:#1a5d3a; font-size:18px; font-weight:800; }
    .kms-name { margin:0; font-weight:750; color:#111827; }
    .kms-id { margin:3px 0 0; color:#9ca3af; font-size:11px; }
    .kms-muted { color:#64748b; }
    .kms-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 9px; border-radius:999px; font-size:11px; font-weight:700; }
    .kms-badge-blue { background:#eff6ff; color:#1d4ed8; }
    .kms-badge-green { background:#ecfdf3; color:#166534; }
    .kms-badge-gray { background:#f3f4f6; color:#4b5563; }
    .kms-dot { width:7px; height:7px; border-radius:50%; display:inline-block; }
    .kms-dot.green { background:#22c55e; }
    .kms-dot.gray { background:#9ca3af; }
    .kms-actions { display:flex; align-items:center; justify-content:flex-end; gap:8px; }
    .kms-actions form { margin:0; }
    .kms-empty { padding:54px 24px; text-align:center; background:#fff; border:1px dashed #cfd4da; border-radius:16px; }
    .kms-empty h2 { margin:0 0 6px; font-size:17px; }
    .kms-empty p { margin:0 0 18px; color:#6b7280; font-size:14px; }
    .kms-mobile-list { display:none; }
    .kms-mobile-card { background:#fff; border:1px solid #eef0f2; border-radius:16px; padding:16px; box-shadow:0 2px 10px rgba(17,24,39,.04); }
    .kms-mobile-top { display:flex; align-items:center; gap:12px; }
    .kms-mobile-meta { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:14px; }
    .kms-mobile-meta-box { background:#f8fafc; border-radius:11px; padding:10px; }
    .kms-mobile-meta-label { color:#9ca3af; font-size:10px; text-transform:uppercase; letter-spacing:.04em; }
    .kms-mobile-meta-value { margin-top:3px; font-size:13px; font-weight:700; color:#475569; }
    .kms-mobile-actions { display:grid; grid-template-columns:1fr 1fr; gap:9px; margin-top:14px; }
    .kms-mobile-actions .kms-btn, .kms-mobile-actions form, .kms-mobile-actions form .kms-btn { width:100%; }
    @media (max-width: 767px) {
        .kms-doctor-head { align-items:stretch; flex-direction:column; }
        .kms-doctor-head .kms-btn-primary { width:100%; }
        .kms-doctor-title { font-size:24px; }
        .kms-stats { grid-template-columns:1fr; }
        .kms-table-card { display:none; }
        .kms-mobile-list { display:grid; gap:12px; }
    }
</style>

<div class="kms-doctor-page">
    <div class="kms-doctor-head">
        <div>
            <p class="kms-doctor-eyebrow">Manajemen Dokter</p>
            <h1 class="kms-doctor-title">Kelola Dokter</h1>
            <p class="kms-doctor-subtitle">Atur profil, status tampil, urutan, foto, dan jadwal dokter.</p>
        </div>

        <a href="{{ route('admin.doctors.create') }}" class="kms-btn kms-btn-primary">
            <svg class="kms-plus" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14" />
            </svg>
            Tambah Dokter
        </a>
    </div>

    <div class="kms-stats">
        <div class="kms-stat">
            <p class="kms-stat-label">Total Dokter</p>
            <p class="kms-stat-value">{{ $doctors->count() }}</p>
        </div>
        <div class="kms-stat">
            <p class="kms-stat-label">Aktif di Website</p>
            <p class="kms-stat-value green">{{ $doctors->where('is_active', true)->count() }}</p>
        </div>
        <div class="kms-stat">
            <p class="kms-stat-label">Nonaktif</p>
            <p class="kms-stat-value">{{ $doctors->where('is_active', false)->count() }}</p>
        </div>
    </div>

    @if($doctors->isEmpty())
        <div class="kms-empty">
            <h2>Belum ada data dokter</h2>
            <p>Tambahkan dokter pertama agar dapat ditampilkan di website.</p>
            <a href="{{ route('admin.doctors.create') }}" class="kms-btn kms-btn-primary">Tambah Dokter</a>
        </div>
    @else
        <div class="kms-table-card">
            <div class="kms-table-wrap">
                <table class="kms-table">
                    <thead>
                        <tr>
                            <th>Dokter</th>
                            <th>Spesialisasi</th>
                            <th class="center">Jadwal</th>
                            <th class="center">Urutan</th>
                            <th class="center">Status</th>
                            <th class="right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                            <tr>
                                <td>
                                    <div class="kms-doctor-info">
                                        <div class="kms-avatar">
                                            @if($doctor->photo)
                                                <img src="{{ asset($doctor->photo) }}" alt="{{ $doctor->name }}">
                                            @else
                                                <span class="kms-avatar-fallback">Dr</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="kms-name">{{ $doctor->name }}</p>
                                            <p class="kms-id">ID #{{ $doctor->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="kms-muted">{{ $doctor->specialization }}</td>
                                <td class="center"><span class="kms-badge kms-badge-blue">{{ $doctor->schedules->count() }} jadwal</span></td>
                                <td class="center kms-muted"><strong>{{ $doctor->sort_order }}</strong></td>
                                <td class="center">
                                    @if($doctor->is_active)
                                        <span class="kms-badge kms-badge-green"><span class="kms-dot green"></span>Aktif</span>
                                    @else
                                        <span class="kms-badge kms-badge-gray"><span class="kms-dot gray"></span>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="right">
                                    <div class="kms-actions">
                                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="kms-btn kms-btn-edit">Edit</a>
                                        <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" onsubmit="return confirm('Hapus dokter {{ addslashes($doctor->name) }}? Data jadwal dokter ini juga akan ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="kms-btn kms-btn-delete">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="kms-mobile-list">
            @foreach($doctors as $doctor)
                <article class="kms-mobile-card">
                    <div class="kms-mobile-top">
                        <div class="kms-avatar">
                            @if($doctor->photo)
                                <img src="{{ asset($doctor->photo) }}" alt="{{ $doctor->name }}">
                            @else
                                <span class="kms-avatar-fallback">Dr</span>
                            @endif
                        </div>
                        <div>
                            <p class="kms-name">{{ $doctor->name }}</p>
                            <p class="kms-id">{{ $doctor->specialization }}</p>
                        </div>
                    </div>

                    <div class="kms-mobile-meta">
                        <div class="kms-mobile-meta-box">
                            <div class="kms-mobile-meta-label">Jadwal</div>
                            <div class="kms-mobile-meta-value">{{ $doctor->schedules->count() }} jadwal</div>
                        </div>
                        <div class="kms-mobile-meta-box">
                            <div class="kms-mobile-meta-label">Urutan</div>
                            <div class="kms-mobile-meta-value">{{ $doctor->sort_order }}</div>
                        </div>
                    </div>

                    <div style="margin-top:12px;">
                        @if($doctor->is_active)
                            <span class="kms-badge kms-badge-green"><span class="kms-dot green"></span>Aktif di Website</span>
                        @else
                            <span class="kms-badge kms-badge-gray"><span class="kms-dot gray"></span>Nonaktif</span>
                        @endif
                    </div>

                    <div class="kms-mobile-actions">
                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="kms-btn kms-btn-edit">Edit</a>
                        <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" onsubmit="return confirm('Hapus dokter {{ addslashes($doctor->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="kms-btn kms-btn-delete">Hapus</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
