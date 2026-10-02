@extends('layouts.app')
@section('pageTitle', 'Cabang Klinik')

@section('content')
<style>
    .branch-public-page{background:#fbf7e9;color:#425349;padding:38px 0 64px;min-height:65vh}.branch-public-wrap{width:min(1180px,calc(100% - 48px));margin:0 auto}.branch-back{display:inline-flex;align-items:center;gap:7px;margin-bottom:20px;color:#17613d;text-decoration:none;font-size:11px;font-weight:800}.branch-back:hover{text-decoration:underline}
    .branch-public-header{text-align:center;max-width:780px;margin:0 auto 30px}.branch-public-kicker{display:inline-flex;align-items:center;gap:8px;padding:7px 12px;border-radius:999px;background:#eaf6ed;color:#17613d;font-size:10px;font-weight:800;letter-spacing:.07em;text-transform:uppercase}.branch-public-kicker:before{content:"";width:7px;height:7px;border-radius:50%;background:#1a7448}.branch-public-title{margin:14px 0 0;color:#174a30;font-size:42px;line-height:1.12;font-weight:800;letter-spacing:-.03em}.branch-public-subtitle{margin:13px auto 0;color:#718077;font-size:14px;line-height:1.75}.branch-public-count{width:max-content;margin:20px auto 0;padding:10px 16px;border:1px solid #dfe7e1;border-radius:14px;background:#fff;color:#738078;font-size:12px}.branch-public-count strong{margin-left:7px;color:#17613d}
    .branch-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}.branch-card{overflow:hidden;display:flex;flex-direction:column;border:1px solid #dfe8e1;border-radius:24px;background:#fff;box-shadow:0 9px 24px rgba(30,75,47,.045);transition:transform .2s,box-shadow .2s,border-color .2s}.branch-card:hover{transform:translateY(-3px);border-color:#bcd9c5;box-shadow:0 14px 30px rgba(30,75,47,.09)}.branch-image{height:245px;background:linear-gradient(135deg,#eaf7ed,#f8fbf7);overflow:hidden;display:flex;align-items:center;justify-content:center;color:#86ac92}.branch-image img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .35s}.branch-card:hover .branch-image img{transform:scale(1.025)}.branch-image-empty{text-align:center;color:#78a087;font-size:10px;font-weight:800;letter-spacing:.08em}.branch-image-empty svg{display:block;width:36px;height:36px;margin:0 auto 8px}
    .branch-body{padding:20px 21px 21px;display:flex;flex-direction:column;flex:1}.branch-type{display:flex;align-items:center;gap:7px;color:#557160;font-size:10px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.branch-card h2{margin:12px 0 0;color:#183d29;font-size:19px;line-height:1.35;font-weight:800}.branch-desc{margin:9px 0 0;color:#7b867f;font-size:12px;line-height:1.65}.branch-actions{margin-top:auto;padding-top:18px;display:flex;gap:8px;flex-wrap:wrap}.branch-btn{min-height:38px;display:inline-flex;align-items:center;justify-content:center;padding:0 13px;border-radius:11px;text-decoration:none;font-size:10px;font-weight:800}.branch-btn-primary{background:#17613d;color:#fff}.branch-btn-secondary{background:#edf7ef;color:#17613d}.branch-empty{grid-column:1/-1;padding:42px;text-align:center;border:1px dashed #ccd9cf;border-radius:22px;background:rgba(255,255,255,.55);color:#7d8981}
    @media(max-width:1024px){.branch-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:700px){.branch-public-page{padding:28px 0 44px}.branch-public-wrap{width:calc(100% - 24px)}.branch-public-title{font-size:32px}.branch-grid{grid-template-columns:1fr;gap:14px}.branch-image{height:220px}.branch-card{border-radius:19px}}
</style>

<section class="branch-public-page">
    <div class="branch-public-wrap">
        <a class="branch-back" href="{{ route('information') }}">← Kembali ke Informasi</a>
        <header class="branch-public-header">
            <div class="branch-public-kicker">Cabang Klinik</div>
            <h1 class="branch-public-title">Lokasi Cabang Kami</h1>
            <p class="branch-public-subtitle">Temukan cabang Klinik Pratama Mitra Sehat dan buka lokasi langsung melalui Google Maps.</p>
            <div class="branch-public-count">Tersedia <strong>{{ $branches->count() }} cabang</strong></div>
        </header>

        <div class="branch-grid">
            @forelse($branches as $branch)
                @php
                    $photoUrl = $branch->photo
                        ? (filter_var($branch->photo, FILTER_VALIDATE_URL) ? $branch->photo : asset($branch->photo))
                        : null;
                @endphp
                <article class="branch-card">
                    <div class="branch-image">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $branch->name }}">
                        @else
                            <div class="branch-image-empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>FOTO CABANG</div>
                        @endif
                    </div>
                    <div class="branch-body">
                        <div class="branch-type">⌖ Cabang Klinik</div>
                        <h2>{{ $branch->name }}</h2>
                        <p class="branch-desc">{{ $branch->description ?: 'Informasi cabang Klinik Mitra Sehat.' }}</p>
                        <div class="branch-actions">
                            <a class="branch-btn branch-btn-primary" href="{{ route('information.branches.show', $branch) }}">Lihat Cabang</a>
                            @if($branch->maps_url)
                                <a class="branch-btn branch-btn-secondary" href="{{ $branch->maps_url }}" target="_blank" rel="noopener">Google Maps ↗</a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="branch-empty">Belum ada data cabang.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
