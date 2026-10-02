@extends('admin.layout')

@section('title', 'Pengaturan AI')

@section('content')
<div class="max-w-5xl mx-auto space-y-5">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan AI</h1>
        <p class="text-sm text-gray-500 mt-1">Pengaturan perilaku chatbot. Alamat server dan model AI tetap dikontrol dari <code>.env</code> agar tidak dapat diubah sembarangan dari dashboard.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="font-bold text-gray-900">Koneksi Mesin AI Lokal</div>
                <div class="text-sm text-gray-600 mt-2 space-y-1">
                    <div>URL: <code>{{ $runtime['url'] }}</code></div>
                    <div>Model: <code>{{ $runtime['model'] }}</code></div>
                    <div>Context: {{ $runtime['context_length'] }} token · Keep alive: {{ $runtime['keep_alive'] }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.ai.settings.test') }}">@csrf<button class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold">Tes Koneksi AI</button></form>
        </div>
    </div>

    @if($errors->any())<div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">{{ $errors->first() }}</div>@endif

    <form method="POST" action="{{ route('admin.ai.settings.update') }}" class="bg-white border border-gray-200 rounded-xl p-5 space-y-5">
        @csrf @method('PUT')

        <div class="grid md:grid-cols-2 gap-4">
            <label class="flex gap-3 p-4 border border-gray-200 rounded-xl">
                <input type="checkbox" name="enabled" value="1" class="mt-1 rounded border-gray-300 text-[#1a5d3a]" @checked($settings['enabled'])>
                <span><strong class="block text-sm text-gray-900">Aktifkan Asisten Klinik</strong><span class="text-xs text-gray-500">Jika dimatikan, widget tetap dapat tampil tetapi memberi informasi bahwa AI sedang nonaktif.</span></span>
            </label>
            <label class="flex gap-3 p-4 border border-gray-200 rounded-xl">
                <input type="checkbox" name="allow_health" value="1" class="mt-1 rounded border-gray-300 text-[#1a5d3a]" @checked($settings['allow_health'])>
                <span><strong class="block text-sm text-gray-900">Izinkan skrining kesehatan umum</strong><span class="text-xs text-gray-500">AI boleh membahas gejala secara hati-hati, tetapi tidak memastikan diagnosis.</span></span>
            </label>
            <label class="flex gap-3 p-4 border border-gray-200 rounded-xl">
                <input type="checkbox" name="store_history" value="1" class="mt-1 rounded border-gray-300 text-[#1a5d3a]" @checked($settings['store_history'])>
                <span><strong class="block text-sm text-gray-900">Simpan riwayat chat</strong><span class="text-xs text-gray-500">Digunakan admin untuk evaluasi kualitas jawaban.</span></span>
            </label>
            <label class="flex gap-3 p-4 border border-gray-200 rounded-xl">
                <input type="checkbox" name="store_unanswered" value="1" class="mt-1 rounded border-gray-300 text-[#1a5d3a]" @checked($settings['store_unanswered'])>
                <span><strong class="block text-sm text-gray-900">Catat pertanyaan belum terjawab</strong><span class="text-xs text-gray-500">Admin dapat mengajarkan jawaban resmi kemudian.</span></span>
            </label>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Asisten</label>
            <input name="assistant_name" value="{{ old('assistant_name', $settings['assistant_name']) }}" class="w-full rounded-lg border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pesan Sambutan</label>
            <textarea name="welcome_message" rows="3" class="w-full rounded-lg border-gray-300" required>{{ old('welcome_message', $settings['welcome_message']) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pesan di Luar Topik</label>
            <textarea name="out_of_scope_message" rows="3" class="w-full rounded-lg border-gray-300" required>{{ old('out_of_scope_message', $settings['out_of_scope_message']) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pesan Ketika Informasi Klinik Belum Tersedia</label>
            <textarea name="unknown_message" rows="4" class="w-full rounded-lg border-gray-300" required>{{ old('unknown_message', $settings['unknown_message']) }}</textarea>
        </div>

        <div class="max-w-xs">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah pesan lama yang diingat AI</label>
            <input type="number" min="2" max="12" name="max_history_messages" value="{{ old('max_history_messages', $settings['max_history_messages']) }}" class="w-full rounded-lg border-gray-300" required>
            <p class="text-xs text-gray-500 mt-1">Lebih kecil = lebih hemat context/RAM. Untuk server saat ini disarankan 6–8.</p>
        </div>

        <div class="flex justify-end"><button class="px-5 py-2 rounded-lg bg-[#1a5d3a] text-white text-sm font-semibold">Simpan Pengaturan</button></div>
    </form>
</div>
@endsection
