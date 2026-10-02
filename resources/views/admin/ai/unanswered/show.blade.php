@extends('admin.layout')

@section('title', 'Ajarkan AI')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    <div>
        <a href="{{ route('admin.ai.unanswered.index') }}" class="text-sm text-[#1a5d3a] font-semibold">← Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Ajarkan AI</h1>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="text-xs uppercase tracking-wide font-bold text-gray-400">Pertanyaan pasien</div>
        <div class="text-lg font-semibold text-gray-900 mt-2">{{ $unanswered->question }}</div>
        <div class="mt-3 text-sm text-gray-500">Sudah ditanyakan <strong>{{ $unanswered->occurrences }} kali</strong>. Terakhir {{ optional($unanswered->last_asked_at)->format('d/m/Y H:i') }}.</div>
        @if($unanswered->last_question && $unanswered->last_question !== $unanswered->question)
            <div class="mt-3 p-3 rounded-lg bg-gray-50 text-sm text-gray-700">Variasi terakhir: {{ $unanswered->last_question }}</div>
        @endif
    </div>

    @if($unanswered->status === 'pending')
        <form method="POST" action="{{ route('admin.ai.unanswered.teach', $unanswered) }}" class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">
            @csrf
            <div class="font-bold text-gray-900">Simpan jawaban resmi ke Pengetahuan AI</div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul pengetahuan</label>
                <input name="title" value="{{ old('title', \Illuminate\Support\Str::limit($unanswered->question, 100, '')) }}" class="w-full rounded-lg border-gray-300" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                <input name="category" value="{{ old('category', 'Informasi Klinik') }}" class="w-full rounded-lg border-gray-300" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kata kunci / variasi</label>
                <textarea name="keywords" rows="2" class="w-full rounded-lg border-gray-300">{{ old('keywords', trim($unanswered->question . "\n" . (($unanswered->last_question && $unanswered->last_question !== $unanswered->question) ? $unanswered->last_question . "\n" : '') . $unanswered->normalized_question)) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jawaban resmi</label>
                <textarea name="content" rows="8" class="w-full rounded-lg border-gray-300" placeholder="Isi informasi yang benar sesuai kebijakan klinik..." required>{{ old('content') }}</textarea>
            </div>
            <div class="flex flex-wrap justify-end gap-3">
                <button formaction="{{ route('admin.ai.unanswered.ignore', $unanswered) }}" formmethod="POST" formnovalidate class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-semibold" onclick="return confirm('Abaikan pertanyaan ini?')">Abaikan</button>
                <button class="px-5 py-2 rounded-lg bg-[#1a5d3a] text-white text-sm font-semibold">Simpan & Ajarkan AI</button>
            </div>
        </form>
    @else
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-sm text-gray-700">Status: <strong>{{ $unanswered->status === 'answered' ? 'Sudah diajarkan' : 'Diabaikan' }}</strong></p>
            @if($unanswered->knowledge)
                <p class="text-sm mt-2">Terhubung ke: <a class="font-semibold text-[#1a5d3a]" href="{{ route('admin.ai.knowledge.edit', $unanswered->knowledge) }}">{{ $unanswered->knowledge->title }}</a></p>
            @endif
            <form method="POST" action="{{ route('admin.ai.unanswered.reopen', $unanswered) }}" class="mt-4">@csrf<button class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold">Buka Kembali</button></form>
        </div>
    @endif
</div>
@endsection
