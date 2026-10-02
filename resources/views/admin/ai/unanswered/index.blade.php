@extends('admin.layout')

@section('title', 'Pertanyaan Belum Terjawab')

@section('content')
<div class="space-y-5">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pertanyaan Belum Terjawab</h1>
        <p class="text-sm text-gray-500 mt-1">Pertanyaan tentang klinik yang belum mempunyai sumber informasi yang cukup akan masuk otomatis ke sini.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach(['pending' => 'Belum Dijawab', 'answered' => 'Sudah Diajarkan', 'ignored' => 'Diabaikan', 'all' => 'Semua'] as $value => $label)
            <a href="{{ route('admin.ai.unanswered.index', ['status' => $value]) }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ $status === $value ? 'bg-[#1a5d3a] text-white' : 'bg-white border border-gray-200 text-gray-700' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Pertanyaan</th>
                        <th class="text-center px-4 py-3">Ditanyakan</th>
                        <th class="text-left px-4 py-3">Terakhir</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-right px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($questions as $item)
                        <tr>
                            <td class="px-4 py-3 max-w-xl">
                                <div class="font-semibold text-gray-900">{{ $item->question }}</div>
                                @if($item->last_question && $item->last_question !== $item->question)
                                    <div class="text-xs text-gray-500 mt-1">Variasi terakhir: {{ $item->last_question }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $item->occurrences }}×</td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ optional($item->last_asked_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $item->status === 'pending' ? 'bg-red-100 text-red-700' : ($item->status === 'answered' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">{{ $item->status === 'pending' ? 'Belum dijawab' : ($item->status === 'answered' ? 'Sudah diajarkan' : 'Diabaikan') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.ai.unanswered.show', $item) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-[#eef8f1] text-[#1a5d3a] font-semibold text-xs">Buka</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-gray-500">Tidak ada pertanyaan pada status ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($questions->hasPages())<div class="px-4 py-3 border-t border-gray-100">{{ $questions->links() }}</div>@endif
    </div>
</div>
@endsection
