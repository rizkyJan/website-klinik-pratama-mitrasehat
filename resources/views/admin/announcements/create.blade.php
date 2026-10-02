@extends('admin.layout')
@section('title', 'Tambah Pengumuman')

@section('content')
@include('admin.announcements._styles')

<div class="kms-ann-page">
    <div class="kms-ann-head">
        <div>
            <p class="kms-ann-eyebrow">Informasi Klinik</p>
            <h1 class="kms-ann-title">Tambah Pengumuman</h1>
            <p class="kms-ann-subtitle">Buat pengumuman baru untuk pengunjung website klinik.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.announcements.store') }}">
        @csrf
        @include('admin.announcements._form')
        <div class="kms-form-actions">
            <a href="{{ route('admin.announcements.index') }}" class="kms-btn kms-btn-secondary">Kembali</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Pengumuman</button>
        </div>
    </form>
</div>
@endsection
