@extends('admin.layout')
@section('title', 'Edit Layanan')

@section('content')
@include('admin.services._styles')

<div class="kms-form-page">
    <a href="{{ route('admin.services.index') }}" class="kms-back">← Kembali</a>

    <div class="kms-form-head">
        <div>
            <p class="kms-eyebrow">Manajemen Layanan</p>
            <h1 class="kms-title">Edit Layanan</h1>
            <p class="kms-subtitle">Perbarui informasi, ikon, status, dan urutan layanan yang tampil di website.</p>
        </div>
        <span id="liveStatus" class="kms-live-status {{ old('is_active', $service->is_active) ? '' : 'off' }}">
            {{ old('is_active', $service->is_active) ? 'Tampil di Website' : 'Disembunyikan' }}
        </span>
    </div>

    @if($errors->any())
        <div class="kms-errors">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.services.update', $service) }}">
        @csrf
        @method('PUT')
        @include('admin.services._form', ['service' => $service, 'submitLabel' => 'Simpan Perubahan'])
    </form>
</div>
@endsection
