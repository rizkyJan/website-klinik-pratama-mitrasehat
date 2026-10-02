@extends('admin.layout')
@section('title', 'Tambah Layanan')

@section('content')
@include('admin.services._styles')

<div class="kms-form-page">
    <a href="{{ route('admin.services.index') }}" class="kms-back">← Kembali</a>

    <div class="kms-form-head">
        <div>
            <p class="kms-eyebrow">Manajemen Layanan</p>
            <h1 class="kms-title">Tambah Layanan</h1>
            <p class="kms-subtitle">Tambahkan layanan baru yang akan dikelola dan ditampilkan di website klinik.</p>
        </div>
        <span id="liveStatus" class="kms-live-status">Tampil di Website</span>
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

    <form method="POST" action="{{ route('admin.services.store') }}">
        @csrf
        @include('admin.services._form', ['service' => null, 'submitLabel' => 'Simpan Layanan'])
    </form>
</div>
@endsection
