@extends('layouts.app')
@section('pageTitle', 'Kontak & Lokasi')

@section('content')
<style>
    .contact-page {
        min-height: 70vh;
        padding: 42px 0 70px;
        background:
            radial-gradient(circle at top left, rgba(229, 244, 233, .72), transparent 30%),
            linear-gradient(180deg, #fffdf8 0%, #fbf7e9 100%);
        color: #405247;
    }

    .contact-wrap {
        width: min(1180px, calc(100% - 48px));
        margin: 0 auto;
    }

    .contact-hero {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .contact-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #eaf6ed;
        color: #17613d;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .contact-kicker::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #1a7448;
    }

    .contact-title {
        margin: 12px 0 0;
        color: #174a30;
        font-size: clamp(32px, 4vw, 48px);
        line-height: 1.08;
        letter-spacing: -.035em;
        font-weight: 800;
    }

    .contact-subtitle {
        max-width: 700px;
        margin: 12px 0 0;
        color: #718077;
        font-size: 14px;
        line-height: 1.75;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: minmax(0, .92fr) minmax(0, 1.08fr);
        gap: 22px;
        align-items: stretch;
    }

    .contact-card {
        overflow: hidden;
        border: 1px solid #dfe8e1;
        border-radius: 24px;
        background: rgba(255, 255, 255, .94);
        box-shadow: 0 14px 38px rgba(32, 75, 49, .07);
    }

    .contact-info {
        padding: 28px;
    }

    .contact-brand-row {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 24px;
    }

    .contact-brand-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border-radius: 15px;
        background: #eaf6ed;
        color: #17613d;
    }

    .contact-brand-icon svg {
        width: 24px;
        height: 24px;
    }

    .contact-brand-title {
        margin: 0;
        color: #174a30;
        font-size: 21px;
        font-weight: 800;
        line-height: 1.2;
    }

    .contact-brand-note {
        margin: 5px 0 0;
        color: #849087;
        font-size: 11px;
    }

    .contact-list {
        display: grid;
        gap: 10px;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 15px;
        border: 1px solid #e5ebe6;
        border-radius: 16px;
        background: #fbfdfb;
    }

    .contact-item-icon {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border-radius: 12px;
        background: #edf7ef;
        color: #1b6842;
    }

    .contact-item-icon svg {
        width: 19px;
        height: 19px;
    }

    .contact-item-label {
        margin: 0 0 3px;
        color: #77847c;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .contact-item-value,
    .contact-item-value a {
        margin: 0;
        color: #344b3d;
        font-size: 13px;
        line-height: 1.55;
        font-weight: 650;
        text-decoration: none;
    }

    .contact-item-value a:hover {
        color: #17613d;
        text-decoration: underline;
    }

    .contact-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-top: 18px;
    }

    .contact-action {
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 0 16px;
        border: 1px solid #1a6a42;
        border-radius: 14px;
        background: #1a5d3a;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 800;
        transition: .2s ease;
    }

    .contact-action:hover {
        transform: translateY(-1px);
        background: #14492f;
        box-shadow: 0 10px 22px rgba(23, 97, 61, .18);
    }

    .contact-action.secondary {
        background: #fff;
        color: #17613d;
        border-color: #cfe1d4;
    }

    .contact-action.secondary:hover {
        background: #eff8f1;
    }

    .contact-action svg {
        width: 18px;
        height: 18px;
        flex: 0 0 auto;
    }

    .social-section {
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid #edf0ed;
    }

    .social-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 11px;
    }

    .social-title {
        margin: 0;
        color: #264d37;
        font-size: 13px;
        font-weight: 800;
    }

    .social-note {
        color: #87938c;
        font-size: 10px;
    }

    .social-links {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .social-link {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
        padding: 12px 13px;
        border: 1px solid #e0e8e2;
        border-radius: 15px;
        background: #fff;
        color: #32493b;
        text-decoration: none;
        transition: .2s ease;
    }

    .social-link:hover {
        transform: translateY(-2px);
        border-color: #bcd8c4;
        box-shadow: 0 9px 22px rgba(31, 76, 48, .08);
    }

    .social-logo {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border-radius: 12px;
    }

    .social-logo.instagram {
        color: #b12b76;
        background: #fff0f7;
    }

    .social-logo.tiktok {
        color: #111827;
        background: #f3f4f6;
    }

    .social-logo svg {
        width: 21px;
        height: 21px;
    }

    .social-text {
        min-width: 0;
    }

    .social-name {
        display: block;
        color: #263f31;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.2;
    }

    .social-handle {
        display: block;
        margin-top: 3px;
        overflow: hidden;
        color: #829087;
        font-size: 9px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .map-card {
        position: relative;
        min-height: 590px;
        background: #eaf6ed;
    }

    .map-frame {
        width: 100%;
        height: 100%;
        min-height: 590px;
        display: block;
        border: 0;
        filter: saturate(.88) contrast(.98);
    }

    .map-overlay {
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 15px;
        border: 1px solid rgba(255, 255, 255, .8);
        border-radius: 17px;
        background: rgba(255, 255, 255, .94);
        box-shadow: 0 12px 30px rgba(25, 70, 44, .14);
        backdrop-filter: blur(10px);
    }

    .map-overlay-copy {
        min-width: 0;
    }

    .map-overlay-label {
        margin: 0 0 3px;
        color: #17613d;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .map-overlay-title {
        margin: 0;
        color: #254333;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.35;
    }

    .map-button {
        min-height: 43px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex: 0 0 auto;
        padding: 0 15px;
        border-radius: 12px;
        background: #17613d;
        color: #fff;
        text-decoration: none;
        font-size: 10px;
        font-weight: 800;
        transition: .2s ease;
    }

    .map-button:hover {
        background: #10482d;
        transform: translateY(-1px);
    }

    .map-button svg {
        width: 16px;
        height: 16px;
    }

    @media (max-width: 980px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }

        .map-card,
        .map-frame {
            min-height: 470px;
        }
    }

    @media (max-width: 700px) {
        .contact-page {
            padding: 30px 0 46px;
        }

        .contact-wrap {
            width: calc(100% - 24px);
        }

        .contact-hero {
            display: block;
            margin-bottom: 20px;
        }

        .contact-info {
            padding: 19px;
        }

        .contact-card {
            border-radius: 19px;
        }

        .contact-actions,
        .social-links {
            grid-template-columns: 1fr;
        }

        .map-card,
        .map-frame {
            min-height: 430px;
        }

        .map-overlay {
            align-items: stretch;
            flex-direction: column;
        }

        .map-button {
            width: 100%;
        }
    }
</style>

<section class="contact-page">
    <div class="contact-wrap">
        <header class="contact-hero">
            <div>
                <div class="contact-kicker">Kontak Klinik</div>
                <h1 class="contact-title">Kontak &amp; Lokasi</h1>
                <p class="contact-subtitle">
                    Temukan Klinik Pratama Mitra Sehat, hubungi kami melalui WhatsApp atau telepon,
                    dan ikuti informasi terbaru melalui Instagram dan TikTok.
                </p>
            </div>
        </header>

        <div class="contact-grid">
            <section class="contact-card contact-info">
                <div class="contact-brand-row">
                    <div class="contact-brand-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.1 7-12a7 7 0 1 0-14 0c0 6.9 7 12 7 12Z"/>
                            <circle cx="12" cy="9" r="2.4" stroke-width="1.8"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="contact-brand-title">Klinik Pratama Mitra Sehat</h2>
                        <p class="contact-brand-note">Sukoharjo, Jawa Tengah</p>
                    </div>
                </div>

                <div class="contact-list">
                    <div class="contact-item">
                        <div class="contact-item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.1 7-12a7 7 0 1 0-14 0c0 6.9 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.3" stroke-width="1.8"/>
                            </svg>
                        </div>
                        <div>
                            <p class="contact-item-label">Alamat</p>
                            <p class="contact-item-value">
                                <a href="https://maps.app.goo.gl/tMDz62R41JstiK9k7" target="_blank" rel="noopener noreferrer">
                                    Jl. Veteran No.70, Ngabeyan, Jetis, Kec. Sukoharjo, Kabupaten Sukoharjo, Jawa Tengah 57511
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M20 11.4a8 8 0 0 1-11.8 7L4 20l1.6-4.1A8 8 0 1 1 20 11.4Z"/>
                                <path stroke-width="1.8" stroke-linecap="round" d="M9 8.5c.4 2 2.3 3.9 4.3 4.4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="contact-item-label">WhatsApp</p>
                            <p class="contact-item-value">
                                <a href="https://wa.me/6281311709726" target="_blank" rel="noopener noreferrer">0813-1170-9726 (Hanya Teks)</a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M7.4 3.5h2.4l1.2 4-1.8 1.5a14.5 14.5 0 0 0 5.8 5.8l1.5-1.8 4 1.2v2.4c0 1.1-.9 2-2 2A15.5 15.5 0 0 1 5.4 5.5c0-1.1.9-2 2-2Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="contact-item-label">Telepon</p>
                            <p class="contact-item-value"><a href="tel:0271592374">0271-592374</a></p>
                        </div>
                    </div>
                </div>

                <div class="contact-actions">
                    <a class="contact-action" href="https://wa.me/6281311709726" target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M20 11.4a8 8 0 0 1-11.8 7L4 20l1.6-4.1A8 8 0 1 1 20 11.4Z"/>
                            <path stroke-width="1.8" stroke-linecap="round" d="M9 8.5c.4 2 2.3 3.9 4.3 4.4"/>
                        </svg>
                        Chat WhatsApp
                    </a>
                    <a class="contact-action secondary" href="tel:0271592374">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M7.4 3.5h2.4l1.2 4-1.8 1.5a14.5 14.5 0 0 0 5.8 5.8l1.5-1.8 4 1.2v2.4c0 1.1-.9 2-2 2A15.5 15.5 0 0 1 5.4 5.5c0-1.1.9-2 2-2Z"/>
                        </svg>
                        Telepon Klinik
                    </a>
                </div>

                <div class="social-section">
                    <div class="social-head">
                        <h3 class="social-title">Sosial Media</h3>
                        <span class="social-note">Klik untuk membuka</span>
                    </div>

                    <div class="social-links">
                        <a class="social-link" href="https://www.instagram.com/klinikmitrasehat24/" target="_blank" rel="noopener noreferrer" aria-label="Buka Instagram Klinik Mitra Sehat">
                            <span class="social-logo instagram" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke-width="1.8"/>
                                    <circle cx="12" cy="12" r="4" stroke-width="1.8"/>
                                    <circle cx="17.5" cy="6.7" r="1" fill="currentColor" stroke="none"/>
                                </svg>
                            </span>
                            <span class="social-text">
                                <span class="social-name">Instagram</span>
                                <span class="social-handle">@klinikmitrasehat24</span>
                            </span>
                        </a>

                        <a class="social-link" href="https://www.tiktok.com/@klinikmitrasehat24" target="_blank" rel="noopener noreferrer" aria-label="Buka TikTok Klinik Mitra Sehat">
                            <span class="social-logo tiktok" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M15.8 3.3c.5 2.1 1.7 3.4 3.8 4v3.1a8.2 8.2 0 0 1-3.8-1.2v5.7a6 6 0 1 1-5.2-5.9v3.2a2.9 2.9 0 1 0 2 2.7V3.3h3.2Z"/>
                                </svg>
                            </span>
                            <span class="social-text">
                                <span class="social-name">TikTok</span>
                                <span class="social-handle">@klinikmitrasehat24</span>
                            </span>
                        </a>
                    </div>
                </div>
            </section>

            <section class="contact-card map-card" aria-label="Lokasi Klinik Pratama Mitra Sehat">
                <iframe
                    class="map-frame"
                    title="Peta lokasi Klinik Pratama Mitra Sehat"
                    src="https://www.google.com/maps?q=Jl.%20Veteran%20No.70%2C%20Ngabeyan%2C%20Jetis%2C%20Kec.%20Sukoharjo%2C%20Kabupaten%20Sukoharjo%2C%20Jawa%20Tengah%2057511&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>

                <div class="map-overlay">
                    <div class="map-overlay-copy">
                        <p class="map-overlay-label">Google Maps</p>
                        <p class="map-overlay-title">Klinik Pratama Mitra Sehat</p>
                    </div>
                    <a class="map-button" href="https://maps.app.goo.gl/tMDz62R41JstiK9k7" target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.1 7-12a7 7 0 1 0-14 0c0 6.9 7 12 7 12Z"/>
                            <circle cx="12" cy="9" r="2.3" stroke-width="1.8"/>
                        </svg>
                        Buka di Google Maps
                    </a>
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
