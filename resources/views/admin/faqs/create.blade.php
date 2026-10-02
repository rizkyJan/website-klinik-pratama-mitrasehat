@extends('admin.layout')
@section('title', 'Tambah Faqs')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.faqs.index') }}" class="text-sm text-[#1a5d3a] hover:underline">Kembali</a>
    <h1 class="text-xl font-bold text-gray-800 mt-2">Tambah Faqs</h1>
</div>
<form method="POST" action="{{ route('admin.faqs.store') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4 max-w-lg">
    @csrf
    <div><label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label><textarea name="question" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a5d3a]"></textarea></div>
    <div><label class="block text-sm font-medium text-gray-700 mb-1">Jawaban</label><textarea name="answer" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a5d3a]"></textarea></div>
    <div><label class="block text-sm font-medium text-gray-700 mb-1">Ikon (opsional)</label><input type="text" name="icon" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a5d3a]"></div>
    <div><label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label><input type="number" name="sort_order" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a5d3a]"></div>
    <button type="submit" class="bg-[#1a5d3a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#154a2e]">Simpan</button>
</form>
@endsection