@extends('layouts.app')
@section('pageTitle', 'Jadwal Pelayanan - Klinik Pratama Mitra Sehat')

@section('content')

<style>
    .schedule-page {
        background: #fbf7e9;
        color: #4f5f54;
    }

    .schedule-wrap {
        width: min(1120px, calc(100% - 48px));
        margin-inline: auto;
    }

    .schedule-back {
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

    .schedule-back:hover {
        gap: 10px;
        color: #154a2e;
    }

    .schedule-back svg {
        width: 17px;
        height: 17px;
        flex: none;
    }

    .schedule-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .schedule-kicker {
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

    .schedule-kicker-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #1a5d3a;
    }

    .schedule-title {
        margin: 12px 0 0;
        color: #1a5d3a;
        font-size: clamp(34px, 3.6vw, 48px);
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .schedule-subtitle {
        margin: 10px auto 0;
        max-width: 650px;
        color: #69736d;
        font-size: 14px;
        line-height: 1.65;
    }

    .schedule-table-card {
        overflow: hidden;
        border-radius: 22px;
        border: 1px solid #dfe8e0;
        background: #ffffff;
        box-shadow: 0 8px 22px rgba(32, 79, 54, .05);
    }

    .schedule-table-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 18px 20px;
        border-bottom: 1px solid #e6ece7;
        background: #ffffff;
    }

    .schedule-table-top-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .schedule-table-icon {
        width: 40px;
        height: 40px;
        flex: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #eaf7ec;
        color: #1a5d3a;
    }

    .schedule-table-icon svg {
        width: 20px;
        height: 20px;
    }

    .schedule-table-title {
        margin: 0;
        color: #1a5d3a;
        font-size: 15px;
        font-weight: 800;
    }

    .schedule-table-caption {
        margin: 3px 0 0;
        color: #7a847d;
        font-size: 11px;
    }

    .schedule-table-scroll {
        overflow-x: auto;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 680px;
    }

    .schedule-table thead {
        background: #1a5d3a;
        color: #ffffff;
    }

    .schedule-table th {
        padding: 13px 18px;
        text-align: left;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: .01em;
    }

    .schedule-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #edf1ed;
        color: #59645c;
        font-size: 12.5px;
        line-height: 1.45;
        vertical-align: middle;
    }

    .schedule-table tbody tr:nth-child(even) {
        background: #f8fbf8;
    }

    .schedule-table tbody tr:hover {
        background: #f1f8f2;
    }

    .schedule-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .service-name {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1a5d3a !important;
        font-weight: 800;
    }

    .service-dot {
        width: 8px;
        height: 8px;
        flex: none;
        border-radius: 50%;
        background: #1a5d3a;
    }

    .schedule-day-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 76px;
        padding: 6px 9px;
        border-radius: 999px;
        background: #edf7ee;
        color: #1a5d3a;
        font-size: 10.5px;
        font-weight: 700;
    }

    .schedule-time {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #536058;
        font-weight: 600;
    }

    .schedule-time svg {
        width: 15px;
        height: 15px;
        flex: none;
        color: #1a5d3a;
    }

    .schedule-24h {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 999px;
        background: #1a5d3a;
        color: #ffffff;
        font-size: 10.5px;
        font-weight: 800;
    }

    .schedule-empty {
        padding: 28px 20px !important;
        text-align: center;
        color: #7a847d !important;
        background: #ffffff !important;
    }

    .schedule-note {
        margin-top: 22px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 17px 18px;
        border-radius: 18px;
        background: #eaf7ec;
        border: 1px solid #d4e8d7;
    }

    .schedule-note-icon {
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

    .schedule-note-icon svg {
        width: 19px;
        height: 19px;
    }

    .schedule-note strong {
        display: block;
        color: #1a5d3a;
        font-size: 13px;
        font-weight: 800;
    }

    .schedule-note p {
        margin: 4px 0 0;
        color: #667168;
        font-size: 12px;
        line-height: 1.55;
    }

    @media (max-width: 900px) {
        .schedule-wrap {
            width: min(100% - 36px, 760px);
        }
    }

    @media (max-width: 767px) {
        .schedule-page {
            padding-top: 28px !important;
            padding-bottom: 38px !important;
        }

        .schedule-wrap {
            width: calc(100% - 24px);
        }

        .schedule-header {
            margin-bottom: 22px;
        }

        .schedule-title {
            font-size: 32px;
        }

        .schedule-subtitle {
            font-size: 13px;
        }

        .schedule-table-card {
            border-radius: 18px;
        }

        .schedule-table-top {
            padding: 15px 16px;
        }

        .schedule-table th,
        .schedule-table td {
            padding-left: 14px;
            padding-right: 14px;
        }
    }

    @media (max-width: 430px) {
        .schedule-wrap {
            width: calc(100% - 18px);
        }

        .schedule-title {
            font-size: 29px;
        }

        .schedule-table-top {
            align-items: flex-start;
        }

        .schedule-table-caption {
            line-height: 1.45;
        }
    }
</style>


<section class="schedule-page py-10 lg:py-14">

    <div class="schedule-wrap">


        {{-- Kembali ke Informasi --}}
        <a
            href="{{ route('information') }}"
            class="schedule-back">
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
        <div class="schedule-header">

            <div class="schedule-kicker">
                <span class="schedule-kicker-dot"></span>
                Waktu Pelayanan
            </div>


            <h1 class="schedule-title">
                Jadwal Pelayanan
            </h1>


            <p class="schedule-subtitle">
                Informasi hari dan jam pelayanan
                Klinik Pratama Mitra Sehat.
            </p>

        </div>



        {{-- Table Card --}}
        <div class="schedule-table-card">

            <div class="schedule-table-top">

                <div class="schedule-table-top-left">

                    <div class="schedule-table-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M8 3v4M16 3v4M5 9h14M5 5h14v16H5z" />
                        </svg>

                    </div>


                    <div>

                        <h2 class="schedule-table-title">
                            Daftar Jadwal Pelayanan
                        </h2>

                        <p class="schedule-table-caption">
                            Jadwal ditampilkan sesuai data pelayanan yang tersedia.
                        </p>

                    </div>

                </div>

            </div>



            <div class="schedule-table-scroll">

                <table class="schedule-table">

                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Hari</th>
                            <th>Jam Pelayanan</th>
                        </tr>
                    </thead>


                    <tbody>

                        @php
                        $hasSchedule = false;
                        @endphp


                        @foreach($services as $service)

                        @foreach($service->schedules as $sched)

                        @php
                        $hasSchedule = true;
                        @endphp

                        <tr>

                            <td>

                                <div class="service-name">

                                    <span class="service-dot"></span>

                                    {{ $service->name }}

                                </div>

                            </td>


                            <td>

                                <span class="schedule-day-badge">
                                    {{ $sched->day }}
                                </span>

                            </td>


                            <td>

                                @if($sched->is_24h)

                                <span class="schedule-24h">
                                    24 Jam
                                </span>

                                @else

                                <span class="schedule-time">

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
                                            d="M12 7v5l3 2" />
                                    </svg>

                                    {{ $sched->open_time }} - {{ $sched->close_time }}

                                </span>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                        @endforeach


                        @if(!$hasSchedule)

                        <tr>
                            <td
                                colspan="3"
                                class="schedule-empty">
                                Jadwal pelayanan belum tersedia.
                            </td>
                        </tr>

                        @endif

                    </tbody>

                </table>

            </div>

        </div>



        {{-- Catatan --}}
        <div class="schedule-note">

            <div class="schedule-note-icon">

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
                    Perhatian
                </strong>


                <p>
                    Jadwal dapat berubah. Silakan konfirmasi kepada admin
                    sebelum berkunjung.
                </p>

            </div>

        </div>

    </div>

</section>

@endsection