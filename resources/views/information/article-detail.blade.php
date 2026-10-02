@extends('layouts.app')
@section('pageTitle', $article->title ?? 'Artikel')

@section('content')
@php
    $photoUrl = null;
    if ($article->photo) {
        $photoUrl = filter_var($article->photo, FILTER_VALIDATE_URL)
            ? $article->photo
            : asset($article->photo);
    }
@endphp

<style>
    .article-detail-page{background:#fbf7e9;color:#4f5f54;padding:42px 0 64px}
    .article-detail-wrap{width:min(940px,calc(100% - 48px));margin:0 auto}
    .article-detail-back{display:inline-flex;align-items:center;gap:8px;color:#17613d;text-decoration:none;font-size:12px;font-weight:800}
    .article-detail-back svg{width:16px;height:16px}
    .article-detail-hero{margin-top:20px;overflow:hidden;border:1px solid #dfe8e1;border-radius:28px;background:#fff;box-shadow:0 12px 34px rgba(30,75,47,.06)}
    .article-detail-photo{height:420px;background:linear-gradient(135deg,#eaf7ed,#f8fbf7);display:flex;align-items:center;justify-content:center;color:#82a98f;overflow:hidden}
    .article-detail-photo img{width:100%;height:100%;object-fit:cover;display:block}
    .article-detail-placeholder{text-align:center;font-size:11px;font-weight:800;letter-spacing:.07em}
    .article-detail-placeholder svg{width:44px;height:44px;display:block;margin:0 auto 10px}
    .article-detail-head{padding:28px 32px 26px;border-bottom:1px solid #eef2ef}
    .article-detail-category{display:inline-flex;align-items:center;gap:7px;padding:7px 11px;border-radius:999px;background:#edf7ee;color:#17613d;font-size:10px;font-weight:800}
    .article-detail-category:before{content:"";width:6px;height:6px;border-radius:50%;background:#1a7448}
    .article-detail-title{margin:15px 0 0;color:#173d29;font-size:36px;line-height:1.2;font-weight:800;letter-spacing:-.025em}
    .article-detail-meta{margin-top:12px;color:#8a958e;font-size:11px}
    .article-detail-content{padding:30px 32px 34px;color:#53625a;font-size:14px;line-height:1.9}
    .article-detail-content p{margin:0 0 16px}
    .article-detail-footer{margin-top:18px;padding:18px 20px;border:1px solid #dfe8e1;border-radius:18px;background:#fff;display:flex;align-items:center;justify-content:space-between;gap:16px}
    .article-detail-footer strong{display:block;color:#173d29;font-size:12px}.article-detail-footer span{display:block;margin-top:3px;color:#869188;font-size:10px}
    .article-detail-footer a{display:inline-flex;align-items:center;justify-content:center;min-height:40px;padding:0 14px;border-radius:11px;background:#17613d;color:#fff;text-decoration:none;font-size:11px;font-weight:800}
    @media(max-width:700px){.article-detail-page{padding:28px 0 42px}.article-detail-wrap{width:calc(100% - 24px)}.article-detail-hero{border-radius:20px}.article-detail-photo{height:240px}.article-detail-head{padding:22px 19px}.article-detail-title{font-size:28px}.article-detail-content{padding:22px 19px;font-size:13px}.article-detail-footer{align-items:flex-start;flex-direction:column}.article-detail-footer a{width:100%;box-sizing:border-box}}
</style>

<section class="article-detail-page">
    <div class="article-detail-wrap">
        <a class="article-detail-back" href="{{ url('/informasi/artikel') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6 6-6"/></svg>
            Kembali ke Artikel
        </a>

        <article class="article-detail-hero">
            <div class="article-detail-photo">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="{{ $article->title }}">
                @else
                    <div class="article-detail-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                        FOTO ARTIKEL
                    </div>
                @endif
            </div>

            <header class="article-detail-head">
                <span class="article-detail-category">{{ $article->category ?: 'Artikel' }}</span>
                <h1 class="article-detail-title">{{ $article->title }}</h1>
                <div class="article-detail-meta">Artikel kesehatan Klinik Pratama Mitra Sehat</div>
            </header>

            <div class="article-detail-content">
                {!! nl2br(e($article->content)) !!}
            </div>
        </article>

        <div class="article-detail-footer">
            <div>
                <strong>Ingin membaca artikel lainnya?</strong>
                <span>Kembali ke daftar artikel dan pilih informasi kesehatan lain.</span>
            </div>
            <a href="{{ url('/informasi/artikel') }}">Lihat Artikel Lain</a>
        </div>
    </div>
</section>
@endsection
