@extends('layouts.app')
@section('pageTitle', 'Informasi - Klinik Pratama Mitra Sehat')

@section('content')

<style>
    .info-page {
        background: #fbf7e9;
        color: #4f5f54;
    }

    .info-wrap {
        width: min(1180px, calc(100% - 48px));
        margin-inline: auto;
    }

    .info-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .info-kicker {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #e8f5e8;
        color: #1a5d3a;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .info-kicker-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #1a5d3a;
    }

    .info-title {
        margin: 12px 0 0;
        color: #1a5d3a;
        font-size: clamp(34px, 3.5vw, 48px);
        line-height: 1.05;
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .info-subtitle {
        margin: 10px auto 0;
        max-width: 650px;
        color: #69736d;
        font-size: 14px;
        line-height: 1.65;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .info-card {
        position: relative;
        min-height: 185px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding: 22px;
        border-radius: 22px;
        border: 1px solid #e0e9e1;
        background: #fff;
        text-decoration: none;
        box-shadow: 0 7px 18px rgba(32, 79, 54, .045);
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            border-color .2s ease;
    }

    .info-card:hover {
        transform: translateY(-4px);
        border-color: #c8dec9;
        box-shadow: 0 14px 28px rgba(32, 79, 54, .09);
    }

    .info-card::after {
        content: '';
        position: absolute;
        width: 105px;
        height: 105px;
        right: -40px;
        bottom: -40px;
        border-radius: 50%;
        background: #f0f8f0;
        transition: transform .25s ease;
    }

    .info-card:hover::after {
        transform: scale(1.12);
    }

    .info-card-top {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .info-icon {
        width: 48px;
        height: 48px;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: #eaf7ec;
        color: #1a5d3a;
    }

    .info-icon svg {
        width: 24px;
        height: 24px;
    }

    .info-arrow {
        width: 32px;
        height: 32px;
        flex: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f4f8f4;
        color: #1a5d3a;
        border: 1px solid #e5ece6;
        transition: transform .2s ease, background .2s ease, color .2s ease;
    }

    .info-arrow svg {
        width: 16px;
        height: 16px;
    }

    .info-card:hover .info-arrow {
        transform: translateX(2px);
        background: #1a5d3a;
        color: #fff;
    }

    .info-card h3 {
        position: relative;
        z-index: 1;
        margin: 16px 0 0;
        color: #145f3a;
        font-size: 18px;
        line-height: 1.25;
        font-weight: 800;
    }

    .info-card p {
        position: relative;
        z-index: 1;
        margin: 8px 0 0;
        color: #6a746d;
        font-size: 13px;
        line-height: 1.6;
    }

    .info-card-footer {
        position: relative;
        z-index: 1;
        margin-top: auto;
        padding-top: 16px;
        color: #1a5d3a;
        font-size: 11px;
        font-weight: 700;
    }

    .info-note {
        margin-top: 26px;
        padding: 18px 20px;
        display: flex;
        align-items: flex-start;
        gap: 13px;
        border-radius: 18px;
        background: #edf7ee;
        border: 1px solid #d8ead9;
    }

    .info-note-icon {
        width: 38px;
        height: 38px;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff;
        color: #1a5d3a;
    }

    .info-note-icon svg {
        width: 19px;
        height: 19px;
    }

    .info-note strong {
        display: block;
        color: #1a5d3a;
        font-size: 13px;
    }

    .info-note p {
        margin: 4px 0 0;
        color: #667169;
        font-size: 12px;
        line-height: 1.55;
    }

    @media (max-width: 1024px) {
        .info-wrap {
            width: min(100% - 36px, 900px);
        }

        .info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .info-page {
            padding-top: 28px !important;
            padding-bottom: 38px !important;
        }

        .info-wrap {
            width: calc(100% - 24px);
        }

        .info-header {
            margin-bottom: 22px;
        }

        .info-title {
            font-size: 32px;
        }

        .info-subtitle {
            font-size: 13px;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .info-card {
            min-height: 165px;
            padding: 18px;
            border-radius: 18px;
        }

        .info-card h3 {
            font-size: 17px;
        }

        .info-card p {
            font-size: 12.5px;
        }
    }

    @media (max-width: 430px) {
        .info-wrap {
            width: calc(100% - 18px);
        }

        .info-title {
            font-size: 29px;
        }

        .info-note {
            padding: 15px;
        }
    }
</style>

<section class="info-page py-10 lg:py-14">
    <div class="info-wrap">

        {{-- Header --}}
        <div class="info-header">

            <div class="info-kicker">
                <span class="info-kicker-dot"></span>
                Pusat Informasi
            </div>

            <h1 class="info-title">
                Informasi
            </h1>

            <p class="info-subtitle">
                Temukan informasi klinik, layanan, edukasi kesehatan,
                dan kegiatan terbaru Klinik Pratama Mitra Sehat.
            </p>

        </div>


        @php
        $items = [
        [
        'route' => 'information.announcements',
        'title' => 'Pengumuman Klinik',
        'desc' => 'Informasi terbaru, perubahan jadwal, dan pemberitahuan penting dari klinik.',
        'icon' => 'announcement'
        ],
        [
        'route' => 'information.bpjs',
        'title' => 'Pelayanan BPJS',
        'desc' => 'Informasi pelayanan BPJS di klinik.',
        'icon' => 'shield'
        ],
        [
        'route' => 'information.faq',
        'title' => 'FAQ',
        'desc' => 'Pertanyaan yang sering diajukan.',
        'icon' => 'help'
        ],
        [
        'route' => 'information.gallery',
        'title' => 'Galeri',
        'desc' => 'Dokumentasi klinik dan kegiatan.',
        'icon' => 'gallery'
        ],
        [
        'route' => 'information.articles',
        'title' => 'Artikel & Edukasi',
        'desc' => 'Konten kesehatan dan edukasi.',
        'icon' => 'article'
        ],
        [
        'route' => 'information.promo',
        'title' => 'Promo & Penawaran',
        'desc' => 'Promo terbaru klinik.',
        'icon' => 'tag'
        ],
        [
        'route' => 'information.schedule',
        'title' => 'Jadwal Pelayanan',
        'desc' => 'Jam operasional setiap poli.',
        'icon' => 'calendar'
        ],
        [
        'route' => 'information.legal',
        'title' => 'Informasi Legal',
        'desc' => 'Kebijakan privasi dan disclaimer.',
        'icon' => 'document'
        ],
        [
        'route' => 'information.branches',
        'title' => 'Cabang',
        'desc' => 'Lokasi dan informasi cabang Klinik Mitra Sehat.',
        'icon' => 'branch'
        ],
        ];
        @endphp


        {{-- Grid Informasi --}}
        <div class="info-grid">

            @foreach ($items as $item)

            <a
                href="{{ route($item['route']) }}"
                class="info-card">

                <div class="info-card-top">

                    <div class="info-icon">

                        @if($item['icon'] === 'announcement')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 11v2a2 2 0 002 2h1l2 4h3l-1.2-4H14l5 3V6l-5 3H7a2 2 0 00-2 2z" />
                        </svg>

                        @elseif($item['icon'] === 'shield')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 3l7 4v5c0 4.5-2.8 8-7 9-4.2-1-7-4.5-7-9V7l7-4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12l2 2 4-4" />
                        </svg>

                        @elseif($item['icon'] === 'help')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9.8 9a2.4 2.4 0 114.2 1.6c-.9.8-2 1.2-2 2.6M12 17h.01" />
                        </svg>

                        @elseif($item['icon'] === 'gallery')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />
                            <circle cx="9" cy="10" r="1.5" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M5 17l4.5-4 3 2.5 2.5-2 4 3.5" />
                        </svg>

                        @elseif($item['icon'] === 'article')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M6 4h9l3 3v13H6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 10h6M9 14h6M9 18h4" />
                        </svg>

                        @elseif($item['icon'] === 'tag')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M20 13l-7 7-9-9V4h7z" />
                            <circle cx="8.5" cy="8.5" r="1.3" stroke-width="1.8" />
                        </svg>

                        @elseif($item['icon'] === 'calendar')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 3v4M16 3v4M5 9h14M5 5h14v16H5z" />
                        </svg>

                        @elseif($item['icon'] === 'document')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M7 3h7l4 4v14H7z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M14 3v5h5M10 13h5M10 17h5" />
                        </svg>

                        @elseif($item['icon'] === 'branch')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M4 20v-9.5L12 4l8 6.5V20" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 20v-5h8v5M9 10h.01M15 10h.01" />
                        </svg>
                        @endif

                    </div>


                    <span class="info-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5l7 7-7 7" />
                        </svg>
                    </span>

                </div>


                <h3>
                    {{ $item['title'] }}
                </h3>


                <p>
                    {{ $item['desc'] }}
                </p>


                <div class="info-card-footer">
                    Lihat Informasi
                </div>

            </a>

            @endforeach

        </div>


        {{-- Catatan --}}
        <div class="info-note">

            <div class="info-note-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" stroke-width="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11v5M12 8h.01" />
                </svg>
            </div>

            <div>
                <strong>Informasi Klinik</strong>

                <p>
                    Pilih salah satu kategori di atas untuk melihat informasi lebih lengkap.
                </p>
            </div>

        </div>

    </div>
</section>

@endsection