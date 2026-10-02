@extends('admin.layout')
@section('title', 'Edit Promo')

@section('content')
@include('admin.promos._styles')

<div class="kms-promo-page">
    @if(session('success'))
        <div class="kms-alert kms-alert-success">{{ session('success') }}</div>
    @endif

    <div class="kms-promo-head">
        <div>
            <p class="kms-promo-eyebrow">Manajemen Promo</p>
            <h1 class="kms-promo-title">Edit Promo</h1>
            <p class="kms-promo-subtitle">Perbarui informasi dan poster promo yang tampil pada website klinik.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.promos.update', $promo) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.promos._form')

        <div class="kms-form-actions">
            <a href="{{ route('admin.promos.index') }}" class="kms-btn kms-btn-secondary">Kembali ke Daftar</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
