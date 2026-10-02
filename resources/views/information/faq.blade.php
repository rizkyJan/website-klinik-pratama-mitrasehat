@extends('layouts.app')
@section('pageTitle', 'FAQ - Klinik Pratama Mitra Sehat')

@section('content')

<style>
    .faq-page {
        background: #fbf7e9;
        color: #4f5f54;
    }

    .faq-wrap {
        width: min(1120px, calc(100% - 48px));
        margin-inline: auto;
    }

    .faq-back {
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

    .faq-back:hover {
        gap: 10px;
        color: #154a2e;
    }

    .faq-back svg {
        width: 17px;
        height: 17px;
        flex: none;
    }

    .faq-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .faq-kicker {
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

    .faq-kicker-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #1a5d3a;
    }

    .faq-title {
        margin: 12px 0 0;
        color: #1a5d3a;
        font-size: clamp(34px, 3.6vw, 48px);
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .faq-subtitle {
        margin: 10px auto 0;
        max-width: 650px;
        color: #69736d;
        font-size: 14px;
        line-height: 1.65;
    }

    .faq-summary {
        margin: 20px auto 0;
        width: fit-content;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border-radius: 13px;
        background: rgba(255, 255, 255, .75);
        border: 1px solid #e3ebe4;
        color: #667168;
        font-size: 11.5px;
    }

    .faq-summary strong {
        color: #1a5d3a;
    }

    .faq-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        align-items: start;
    }

    .faq-card {
        overflow: hidden;
        border-radius: 20px;
        border: 1px solid #e0e9e1;
        background: #fff;
        box-shadow: 0 7px 18px rgba(32, 79, 54, .045);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .faq-card:hover {
        transform: translateY(-2px);
        border-color: #c9dfcb;
        box-shadow: 0 12px 24px rgba(32, 79, 54, .08);
    }

    .faq-card details {
        width: 100%;
    }

    .faq-card summary {
        list-style: none;
    }

    .faq-card summary::-webkit-details-marker {
        display: none;
    }

    .faq-question {
        position: relative;
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 82px;
        padding: 18px 18px;
        cursor: pointer;
        color: #3f4d44;
        font-size: 13.5px;
        font-weight: 700;
        line-height: 1.5;
        transition: color .2s ease, background .2s ease;
    }

    .faq-question:hover {
        color: #1a5d3a;
        background: #fbfdfb;
    }

    .faq-question-icon {
        width: 36px;
        height: 36px;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #eaf7ec;
        color: #1a5d3a;
    }

    .faq-question-icon svg {
        width: 18px;
        height: 18px;
    }

    .faq-question-text {
        flex: 1;
        min-width: 0;
    }

    .faq-toggle {
        width: 32px;
        height: 32px;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f3f8f4;
        border: 1px solid #e3ebe4;
        color: #1a5d3a;
        transition: transform .2s ease, background .2s ease, color .2s ease;
    }

    .faq-toggle svg {
        width: 16px;
        height: 16px;
        transition: transform .2s ease;
    }

    details[open] .faq-toggle {
        background: #1a5d3a;
        color: #fff;
    }

    details[open] .faq-toggle svg {
        transform: rotate(180deg);
    }

    .faq-answer-wrap {
        padding: 0 18px 18px;
    }

    .faq-answer {
        margin: 0;
        padding: 15px 16px;
        border-radius: 14px;
        background: #edf7ee;
        border: 1px solid #dcecdf;
        color: #5e6961;
        font-size: 12.5px;
        line-height: 1.7;
    }

    .faq-note {
        margin-top: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 17px 18px;
        border-radius: 18px;
        background: #eaf7ec;
        border: 1px solid #d4e8d7;
    }

    .faq-note-icon {
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

    .faq-note-icon svg {
        width: 19px;
        height: 19px;
    }

    .faq-note strong {
        display: block;
        color: #1a5d3a;
        font-size: 13px;
        font-weight: 800;
    }

    .faq-note p {
        margin: 4px 0 0;
        color: #667168;
        font-size: 12px;
        line-height: 1.55;
    }

    .faq-empty {
        grid-column: 1 / -1;
        padding: 26px;
        border-radius: 18px;
        border: 1px solid #e1e9e2;
        background: #fff;
        color: #6d776f;
        text-align: center;
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .faq-wrap {
            width: min(100% - 36px, 760px);
        }

        .faq-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .faq-page {
            padding-top: 28px !important;
            padding-bottom: 38px !important;
        }

        .faq-wrap {
            width: calc(100% - 24px);
        }

        .faq-header {
            margin-bottom: 22px;
        }

        .faq-title {
            font-size: 32px;
        }

        .faq-subtitle {
            font-size: 13px;
        }

        .faq-card {
            border-radius: 18px;
        }

        .faq-question {
            min-height: 74px;
            padding: 16px;
            font-size: 13px;
        }

        .faq-answer-wrap {
            padding: 0 16px 16px;
        }
    }

    @media (max-width: 430px) {
        .faq-wrap {
            width: calc(100% - 18px);
        }

        .faq-title {
            font-size: 29px;
        }

        .faq-question {
            gap: 10px;
        }

        .faq-question-icon {
            width: 34px;
            height: 34px;
        }

        .faq-toggle {
            width: 30px;
            height: 30px;
        }
    }
</style>


<section class="faq-page py-10 lg:py-14">
    <div class="faq-wrap">

        {{-- Kembali ke Informasi --}}
        <a
            href="{{ route('information') }}"
            class="faq-back">
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
        <div class="faq-header">

            <div class="faq-kicker">
                <span class="faq-kicker-dot"></span>
                Pusat Bantuan
            </div>

            <h1 class="faq-title">
                Frequently Asked Questions
            </h1>

            <p class="faq-subtitle">
                Temukan jawaban dari pertanyaan yang paling sering
                ditanyakan masyarakat mengenai Klinik Pratama Mitra Sehat.
            </p>

            <div class="faq-summary">
                <span>Tersedia</span>
                <strong>{{ $faqs->count() }} pertanyaan</strong>
            </div>

        </div>


        {{-- FAQ List --}}
        <div class="faq-grid">

            @forelse($faqs as $faq)

            <div class="faq-card">

                <details>

                    <summary class="faq-question">

                        <div class="faq-question-icon">
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
                                    d="M9.8 9a2.4 2.4 0 114.2 1.6c-.9.8-2 1.2-2 2.6M12 17h.01" />
                            </svg>
                        </div>


                        <span class="faq-question-text">
                            {{ $faq->question }}
                        </span>


                        <span class="faq-toggle">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                aria-hidden="true">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 9l6 6 6-6" />
                            </svg>
                        </span>

                    </summary>


                    <div class="faq-answer-wrap">

                        <p class="faq-answer">
                            {{ $faq->answer }}
                        </p>

                    </div>

                </details>

            </div>

            @empty

            <div class="faq-empty">
                FAQ belum tersedia.
            </div>

            @endforelse

        </div>


        {{-- Catatan --}}
        <div class="faq-note">

            <div class="faq-note-icon">
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
                    Masih punya pertanyaan?
                </strong>

                <p>
                    Informasi pada halaman ini dapat diperbarui sesuai kebutuhan klinik.
                </p>
            </div>

        </div>

    </div>
</section>

@endsection