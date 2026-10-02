@extends('layouts.app')

@section('pageTitle', 'Cara Pendaftaran - Klinik Pratama Mitra Sehat')

@section('content')
<section class="py-10 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="max-w-3xl mb-8 lg:mb-10">
            <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-[#1a5d3a] mb-4">
                Informasi Pasien
            </span>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#1a5d3a] leading-tight">
                Cara Pendaftaran
            </h1>

            <p class="mt-4 text-base lg:text-lg text-gray-600 leading-relaxed">
                Pendaftaran pasien di Klinik Pratama Mitra Sehat dilakukan secara langsung di klinik
                atau melalui aplikasi Mobile JKN sesuai jenis layanan dan kepesertaan pasien.
            </p>
        </div>

        {{-- Notice --}}
        <div class="mb-8 rounded-2xl border border-[#F5C518]/50 bg-[#fff9df] p-4 sm:p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#F5C518]/20 text-[#806600]">
                    <svg width="20" height="20" style="width:20px;height:20px;flex:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div>
                    <h2 class="font-semibold text-gray-800">
                        Pendaftaran tidak dilakukan melalui website
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                        Website ini hanya memberikan informasi cara pendaftaran. Untuk mendaftar,
                        pasien dapat datang langsung ke klinik atau menggunakan aplikasi Mobile JKN
                        apabila layanan tersebut tersedia untuk kepesertaan pasien.
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid lg:grid-cols-5 gap-6 lg:gap-8 items-start">

            {{-- Left --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Offline --}}
                <div class="rounded-2xl border border-green-100 bg-green-50/70 p-6 sm:p-7">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#1a5d3a] text-white">
                            <svg width="24" height="24" style="width:24px;height:24px;flex:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h2m2 0h2m-6 4h2m2 0h2m-5 8v-4h4v4" />
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-xl sm:text-2xl font-bold text-[#1a5d3a]">
                                    Datang Langsung ke Klinik
                                </h2>
                                <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-[#1a5d3a] border border-green-100">
                                    Pendaftaran Offline
                                </span>
                            </div>

                            <p class="mt-2 text-sm sm:text-base text-gray-600 leading-relaxed">
                                Pasien dapat melakukan pendaftaran secara langsung dengan datang ke
                                Klinik Pratama Mitra Sehat pada jam pelayanan.
                            </p>
                        </div>
                    </div>

                    @php
                    $offlineSteps = [
                    'Datang ke Klinik Pratama Mitra Sehat pada jam pelayanan.',
                    'Ambil nomor antrean yang telah disediakan.',
                    'Tunggu sampai nomor antrean dipanggil, kemudian menuju ke loket pendaftaran.',
                    'Siapkan identitas pasien serta dokumen yang diperlukan.',
                    'Petugas klinik akan membantu proses pendaftaran dan antrean pelayanan.',
                    ];
                    @endphp

                    <div class="mt-6 space-y-4">
                        @foreach ($offlineSteps as $index => $step)
                        <div class="flex items-start gap-3">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#1a5d3a] text-xs font-bold text-white">
                                {{ $index + 1 }}
                            </span>
                            <p class="pt-0.5 text-sm sm:text-base text-gray-700 leading-relaxed">
                                {{ $step }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile JKN --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-7 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#1a5d3a] text-white">
                            <svg width="24" height="24" style="width:24px;height:24px;flex:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-xl sm:text-2xl font-bold text-[#1a5d3a]">
                                    Melalui Aplikasi Mobile JKN
                                </h2>
                                <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-[#1a5d3a] border border-green-100">
                                    Peserta JKN
                                </span>
                            </div>

                            <p class="mt-2 text-sm sm:text-base text-gray-600 leading-relaxed">
                                Peserta JKN dapat menggunakan aplikasi Mobile JKN sesuai ketersediaan fitur,
                                jenis pelayanan, dan ketentuan kepesertaan yang berlaku.
                            </p>
                        </div>
                    </div>

                    @php
                    $jknSteps = [
                    'Buka aplikasi Mobile JKN dan masuk menggunakan akun peserta.',
                    'Gunakan menu pendaftaran atau antrean pelayanan yang tersedia pada aplikasi.',
                    'Pilih fasilitas kesehatan dan layanan sesuai kepesertaan pasien.',
                    'Ikuti petunjuk pada aplikasi sampai proses pendaftaran selesai.',
                    ];
                    @endphp

                    <div class="mt-6 space-y-4">
                        @foreach ($jknSteps as $index => $step)
                        <div class="flex items-start gap-3">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#1a5d3a] text-xs font-bold text-white">
                                {{ $index + 1 }}
                            </span>
                            <p class="pt-0.5 text-sm sm:text-base text-gray-700 leading-relaxed">
                                {{ $step }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 rounded-xl bg-gray-50 p-4">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <span class="font-semibold text-gray-700">Catatan:</span>
                            menu dan ketersediaan layanan pada Mobile JKN dapat mengikuti ketentuan
                            BPJS Kesehatan dan status kepesertaan pasien.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right --}}
            <aside class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 sm:p-7 shadow-sm lg:sticky lg:top-24">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-[#1a5d3a]">
                        <svg width="20" height="20" style="width:20px;height:20px;flex:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-[#1a5d3a]">
                        Yang Perlu Disiapkan
                    </h2>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed">
                    Siapkan data atau dokumen berikut agar proses pendaftaran lebih mudah.
                </p>

                @php
                $requirements = [
                'KTP / NIK atau identitas pasien',
                'Kartu JKN/KIS untuk pasien BPJS',
                'Nomor telepon yang aktif',
                'Jenis layanan atau poli yang dituju',
                'Dokumen pendukung apabila diperlukan',
                ];
                @endphp

                <div class="mt-5 space-y-3">
                    @foreach ($requirements as $item)
                    <div class="flex items-start gap-3 rounded-xl border border-gray-100 bg-[#faf9f5] p-3.5">
                        <svg width="20" height="20" style="width:20px;height:20px;flex:none;margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" class="text-[#1a5d3a]">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm text-gray-700 leading-relaxed">
                            {{ $item }}
                        </span>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm font-semibold text-gray-800 mb-3">
                        Butuh informasi sebelum datang?
                    </p>

                    <div class="grid gap-3">
                        <a href="{{ route('information.schedule') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1a5d3a] px-4 py-3 text-sm font-semibold text-white hover:bg-[#154a2e] transition-colors">
                            <svg width="18" height="18" style="width:18px;height:18px;min-width:18px;max-width:18px;flex:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Lihat Jadwal Pelayanan</span>
                        </a>

                        <a href="{{ route('contact') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#1a5d3a] bg-white px-4 py-3 text-sm font-semibold text-[#1a5d3a] hover:bg-green-50 transition-colors">
                            <svg width="18" height="18" style="width:18px;height:18px;min-width:18px;max-width:18px;flex:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Kontak Klinik</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection