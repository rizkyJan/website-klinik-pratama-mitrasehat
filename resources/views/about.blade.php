@extends('layouts.app')
@section('pageTitle', 'Tentang Kami - Klinik Pratama Mitra Sehat')

@section('content')

<style>
    .about-page {
        background: #fbf7e9;
        color: #4f5f54;
    }

    .about-wrap {
        width: min(1120px, calc(100% - 48px));
        margin-inline: auto;
    }

    .about-kicker {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 11px;
        border-radius: 999px;
        background: #e8f5e8;
        color: #1a5d3a;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
    }

    .about-kicker-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #1a5d3a;
    }

    .about-title {
        margin: 10px 0 0;
        color: #1a5d3a;
        font-size: clamp(32px, 3vw, 44px);
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: -.025em;
    }

    .about-lead {
        margin: 10px 0 0;
        max-width: 680px;
        color: #68736c;
        font-size: 14px;
        line-height: 1.65;
    }

    .about-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(320px, .95fr);
        gap: 22px;
        align-items: stretch;
        margin-top: 24px;
    }

    .about-profile-card {
        position: relative;
        overflow: hidden;
        min-height: 330px;
        border-radius: 22px;
        background: linear-gradient(135deg, #e8f6e8 0%, #f5fbf3 100%);
        border: 1px solid #d8ead9;
        padding: 28px 30px;
    }

    .about-profile-card::before {
        content: '';
        position: absolute;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: rgba(132, 184, 125, .13);
        top: -48px;
        right: -45px;
    }

    .about-profile-card::after {
        content: '';
        position: absolute;
        width: 95px;
        height: 95px;
        border-radius: 50%;
        background: rgba(239, 189, 61, .08);
        bottom: -35px;
        left: -30px;
    }

    .about-section-label {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #1a5d3a;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .about-section-label svg {
        width: 16px;
        height: 16px;
        flex: none;
    }

    .about-profile-card h2 {
        position: relative;
        z-index: 1;
        margin: 12px 0 0;
        color: #145b38;
        font-size: 22px;
        line-height: 1.2;
        font-weight: 800;
    }

    .about-profile-card p {
        position: relative;
        z-index: 1;
        margin: 11px 0 0;
        color: #56645b;
        font-size: 13px;
        line-height: 1.7;
    }

    .about-history {
        position: relative;
        z-index: 1;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid rgba(26, 93, 58, .12);
    }

    .about-history h3 {
        margin: 0 0 10px;
        color: #1a5d3a;
        font-size: 16px;
        font-weight: 800;
    }

    .about-history-list {
        display: grid;
        gap: 7px;
    }

    .about-history-item {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        color: #5d6a61;
        font-size: 12.5px;
        line-height: 1.55;
    }

    .about-history-item::before {
        content: '';
        width: 7px;
        height: 7px;
        margin-top: 6px;
        border-radius: 50%;
        background: #1a5d3a;
        flex: none;
    }

    .about-photo-card {
        position: relative;
        height: 330px;
        overflow: hidden;
        border-radius: 22px;
        border: 3px solid #1a5d3a;
        background: #edf7ee;
        box-shadow: 0 10px 24px rgba(29, 91, 59, .10);
    }

    .about-photo-card img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        object-position: 50% 56%;
    }

    .about-photo-overlay {
        position: absolute;
        inset: auto 14px 14px 14px;
        padding: 12px 14px;
        border-radius: 14px;
        background: rgba(255, 255, 255, .92);
        backdrop-filter: blur(8px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
    }

    .about-photo-overlay strong {
        display: block;
        color: #1a5d3a;
        font-size: 14px;
        line-height: 1.2;
    }

    .about-photo-overlay span {
        display: block;
        margin-top: 3px;
        color: #6b746e;
        font-size: 11px;
    }

    .about-values {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-top: 20px;
    }

    .about-value-card {
        position: relative;
        overflow: hidden;
        min-height: 170px;
        border-radius: 20px;
        padding: 22px 24px;
        border: 1px solid #dde9df;
        background: #fff;
        box-shadow: 0 6px 18px rgba(32, 79, 54, .04);
    }

    .about-value-card::after {
        content: '';
        position: absolute;
        right: -30px;
        bottom: -30px;
        width: 105px;
        height: 105px;
        border-radius: 50%;
        background: #edf7ee;
    }

    .about-value-icon {
        position: relative;
        z-index: 1;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e8f5e8;
        color: #1a5d3a;
    }

    .about-value-icon svg {
        width: 22px;
        height: 22px;
    }

    .about-value-card h2 {
        position: relative;
        z-index: 1;
        margin: 12px 0 0;
        color: #1a5d3a;
        font-size: 19px;
        font-weight: 800;
    }

    .about-value-card p,
    .about-value-card ol {
        position: relative;
        z-index: 1;
        margin: 8px 0 0;
        color: #667068;
        font-size: 12.5px;
        line-height: 1.65;
    }

    .about-value-card ol {
        padding-left: 18px;
    }

    .about-value-card li+li {
        margin-top: 4px;
    }

    .about-facility-section {
        margin-top: 28px;
    }

    .about-facility-head {
        margin-bottom: 14px;
    }

    .about-facility-head h2 {
        margin: 0;
        color: #1a5d3a;
        font-size: 24px;
        line-height: 1.2;
        font-weight: 800;
    }

    .about-facility-head p {
        margin: 5px 0 0;
        color: #6b756f;
        font-size: 12.5px;
        line-height: 1.55;
    }

    .about-facility-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .about-facility-card {
        min-height: 74px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid #e0e9e1;
        background: #fff;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .about-facility-card:hover {
        transform: translateY(-2px);
        border-color: #c6dec9;
        box-shadow: 0 7px 18px rgba(26, 93, 58, .06);
    }

    .about-facility-icon {
        width: 38px;
        height: 38px;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #edf7ee;
        color: #1a5d3a;
    }

    .about-facility-icon svg {
        width: 19px;
        height: 19px;
    }

    .about-facility-card span {
        color: #3f4d44;
        font-size: 12.5px;
        font-weight: 700;
        line-height: 1.35;
    }

    @media (max-width: 1024px) {
        .about-wrap {
            width: min(100% - 36px, 900px);
        }

        .about-hero {
            grid-template-columns: 1fr;
        }

        .about-photo-card {
            height: 320px;
        }
    }

    @media (max-width: 767px) {
        .about-wrap {
            width: calc(100% - 24px);
        }

        .about-page {
            padding-top: 28px !important;
            padding-bottom: 36px !important;
        }

        .about-title {
            font-size: 32px;
        }

        .about-lead {
            font-size: 13.5px;
        }

        .about-profile-card {
            min-height: 0;
            padding: 22px 19px;
            border-radius: 20px;
        }

        .about-photo-card {
            height: 260px;
            border-radius: 20px;
        }

        .about-values {
            grid-template-columns: 1fr;
        }

        .about-value-card {
            min-height: 0;
            padding: 20px 18px;
        }

        .about-facility-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .about-facility-card {
            min-height: 68px;
            padding: 12px;
        }
    }

    @media (max-width: 430px) {
        .about-wrap {
            width: calc(100% - 18px);
        }

        .about-title {
            font-size: 29px;
        }

        .about-photo-card {
            height: 230px;
        }

        .about-photo-overlay {
            inset: auto 10px 10px 10px;
        }

        .about-facility-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="about-page py-10 lg:py-12">
    <div class="about-wrap">

        {{-- Header --}}
        <div>
            <div class="about-kicker">
                <span class="about-kicker-dot"></span>
                Tentang Klinik
            </div>

            <h1 class="about-title">Tentang Kami</h1>

            <p class="about-lead">
                Mengenal lebih dekat Klinik Pratama Mitra Sehat, perjalanan kami,
                visi, misi, serta fasilitas yang mendukung pelayanan kesehatan.
            </p>
        </div>


        {{-- Profil + Foto Klinik --}}
        <div class="about-hero">

            <div class="about-profile-card">
                <div class="about-section-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21h18M5 21V7l7-4 7 4v14M9 9h2m2 0h2m-6 4h2m2 0h2m-5 8v-4h4v4" />
                    </svg>
                    Profil Klinik
                </div>

                <h2>Klinik Pratama Mitra Sehat</h2>

                <p>{{ $profile }}</p>

                <div class="about-history">
                    <h3>Sejarah</h3>

                    <div class="about-history-list">
                        @php
                        $historyLines = preg_split('/\r\n|\r|\n|\\\\n/', $history);
                        @endphp

                        @foreach($historyLines as $line)
                        @if(trim($line) !== '')
                        <div class="about-history-item">
                            <span>{{ $line }}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>


            <div class="about-photo-card">
                <img
                    src="{{ asset('images/foto-klinik.png') }}"
                    alt="Gedung Klinik Pratama Mitra Sehat">

                <div class="about-photo-overlay">
                    <strong>Klinik Pratama Mitra Sehat</strong>
                    <span>Jl. Veteran No. 70, Sukoharjo</span>
                </div>
            </div>

        </div>


        {{-- Visi & Misi --}}
        <div class="about-values">

            <div class="about-value-card">
                <div class="about-value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3l7 4v5c0 4.5-2.8 8-7 9-4.2-1-7-4.5-7-9V7l7-4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4" />
                    </svg>
                </div>

                <h2>Visi</h2>

                <p>{{ $vision }}</p>
            </div>


            <div class="about-value-card">
                <div class="about-value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 11l3 3L22 4M21 12a9 9 0 11-5.3-8.2" />
                    </svg>
                </div>

                <h2>Misi</h2>

                <ol>
                    @php
                    $missionLines = preg_split('/\r\n|\r|\n|\\\\n/', $mission);
                    @endphp

                    @foreach($missionLines as $line)
                    @if(trim($line) !== '')
                    <li>{{ $line }}</li>
                    @endif
                    @endforeach
                </ol>
            </div>

        </div>


        {{-- Fasilitas --}}
        <div class="about-facility-section">

            <div class="about-facility-head">
                <h2>Fasilitas</h2>
                <p>
                    Fasilitas yang tersedia untuk mendukung kenyamanan dan pelayanan pasien.
                </p>
            </div>


            <div class="about-facility-grid">

                @foreach($facilities as $facility)

                <div class="about-facility-card">

                    <div class="about-facility-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <span>{{ $facility->name }}</span>

                </div>

                @endforeach

            </div>

        </div>

    </div>
</section>

@endsection