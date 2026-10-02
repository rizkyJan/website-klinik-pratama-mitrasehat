@extends('admin.layout')

@section('title', 'Detail Riwayat Chat')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.ai.history.index') }}" class="text-sm text-[#1a5d3a] font-semibold">← Kembali</a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $conversation->title ?: 'Percakapan' }}</h1>
            <p class="text-xs text-gray-500 mt-1">{{ optional($conversation->last_activity_at)->format('d/m/Y H:i') }} · {{ $conversation->last_topic }}</p>
        </div>
        <form method="POST" action="{{ route('admin.ai.history.destroy', $conversation) }}" onsubmit="return confirm('Hapus seluruh riwayat percakapan ini?')">
            @csrf @method('DELETE')
            <button class="px-3 py-2 rounded-lg bg-red-50 text-red-700 text-xs font-semibold">Hapus</button>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 space-y-4">
        @forelse($conversation->messages as $message)
            <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm whitespace-pre-wrap {{ $message->role === 'user' ? 'bg-[#1a5d3a] text-white rounded-br-md' : 'bg-gray-100 text-gray-800 rounded-bl-md' }}">{{ $message->content }}</div>
            </div>
            @if($message->role === 'assistant' && $message->response_time_ms)
                <div class="text-[11px] text-gray-400 -mt-2">Jawaban AI: {{ number_format($message->response_time_ms / 1000, 1) }} detik · {{ $message->classification }}</div>
            @endif
        @empty
            <div class="text-center text-gray-500 py-8">Tidak ada pesan.</div>
        @endforelse
    </div>
</div>
@endsection
