<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Klinik Mitra Sehat</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-sm w-full">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#1a5d3a]">Admin Panel</h1>
            <p class="text-sm text-gray-500 mt-1">Klinik Pratama Mitra Sehat</p>
        </div>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5d3a] focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5d3a] focus:border-transparent">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-[#1a5d3a]">
                <label for="remember" class="ml-2 text-sm text-gray-500">Ingat saya</label>
            </div>
            <button type="submit" class="w-full bg-[#1a5d3a] text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-[#154a2e] transition-colors">
                Login
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-4">
            <a href="{{ route('home') }}" class="hover:text-[#1a5d3a]">kembali ke website</a>
        </p>
    </div>
</body>
</html>