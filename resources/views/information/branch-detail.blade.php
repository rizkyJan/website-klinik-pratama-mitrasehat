@extends('layouts.app')
@section('pageTitle', $branch->name)

@section('content')
@php
    $photoUrl = $branch->photo
        ? (filter_var($branch->photo, FILTER_VALIDATE_URL) ? $branch->photo : asset($branch->photo))
        : null;
@endphp
<style>
    .branch-detail-page{background:#fbf7e9;padding:38px 0 64px;min-height:65vh;color:#425349}.branch-detail-wrap{width:min(1080px,calc(100% - 48px));margin:0 auto}.branch-detail-back{display:inline-flex;align-items:center;gap:7px;margin-bottom:20px;color:#17613d;text-decoration:none;font-size:11px;font-weight:800}.branch-detail-back:hover{text-decoration:underline}.branch-detail-card{overflow:hidden;border:1px solid #dfe8e1;border-radius:28px;background:#fff;box-shadow:0 12px 34px rgba(30,75,47,.07)}.branch-detail-photo{aspect-ratio:16/8;background:linear-gradient(135deg,#eaf7ed,#f8fbf7);display:flex;align-items:center;justify-content:center;color:#86ac92;overflow:hidden}.branch-detail-photo img{width:100%;height:100%;object-fit:cover;display:block}.branch-detail-empty{text-align:center;font-size:11px;font-weight:800;letter-spacing:.08em}.branch-detail-content{padding:30px}.branch-detail-kicker{display:inline-flex;padding:7px 11px;border-radius:999px;background:#edf7ef;color:#17613d;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.07em}.branch-detail-title{margin:14px 0 0;color:#174a30;font-size:36px;line-height:1.18;font-weight:800;letter-spacing:-.03em}.branch-detail-description{margin:14px 0 0;color:#69786f;font-size:14px;line-height:1.8;white-space:pre-line}.branch-detail-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:24px}.branch-detail-btn{min-height:44px;display:inline-flex;align-items:center;justify-content:center;padding:0 16px;border-radius:12px;text-decoration:none;font-size:11px;font-weight:800}.branch-detail-btn-primary{background:#17613d;color:#fff}.branch-detail-btn-secondary{background:#edf7ef;color:#17613d}.branch-detail-note{margin-top:16px;padding:13px 15px;border-radius:13px;background:#f6faf7;color:#758178;font-size:11px;line-height:1.6}
    @media(max-width:700px){.branch-detail-page{padding:28px 0 44px}.branch-detail-wrap{width:calc(100% - 24px)}.branch-detail-card{border-radius:20px}.branch-detail-content{padding:20px}.branch-detail-title{font-size:28px}.branch-detail-photo{aspect-ratio:16/10}}
</style>

<section class="branch-detail-page">
    <div class="branch-detail-wrap">
        <a class="branch-detail-back" href="{{ route('information.branches') }}">← Kembali ke Daftar Cabang</a>
        <article class="branch-detail-card">
            <div class="branch-detail-photo">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="{{ $branch->name }}">
                @else
                    <div class="branch-detail-empty">FOTO CABANG</div>
                @endif
            </div>
            <div class="branch-detail-content">
                <span class="branch-detail-kicker">Cabang Klinik</span>
                <h1 class="branch-detail-title">{{ $branch->name }}</h1>
                @if($branch->description)
                    <p class="branch-detail-description">{{ $branch->description }}</p>
                @endif
                <div class="branch-detail-actions">
                    @if($branch->maps_url)
                        <a class="branch-detail-btn branch-detail-btn-primary" href="{{ $branch->maps_url }}" target="_blank" rel="noopener">⌖ Buka di Google Maps</a>
                    @endif
                    <a class="branch-detail-btn branch-detail-btn-secondary" href="{{ route('information.branches') }}">Lihat Semua Cabang</a>
                </div>
                @if($branch->maps_url)
                    <div class="branch-detail-note">Lokasi menggunakan link Google Maps yang disimpan melalui halaman admin. Klik tombol di atas untuk membuka petunjuk arah.</div>
                @endif
            </div>
        </article>
    </div>
</section>
@endsection
