{{-- Navigation Bar --}}
@php
$navItems = [
['route' => 'home', 'label' => 'Home'],
['route' => 'about', 'label' => 'Tentang Kami'],
['route' => 'services', 'label' => 'Layanan'],
['route' => 'doctors', 'label' => 'Dokter'],
['route' => 'information', 'label' => 'Informasi'],
['route' => 'feedback.index', 'label' => 'Kritik & Saran'],
['route' => 'contact', 'label' => 'Kontak'],
];
@endphp

<nav
    class="bg-white shadow-sm sticky top-0 z-50"
    id="mainNavbar">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between h-16 gap-3">


            {{-- =========================================================
                 BRAND KIRI
            ========================================================== --}}
            <a
                href="{{ route('home') }}"
                class="group flex items-center gap-3 min-w-0 shrink-0"
                aria-label="Klinik Pratama Mitra Sehat">

                {{-- Logo Pure --}}
                <img
                    src="{{ asset('images/logo-klinik-mitra-sehat.png') }}"
                    alt="Logo Klinik Pratama Mitra Sehat"

                    class="
                        h-11
                        sm:h-12
                        lg:h-[50px]

                        w-auto
                        max-w-none

                        object-contain
                        shrink-0
                    ">


                {{-- Nama Klinik --}}
                <div class="min-w-0 leading-tight">


                    {{-- Desktop / Tablet --}}
                    <div class="hidden sm:flex flex-col">

                        <!-- <span
                            class="
                                text-sm
                                lg:text-base

                                font-semibold

                                text-[#1a5d3a]

                                whitespace-nowrap
                            ">
                            Klinik Pratama
                        </span> -->


                        <span
                            class="
                                text-lg
                                lg:text-xl

                                font-bold

                                text-[#1a5d3a]

                                whitespace-nowrap

                                mt-0.5
                            ">
                            Klinik Pratama Mitra Sehat
                        </span>

                    </div>


                    {{-- Mobile --}}
                    <span
                        class="
        sm:hidden
        text-lg
        font-bold
        text-[#1a5d3a]
        whitespace-nowrap
        mt-0.5
    ">
                        Mitra Sehat
                    </span>

                </div>

            </a>



            {{-- =========================================================
                 BAGIAN KANAN DESKTOP
            ========================================================== --}}
            <div
                class="
                    hidden
                    lg:flex

                    items-center

                    ml-auto

                    gap-3
                    xl:gap-4
                ">


                {{-- Menu Desktop --}}
                <div
                    class="
                        flex
                        items-center

                        gap-0.5

                        rounded-xl

                        bg-[#f8faf8]

                        border
                        border-gray-100

                        p-1
                    ">

                    @foreach ($navItems as $item)

                    @php
                    $isActive = request()->routeIs(
                    $item['route'] . '*'
                    );
                    @endphp


                    <a
                        href="{{ route($item['route']) }}"

                        class="
                                inline-flex
                                items-center
                                justify-center

                                min-h-[38px]

                                px-3
                                xl:px-3.5

                                rounded-lg

                                text-sm

                                transition-all
                                duration-200

                                {{
                                    $isActive
                                        ? 'bg-white text-[#1a5d3a] font-semibold shadow-sm'
                                        : 'text-gray-600 font-medium hover:text-[#1a5d3a] hover:bg-white/70'
                                }}
                            ">
                        {{ $item['label'] }}
                    </a>

                    @endforeach

                </div>



                {{-- Divider --}}
                <div
                    class="
                        hidden
                        xl:block

                        w-px
                        h-8

                        bg-gray-200
                    "
                    aria-hidden="true"></div>



                {{-- CTA --}}
                <a
                    href="{{ route('registration') }}"

                    class="
                        inline-flex
                        items-center
                        justify-center

                        min-h-[42px]

                        bg-[#1a5d3a]
                        text-white

                        px-5
                        xl:px-6

                        rounded-xl

                        text-sm
                        font-semibold

                        whitespace-nowrap

                        shadow-sm

                        hover:bg-[#154a2e]
                        hover:shadow-md

                        transition-all
                        duration-200

                        shrink-0
                    ">
                    Cara Pendaftaran
                </a>

            </div>



            {{-- =========================================================
                 MOBILE BUTTON
            ========================================================== --}}
            <button
                id="mobileMenuBtn"
                type="button"

                class="
                    lg:hidden

                    w-10
                    h-10

                    inline-flex
                    items-center
                    justify-center

                    rounded-lg

                    text-gray-600

                    hover:text-[#1a5d3a]
                    hover:bg-green-50

                    transition-colors

                    shrink-0
                "

                aria-label="Buka menu navigasi"
                aria-controls="mobileMenu"
                aria-expanded="false">

                {{-- Hamburger --}}
                <svg
                    id="menuOpenIcon"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    aria-hidden="true">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>


                {{-- Close --}}
                <svg
                    id="menuCloseIcon"
                    class="hidden"
                    width="24"
                    height="24"
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

    </div>



    {{-- =========================================================
         MOBILE MENU
    ========================================================== --}}
    <div
        id="mobileMenu"

        class="
            hidden
            lg:hidden

            bg-white

            border-t
            border-gray-100

            shadow-lg
        ">

        <div class="px-3 sm:px-6 py-3 space-y-1">

            @foreach ($navItems as $item)

            @php
            $isActive = request()->routeIs(
            $item['route'] . '*'
            );
            @endphp


            <a
                href="{{ route($item['route']) }}"

                class="
                        block

                        px-3
                        py-2.5

                        text-sm
                        font-medium

                        rounded-lg

                        transition-colors

                        {{
                            $isActive
                                ? 'text-[#1a5d3a] bg-green-50 font-semibold'
                                : 'text-gray-600 hover:text-[#1a5d3a] hover:bg-gray-50'
                        }}
                    ">
                {{ $item['label'] }}
            </a>

            @endforeach



            {{-- CTA Mobile --}}
            <a
                href="{{ route('registration') }}"

                class="
                    block

                    mt-3

                    bg-[#1a5d3a]
                    text-white

                    px-5
                    py-2.5

                    rounded-lg

                    text-sm
                    font-semibold

                    text-center

                    hover:bg-[#154a2e]

                    transition-colors
                ">
                Cara Pendaftaran
            </a>

        </div>

    </div>

</nav>



<script>
    document.addEventListener('DOMContentLoaded', function() {

        const button =
            document.getElementById('mobileMenuBtn');

        const menu =
            document.getElementById('mobileMenu');

        const openIcon =
            document.getElementById('menuOpenIcon');

        const closeIcon =
            document.getElementById('menuCloseIcon');


        if (!button || !menu) {
            return;
        }


        function setOpen(open) {

            menu.classList.toggle(
                'hidden',
                !open
            );

            button.setAttribute(
                'aria-expanded',
                String(open)
            );

            openIcon?.classList.toggle(
                'hidden',
                open
            );

            closeIcon?.classList.toggle(
                'hidden',
                !open
            );

        }


        button.addEventListener(
            'click',
            function() {

                setOpen(
                    menu.classList.contains('hidden')
                );

            }
        );


        window.addEventListener(
            'resize',
            function() {

                if (window.innerWidth >= 1024) {
                    setOpen(false);
                }

            }
        );

    });
</script>