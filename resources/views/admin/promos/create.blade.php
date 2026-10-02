@extends('admin.layout')
@section('title', 'Tambah Promo')

@section('content')
@include('admin.promos._styles')

<div class="kms-promo-page">
    <div class="kms-promo-head">
        <div>
            <p class="kms-promo-eyebrow">Manajemen Promo</p>
            <h1 class="kms-promo-title">Tambah Promo</h1>
            <p class="kms-promo-subtitle">Tambahkan informasi dan poster promo baru untuk website klinik.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.promos.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.promos._form')

        <div class="kms-form-actions">
            <a href="{{ route('admin.promos.index') }}" class="kms-btn kms-btn-secondary">Kembali</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Promo</button>
        </div>
    </form>
</div>
@endsection
