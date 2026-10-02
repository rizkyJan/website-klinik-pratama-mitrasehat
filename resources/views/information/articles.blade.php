@extends('layouts.app')
@section('pageTitle', 'Artikel Kesehatan')

@section('content')
<style>
    .article-public-page{background:#fbf7e9;color:#425349;padding:48px 0 64px}
    .article-public-wrap{width:min(1180px,calc(100% - 48px));margin:0 auto}
    .article-public-back{display:inline-flex;align-items:center;gap:8px;margin:0 0 22px;padding:10px 14px;border:1px solid #d9e4dc;border-radius:12px;background:#fff;color:#17613d;text-decoration:none;font-size:12px;font-weight:800;box-shadow:0 6px 18px rgba(30,75,47,.05);transition:transform .2s,box-shadow .2s,border-color .2s}
    .article-public-back:hover{transform:translateX(-2px);border-color:#bcd9c5;box-shadow:0 9px 22px rgba(30,75,47,.09)}
    .article-public-back svg{width:16px;height:16px;flex:0 0 16px}
    .article-public-header{text-align:center;max-width:760px;margin:0 auto 32px}
    .article-public-kicker{display:inline-flex;align-items:center;gap:8px;color:#1a5d3a;font-size:11px;font-weight:800;letter-spacing:.09em;text-transform:uppercase}
    .article-public-kicker span{width:7px;height:7px;border-radius:50%;background:#1a5d3a}
    .article-public-title{margin:10px 0 0;color:#174a30;font-size:42px;line-height:1.12;font-weight:800;letter-spacing:-.03em}
    .article-public-subtitle{margin:13px auto 0;color:#718077;font-size:14px;line-height:1.75}
    .article-public-count{width:max-content;margin:20px auto 0;padding:10px 16px;border:1px solid #dfe7e1;border-radius:14px;background:#fff;color:#738078;font-size:12px}
    .article-public-count strong{margin-left:7px;color:#17613d}
    .article-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}
    .article-card{min-width:0;overflow:hidden;display:flex;flex-direction:column;border:1px solid #dfe8e1;border-radius:24px;background:#fff;text-decoration:none;box-shadow:0 9px 24px rgba(30,75,47,.045);transition:transform .2s,box-shadow .2s,border-color .2s}
    .article-card:hover{transform:translateY(-3px);border-color:#bcd9c5;box-shadow:0 14px 30px rgba(30,75,47,.09)}
    .article-image{position:relative;height:250px;background:linear-gradient(135deg,#eaf7ed,#f8fbf7);overflow:hidden;display:flex;align-items:center;justify-content:center;color:#86ac92}
    .article-image img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .35s}
    .article-card:hover .article-image img{transform:scale(1.025)}
    .article-image-empty{text-align:center;color:#78a087;font-size:10px;font-weight:800;letter-spacing:.08em}
    .article-image-empty svg{display:block;width:36px;height:36px;margin:0 auto 8px}
    .article-category{position:absolute;left:17px;bottom:15px;display:inline-flex;align-items:center;gap:7px;padding:7px 11px;border-radius:999px;background:rgba(255,255,255,.92);color:#17613d;font-size:10px;font-weight:800;box-shadow:0 5px 16px rgba(31,76,48,.08)}
    .article-category:before{content:"";width:6px;height:6px;border-radius:50%;background:#1a7448}
    .article-body{padding:20px 21px 21px;display:flex;flex-direction:column;flex:1}
    .article-type{display:flex;align-items:center;gap:7px;color:#557160;font-size:10px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}
    .article-type svg{width:14px;height:14px;color:#1b7046}
    .article-card h2{margin:13px 0 0;color:#183d29;font-size:18px;line-height:1.35;font-weight:800}
    .article-excerpt{margin:9px 0 0;color:#7b867f;font-size:12px;line-height:1.65}
    .article-footer{margin-top:auto;padding-top:20px;display:flex;align-items:center;justify-content:space-between;gap:12px;color:#17613d;font-size:11px;font-weight:800}
    .article-arrow{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#edf7ef;color:#17613d;transition:background .2s,color .2s,transform .2s}
    .article-card:hover .article-arrow{background:#17613d;color:#fff;transform:translateX(2px)}
    .article-arrow svg{width:16px;height:16px}
    .article-empty{grid-column:1/-1;padding:42px;text-align:center;border:1px dashed #ccd9cf;border-radius:22px;background:rgba(255,255,255,.55);color:#7d8981}
    @media(max-width:1024px){.article-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:700px){.article-public-page{padding:34px 0 44px}.article-public-wrap{width:calc(100% - 24px)}.article-public-title{font-size:32px}.article-grid{grid-template-columns:1fr;gap:14px}.article-image{height:215px}.article-card{border-radius:19px}}
</style>

<section class="article-public-page">
    <div class="article-public-wrap">
        <a href="{{ route('information') }}" class="article-public-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6 6-6"/></svg>
            Kembali ke Informasi
        </a>

        <header class="article-public-header">
            <div class="article-public-kicker"><span></span>Informasi Kesehatan</div>
            <h1 class="article-public-title">Artikel & Edukasi</h1>
            <p class="article-public-subtitle">Baca informasi kesehatan, edukasi layanan, dan kegiatan Klinik Pratama Mitra Sehat.</p>
            <div class="article-public-count">Tersedia <strong>{{ $articles->count() }} artikel</strong></div>
        </header>

        <div class="article-grid">
            @forelse($articles as $article)
                @php
                    $photoUrl = null;
                    if ($article->photo) {
                        $photoUrl = filter_var($article->photo, FILTER_VALIDATE_URL)
                            ? $article->photo
                            : asset($article->photo);
                    }
                @endphp
                <a class="article-card" href="{{ route('articles.show', $article) }}">
                    <div class="article-image">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $article->title }}">
                        @else
                            <div class="article-image-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                                FOTO
                            </div>
                        @endif
                        <span class="article-category">{{ $article->category ?: 'Artikel' }}</span>
                    </div>
                    <div class="article-body">
                        <div class="article-type">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M6 3h9l3 3v15H6zM15 3v4h4M9 11h6M9 15h6"/></svg>
                            Artikel Kesehatan
                        </div>
                        <h2>{{ $article->title }}</h2>
                        <p class="article-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 120) }}</p>
                        <div class="article-footer">
                            <span>Baca selengkapnya</span>
                            <span class="article-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="article-empty">Belum ada artikel yang tersedia.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
