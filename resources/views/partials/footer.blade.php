{{-- Footer --}}
<style>
    .site-footer {
        background: #1a5d3a;
        color: #ffffff;
    }

    .site-footer-inner {
        width: min(1280px, calc(100% - 32px));
        margin-inline: auto;
        padding: 22px 0;
    }

    .site-footer-row {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 28px;
    }

    .site-footer-brand {
        min-width: 0;
    }

    .site-footer-brand-title {
        margin: 0;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.35;
        white-space: nowrap;
    }

    .site-footer-brand-tagline {
        margin: 4px 0 0;
        color: rgba(255, 255, 255, .66);
        font-size: 11px;
        line-height: 1.4;
    }

    .site-footer-contact {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        min-width: 0;
        color: rgba(255, 255, 255, .82);
        font-size: 12px;
    }

    .site-footer-contact-item {
        display: flex;
        align-items: center;
        gap: 7px;
        min-width: 0;
        color: inherit;
        text-decoration: none;
        white-space: nowrap;
    }

    .site-footer-contact-item:hover {
        color: #ffffff;
    }

    .site-footer-contact-icon {
        width: 15px;
        height: 15px;
        min-width: 15px;
        min-height: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: none;
    }

    .site-footer-contact-icon svg {
        width: 15px;
        height: 15px;
        display: block;
    }

    .site-footer-dot {
        color: rgba(255, 255, 255, .32);
    }

    .site-footer-social {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex: none;
    }

    .site-footer-social-link {
        width: 36px;
        height: 36px;
        min-width: 36px;
        min-height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, .22);
        background: rgba(255, 255, 255, .07);
        color: #ffffff;
        text-decoration: none;
        transition: .2s ease;
    }

    .site-footer-social-link:hover {
        background: #ffffff;
        color: #1a5d3a;
        transform: translateY(-2px);
    }

    .site-footer-social-link svg {
        width: 18px;
        height: 18px;
        display: block;
    }

    .site-footer-mobile-divider {
        display: none;
    }

    @media (max-width: 900px) {
        .site-footer-row {
            grid-template-columns: 1fr;
            gap: 16px;
            text-align: center;
        }

        .site-footer-brand-title {
            white-space: normal;
        }

        .site-footer-contact {
            flex-wrap: wrap;
        }

        .site-footer-social {
            justify-content: center;
        }
    }

    @media (max-width: 600px) {
        .site-footer-inner {
            width: calc(100% - 24px);
            padding: 18px 0 20px;
        }

        .site-footer-row {
            gap: 0;
        }

        .site-footer-brand {
            padding-bottom: 14px;
        }

        .site-footer-brand-title {
            font-size: 13px;
        }

        .site-footer-brand-tagline {
            margin-top: 3px;
            font-size: 10.5px;
        }

        .site-footer-mobile-divider {
            display: block;
            width: 100%;
            height: 1px;
            margin: 0 0 14px;
            background: rgba(255, 255, 255, .12);
        }

        .site-footer-contact {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            padding: 0 2px;
            font-size: 11.5px;
        }

        .site-footer-contact-item {
            justify-content: center;
            white-space: normal;
            text-align: center;
        }

        .site-footer-dot {
            display: none;
        }

        .site-footer-social {
            margin-top: 15px;
            gap: 9px;
        }

        .site-footer-social-link {
            width: 34px;
            height: 34px;
            min-width: 34px;
            min-height: 34px;
        }

        .site-footer-social-link svg {
            width: 17px;
            height: 17px;
        }
    }

    @media (max-width: 340px) {
        .site-footer-inner {
            width: calc(100% - 18px);
        }

        .site-footer-brand-title {
            font-size: 12.5px;
        }

        .site-footer-contact {
            font-size: 11px;
        }

        .site-footer-social-link {
            width: 32px;
            height: 32px;
            min-width: 32px;
            min-height: 32px;
        }
    }
</style>

