@extends('admin.layout')
@section('title', 'Tambah Galeri')

@section('content')
@include('admin.galleries._styles')

<div class="kms-gallery-page">
    <div class="kms-gallery-head">
        <div>
            <p class="kms-gallery-eyebrow">Manajemen Galeri</p>
            <h1 class="kms-gallery-title">Tambah Foto Galeri</h1>
            <p class="kms-gallery-subtitle">Tambahkan dokumentasi baru yang akan tampil pada halaman galeri website klinik.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.galleries._form')

        <div class="kms-form-actions">
            <a href="{{ route('admin.galleries.index') }}" class="kms-btn kms-btn-secondary">Kembali</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Galeri</button>
        </div>
    </form>
</div>
@endsection
