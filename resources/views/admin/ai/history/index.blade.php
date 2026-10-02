@extends('admin.layout')

@section('title', 'Riwayat Chat AI')

@section('content')
<div class="space-y-5">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Chat</h1>
        <p class="text-sm text-gray-500 mt-1">Percakapan disimpan tanpa IP pengguna. NIK, email, dan nomor telepon yang terdeteksi juga disamarkan sebelum diteruskan ke AI.</p>
    </div>

    <form method="GET" class="flex flex-wrap gap-2 bg-white border border-gray-200 rounded-xl p-4">
        <select name="topic" class="rounded-lg border-gray-300 text-sm">
            <option value="">Semua topik</option>
            <option value="clinic" @selected(request('topic') === 'clinic')>Informasi Klinik</option>
            <option value="health" @selected(request('topic') === 'health')>Kesehatan</option>
            <option value="health_urgent" @selected(request('topic') === 'health_urgent')>Tanda Bahaya</option>
            <option value="clinic_unknown" @selected(request('topic') === 'clinic_unknown')>Belum Terjawab</option>
            <option value="out_of_scope" @selected(request('topic') === 'out_of_scope')>Di Luar Topik</option>
        </select>
        <button class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold">Filter</button>
    </form>

    <div class="grid gap-3">
        @forelse($conversations as $conversation)
            <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="font-semibold text-gray-900 truncate">{{ $conversation->title ?: 'Percakapan tanpa judul' }}</div>
                    <div class="mt-1 text-xs text-gray-500 flex flex-wrap gap-x-4 gap-y-1">
                        <span>{{ $conversation->messages_count }} pesan</span>
                        <span>Topik: {{ $conversation->last_topic ?: '-' }}</span>
                        <span>{{ optional($conversation->last_activity_at)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.ai.history.show', $conversation) }}" class="inline-flex justify-center px-4 py-2 rounded-lg bg-[#eef8f1] text-[#1a5d3a] text-sm font-semibold">Lihat Percakapan</a>
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-xl p-10 text-center text-gray-500">Belum ada riwayat chat.</div>
        @endforelse
    </div>

    @if($conversations->hasPages())<div>{{ $conversations->links() }}</div>@endif
</div>
@endsection
