@extends('layouts.app')
@section('pageTitle', 'Pelayanan BPJS - Klinik Pratama Mitra Sehat')

@section('content')

<style>
    .bpjs-page {
        background: #fbf7e9;
        color: #4f5f54;
    }

    .bpjs-wrap {
        width: min(1120px, calc(100% - 48px));
        margin-inline: auto;
    }



    .bpjs-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        margin-bottom: 20px;

        color: #1a5d3a;

        font-size: 12.5px;
        font-weight: 700;

        text-decoration: none;

        transition:
            gap .2s ease,
            color .2s ease;
    }

    .bpjs-back:hover {
        gap: 10px;
        color: #154a2e;
    }

    .bpjs-back svg {
        width: 17px;
        height: 17px;
        flex: none;
    }

    /* =========================================================
       HEADER
    ========================================================== */

    .bpjs-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .bpjs-kicker {
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

    .bpjs-kicker-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: #1a5d3a;
    }

    .bpjs-title {
        margin: 12px 0 0;

        color: #1a5d3a;

        font-size: clamp(34px, 3.5vw, 46px);
        line-height: 1.08;

        font-weight: 800;

        letter-spacing: -.03em;
    }

    .bpjs-subtitle {
        margin: 10px auto 0;

        max-width: 620px;

        color: #69736d;

        font-size: 14px;
        line-height: 1.65;
    }


    /* =========================================================
       NOTICE
    ========================================================== */

    .bpjs-notice {
        display: flex;
        align-items: center;
        gap: 14px;

        margin-bottom: 22px;

        padding: 18px 20px;

        border-radius: 18px;

        background: #eaf7ec;

        border: 1px solid #d4e8d7;
    }

    .bpjs-notice-icon {
        width: 42px;
        height: 42px;

        flex: none;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: #ffffff;

        color: #1a5d3a;
    }

    .bpjs-notice-icon svg {
        width: 21px;
        height: 21px;
    }

    .bpjs-notice-content strong {
        display: block;

        color: #1a5d3a;

        font-size: 13px;
        font-weight: 800;
    }

    .bpjs-notice-content p {
        margin: 4px 0 0;

        color: #5e6c62;

        font-size: 12.5px;
        line-height: 1.6;
    }


    /* =========================================================
       CONTENT
    ========================================================== */

    .bpjs-grid {
        display: grid;

        grid-template-columns:
            minmax(0, .9fr) minmax(0, 1.1fr);

        gap: 20px;

        align-items: stretch;
    }

    .bpjs-card {
        position: relative;

        overflow: hidden;

        padding: 24px;

        border-radius: 22px;

        background: #ffffff;

        border: 1px solid #e0e9e1;

        box-shadow:
            0 7px 20px rgba(32, 79, 54, .045);
    }

    .bpjs-card::after {
        content: '';

        position: absolute;

        width: 120px;
        height: 120px;

        right: -45px;
        bottom: -45px;

        border-radius: 50%;

        background: #f1f8f1;
    }

    .bpjs-card-heading {
        position: relative;
        z-index: 1;

        display: flex;
        align-items: center;

        gap: 11px;

        margin-bottom: 20px;
    }

    .bpjs-card-heading-icon {
        width: 42px;
        height: 42px;

        flex: none;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: #eaf7ec;

        color: #1a5d3a;
    }

    .bpjs-card-heading-icon svg {
        width: 21px;
        height: 21px;
    }

    .bpjs-card-heading h2 {
        margin: 0;

        color: #1a5d3a;

        font-size: 19px;
        line-height: 1.25;

        font-weight: 800;
    }


    /* =========================================================
       DOCUMENTS
    ========================================================== */

    .bpjs-doc-list {
        position: relative;
        z-index: 1;

        display: grid;

        gap: 11px;
    }

    .bpjs-doc-item {
        display: flex;
        align-items: center;

        gap: 11px;

        padding: 12px 13px;

        border-radius: 13px;

        background: #f8fbf8;

        border: 1px solid #edf1ed;
    }

    .bpjs-check {
        width: 30px;
        height: 30px;

        flex: none;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #e5f5e8;

        color: #1a5d3a;
    }

    .bpjs-check svg {
        width: 16px;
        height: 16px;
    }

    .bpjs-doc-item span {
        color: #536158;

        font-size: 12.5px;
        line-height: 1.45;

        font-weight: 600;
    }


    /* =========================================================
       STEPS
    ========================================================== */

    .bpjs-steps {
        position: relative;
        z-index: 1;

        display: grid;

        gap: 0;
    }

    .bpjs-step {
        position: relative;

        display: grid;

        grid-template-columns: 38px 1fr;

        gap: 13px;

        min-height: 62px;
    }

    .bpjs-step:not(:last-child)::after {
        content: '';

        position: absolute;

        left: 18px;
        top: 37px;
        bottom: -2px;

        width: 2px;

        background: #dcecdf;
    }

    .bpjs-step-number {
        position: relative;
        z-index: 2;

        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #1a5d3a;
        color: #ffffff;

        font-size: 12px;
        font-weight: 800;

        box-shadow:
            0 4px 12px rgba(26, 93, 58, .15);
    }

    .bpjs-step-content {
        padding-top: 7px;
    }

    .bpjs-step-content strong {
        display: block;

        color: #344b3c;

        font-size: 13px;
        line-height: 1.4;

        font-weight: 700;
    }

    .bpjs-step-content span {
        display: block;

        margin-top: 3px;

        color: #849087;

        font-size: 10.5px;
    }


    /* =========================================================
       BOTTOM NOTE
    ========================================================== */

    .bpjs-bottom-note {
        margin-top: 22px;

        display: flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        padding: 12px 16px;

        border-radius: 14px;

        background: rgba(255, 255, 255, .7);

        border: 1px solid #e4ebe5;

        color: #6d776f;

        font-size: 11px;
        line-height: 1.5;

        text-align: center;
    }

    .bpjs-bottom-note svg {
        width: 16px;
        height: 16px;

        flex: none;

        color: #1a5d3a;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 900px) {

        .bpjs-wrap {
            width: min(100% - 36px, 760px);
        }

        .bpjs-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 767px) {

        .bpjs-page {
            padding-top: 28px !important;
            padding-bottom: 38px !important;
        }

        .bpjs-wrap {
            width: calc(100% - 24px);
        }

        .bpjs-header {
            margin-bottom: 22px;
        }

        .bpjs-title {
            font-size: 32px;
        }

        .bpjs-subtitle {
            font-size: 13px;
        }

        .bpjs-notice {
            align-items: flex-start;

            padding: 16px;

            border-radius: 16px;
        }

        .bpjs-card {
            padding: 20px 18px;

            border-radius: 18px;
        }

        .bpjs-card-heading h2 {
            font-size: 17px;
        }

    }


    @media (max-width: 430px) {

        .bpjs-wrap {
            width: calc(100% - 18px);
        }

        .bpjs-title {
            font-size: 29px;
        }

        .bpjs-notice-icon {
            width: 38px;
            height: 38px;
        }

        .bpjs-doc-item {
            padding: 11px;
        }

    }