<footer class="site-footer">

    <div class="site-footer-inner">

        <div class="site-footer-row">

            {{-- =========================================================
                 KIRI - BRAND
            ========================================================== --}}
            <div class="site-footer-brand">

                <p class="site-footer-brand-title">
                    © {{ date('Y') }} Klinik Pratama Mitra Sehat
                </p>

                <p class="site-footer-brand-tagline">
                    Mitra Tepat Menuju Sehat
                </p>

            </div>


            <div class="site-footer-mobile-divider"></div>


            {{-- =========================================================
                 TENGAH - KONTAK
            ========================================================== --}}
            <div class="site-footer-contact">

                {{-- Lokasi --}}
                <div class="site-footer-contact-item">

                    <span class="site-footer-contact-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 21s6-5.1 6-11a6 6 0 10-12 0c0 5.9 6 11 6 11z" />
                            <circle
                                cx="12"
                                cy="10"
                                r="2"
                                stroke-width="1.8" />
                        </svg>
                    </span>

                    <span>
                        Jl. Veteran No. 70, Sukoharjo
                    </span>

                </div>


                <span class="site-footer-dot">
                    •
                </span>


                {{-- Telepon --}}
                <a
                    href="tel:081311709726"
                    class="site-footer-contact-item">

                    <span class="site-footer-contact-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M6.6 3.8l2.2-.8 2.2 5-1.7 1.4a14.5 14.5 0 005.3 5.3l1.4-1.7 5 2.2-.8 2.2c-.4 1.2-1.5 2-2.8 2C9.9 19.4 4.6 14.1 4.6 7c0-1.3.8-2.4 2-2.8z" />
                        </svg>
                    </span>

                    <span>
                        0271-592374
                    </span>

                </a>

            </div>



            {{-- =========================================================
                 KANAN - SOCIAL MEDIA
            ========================================================== --}}
            <div class="site-footer-social">

                {{-- Instagram --}}
                {{-- Ganti # dengan URL Instagram klinik jika sudah ada --}}
                <a
                    href="https://www.instagram.com/klinikmitrasehat24/"
                    class="site-footer-social-link"
                    aria-label="Instagram Klinik Pratama Mitra Sehat"
                    title="Instagram">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true">
                        <rect
                            x="3"
                            y="3"
                            width="18"
                            height="18"
                            rx="5"
                            stroke-width="1.8" />
                        <circle
                            cx="12"
                            cy="12"
                            r="4"
                            stroke-width="1.8" />
                        <circle
                            cx="17.5"
                            cy="6.5"
                            r="1"
                            fill="currentColor"
                            stroke="none" />
                    </svg>
                </a>


                {{-- TikTok --}}
                <a
                    href="https://www.tiktok.com/@klinikmitrasehat24?_r=1&_t=ZS-9A2qYTkNw4j"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="site-footer-social-link"
                    aria-label="TikTok Klinik Pratama Mitra Sehat"
                    title="TikTok">
                    <svg
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        aria-hidden="true">
                        <path
                            d="M14.5 3c.4 2.4 1.8 3.8 4.2 4.1v3.2c-1.4 0-2.8-.4-4.1-1.2v6.1c0 3.8-2.7 6.5-6.2 6.5-3.2 0-5.9-2.5-5.9-5.8 0-3.6 2.8-6.2 6.5-6.2.4 0 .8 0 1.1.1v3.3c-.4-.1-.7-.2-1.1-.2-1.8 0-3.2 1.2-3.2 3 0 1.6 1.3 2.8 2.8 2.8 1.9 0 3-1.3 3-3.5V3h2.9z" />
                    </svg>
                </a>


                {{-- WhatsApp --}}
                <a
                    href="https://wa.me/6281311709726"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="site-footer-social-link"
                    aria-label="WhatsApp Klinik Pratama Mitra Sehat"
                    title="WhatsApp">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M20.5 11.7a8.5 8.5 0 01-12.7 7.4L3 20.4l1.3-4.7A8.5 8.5 0 1120.5 11.7z" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M8.3 8.2c.5 2.9 2.3 4.7 5.2 5.3" />
                    </svg>
                </a>

            </div>

        </div>

    </div>

</footer>