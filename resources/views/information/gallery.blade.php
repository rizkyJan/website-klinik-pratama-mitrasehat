@extends('layouts.app')
@section('pageTitle', 'Galeri Klinik')

@section('content')
<style>
    .gallery-public-page{background:#fbf7e9;color:#425349;padding:42px 0 64px;min-height:65vh}
    .gallery-public-wrap{width:min(1180px,calc(100% - 48px));margin:0 auto}
    .gallery-public-back{display:inline-flex;align-items:center;gap:8px;margin:0 0 22px;padding:10px 14px;border:1px solid #d9e4dc;border-radius:12px;background:#fff;color:#17613d;text-decoration:none;font-size:12px;font-weight:800;box-shadow:0 6px 18px rgba(30,75,47,.05);transition:transform .2s,box-shadow .2s,border-color .2s}
    .gallery-public-back:hover{transform:translateX(-2px);border-color:#bcd9c5;box-shadow:0 9px 22px rgba(30,75,47,.09)}
    .gallery-public-back svg{width:16px;height:16px;flex:0 0 16px}
    .gallery-public-header{text-align:center;max-width:790px;margin:0 auto 28px}
    .gallery-public-kicker{display:inline-flex;align-items:center;gap:8px;padding:7px 12px;border-radius:999px;background:#eaf6ed;color:#17613d;font-size:10px;font-weight:800;letter-spacing:.07em;text-transform:uppercase}
    .gallery-public-kicker:before{content:"";width:7px;height:7px;border-radius:50%;background:#1a7448}
    .gallery-public-title{margin:14px 0 0;color:#174a30;font-size:42px;line-height:1.12;font-weight:800;letter-spacing:-.03em}
    .gallery-public-subtitle{margin:13px auto 0;color:#718077;font-size:14px;line-height:1.75}
    .gallery-public-count{width:max-content;margin:20px auto 0;padding:10px 16px;border:1px solid #dfe7e1;border-radius:14px;background:#fff;color:#738078;font-size:12px}
    .gallery-public-count strong{margin-left:7px;color:#17613d}
    .gallery-filter{display:flex;justify-content:center;flex-wrap:wrap;gap:8px;margin:0 auto 24px}
    .gallery-filter button{min-height:36px;padding:0 13px;border:1px solid #dce6de;border-radius:999px;background:#fff;color:#607067;font:inherit;font-size:10px;font-weight:800;cursor:pointer;transition:.2s}
    .gallery-filter button:hover,.gallery-filter button.active{border-color:#1a6a42;background:#1a5d3a;color:#fff;box-shadow:0 6px 14px rgba(25,91,56,.12)}
    .gallery-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
    .gallery-card{overflow:hidden;border:1px solid #dfe8e1;border-radius:22px;background:#fff;box-shadow:0 9px 24px rgba(30,75,47,.045);transition:transform .2s,box-shadow .2s,border-color .2s;cursor:pointer}
    .gallery-card:hover{transform:translateY(-3px);border-color:#bcd9c5;box-shadow:0 14px 30px rgba(30,75,47,.09)}
    .gallery-image{position:relative;aspect-ratio:4/3;background:linear-gradient(135deg,#eaf7ed,#f8fbf7);overflow:hidden;display:flex;align-items:center;justify-content:center;color:#86ac92}
    .gallery-image img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .35s}
    .gallery-card:hover .gallery-image img{transform:scale(1.035)}
    .gallery-image-empty{text-align:center;color:#78a087;font-size:10px;font-weight:800;letter-spacing:.08em}
    .gallery-image-empty svg{display:block;width:38px;height:38px;margin:0 auto 8px}
    .gallery-badge{position:absolute;left:14px;bottom:13px;display:inline-flex;align-items:center;gap:7px;padding:7px 11px;border-radius:999px;background:rgba(255,255,255,.94);color:#17613d;font-size:10px;font-weight:800;box-shadow:0 5px 16px rgba(31,76,48,.08)}
    .gallery-badge:before{content:"";width:6px;height:6px;border-radius:50%;background:#1a7448}
    .gallery-body{padding:14px 16px 16px}
    .gallery-caption{margin:0;color:#334c3d;font-size:12px;line-height:1.6;font-weight:650}
    .gallery-caption-empty{margin:0;color:#919b95;font-size:11px;font-style:italic}
    .gallery-empty{grid-column:1/-1;padding:42px;text-align:center;border:1px dashed #ccd9cf;border-radius:22px;background:rgba(255,255,255,.55);color:#7d8981}
    .gallery-card.is-hidden{display:none}
    .gallery-lightbox[hidden]{display:none!important}
    .gallery-lightbox{position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;padding:22px}
    .gallery-lightbox-backdrop{position:absolute;inset:0;background:rgba(8,24,15,.82);backdrop-filter:blur(4px)}
    .gallery-lightbox-dialog{position:relative;width:min(980px,100%);max-height:calc(100vh - 44px);overflow:auto;border-radius:22px;background:#fff;box-shadow:0 30px 80px rgba(0,0,0,.35)}
    .gallery-lightbox-photo{background:#102a1d;display:flex;align-items:center;justify-content:center;min-height:360px}
    .gallery-lightbox-photo img{display:block;max-width:100%;max-height:72vh;object-fit:contain}
    .gallery-lightbox-info{padding:17px 20px 20px}
    .gallery-lightbox-category{display:inline-flex;padding:6px 10px;border-radius:999px;background:#edf7ee;color:#17613d;font-size:9px;font-weight:800}
    .gallery-lightbox-caption{margin:10px 0 0;color:#425349;font-size:12px;line-height:1.65}
    .gallery-lightbox-close{position:absolute;right:14px;top:14px;z-index:2;width:40px;height:40px;border:1px solid rgba(255,255,255,.55);border-radius:12px;background:rgba(20,46,30,.78);color:#fff;font-size:25px;line-height:1;cursor:pointer}
    body.gallery-lightbox-lock{overflow:hidden}
    @media(max-width:1024px){.gallery-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:700px){.gallery-public-page{padding:32px 0 44px}.gallery-public-wrap{width:calc(100% - 24px)}.gallery-public-title{font-size:32px}.gallery-grid{grid-template-columns:1fr;gap:14px}.gallery-card{border-radius:19px}.gallery-lightbox{padding:8px}.gallery-lightbox-dialog{max-height:calc(100vh - 16px);border-radius:17px}.gallery-lightbox-photo{min-height:260px}}
</style>

<section class="gallery-public-page">
    <div class="gallery-public-wrap">
        <a href="{{ route('information') }}" class="gallery-public-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6 6-6"/></svg>
            Kembali ke Informasi
        </a>

        <header class="gallery-public-header">
            <div class="gallery-public-kicker">Galeri Klinik</div>
            <h1 class="gallery-public-title">Momen &amp; Dokumentasi</h1>
            <p class="gallery-public-subtitle">Lihat dokumentasi pelayanan, fasilitas, kegiatan kesehatan, dan berbagai momen Klinik Pratama Mitra Sehat.</p>
            <div class="gallery-public-count">Tersedia <strong>{{ $galleries->count() }} foto</strong></div>
        </header>

        @php
            $categories = $galleries->pluck('category')->filter()->unique()->values();
        @endphp

        @if($categories->isNotEmpty())
            <div class="gallery-filter" aria-label="Filter kategori galeri">
                <button type="button" class="active" data-gallery-filter="all">Semua</button>
                @foreach($categories as $category)
                    <button type="button" data-gallery-filter="{{ \Illuminate\Support\Str::slug($category) }}">{{ $category }}</button>
                @endforeach
            </div>
        @endif

        <div class="gallery-grid" id="galleryGrid">
            @forelse($galleries as $gallery)
                @php
                    $photoUrl = null;
                    if ($gallery->photo) {
                        $photoUrl = filter_var($gallery->photo, FILTER_VALIDATE_URL)
                            ? $gallery->photo
                            : asset($gallery->photo);
                    }
                    $categorySlug = \Illuminate\Support\Str::slug($gallery->category ?: 'lainnya');
                @endphp

                <article
                    class="gallery-card"
                    data-gallery-card
                    data-category="{{ $categorySlug }}"
                    data-photo="{{ $photoUrl }}"
                    data-title="{{ $gallery->category ?: 'Galeri' }}"
                    data-caption="{{ $gallery->caption ?: '' }}"
                    tabindex="0"
                    role="button"
                    aria-label="Lihat foto {{ $gallery->category ?: 'galeri' }}"
                >
                    <div class="gallery-image">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $gallery->caption ?: $gallery->category }}" loading="lazy">
                        @else
                            <div class="gallery-image-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                                FOTO GALERI
                            </div>
                        @endif
                        <span class="gallery-badge">{{ $gallery->category ?: 'Galeri' }}</span>
                    </div>

                    <div class="gallery-body">
                        @if($gallery->caption)
                            <p class="gallery-caption">{{ $gallery->caption }}</p>
                        @else
                            <p class="gallery-caption-empty">Dokumentasi Klinik Pratama Mitra Sehat</p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="gallery-empty">Belum ada foto galeri yang tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<div class="gallery-lightbox" id="galleryLightbox" hidden>
    <div class="gallery-lightbox-backdrop" data-gallery-close></div>
    <section class="gallery-lightbox-dialog" role="dialog" aria-modal="true" aria-label="Preview foto galeri">
        <button type="button" class="gallery-lightbox-close" data-gallery-close aria-label="Tutup">&times;</button>
        <div class="gallery-lightbox-photo">
            <img id="galleryLightboxImage" src="" alt="Preview foto galeri">
        </div>
        <div class="gallery-lightbox-info">
            <span class="gallery-lightbox-category" id="galleryLightboxCategory">Galeri</span>
            <p class="gallery-lightbox-caption" id="galleryLightboxCaption"></p>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('[data-gallery-filter]');
        const cards = document.querySelectorAll('[data-gallery-card]');
        const lightbox = document.getElementById('galleryLightbox');
        const lightboxImage = document.getElementById('galleryLightboxImage');
        const lightboxCategory = document.getElementById('galleryLightboxCategory');
        const lightboxCaption = document.getElementById('galleryLightboxCaption');

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const selected = this.getAttribute('data-gallery-filter');

                filterButtons.forEach(function (item) { item.classList.remove('active'); });
                this.classList.add('active');

                cards.forEach(function (card) {
                    const show = selected === 'all' || card.getAttribute('data-category') === selected;
                    card.classList.toggle('is-hidden', !show);
                });
            });
        });

        function openLightbox(card) {
            const photo = card.getAttribute('data-photo');
            if (!photo) return;

            lightboxImage.src = photo;
            lightboxImage.alt = card.getAttribute('data-caption') || card.getAttribute('data-title') || 'Foto galeri';
            lightboxCategory.textContent = card.getAttribute('data-title') || 'Galeri';
            lightboxCaption.textContent = card.getAttribute('data-caption') || 'Dokumentasi Klinik Pratama Mitra Sehat';
            lightbox.hidden = false;
            document.body.classList.add('gallery-lightbox-lock');
        }

        function closeLightbox() {
            lightbox.hidden = true;
            lightboxImage.src = '';
            document.body.classList.remove('gallery-lightbox-lock');
        }

        cards.forEach(function (card) {
            card.addEventListener('click', function () { openLightbox(card); });
            card.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openLightbox(card);
                }
            });
        });

        lightbox.querySelectorAll('[data-gallery-close]').forEach(function (item) {
            item.addEventListener('click', closeLightbox);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !lightbox.hidden) closeLightbox();
        });
    });
</script>
@endsection
