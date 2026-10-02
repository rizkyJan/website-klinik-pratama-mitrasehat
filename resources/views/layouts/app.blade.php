<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDescription ?? 'Klinik Pratama Mitra Sehat - Mitra Tepat Menuju Sehat' }}">
    <title>{{ $pageTitle ?? 'Klinik Pratama Mitra Sehat' }}</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
