@extends('layouts.app')
@section('pageTitle', 'Promo & Penawaran')

@section('content')
<style>
    .promo-public-page{background:#fbf7e9;color:#425349;padding:42px 0 64px}
    .promo-public-wrap{width:min(1180px,calc(100% - 48px));margin:0 auto}
    .promo-public-back{display:inline-flex;align-items:center;gap:8px;margin:0 0 22px;padding:10px 14px;border:1px solid #d9e4dc;border-radius:12px;background:#fff;color:#17613d;text-decoration:none;font-size:12px;font-weight:800;box-shadow:0 6px 18px rgba(30,75,47,.05);transition:transform .2s,box-shadow .2s,border-color .2s}
    .promo-public-back:hover{transform:translateX(-2px);border-color:#bcd9c5;box-shadow:0 9px 22px rgba(30,75,47,.09)}
    .promo-public-back svg{width:16px;height:16px;flex:0 0 16px}
    .promo-public-header{text-align:center;max-width:780px;margin:0 auto 30px}
    .promo-public-kicker{display:inline-flex;align-items:center;gap:8px;padding:7px 12px;border-radius:999px;background:#eaf6ed;color:#17613d;font-size:10px;font-weight:800;letter-spacing:.07em;text-transform:uppercase}
    .promo-public-kicker:before{content:"";width:7px;height:7px;border-radius:50%;background:#1a7448}
    .promo-public-title{margin:14px 0 0;color:#174a30;font-size:42px;line-height:1.12;font-weight:800;letter-spacing:-.03em}
    .promo-public-subtitle{margin:13px auto 0;color:#718077;font-size:14px;line-height:1.75}
    .promo-public-count{width:max-content;margin:20px auto 0;padding:10px 16px;border:1px solid #dfe7e1;border-radius:14px;background:#fff;color:#738078;font-size:12px}
    .promo-public-count strong{margin-left:7px;color:#17613d}
    .promo-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}
    .promo-card{overflow:hidden;display:flex;flex-direction:column;border:1px solid #dfe8e1;border-radius:24px;background:#fff;box-shadow:0 9px 24px rgba(30,75,47,.045);transition:transform .2s,box-shadow .2s,border-color .2s}
    .promo-card:hover{transform:translateY(-3px);border-color:#bcd9c5;box-shadow:0 14px 30px rgba(30,75,47,.09)}
    .promo-poster{position:relative;height:255px;background:linear-gradient(135deg,#eaf7ed,#f8fbf7);overflow:hidden;display:flex;align-items:center;justify-content:center;color:#86ac92}
    .promo-poster img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .35s}
    .promo-card:hover .promo-poster img{transform:scale(1.025)}
    .promo-poster-empty{text-align:center;color:#78a087;font-size:10px;font-weight:800;letter-spacing:.08em}
    .promo-poster-empty svg{display:block;width:38px;height:38px;margin:0 auto 8px}
    .promo-badge{position:absolute;left:16px;bottom:14px;display:inline-flex;align-items:center;gap:7px;padding:7px 11px;border-radius:999px;background:rgba(255,255,255,.94);color:#17613d;font-size:10px;font-weight:800;box-shadow:0 5px 16px rgba(31,76,48,.08)}
    .promo-badge:before{content:"";width:6px;height:6px;border-radius:50%;background:#1a7448}
    .promo-body{padding:19px;display:flex;flex-direction:column;gap:12px;flex:1}
    .promo-title{margin:0;color:#183d29;font-size:18px;line-height:1.35;font-weight:800}
    .promo-info{display:grid;grid-template-columns:1fr;gap:9px;margin-top:2px}
    .promo-info-row{display:flex;align-items:center;gap:11px;padding:10px 11px;border:1px solid #edf1ee;border-radius:13px;background:#f8faf8}
    .promo-icon{width:32px;height:32px;flex:0 0 32px;border-radius:10px;background:#e9f6ed;color:#1a6d43;display:flex;align-items:center;justify-content:center}
    .promo-icon svg{width:16px;height:16px}
    .promo-info-label{display:block;color:#8b958f;font-size:8.5px;font-weight:800;text-transform:uppercase;letter-spacing:.06em}
    .promo-info-value{display:block;margin-top:2px;color:#334c3d;font-size:11px;font-weight:800}
    .promo-empty{grid-column:1/-1;padding:42px;text-align:center;border:1px dashed #ccd9cf;border-radius:22px;background:rgba(255,255,255,.55);color:#7d8981}
    @media(max-width:1024px){.promo-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:700px){.promo-public-page{padding:32px 0 44px}.promo-public-wrap{width:calc(100% - 24px)}.promo-public-title{font-size:32px}.promo-grid{grid-template-columns:1fr;gap:14px}.promo-poster{height:230px}.promo-card{border-radius:19px}}
</style>

<section class="promo-public-page">
    <div class="promo-public-wrap">
        <a href="{{ route('information') }}" class="promo-public-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6 6-6"/></svg>
            Kembali ke Informasi
        </a>

        <header class="promo-public-header">
            <div class="promo-public-kicker">Promo Klinik</div>
            <h1 class="promo-public-title">Promo &amp; Penawaran</h1>
            <p class="promo-public-subtitle">Temukan informasi promo dan penawaran Klinik Pratama Mitra Sehat. Informasi dapat diperbarui sesuai periode yang berlaku.</p>
            <div class="promo-public-count">Tersedia <strong>{{ $promos->count() }} promo</strong></div>
        </header>

        <div class="promo-grid">
            @forelse($promos as $promo)
                @php
                    $posterUrl = null;
                    if ($promo->poster) {
                        $posterUrl = filter_var($promo->poster, FILTER_VALIDATE_URL)
                            ? $promo->poster
                            : asset($promo->poster);
                    }
                @endphp

                <article class="promo-card">
                    <div class="promo-poster">
                        @if($posterUrl)
                            <img src="{{ $posterUrl }}" alt="{{ $promo->title }}">
                        @else
                            <div class="promo-poster-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                                POSTER PROMO
                            </div>
                        @endif
                        <span class="promo-badge">Promo</span>
                    </div>

                    <div class="promo-body">
                        <h2 class="promo-title">{{ $promo->title }}</h2>

                        <div class="promo-info">
                            <div class="promo-info-row">
                                <span class="promo-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" d="M7 3v3M17 3v3M4 9h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/></svg>
                                </span>
                                <span><span class="promo-info-label">Periode</span><span class="promo-info-value">{{ $promo->period ?: '-' }}</span></span>
                            </div>

                            <div class="promo-info-row">
                                <span class="promo-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linejoin="round" d="M3 12l9-9 8 8-9 9-8-8zM15 8h.01"/></svg>
                                </span>
                                <span><span class="promo-info-label">Harga</span><span class="promo-info-value">{{ $promo->price ?: '-' }}</span></span>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="promo-empty">Belum ada promo yang tersedia.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
