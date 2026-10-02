@extends('layouts.app')
@section('pageTitle', 'Layanan Kesehatan - Klinik Pratama Mitra Sehat')

@section('content')

<style>
    .services-page {
        background: #fbf7e9;
        color: #4f5f54;
    }

    .services-wrap {
        width: min(1180px, calc(100% - 48px));
        margin-inline: auto;
    }

    .services-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .services-kicker {
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

    .services-kicker-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #1a5d3a;
    }

    .services-title {
        margin: 12px 0 0;
        color: #1a5d3a;
        font-size: clamp(34px, 3.5vw, 48px);
        line-height: 1.05;
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .services-subtitle {
        margin: 10px auto 0;
        max-width: 620px;
        color: #69736d;
        font-size: 14px;
        line-height: 1.65;
    }

    .services-summary {
        margin: 24px auto 0;
        width: fit-content;
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 10px 14px;
        border-radius: 14px;
        background: rgba(255, 255, 255, .72);
        border: 1px solid #e2ebe3;
        color: #5d685f;
        font-size: 12px;
    }

    .services-summary strong {
        color: #1a5d3a;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .service-card {
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

    .service-card:hover {
        transform: translateY(-4px);
        border-color: #c8dec9;
        box-shadow: 0 14px 28px rgba(32, 79, 54, .09);
    }

    .service-card::after {
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

    .service-card:hover::after {
        transform: scale(1.12);
    }

    .service-card-top {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .service-icon {
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

    .service-icon svg {
        width: 24px;
        height: 24px;
    }

    .service-number {
        min-width: 34px;
        height: 26px;
        padding: 0 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #f5f8f5;
        border: 1px solid #e7ece8;
        color: #7a847d;
        font-size: 10px;
        font-weight: 700;
    }

    .service-card h3 {
        position: relative;
        z-index: 1;
        margin: 16px 0 0;
        color: #145f3a;
        font-size: 19px;
        line-height: 1.25;
        font-weight: 800;
    }

    .service-card p {
        position: relative;
        z-index: 1;
        margin: 8px 0 0;
        color: #6a746d;
        font-size: 13px;
        line-height: 1.6;
    }

    .service-card-footer {
        position: relative;
        z-index: 1;
        margin-top: auto;
        padding-top: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .service-detail-text {
        color: #1a5d3a;
        font-size: 11px;
        font-weight: 700;
    }

    .service-arrow {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #1a5d3a;
        color: #fff;
        transition: transform .2s ease, background .2s ease;
    }

    .service-arrow svg {
        width: 16px;
        height: 16px;
    }

    .service-card:hover .service-arrow {
        transform: translateX(2px);
        background: #154a2e;
    }

    .services-note {
        margin-top: 26px;
        padding: 18px 20px;
        display: flex;
        align-items: flex-start;
        gap: 13px;
        border-radius: 18px;
        background: #edf7ee;
        border: 1px solid #d8ead9;
    }

    .services-note-icon {
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

    .services-note-icon svg {
        width: 19px;
        height: 19px;
    }

    .services-note strong {
        display: block;
        color: #1a5d3a;
        font-size: 13px;
    }

    .services-note p {
        margin: 4px 0 0;
        color: #667169;
        font-size: 12px;
        line-height: 1.55;
    }

    @media (max-width: 1024px) {
        .services-wrap {
            width: min(100% - 36px, 900px);
        }

        .services-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .services-page {
            padding-top: 28px !important;
            padding-bottom: 38px !important;
        }

        .services-wrap {
            width: calc(100% - 24px);
        }

        .services-header {
            margin-bottom: 22px;
        }

        .services-title {
            font-size: 32px;
        }

        .services-subtitle {
            font-size: 13px;
        }

        .services-summary {
            margin-top: 18px;
        }

        .services-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .service-card {
            min-height: 165px;
            padding: 18px;
            border-radius: 18px;
        }

        .service-card h3 {
            font-size: 18px;
        }

        .service-card p {
            font-size: 12.5px;
        }
    }

    @media (max-width: 430px) {
        .services-wrap {
            width: calc(100% - 18px);
        }

        .services-title {
            font-size: 29px;
        }

        .services-summary {
            width: 100%;
            justify-content: center;
        }

        .services-note {
            padding: 15px;
        }
    }
</style>

<section class="services-page py-10 lg:py-14">
    <div class="services-wrap">

        {{-- Header --}}
        <div class="services-header">

            <div class="services-kicker">
                <span class="services-kicker-dot"></span>
                Pelayanan Klinik
            </div>

            <h1 class="services-title">
                Layanan Kesehatan
            </h1>

            <p class="services-subtitle">
                Pilih layanan kesehatan sesuai kebutuhan Anda.
                Klik salah satu layanan untuk melihat informasi lebih lengkap.
            </p>

            <div class="services-summary">
                <span>Tersedia</span>
                <strong>{{ $services->count() }} layanan</strong>
                <span>di Klinik Pratama Mitra Sehat</span>
            </div>

        </div>


        {{-- Service Cards --}}
        <div class="services-grid">

            @foreach($services as $service)

            <a
                href="{{ route('services.show', $service->slug) }}"
                class="service-card">

                <div class="service-card-top">

                    <div class="service-icon">
                        <x-service-icon :name="$service->icon" />
                    </div>

                    <span class="service-number">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>

                </div>


                <h3>
                    {{ $service->name }}
                </h3>


                <p>
                    {{ $service->description }}
                </p>


                <div class="service-card-footer">

                    <span class="service-detail-text">
                        Lihat Detail Layanan
                    </span>

                    <span class="service-arrow">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7" />
                        </svg>
                    </span>

                </div>

            </a>

            @endforeach

        </div>


        {{-- Informasi --}}
        <div class="services-note">

            <div class="services-note-icon">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    aria-hidden="true">
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke-width="2" />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 11v5M12 8h.01" />
                </svg>
            </div>

            <div>
                <strong>Informasi Layanan</strong>

                <p>
                    Detail jadwal, ketentuan, dan informasi tambahan dapat dilihat
                    pada masing-masing halaman layanan.
                </p>
            </div>

        </div>

    </div>
</section>

@endsection