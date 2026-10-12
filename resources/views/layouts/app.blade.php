<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        /*
        |--------------------------------------------------------------------------
        | SEO Website Klinik Pratama Mitra Sehat
        |--------------------------------------------------------------------------
        | Mendukung @section('pageTitle') pada halaman Blade,
        | serta $pageTitle dari controller jika tersedia.
        */

        $defaultTitle = 'Klinik Pratama Mitra Sehat Sukoharjo | Website Resmi';

        $defaultDescription = 'Website resmi Klinik Pratama Mitra Sehat Sukoharjo. Informasi layanan kesehatan, jadwal dokter, pendaftaran pasien, dan kontak klinik.';

        $seoTitle = trim(
            $__env->yieldContent(
                'pageTitle',
                $pageTitle ?? $defaultTitle
            )
        );

        $seoDescription = trim(
            $__env->yieldContent(
                'metaDescription',
                $metaDescription ?? $defaultDescription
            )
        );

        $seoTitle = $seoTitle ?: $defaultTitle;
        $seoDescription = $seoDescription ?: $defaultDescription;

        // Canonical URL tanpa query string
        $seoCanonical = $canonicalUrl ??
            rtrim(config('app.url'), '/') . request()->getPathInfo();
    @endphp

    <!-- SEO Utama -->
    <title>{{ $seoTitle }}</title>

    <meta name="description"
          content="{{ $seoDescription }}">

    <meta name="robots" content="index, follow">

    <link rel="canonical" href="{{ $seoCanonical }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">

    <meta property="og:site_name"
          content="Klinik Pratama Mitra Sehat">

    <meta property="og:title"
          content="{{ $seoTitle }}">

    <meta property="og:description"
          content="{{ $seoDescription }}">

    <meta property="og:url"
          content="{{ $seoCanonical }}">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary">

    <meta name="twitter:title"
          content="{{ $seoTitle }}">

    <meta name="twitter:description"
          content="{{ $seoDescription }}">


    <!-- Structured Data: Klinik Pratama Mitra Sehat -->
    @if(request()->getPathInfo() === '/')
        @php
            $clinicSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'MedicalClinic',
                '@id' => 'https://mitrasehatms.com/#medicalclinic',

                'name' => 'Klinik Pratama Mitra Sehat',
                'alternateName' => 'Klinik Pratama Mitra Sehat Sukoharjo',

                'url' => 'https://mitrasehatms.com/',

                
                'image' => 'https://mitrasehatms.com/images/foto-klinik.png',
                
                'logo' => 'https://mitrasehatms.com/images/logo-klinik-mitra-sehat.png',


                'description' => 'Klinik Pratama Mitra Sehat di Sukoharjo menyediakan layanan kesehatan, informasi jadwal dokter, dan pendaftaran pasien.',

                'telephone' => '+62-271-592374',

                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Jl. Veteran No.70, Ngabeyan, Jetis, Kec. Sukoharjo',
                    'addressLocality' => 'Sukoharjo',
                    'addressRegion' => 'Jawa Tengah',
                    'postalCode' => '57511',
                    'addressCountry' => 'ID',
                ],
            ];
        @endphp

        <script type="application/ld+json">
            @json($clinicSchema)
        </script>
    @endif

    
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts: Inter -->
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    @stack('styles')
</head>

<body class="font-inter bg-[#faf9f5] text-gray-800 antialiased">

    <!-- Top accent line -->
    <div class="h-1 w-full bg-[#F5C518]"></div>

    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Asisten Klinik -->
    @include('partials.ai-chat')

    @stack('scripts')
</body>
</html>
