@extends('admin.layout')
@section('title', 'Edit Galeri')

@section('content')
@include('admin.galleries._styles')

<div class="kms-gallery-page">
    @if(session('success'))
        <div class="kms-alert kms-alert-success">{{ session('success') }}</div>
    @endif

    <div class="kms-gallery-head">
        <div>
            <p class="kms-gallery-eyebrow">Manajemen Galeri</p>
            <h1 class="kms-gallery-title">Edit Foto Galeri</h1>
            <p class="kms-gallery-subtitle">Perbarui kategori, caption, foto, dan komposisi gambar tanpa mengubah modul lainnya.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.galleries._form')

        <div class="kms-form-actions">
            <a href="{{ route('admin.galleries.index') }}" class="kms-btn kms-btn-secondary">Kembali ke Daftar</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
