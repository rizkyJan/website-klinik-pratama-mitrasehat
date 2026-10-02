@extends('admin.layout')

@section('title', $knowledge->exists ? 'Edit Pengetahuan AI' : 'Tambah Pengetahuan AI')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-5">
        <a href="{{ route('admin.ai.knowledge.index') }}" class="text-sm text-[#1a5d3a] font-semibold">← Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $knowledge->exists ? 'Edit Pengetahuan AI' : 'Tambah Pengetahuan AI' }}</h1>
        <p class="text-sm text-gray-500 mt-1">Gunakan jawaban resmi dan spesifik. AI akan mencoba memahami variasi pertanyaan pasien dari kata kunci dan isi informasi ini.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $knowledge->exists ? route('admin.ai.knowledge.update', $knowledge) : route('admin.ai.knowledge.store') }}" class="bg-white border border-gray-200 rounded-xl p-5 space-y-5">
        @csrf
        @if($knowledge->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title', $knowledge->title) }}" class="w-full rounded-lg border-gray-300" required>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $knowledge->category ?: 'Informasi Klinik') }}" placeholder="Contoh: BPJS, Poli Gigi, Pendaftaran" class="w-full rounded-lg border-gray-300" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Urutan</label>
                <input type="number" min="0" max="9999" name="sort_order" value="{{ old('sort_order', $knowledge->sort_order ?? 0) }}" class="w-full rounded-lg border-gray-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Kata kunci / variasi pertanyaan</label>
            <textarea name="keywords" rows="3" class="w-full rounded-lg border-gray-300" placeholder="Contoh: scaling, skeling, karang gigi, bersihin karang gigi">{{ old('keywords', $knowledge->keywords) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Tidak harus berupa kalimat. Pisahkan dengan spasi atau koma.</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Informasi resmi / jawaban acuan</label>
            <textarea name="content" rows="10" class="w-full rounded-lg border-gray-300" required>{{ old('content', $knowledge->content) }}</textarea>
        </div>

        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-[#1a5d3a]" @checked(old('is_active', $knowledge->exists ? $knowledge->is_active : true))>
            <span class="text-sm font-semibold text-gray-700">Aktifkan pengetahuan ini</span>
        </label>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.ai.knowledge.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-semibold">Batal</a>
            <button class="px-5 py-2 rounded-lg bg-[#1a5d3a] text-white text-sm font-semibold">Simpan</button>
        </div>
    </form>
</div>
@endsection
