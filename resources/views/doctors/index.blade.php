@extends('layouts.app')

@section('pageTitle', 'Jadwal Praktek Dokter - Klinik Pratama Mitra Sehat')



@section('content')



@php

$scheduleData = [];



foreach ($groupedDoctors->flatten() as $doctor) {

$scheduleData[$doctor->id] = [

'name' => $doctor->name,

'specialization' => $doctor->specialization,

'schedules' => $doctor->schedules
->sortBy(function ($sched) {
    $dayOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
    $day = str_pad((string) ($dayOrder[$sched->day] ?? 99), 2, '0', STR_PAD_LEFT);
    $time = $sched->is_off ? '99:99' : substr((string) $sched->start_time, 0, 5);
    return $day.'-'.$time;
})
->map(function ($sched) {

return [

'day' => $sched->day,

'time' => $sched->is_off

? 'LIBUR'

: ($sched->start_time . ' - ' . $sched->end_time . ' WIB'),

'isOff' => (bool) $sched->is_off,

];

})->values()->all(),

];

}

@endphp



<style>
    .doctors-page {

        background: #fbf7e9;

        color: #4f5f54;

    }



    .doctors-wrap {

        width: min(1180px, calc(100% - 48px));

        margin-inline: auto;

    }



    .doctors-header {

        text-align: center;

        margin-bottom: 34px;

    }



    .doctors-kicker {

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



    .doctors-kicker-dot {

        width: 7px;

        height: 7px;

        border-radius: 50%;

        background: #1a5d3a;

    }



    .doctors-title {

        margin: 12px 0 0;

        color: #1a5d3a;

        font-size: clamp(34px, 3.5vw, 48px);

        line-height: 1.05;

        font-weight: 800;

        letter-spacing: -.03em;

    }



    .doctors-subtitle {

        margin: 10px auto 0;

        max-width: 650px;

        color: #6a746d;

        font-size: 14px;

        line-height: 1.65;

    }



    .doctors-update-note {

        margin: 16px auto 0;

        width: fit-content;

        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 9px 13px;

        border-radius: 12px;

        border: 1px solid #e1ebe2;

        background: rgba(255, 255, 255, .72);

        color: #758078;

        font-size: 11px;

    }



    .doctors-update-note svg {

        width: 15px;

        height: 15px;

        color: #1a5d3a;

        flex: none;

    }



    .doctor-group {

        margin-bottom: 34px;

    }



    .doctor-group:last-of-type {

        margin-bottom: 0;

    }



    .doctor-group-head {

        display: flex;

        align-items: center;

        gap: 12px;

        margin-bottom: 16px;

    }



    .doctor-group-icon {

        width: 38px;

        height: 38px;

        flex: none;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 12px;

        background: #e8f5e8;

        color: #1a5d3a;

    }



    .doctor-group-icon svg {

        width: 19px;

        height: 19px;

    }



    .doctor-group-head h2 {

        margin: 0;

        color: #1a5d3a;

        font-size: 20px;

        line-height: 1.2;

        font-weight: 800;

    }



    .doctor-group-count {

        margin-left: auto;

        padding: 6px 10px;

        border-radius: 999px;

        background: #fff;

        border: 1px solid #e5ebe6;

        color: #788179;

        font-size: 10px;

        font-weight: 700;

    }



    .doctor-grid {

        display: grid;

        grid-template-columns: repeat(4, minmax(0, 1fr));

        gap: 18px;

    }



    .doctor-card {

        position: relative;

        overflow: hidden;

        min-height: 305px;

        display: flex;

        flex-direction: column;

        align-items: center;

        padding: 22px 20px 20px;

        border-radius: 22px;

        border: 1px solid #e1e9e2;

        background: #fff;

        text-align: center;

        box-shadow: 0 7px 18px rgba(32, 79, 54, .045);

        transition:

            transform .2s ease,

            box-shadow .2s ease,

            border-color .2s ease;

    }



    .doctor-card:hover {

        transform: translateY(-4px);

        border-color: #c9dfcb;

        box-shadow: 0 14px 28px rgba(32, 79, 54, .09);

    }



    .doctor-card::before {

        content: '';

        position: absolute;

        width: 130px;

        height: 130px;

        top: -78px;

        right: -58px;

        border-radius: 50%;

        background: #f0f8f0;

    }



    .doctor-photo-wrap {

        position: relative;

        z-index: 1;

        width: 112px;

        height: 112px;

        margin-bottom: 16px;

        border-radius: 50%;

        padding: 4px;

        background: #fff;

        border: 2px solid #1a5d3a;

        box-shadow: 0 7px 18px rgba(26, 93, 58, .10);

    }



    .doctor-photo {

        width: 100%;

        height: 100%;

        overflow: hidden;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        background: #eef8ef;

    }



    .doctor-photo img {

        width: 100%;

        height: 100%;

        display: block;

        object-fit: cover;

        object-position: center top;

    }



    .doctor-photo svg {

        width: 46px;

        height: 46px;

        color: #1a5d3a;

        opacity: .34;

    }



    .doctor-card h3 {

        position: relative;

        z-index: 1;

        margin: 0;

        color: #145f3a;

        font-size: 16px;

        line-height: 1.3;

        font-weight: 800;

    }



    .doctor-specialization {

        position: relative;

        z-index: 1;

        margin: 6px 0 0;

        color: #778079;

        font-size: 12px;

        line-height: 1.4;

    }



    .doctor-card-divider {

        width: 44px;

        height: 2px;

        margin: 14px auto;

        border-radius: 999px;

        background: #e2eee4;

    }



    .doctor-schedule-btn {

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

        color: #fff;

        font-size: 12px;

        font-weight: 700;

        cursor: pointer;

        transition: transform .2s ease, background .2s ease;

    }



    .doctor-schedule-btn:hover {

        transform: translateY(-1px);

        background: #154a2e;

    }



    .doctor-schedule-btn svg {

        width: 17px;

        height: 17px;

        flex: none;

    }



    .doctors-bottom-note {

        margin-top: 26px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 9px;

        padding: 13px 18px;

        border-radius: 15px;

        border: 1px solid #d8ead9;

        background: #edf7ee;

        color: #1a5d3a;

        font-size: 11.5px;

        font-style: italic;

    }



    .doctors-bottom-note svg {

        width: 17px;

        height: 17px;

        flex: none;

    }



    /* MODAL */

    .schedule-modal {

        position: fixed;

        inset: 0;

        z-index: 100;

        display: flex;

        align-items: center;

        justify-content: center;

        padding: 18px;

    }



    .schedule-modal.is-hidden {

        display: none;

    }



    .schedule-backdrop {

        position: absolute;

        inset: 0;

        background: rgba(17, 24, 20, .55);

        backdrop-filter: blur(3px);

    }



    .schedule-dialog {

        position: relative;

        z-index: 1;

        width: min(100%, 520px);

        max-height: calc(100vh - 36px);

        overflow: auto;

        border-radius: 24px;

        background: #fff;

        box-shadow: 0 24px 70px rgba(0, 0, 0, .22);

    }



    .schedule-modal-head {

        position: relative;

        padding: 24px 58px 19px 24px;

        background: linear-gradient(135deg, #e9f6e9 0%, #f8fbf5 100%);

        border-bottom: 1px solid #e1ebe2;

    }



    .schedule-modal-kicker {

        color: #6f7a72;

        font-size: 10px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .07em;

    }



    .schedule-modal-title {

        margin: 5px 0 0;

        color: #1a5d3a;

        font-size: 20px;

        line-height: 1.25;

        font-weight: 800;

    }



    .schedule-modal-specialization {

        margin: 5px 0 0;

        color: #727d75;

        font-size: 11.5px;

    }



    .schedule-close {

        position: absolute;

        top: 17px;

        right: 17px;

        width: 36px;

        height: 36px;

        border: 1px solid #dfe8e0;

        border-radius: 11px;

        display: flex;

        align-items: center;

        justify-content: center;

        background: #fff;

        color: #1a5d3a;

        cursor: pointer;

        transition: background .2s ease;

    }



    .schedule-close:hover {

        background: #edf7ee;

    }



    .schedule-close svg {

        width: 18px;

        height: 18px;

    }



    .schedule-modal-body {

        padding: 20px 24px 24px;

    }



    .schedule-table-wrap {

        overflow: hidden;

        border: 1px solid #e1e8e2;

        border-radius: 15px;

    }



    .schedule-table {

        width: 100%;

        border-collapse: collapse;

    }



    .schedule-table thead {

        background: #1a5d3a;

        color: #fff;

    }



    .schedule-table th {

        padding: 11px 13px;

        text-align: left;

        font-size: 11px;

        font-weight: 700;

    }



    .schedule-table td {

        padding: 11px 13px;

        border-bottom: 1px solid #edf0ed;

        color: #566158;

        font-size: 11.5px;

        line-height: 1.4;

    }



    .schedule-table tbody tr:last-child td {

        border-bottom: 0;

    }



    .schedule-row-alt {

        background: #f7fbf7;

    }



    .schedule-day {

        color: #355441 !important;

        font-weight: 700;

    }



    .schedule-off {

        color: #c0392b !important;

        font-weight: 800;

    }



    .schedule-empty {

        padding: 22px 14px !important;

        text-align: center;

        color: #7a827c !important;

    }



    @media (max-width: 1024px) {

        .doctors-wrap {

            width: min(100% - 36px, 900px);

        }



        .doctor-grid {

            grid-template-columns: repeat(3, minmax(0, 1fr));

        }

    }



    @media (max-width: 767px) {

        .doctors-page {

            padding-top: 28px !important;

            padding-bottom: 38px !important;

        }



        .doctors-wrap {

            width: calc(100% - 24px);

        }



        .doctors-header {

            margin-bottom: 26px;

        }



        .doctors-title {

            font-size: 32px;

        }



        .doctors-subtitle {

            font-size: 13px;

        }



        .doctor-grid {

            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 12px;

        }



        .doctor-card {

            min-height: 285px;

            padding: 18px 14px 16px;

            border-radius: 18px;

        }



        .doctor-photo-wrap {

            width: 96px;

            height: 96px;

        }



        .doctor-card h3 {

            font-size: 14.5px;

        }



        .schedule-dialog {

            border-radius: 20px;

        }

    }



    @media (max-width: 480px) {

        .doctors-wrap {

            width: calc(100% - 18px);

        }



        .doctor-grid {

            grid-template-columns: 1fr;

        }



        .doctor-card {

            min-height: 0;

        }



        .doctor-group-head {

            align-items: flex-start;

        }



        .doctor-group-count {

            margin-left: auto;

        }



        .schedule-modal {

            padding: 10px;

        }



        .schedule-modal-head {

            padding: 21px 54px 16px 18px;

        }



        .schedule-modal-body {

            padding: 16px 18px 20px;

        }

    }
</style>



<section class="doctors-page py-10 lg:py-14">

    <div class="doctors-wrap">



        {{-- Header --}}

        <div class="doctors-header">



            <div class="doctors-kicker">

                <span class="doctors-kicker-dot"></span>

                Dokter Klinik

            </div>



            <h1 class="doctors-title">

                Jadwal Praktek Dokter

            </h1>



            <p class="doctors-subtitle">

                Lihat profil dokter dan jadwal praktik yang tersedia

                di Klinik Pratama Mitra Sehat.

            </p>



            <div class="doctors-update-note">

                <svg

                    viewBox="0 0 24 24"

                    fill="none"

                    stroke="currentColor"

                    aria-hidden="true">

                    <path

                        stroke-linecap="round"

                        stroke-linejoin="round"

                        stroke-width="2"

                        d="M12 8v4l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                </svg>



                Profil dan jadwal dapat diperbarui oleh admin

            </div>



        </div>





        {{-- Doctor Groups --}}

        @foreach($groupedDoctors as $specialization => $doctors)



        <div class="doctor-group">



            <div class="doctor-group-head">



                <div class="doctor-group-icon">

                    <svg

                        viewBox="0 0 24 24"

                        fill="none"

                        stroke="currentColor"

                        aria-hidden="true">

                        <path

                            stroke-linecap="round"

                            stroke-linejoin="round"

                            stroke-width="1.8"

                            d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8z" />

                        <path

                            stroke-linecap="round"

                            stroke-linejoin="round"

                            stroke-width="1.8"

                            d="M19 8v6M16 11h6" />

                    </svg>

                </div>



                <h2>

                    {{ $specialization }}

                </h2>



                <span class="doctor-group-count">

                    {{ $doctors->count() }} dokter

                </span>



            </div>





            <div class="doctor-grid">



                @foreach($doctors as $doctor)



                <div class="doctor-card">



                    <div class="doctor-photo-wrap">



                        <div class="doctor-photo">



                            @if($doctor->photo)



                            <img

                                src="{{ asset($doctor->photo) }}"

                                alt="{{ $doctor->name }}">



                            @else



                            <svg

                                viewBox="0 0 24 24"

                                fill="none"

                                stroke="currentColor"

                                aria-hidden="true">

                                <path

                                    stroke-linecap="round"

                                    stroke-linejoin="round"

                                    stroke-width="1.5"

                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                            </svg>



                            @endif



                        </div>



                    </div>





                    <h3>

                        {{ $doctor->name }}

                    </h3>



                    <p class="doctor-specialization">

                        {{ $doctor->specialization }}

                    </p>



                    <div class="doctor-card-divider"></div>





                    <button

                        type="button"

                        data-doctor-id="{{ $doctor->id }}"

                        class="doctor-schedule-btn">

                        <svg

                            viewBox="0 0 24 24"

                            fill="none"

                            stroke="currentColor"

                            aria-hidden="true">

                            <path

                                stroke-linecap="round"

                                stroke-linejoin="round"

                                stroke-width="2"

                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />

                        </svg>



                        Lihat Jadwal

                    </button>



                </div>



                @endforeach



            </div>



        </div>



        @endforeach





        {{-- Bottom Note --}}

        <div class="doctors-bottom-note">



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



            Jadwal praktik dapat berubah sewaktu-waktu.



        </div>



    </div>

</section>





{{-- Schedule Data --}}
<script
    id="doctorScheduleData"
    type="application/json">
    @json($scheduleData)
</script>


{{-- Schedule Modal --}}

<div

    id="scheduleModal"

    class="schedule-modal is-hidden"

    role="dialog"

    aria-modal="true"

    aria-labelledby="modalTitle">

    <div

        id="scheduleBackdrop"

        class="schedule-backdrop"></div>





    <div class="schedule-dialog">



        <div class="schedule-modal-head">



            <div class="schedule-modal-kicker">

                Jadwal Praktik

            </div>



            <h2

                id="modalTitle"

                class="schedule-modal-title">

                Jadwal Dokter

            </h2>



            <p

                id="modalSpecialization"

                class="schedule-modal-specialization"></p>





            <button

                id="scheduleCloseBtn"

                type="button"

                class="schedule-close"

                aria-label="Tutup jadwal">

                <svg

                    viewBox="0 0 24 24"

                    fill="none"

                    stroke="currentColor"

                    aria-hidden="true">

                    <path

                        stroke-linecap="round"

                        stroke-linejoin="round"

                        stroke-width="2"

                        d="M6 18L18 6M6 6l12 12" />

                </svg>

            </button>



        </div>





        <div class="schedule-modal-body">



            <div class="schedule-table-wrap">



                <table class="schedule-table">



                    <thead>

                        <tr>

                            <th>Hari</th>

                            <th>Jam Praktik</th>

                        </tr>

                    </thead>



                    <tbody id="scheduleBody"></tbody>



                </table>



            </div>



        </div>



    </div>

</div>





<script>
    document.addEventListener('DOMContentLoaded', function() {



        const dataElement =

            document.getElementById('doctorScheduleData');



        const modal =

            document.getElementById('scheduleModal');



        const modalTitle =

            document.getElementById('modalTitle');



        const modalSpecialization =

            document.getElementById('modalSpecialization');



        const scheduleBody =

            document.getElementById('scheduleBody');





        let allSchedules = {};





        if (dataElement) {

            try {

                allSchedules =

                    JSON.parse(dataElement.textContent || '{}');

            } catch (error) {

                console.error(

                    'Gagal membaca data jadwal dokter:',

                    error

                );

            }

        }





        function openSchedule(doctorId) {



            const data =

                allSchedules[String(doctorId)] ??

                allSchedules[doctorId];



            if (!data || !modal) {

                return;

            }





            modalTitle.textContent =

                'Jadwal ' + data.name;



            modalSpecialization.textContent =

                data.specialization || '';





            if (

                !Array.isArray(data.schedules) ||

                data.schedules.length === 0

            ) {



                scheduleBody.innerHTML = `

                    <tr>

                        <td

                            colspan="2"

                            class="schedule-empty"

                        >

                            Jadwal belum tersedia.

                        </td>

                    </tr>

                `;



            } else {



                scheduleBody.innerHTML =

                    data.schedules

                    .map(function(item, index) {



                        const rowClass =

                            index % 2 === 0 ?

                            '' :

                            'schedule-row-alt';



                        const timeClass =

                            item.isOff ?

                            'schedule-off' :

                            '';



                        return `

                                <tr class="${rowClass}">

                                    <td class="schedule-day">

                                        ${escapeHtml(item.day)}

                                    </td>



                                    <td class="${timeClass}">

                                        ${escapeHtml(item.time)}

                                    </td>

                                </tr>

                            `;



                    })

                    .join('');



            }





            modal.classList.remove('is-hidden');



            document.body.style.overflow =

                'hidden';

        }





        function closeSchedule() {



            if (!modal) {

                return;

            }



            modal.classList.add('is-hidden');



            document.body.style.overflow =

                '';

        }





        window.addEventListener(

            'keydown',

            function(event) {



                if (

                    event.key === 'Escape' &&

                    modal &&

                    !modal.classList.contains('is-hidden')

                ) {

                    closeSchedule();

                }



            }

        );





        function escapeHtml(value) {



            const element =

                document.createElement('div');



            element.textContent =

                value ?? '';



            return element.innerHTML;

        }





        /*

         * Event listener tombol jadwal.

         * Blade hanya mengisi data-doctor-id di HTML.

         * JavaScript membaca ID dokter dari dataset tombol,

         * jadi tidak perlu inline onclick.

         */

        document

            .querySelectorAll('.doctor-schedule-btn[data-doctor-id]')

            .forEach(function(button) {



                button.addEventListener('click', function() {



                    openSchedule(

                        button.dataset.doctorId

                    );



                });



            });





        const scheduleBackdrop =

            document.getElementById('scheduleBackdrop');



        const scheduleCloseBtn =

            document.getElementById('scheduleCloseBtn');





        if (scheduleBackdrop) {



            scheduleBackdrop.addEventListener(

                'click',

                closeSchedule

            );



        }





        if (scheduleCloseBtn) {



            scheduleCloseBtn.addEventListener(

                'click',

                closeSchedule

            );



        }



    });
</script>



@endsection