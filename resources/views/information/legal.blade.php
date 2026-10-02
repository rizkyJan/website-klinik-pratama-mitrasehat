@extends('layouts.app')
@section('pageTitle', 'Informasi Legal - Klinik Pratama Mitra Sehat')

@section('content')

<style>
    .legal-page {
        background: #fbf7e9;
        color: #4f5f54;
    }

    .legal-wrap {
        width: min(1120px, calc(100% - 48px));
        margin-inline: auto;
    }

    .legal-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 20px;
        color: #1a5d3a;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        transition: gap .2s ease, color .2s ease;
    }

    .legal-back:hover {
        gap: 10px;
        color: #154a2e;
    }

    .legal-back svg {
        width: 17px;
        height: 17px;
        flex: none;
    }

    .legal-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .legal-kicker {
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

    .legal-kicker-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #1a5d3a;
    }

    .legal-title {
        margin: 12px 0 0;
        color: #1a5d3a;
        font-size: clamp(34px, 3.6vw, 48px);
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .legal-subtitle {
        margin: 10px auto 0;
        max-width: 670px;
        color: #69736d;
        font-size: 14px;
        line-height: 1.65;
    }

    .legal-summary {
        margin: 18px auto 0;
        width: fit-content;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border-radius: 13px;
        background: rgba(255, 255, 255, .78);
        border: 1px solid #e3ebe4;
        color: #667168;
        font-size: 11.5px;
    }

    .legal-summary strong {
        color: #1a5d3a;
    }

    .legal-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .legal-card {
        position: relative;
        overflow: hidden;
        min-height: 300px;
        display: flex;
        flex-direction: column;
        padding: 22px 20px 20px;
        border-radius: 22px;
        background: #ffffff;
        border: 1px solid #e0e9e1;
        box-shadow: 0 7px 18px rgba(32, 79, 54, .045);
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            border-color .2s ease;
    }

    .legal-card:hover {
        transform: translateY(-4px);
        border-color: #c9dfcb;
        box-shadow: 0 14px 28px rgba(32, 79, 54, .09);
    }

    .legal-card::before {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        top: -62px;
        right: -48px;
        border-radius: 50%;
        background: #f0f8f0;
    }

    .legal-icon {
        position: relative;
        z-index: 1;
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        border-radius: 14px;
        background: #eaf7ec;
        color: #1a5d3a;
    }

    .legal-icon svg {
        width: 23px;
        height: 23px;
    }

    .legal-card-title {
        position: relative;
        z-index: 1;
        margin: 0;
        color: #1a5d3a;
        font-size: 17px;
        line-height: 1.4;
        font-weight: 800;
    }

    .legal-card-desc {
        position: relative;
        z-index: 1;
        margin: 9px 0 0;
        color: #68736b;
        font-size: 12.5px;
        line-height: 1.7;
    }

    .legal-card-divider {
        position: relative;
        z-index: 1;
        width: 42px;
        height: 2px;
        margin: 16px 0;
        border-radius: 999px;
        background: #dcecdf;
    }

    .legal-button {
        position: relative;
        z-index: 1;
        width: 100%;
        min-height: 42px;
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 0;
        border-radius: 11px;
        background: #1a5d3a;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition:
            transform .2s ease,
            background .2s ease,
            box-shadow .2s ease;
    }

    .legal-button:hover {
        transform: translateY(-1px);
        background: #154a2e;
        box-shadow: 0 7px 16px rgba(26, 93, 58, .14);
    }

    .legal-button svg {
        width: 15px;
        height: 15px;
    }

    .legal-note {
        margin-top: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 17px 18px;
        border-radius: 18px;
        background: #eaf7ec;
        border: 1px solid #d4e8d7;
    }

    .legal-note-icon {
        width: 38px;
        height: 38px;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #ffffff;
        color: #1a5d3a;
    }

    .legal-note-icon svg {
        width: 19px;
        height: 19px;
    }

    .legal-note strong {
        display: block;
        color: #1a5d3a;
        font-size: 13px;
        font-weight: 800;
    }

    .legal-note p {
        margin: 4px 0 0;
        color: #667168;
        font-size: 12px;
        line-height: 1.55;
    }

    @media (max-width: 1024px) {
        .legal-wrap {
            width: min(100% - 36px, 900px);
        }

        .legal-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .legal-card:last-child {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 767px) {
        .legal-page {
            padding-top: 28px !important;
            padding-bottom: 38px !important;
        }

        .legal-wrap {
            width: calc(100% - 24px);
        }

        .legal-header {
            margin-bottom: 22px;
        }

        .legal-title {
            font-size: 32px;
        }

        .legal-subtitle {
            font-size: 13px;
        }

        .legal-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .legal-card,
        .legal-card:last-child {
            grid-column: auto;
        }

        .legal-card {
            min-height: 0;
            border-radius: 18px;
        }
    }

    @media (max-width: 430px) {
        .legal-wrap {
            width: calc(100% - 18px);
        }

        .legal-title {
            font-size: 29px;
        }

        .legal-card {
            padding: 19px 17px 17px;
        }
    }
</style>


<section class="legal-page py-10 lg:py-14">

    <div class="legal-wrap">

        {{-- Kembali ke Informasi --}}
        <a
            href="{{ route('information') }}"
            class="legal-back">
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

            Kembali ke Informasi
        </a>


        {{-- Header --}}
        <div class="legal-header">

            <div class="legal-kicker">
                <span class="legal-kicker-dot"></span>
                Transparansi Website
            </div>


            <h1 class="legal-title">
                Informasi Legal
            </h1>


            <p class="legal-subtitle">
                Informasi pendukung untuk keamanan, transparansi,
                dan penggunaan website Klinik Pratama Mitra Sehat.
            </p>


            <div class="legal-summary">
                <span>Tersedia</span>

                <strong>
                    3 informasi legal
                </strong>
            </div>

        </div>


        @php
        $legal = [
        [
        'title' => 'Kebijakan Privasi',
        'desc' => 'Penjelasan mengenai pengelolaan data pengunjung dan pendaftar.',
        'icon' => 'privacy',
        ],
        [
        'title' => 'Syarat & Ketentuan',
        'desc' => 'Aturan penggunaan website dan layanan informasi.',
        'icon' => 'terms',
        ],
        [
        'title' => 'Disclaimer Kesehatan',
        'desc' => 'Informasi website tidak menggantikan konsultasi langsung dengan tenaga kesehatan.',
        'icon' => 'health',
        ],
        ];
        @endphp


        {{-- Legal Cards --}}
        <div class="legal-grid">

            @foreach ($legal as $item)

            <div class="legal-card">

                <div class="legal-icon">

                    @if($item['icon'] === 'privacy')

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 3l7 3v5c0 4.8-2.9 8.2-7 10-4.1-1.8-7-5.2-7-10V6l7-3z" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M9.5 12l1.7 1.7 3.6-3.7" />
                    </svg>

                    @elseif($item['icon'] === 'terms')

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M7 3h7l4 4v14H7z" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M14 3v5h5M10 12h5M10 16h5" />
                    </svg>

                    @else

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 21s-7-4.3-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.7-7 10-7 10z" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M8.5 12h2l1-2 1.5 4 1-2h1.5" />
                    </svg>

                    @endif

                </div>


                <h3 class="legal-card-title">
                    {{ $item['title'] }}
                </h3>


                <p class="legal-card-desc">
                    {{ $item['desc'] }}
                </p>


                <div class="legal-card-divider"></div>


                <button
                    type="button"
                    class="legal-button">
                    Baca Selengkapnya

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
                </button>

            </div>

            @endforeach

        </div>


        {{-- Catatan --}}
        <div class="legal-note">

            <div class="legal-note-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    aria-hidden="true">
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke-width="1.8" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 11v5M12 8h.01" />
                </svg>

            </div>


            <div>

                <strong>
                    Catatan Penting
                </strong>


                <p>
                    Isi legal final sebaiknya ditinjau dan disetujui
                    oleh pihak Klinik Pratama Mitra Sehat sebelum dipublikasikan.
                </p>

            </div>

        </div>

    </div>

</section>

@endsection