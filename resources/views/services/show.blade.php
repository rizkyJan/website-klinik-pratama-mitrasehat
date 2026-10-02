@extends('layouts.app')
@section('pageTitle', $service->name ?? 'Layanan')

@section('content')

<style>
    .service-detail-page {
        background: #fbf7e9;
        color: #4f5f54;
    }

    .service-detail-wrap {
        width: min(1120px, calc(100% - 48px));
        margin-inline: auto;
    }

    .service-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1a5d3a;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: gap .2s ease, color .2s ease;
    }

    .service-back:hover {
        gap: 10px;
        color: #154a2e;
    }

    .service-back svg {
        width: 17px;
        height: 17px;
        flex: none;
    }

    .service-hero {
        margin-top: 18px;
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(280px, .8fr);
        gap: 22px;
        align-items: stretch;
    }

    .service-hero-main {
        position: relative;
        overflow: hidden;
        padding: 30px;
        border-radius: 24px;
        border: 1px solid #dce9dd;
        background: linear-gradient(135deg, #e9f6e9 0%, #f8fbf5 100%);
    }

    .service-hero-main::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        right: -55px;
        bottom: -55px;
        border-radius: 50%;
        background: rgba(26, 93, 58, .06);
    }

    .service-badge {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 11px;
        border-radius: 999px;
        background: #ffffff;
        color: #1a5d3a;
        border: 1px solid #dce9dd;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
    }

    .service-badge svg {
        width: 15px;
        height: 15px;
    }

    .service-title {
        position: relative;
        z-index: 1;
        margin: 16px 0 0;
        color: #1a5d3a;
        font-size: clamp(32px, 3.7vw, 48px);
        line-height: 1.05;
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .service-description {
        position: relative;
        z-index: 1;
        margin: 12px 0 0;
        max-width: 640px;
        color: #667168;
        font-size: 15px;
        line-height: 1.7;
    }

    .service-side-card {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 26px;
        border-radius: 24px;
        background: #1a5d3a;
        color: #fff;
        box-shadow: 0 10px 28px rgba(26, 93, 58, .13);
    }

    .service-side-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(255, 255, 255, .12);
    }

    .service-side-icon svg {
        width: 24px;
        height: 24px;
    }

    .service-side-card h2 {
        margin: 16px 0 0;
        font-size: 19px;
        line-height: 1.25;
        font-weight: 800;
    }

    .service-side-card p {
        margin: 8px 0 0;
        color: rgba(255, 255, 255, .82);
        font-size: 12.5px;
        line-height: 1.6;
    }

    .service-content-grid {
        margin-top: 22px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(260px, .36fr);
        gap: 22px;
        align-items: start;
    }

    .service-info-card {
        padding: 26px 28px;
        border-radius: 22px;
        background: #fff;
        border: 1px solid #e2e9e3;
        box-shadow: 0 7px 20px rgba(32, 79, 54, .04);
    }

    .service-info-heading {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 14px;
    }

    .service-info-heading-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #edf7ee;
        color: #1a5d3a;
        flex: none;
    }

    .service-info-heading-icon svg {
        width: 20px;
        height: 20px;
    }

    .service-info-heading h2 {
        margin: 0;
        color: #1a5d3a;
        font-size: 20px;
        font-weight: 800;
    }

    .service-detail-text {
        color: #5f6b62;
        font-size: 13.5px;
        line-height: 1.8;
        white-space: pre-line;
    }

    .service-contact-card {
        padding: 24px;
        border-radius: 22px;
        background: #edf7ee;
        border: 1px solid #d8ead9;
    }

    .service-contact-card h3 {
        margin: 0;
        color: #1a5d3a;
        font-size: 17px;
        font-weight: 800;
    }

    .service-contact-card p {
        margin: 8px 0 0;
        color: #667168;
        font-size: 12.5px;
        line-height: 1.6;
    }

    .service-wa-btn {
        margin-top: 16px;
        min-height: 44px;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 0 16px;
        border-radius: 11px;
        background: #1a5d3a;
        color: #fff;
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 700;
        transition: transform .2s ease, background .2s ease;
    }

    .service-wa-btn:hover {
        transform: translateY(-1px);
        background: #154a2e;
    }

    .service-wa-btn svg {
        width: 18px;
        height: 18px;
        flex: none;
    }

    @media (max-width: 900px) {
        .service-detail-wrap {
            width: min(100% - 36px, 760px);
        }

        .service-hero,
        .service-content-grid {
            grid-template-columns: 1fr;
        }

        .service-side-card {
            min-height: 190px;
        }
    }

    @media (max-width: 767px) {
        .service-detail-page {
            padding-top: 28px !important;
            padding-bottom: 38px !important;
        }

        .service-detail-wrap {
            width: calc(100% - 24px);
        }

        .service-hero-main,
        .service-side-card,
        .service-info-card,
        .service-contact-card {
            border-radius: 18px;
        }

        .service-hero-main {
            padding: 22px 20px;
        }

        .service-title {
            font-size: 32px;
        }

        .service-description {
            font-size: 13.5px;
        }

        .service-info-card {
            padding: 22px 20px;
        }
    }

    @media (max-width: 430px) {
        .service-detail-wrap {
            width: calc(100% - 18px);
        }

        .service-title {
            font-size: 29px;
        }

        .service-side-card {
            padding: 22px 20px;
        }
    }
</style>

<section class="service-detail-page py-10 lg:py-14">
    <div class="service-detail-wrap">

        {{-- Back --}}
        <a
            href="{{ route('services') }}"
            class="service-back">
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                aria-hidden="true">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 18l-6-6 6-6" />
            </svg>

            Kembali ke Layanan
        </a>


        {{-- Hero --}}
        <div class="service-hero">

            <div class="service-hero-main">

                <div class="service-badge">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v12M6 12h12" />
                    </svg>

                    Layanan Klinik
                </div>


                <h1 class="service-title">
                    {{ $service->name }}
                </h1>


                <p class="service-description">
                    {{ $service->description }}
                </p>

            </div>


            <div class="service-side-card">

                <div class="service-side-icon">
                    <x-service-icon :name="$service->icon" />
                </div>

                <h2>
                    Pelayanan Profesional
                </h2>

                <p>
                    Pelayanan diberikan sesuai kebutuhan pasien dan ketentuan
                    yang berlaku di Klinik Pratama Mitra Sehat.
                </p>

            </div>

        </div>


        {{-- Content --}}
        <div class="service-content-grid">

            <div class="service-info-card">

                <div class="service-info-heading">

                    <div class="service-info-heading-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke-width="2" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 11v5M12 8h.01" />
                        </svg>
                    </div>

                    <h2>
                        Informasi Layanan
                    </h2>

                </div>


                <div class="service-detail-text">
                    {{ $service->detail ?? $service->description }}
                </div>

            </div>


            <div class="service-contact-card">

                <h3>
                    Butuh Informasi?
                </h3>

                <p>
                    Hubungi Klinik Pratama Mitra Sehat untuk menanyakan
                    informasi lebih lanjut mengenai layanan ini.
                </p>


                <a
                    href="https://wa.me/6281311709726"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="service-wa-btn">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M21 11.5a8.5 8.5 0 01-12.9 7.3L3 20l1.3-4.9A8.5 8.5 0 1121 11.5z" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M8.5 8.5c.5 2.8 2.2 4.5 5 5" />
                    </svg>

                    Tanyakan via WhatsApp
                </a>

            </div>

        </div>

    </div>
</section>

@endsection