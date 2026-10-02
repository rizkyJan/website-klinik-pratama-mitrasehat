@extends('admin.layout')

@section('title', 'Pengetahuan AI')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengetahuan AI</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi resmi yang boleh dipakai Asisten Klinik untuk menjawab pertanyaan pasien.</p>
        </div>
        <a href="{{ route('admin.ai.knowledge.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-[#1a5d3a] text-white text-sm font-semibold hover:bg-[#154a2e]">+ Tambah Pengetahuan</a>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800">
        AI tidak belajar otomatis dari pasien. Hanya informasi yang ditambahkan atau disetujui admin di halaman ini yang menjadi pengetahuan resmi klinik.
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, kategori, kata kunci..." class="flex-1 rounded-lg border-gray-300 text-sm">
        <select name="status" class="rounded-lg border-gray-300 text-sm">
            <option value="">Semua status</option>
            <option value="active" @selected(request('status') === 'active')>Aktif</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
        </select>
        <button class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold">Cari</button>
    </form>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Judul</th>
                        <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                        <th class="text-left px-4 py-3 font-semibold">Sumber</th>
                        <th class="text-center px-4 py-3 font-semibold">Status</th>
                        <th class="text-right px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($knowledge as $item)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-4 py-3 align-top">
                                <div class="font-semibold text-gray-900">{{ $item->title }}</div>
                                <div class="text-xs text-gray-500 mt-1 max-w-xl">{{ \Illuminate\Support\Str::limit($item->content, 150) }}</div>
                            </td>
                            <td class="px-4 py-3 align-top text-gray-700">{{ $item->category }}</td>
                            <td class="px-4 py-3 align-top text-gray-500">{{ $item->source === 'template_whatsapp' ? 'Template WhatsApp' : ($item->source === 'unanswered_question' ? 'Diajarkan Admin' : 'Manual') }}</td>
                            <td class="px-4 py-3 align-top text-center">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.ai.knowledge.edit', $item) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs">Edit</a>
                                    <form method="POST" action="{{ route('admin.ai.knowledge.destroy', $item) }}" onsubmit="return confirm('Hapus pengetahuan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 font-semibold text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada pengetahuan AI.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($knowledge->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $knowledge->links() }}</div>
        @endif
    </div>
</div>
@endsection