</style>


<section class="bpjs-page py-10 lg:py-14">

    <div class="bpjs-wrap">


        {{-- Kembali ke Informasi --}}
        <a
            href="{{ route('information') }}"
            class="bpjs-back">
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


        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="bpjs-header">

            <div class="bpjs-kicker">

                <span class="bpjs-kicker-dot"></span>

                Informasi BPJS

            </div>


            <h1 class="bpjs-title">
                Pelayanan BPJS
            </h1>


            <p class="bpjs-subtitle">
                Informasi umum pelayanan BPJS di Klinik Pratama Mitra Sehat.
            </p>

        </div>



        {{-- =========================================================
             NOTICE
        ========================================================== --}}
        <div class="bpjs-notice">

            <div class="bpjs-notice-icon">

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


            <div class="bpjs-notice-content">

                <strong>
                    Ketentuan Pelayanan
                </strong>

                <p>
                    Layanan mengikuti kerja sama, prosedur,
                    dan ketentuan BPJS yang berlaku.
                </p>

            </div>

        </div>



        {{-- =========================================================
             CONTENT
        ========================================================== --}}
        <div class="bpjs-grid">


            {{-- =====================================================
                 YANG PERLU DIBAWA
            ====================================================== --}}
            <div class="bpjs-card">

                <div class="bpjs-card-heading">

                    <div class="bpjs-card-heading-icon">

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
                                d="M14 3v5h5" />
                        </svg>

                    </div>


                    <h2>
                        Yang Perlu Dibawa
                    </h2>

                </div>


                @php
                $docs = [
                'Kartu/identitas kepesertaan',
                'Identitas diri',
                'Dokumen lain jika diperlukan'
                ];
                @endphp


                <div class="bpjs-doc-list">

                    @foreach ($docs as $doc)

                    <div class="bpjs-doc-item">

                        <div class="bpjs-check">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                aria-hidden="true">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>

                        </div>


                        <span>
                            {{ $doc }}
                        </span>

                    </div>

                    @endforeach

                </div>

            </div>



            {{-- =====================================================
                 ALUR BPJS
            ====================================================== --}}
            <div class="bpjs-card">

                <div class="bpjs-card-heading">

                    <div class="bpjs-card-heading-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M5 6h14M5 12h14M5 18h14" />

                            <circle
                                cx="4"
                                cy="6"
                                r="1"
                                fill="currentColor"
                                stroke="none" />

                            <circle
                                cx="4"
                                cy="12"
                                r="1"
                                fill="currentColor"
                                stroke="none" />

                            <circle
                                cx="4"
                                cy="18"
                                r="1"
                                fill="currentColor"
                                stroke="none" />
                        </svg>

                    </div>


                    <h2>
                        Alur Pelayanan BPJS
                    </h2>

                </div>


                @php
                $steps = [
                'Datang / Daftar',
                'Verifikasi',
                'Pemeriksaan',
                'Pelayanan',
                'Farmasi / Rujukan'
                ];
                @endphp


                <div class="bpjs-steps">

                    @foreach ($steps as $i => $step)

                    <div class="bpjs-step">

                        <div class="bpjs-step-number">
                            {{ $i + 1 }}
                        </div>


                        <div class="bpjs-step-content">

                            <strong>
                                {{ $step }}
                            </strong>

                            <span>
                                Tahap {{ $i + 1 }}
                            </span>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>



        {{-- =========================================================
             BOTTOM NOTE
        ========================================================== --}}
        <div class="bpjs-bottom-note">

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

            Informasi pelayanan mengikuti ketentuan BPJS yang berlaku.

        </div>

    </div>

</section>

@endsection