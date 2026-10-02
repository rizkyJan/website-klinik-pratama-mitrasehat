@extends('admin.layout')
@section('title', 'Tambah Cabang')

@section('content')
@include('admin.branches._styles')

<div class="kms-branch-page">
    <div class="kms-branch-head">
        <div>
            <p class="kms-branch-eyebrow">Manajemen Cabang</p>
            <h1 class="kms-branch-title">Tambah Cabang</h1>
            <p class="kms-branch-subtitle">Tambahkan lokasi cabang baru yang akan tampil pada halaman publik.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error"><strong>Ada data yang perlu diperbaiki:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('admin.branches.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.branches._form')
        <div class="kms-form-actions">
            <a href="{{ route('admin.branches.index') }}" class="kms-btn kms-btn-secondary">Kembali ke Daftar</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Cabang</button>
        </div>
    </form>
</div>
@endsection
