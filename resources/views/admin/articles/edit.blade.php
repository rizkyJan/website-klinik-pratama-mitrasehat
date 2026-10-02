@extends('admin.layout')
@section('title', 'Edit Artikel')

@section('content')
@include('admin.articles._styles')

<div class="kms-article-page">
    @if(session('success'))
        <div class="kms-alert kms-alert-success">{{ session('success') }}</div>
    @endif

    <div class="kms-article-head">
        <div>
            <p class="kms-article-eyebrow">Manajemen Artikel</p>
            <h1 class="kms-article-title">Edit Artikel</h1>
            <p class="kms-article-subtitle">Perbarui foto dan isi artikel tanpa mengubah modul lain.</p>
        </div>
        <a href="{{ route('articles.show', $article) }}" class="kms-btn kms-btn-secondary" target="_blank">Lihat di Website</a>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.articles._form', ['article' => $article])

        <div class="kms-form-actions">
            <a href="{{ route('admin.articles.index') }}" class="kms-btn kms-btn-secondary">Kembali ke Daftar</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
