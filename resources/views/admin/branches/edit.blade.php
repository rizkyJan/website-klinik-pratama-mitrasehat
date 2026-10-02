@extends('admin.layout')
@section('title', 'Edit Cabang')

@section('content')
@include('admin.branches._styles')

<div class="kms-branch-page">
    @if(session('success'))
        <div class="kms-alert kms-alert-success">{{ session('success') }}</div>
    @endif

    <div class="kms-branch-head">
        <div>
            <p class="kms-branch-eyebrow">Manajemen Cabang</p>
            <h1 class="kms-branch-title">Edit Cabang</h1>
            <p class="kms-branch-subtitle">Perbarui nama, lokasi, foto, dan deskripsi singkat cabang.</p>
        </div>
        <a href="{{ route('information.branches.show', $branch) }}" target="_blank" class="kms-btn kms-btn-secondary">Lihat di Website</a>
    </div>

    @if($errors->any())
        <div class="kms-alert kms-alert-error"><strong>Ada data yang perlu diperbaiki:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('admin.branches.update', $branch) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.branches._form')
        <div class="kms-form-actions">
            <a href="{{ route('admin.branches.index') }}" class="kms-btn kms-btn-secondary">Kembali ke Daftar</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
