@extends('admin.layout')
@section('title', 'Tambah Artikel')

@section('content')
@include('admin.articles._styles')

<div class="kms-article-page">
    <div class="kms-article-head">
        <div>
            <p class="kms-article-eyebrow">Manajemen Artikel</p>
            <h1 class="kms-article-title">Tambah Artikel</h1>
            <p class="kms-article-subtitle">Buat artikel baru untuk dibaca pengunjung website klinik.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.articles._form')

        <div class="kms-form-actions">
            <a href="{{ route('admin.articles.index') }}" class="kms-btn kms-btn-secondary">Kembali</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Artikel</button>
        </div>
    </form>
</div>
@endsection
