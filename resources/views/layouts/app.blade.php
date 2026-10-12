
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        // SEO default website klinik
        $seoTitle = $pageTitle ??
            'Klinik Pratama Mitra Sehat Sukoharjo | Website Resmi';

        $seoDescription = $metaDescription ??
            'Website resmi Klinik Pratama Mitra Sehat Sukoharjo. Temukan informasi layanan kesehatan, jadwal dokter, pendaftaran pasien, dan kontak klinik.';

        // URL utama setiap halaman tanpa query string
        $seoCanonical = $canonicalUrl ??
            rtrim(config('app.url'), '/') . request()->getPathInfo();
    @endphp

    <!-- SEO Utama -->
    <title>{{ $seoTitle }}</title>

    <meta name="description"
          content="{{ $seoDescription }}">

    <link rel="canonical"
          href="{{ $seoCanonical }}">

    <!-- Open Graph: WhatsApp, Facebook, dan media sosial -->
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

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary">

    <meta name="twitter:title"
          content="{{ $seoTitle }}">

    <meta name="twitter:description"
          content="{{ $seoDescription }}">

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
