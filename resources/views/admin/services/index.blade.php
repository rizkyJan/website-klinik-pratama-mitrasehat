@extends('admin.layout')
@section('title', 'Kelola Layanan')

@section('content')
@php
    $activeCount = $services->where('is_active', true)->count();
    $inactiveCount = $services->count() - $activeCount;
@endphp

<style>
    .kms-page { color:#102a1d; }
    .kms-head { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:22px; }
    .kms-eyebrow { margin:0 0 5px; color:#1a5d3a; font-size:11px; font-weight:800; letter-spacing:.09em; text-transform:uppercase; }
    .kms-title { margin:0; color:#102a1d; font-size:28px; line-height:1.1; font-weight:800; letter-spacing:-.02em; }
    .kms-subtitle { margin:8px 0 0; color:#6b7280; font-size:13px; line-height:1.6; }
    .kms-add { min-height:42px; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:0 16px; border-radius:12px; background:#1a5d3a; color:white; text-decoration:none; font-size:13px; font-weight:800; box-shadow:0 8px 18px rgba(26,93,58,.13); }
    .kms-add:hover { background:#154a2e; }
    .kms-add svg { width:16px; height:16px; }

    .kms-stats { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin-bottom:18px; }
    .kms-stat { padding:16px 18px; border:1px solid #e6ece7; border-radius:16px; background:#fff; box-shadow:0 4px 12px rgba(31,71,48,.035); }
    .kms-stat-label { color:#7b847d; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; }
    .kms-stat-value { margin-top:4px; color:#163d29; font-size:24px; font-weight:800; }

    .kms-panel { overflow:hidden; border:1px solid #e4ebe5; border-radius:18px; background:#fff; box-shadow:0 7px 20px rgba(31,71,48,.045); }
    .kms-list-head, .kms-row { display:grid; grid-template-columns:58px minmax(160px,1.1fr) minmax(250px,1.8fr) 90px 110px 150px; gap:14px; align-items:center; }
    .kms-list-head { padding:12px 18px; background:#f7faf8; border-bottom:1px solid #e8eee9; color:#6f786f; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
    .kms-row { padding:14px 18px; border-bottom:1px solid #edf1ee; }
    .kms-row:last-child { border-bottom:0; }
    .kms-row:hover { background:#fbfdfb; }
    .kms-icon-box { width:44px; height:44px; display:flex; align-items:center; justify-content:center; border-radius:13px; background:#eaf7ec; color:#1a5d3a; }
    .kms-icon-box svg { width:22px; height:22px; }
    .kms-name { color:#1f2937; font-size:13px; font-weight:800; }
    .kms-slug { margin-top:3px; color:#98a19a; font-size:10px; }
    .kms-desc { color:#667168; font-size:12px; line-height:1.55; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .kms-order { width:34px; height:28px; display:inline-flex; align-items:center; justify-content:center; border-radius:9px; background:#f5f7f5; border:1px solid #e4e9e5; color:#5c665f; font-size:11px; font-weight:800; }
    .kms-status { display:inline-flex; align-items:center; gap:6px; width:max-content; padding:6px 9px; border-radius:999px; font-size:10px; font-weight:800; }
    .kms-status.active { background:#eaf8ee; color:#16703a; }
    .kms-status.inactive { background:#f3f4f6; color:#6b7280; }
    .kms-dot { width:6px; height:6px; border-radius:50%; background:currentColor; }
    .kms-actions { display:flex; align-items:center; justify-content:flex-end; gap:7px; }
    .kms-btn { min-height:33px; display:inline-flex; align-items:center; justify-content:center; padding:0 11px; border-radius:9px; border:1px solid transparent; font-size:11px; font-weight:800; text-decoration:none; cursor:pointer; background:none; }
    .kms-edit { color:#145b37; border-color:#cfe1d3; background:#f7fcf8; }
    .kms-edit:hover { background:#ecf8ef; }
    .kms-delete { color:#c53030; border-color:#f0d1d1; background:#fffafa; }
    .kms-delete:hover { background:#fff0f0; }
    .kms-empty { padding:42px 20px; text-align:center; color:#7b847d; font-size:13px; }

    @media (max-width: 1000px) {
        .kms-list-head { display:none; }
        .kms-row { grid-template-columns:52px minmax(0,1fr) auto; grid-template-areas:'icon name status' 'icon desc desc' 'icon meta actions'; align-items:start; }
        .kms-row > :nth-child(1){grid-area:icon}
        .kms-row > :nth-child(2){grid-area:name}
        .kms-row > :nth-child(3){grid-area:desc}
        .kms-row > :nth-child(4){grid-area:meta}
        .kms-row > :nth-child(5){grid-area:status; justify-self:end}
        .kms-row > :nth-child(6){grid-area:actions; justify-self:end}
        .kms-order::before { content:'Urutan '; margin-right:3px; }
        .kms-order { width:auto; padding:0 9px; }
    }
    @media (max-width: 700px) {
        .kms-head { align-items:stretch; flex-direction:column; }
        .kms-add { width:100%; }
        .kms-stats { grid-template-columns:1fr; }
        .kms-stat { display:flex; align-items:center; justify-content:space-between; padding:13px 15px; }
        .kms-stat-value { margin:0; font-size:20px; }
        .kms-row { grid-template-columns:46px minmax(0,1fr); grid-template-areas:'icon name' 'icon status' 'desc desc' 'meta meta' 'actions actions'; gap:9px 10px; }
        .kms-row > :nth-child(5){justify-self:start}
        .kms-row > :nth-child(6){justify-self:stretch}
        .kms-actions { justify-content:stretch; }
        .kms-actions .kms-btn, .kms-actions form { flex:1; }
        .kms-actions form .kms-btn { width:100%; }
        .kms-icon-box { width:40px; height:40px; }
    }
</style>

<div class="kms-page">
    <div class="kms-head">
        <div>
            <p class="kms-eyebrow">Manajemen Layanan</p>
            <h1 class="kms-title">Kelola Layanan</h1>
            <p class="kms-subtitle">Atur nama, deskripsi, ikon, status, dan urutan layanan yang tampil di website klinik.</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="kms-add">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14" />
            </svg>
            Tambah Layanan
        </a>
    </div>

    <div class="kms-stats">
        <div class="kms-stat">
            <div class="kms-stat-label">Total Layanan</div>
            <div class="kms-stat-value">{{ $services->count() }}</div>
        </div>
        <div class="kms-stat">
            <div class="kms-stat-label">Aktif di Website</div>
            <div class="kms-stat-value">{{ $activeCount }}</div>
        </div>
        <div class="kms-stat">
            <div class="kms-stat-label">Disembunyikan</div>
            <div class="kms-stat-value">{{ $inactiveCount }}</div>
        </div>
    </div>

    <div class="kms-panel">
        <div class="kms-list-head">
            <div>Ikon</div>
            <div>Layanan</div>
            <div>Deskripsi Singkat</div>
            <div>Urutan</div>
            <div>Status</div>
            <div style="text-align:right;">Aksi</div>
        </div>

        @forelse($services as $service)
            <div class="kms-row">
                <div class="kms-icon-box">
                    <x-service-icon :name="$service->icon" />
                </div>
                <div>
                    <div class="kms-name">{{ $service->name }}</div>
                    <div class="kms-slug">/{{ $service->slug }}</div>
                </div>
                <div class="kms-desc">{{ $service->description ?: 'Belum ada deskripsi singkat.' }}</div>
                <div><span class="kms-order">{{ $service->sort_order }}</span></div>
                <div>
                    <span class="kms-status {{ $service->is_active ? 'active' : 'inactive' }}">
                        <span class="kms-dot"></span>
                        {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="kms-actions">
                    <a href="{{ route('admin.services.edit', $service) }}" class="kms-btn kms-edit">Edit</a>
                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Hapus layanan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="kms-btn kms-delete">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="kms-empty">Belum ada layanan. Klik <strong>Tambah Layanan</strong> untuk membuat layanan pertama.</div>
        @endforelse
    </div>
</div>
@endsection
