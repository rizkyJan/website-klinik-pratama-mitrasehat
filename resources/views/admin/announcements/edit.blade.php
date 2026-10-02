@extends('admin.layout')
@section('title', 'Edit Pengumuman')

@section('content')
@include('admin.announcements._styles')

<div class="kms-ann-page">
    <div class="kms-ann-head">
        <div>
            <p class="kms-ann-eyebrow">Informasi Klinik</p>
            <h1 class="kms-ann-title">Edit Pengumuman</h1>
            <p class="kms-ann-subtitle">Perbarui isi, periode, atau status pengumuman.</p>
        </div>
        <a href="{{ route('information.announcements') }}" target="_blank" class="kms-btn kms-btn-secondary">Lihat Halaman Publik</a>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
        @csrf
        @method('PUT')
        @include('admin.announcements._form', ['announcement' => $announcement])
        <div class="kms-form-actions">
            <a href="{{ route('admin.announcements.index') }}" class="kms-btn kms-btn-secondary">Kembali ke Daftar</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
