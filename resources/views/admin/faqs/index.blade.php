@extends('admin.layout')
@section('title', 'Kelola FAQ')

@section('content')
<style>
    .faq-admin-mobile { display: none; }
    @media (max-width: 767px) {
        .faq-admin-desktop { display: none; }
        .faq-admin-mobile { display: grid; gap: 12px; }
        .faq-mobile-card {
            background: #fff;
            border: 1px solid #edf0ee;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 5px 14px rgba(23, 74, 48, .04);
        }
        .faq-mobile-question {
            margin: 0;
            color: #17261f;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.5;
        }
        .faq-mobile-answer {
            margin: 8px 0 0;
            color: #69756f;
            font-size: 12px;
            line-height: 1.65;
        }
        .faq-mobile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 12px;
        }
        .faq-mobile-badge {
            display: inline-flex;
            min-height: 28px;
            align-items: center;
            padding: 0 9px;
            border-radius: 999px;
            background: #edf7ef;
            color: #17613d;
            font-size: 10px;
            font-weight: 700;
        }
        .faq-mobile-actions {
            display: flex;
            gap: 8px;
            margin-top: 14px;
        }
        .faq-mobile-edit,
        .faq-mobile-delete {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }
        .faq-mobile-edit {
            background: #edf7ef;
            color: #17613d;
        }
        .faq-mobile-delete {
            border: 0;
            background: #fff0f0;
            color: #c62828;
            cursor: pointer;
        }
    }
</style>

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola FAQ</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pertanyaan dan jawaban yang tampil pada website klinik.</p>
    </div>
    <a href="{{ route('admin.faqs.create') }}" class="bg-[#1a5d3a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#154a2e] whitespace-nowrap">+ Tambah</a>
</div>

<div class="faq-admin-desktop bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
    <table class="w-full min-w-[860px] text-sm">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-xs uppercase">
                <th class="px-4 py-3 text-left">Pertanyaan</th>
                <th class="px-4 py-3 text-left">Jawaban</th>
                <th class="px-4 py-3 text-left">Ikon</th>
                <th class="px-4 py-3 text-left">Urutan</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($faqs as $faq)
            <tr class="border-t border-gray-100 hover:bg-gray-50 align-top">
                <td class="px-4 py-3 text-gray-700 font-medium max-w-[260px]">{{ $faq->question }}</td>
                <td class="px-4 py-3 text-gray-600 max-w-[440px]">{{ $faq->answer }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $faq->icon ?: '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $faq->sort_order }}</td>
                <td class="px-4 py-3 text-right whitespace-nowrap">
                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-[#1a5d3a] hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="inline" onsubmit="return confirm('Hapus FAQ ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-xs ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada FAQ.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="faq-admin-mobile">
    @forelse($faqs as $faq)
        <article class="faq-mobile-card">
            <h2 class="faq-mobile-question">{{ $faq->question }}</h2>
            <p class="faq-mobile-answer">{{ $faq->answer }}</p>
            <div class="faq-mobile-meta">
                <span class="faq-mobile-badge">Urutan {{ $faq->sort_order }}</span>
                @if($faq->icon)
                    <span class="faq-mobile-badge">Ikon: {{ $faq->icon }}</span>
                @endif
            </div>
            <div class="faq-mobile-actions">
                <a class="faq-mobile-edit" href="{{ route('admin.faqs.edit', $faq) }}">Edit</a>
                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Hapus FAQ ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="faq-mobile-delete" type="submit">Hapus</button>
                </form>
            </div>
        </article>
    @empty
        <div class="faq-mobile-card" style="text-align:center;color:#718077;">Belum ada FAQ.</div>
    @endforelse
</div>
@endsection
