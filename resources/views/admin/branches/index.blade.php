@extends('admin.layout')
@section('title', 'Kelola Cabang')

@section('content')
@include('admin.branches._styles')

<div class="kms-branch-page">
    @if(session('success'))
        <div class="kms-alert kms-alert-success">{{ session('success') }}</div>
    @endif

    <div class="kms-branch-head">
        <div>
            <p class="kms-branch-eyebrow">Manajemen Cabang</p>
            <h1 class="kms-branch-title">Kelola Cabang</h1>
            <p class="kms-branch-subtitle">Kelola nama, lokasi Google Maps, foto, dan deskripsi singkat cabang Klinik Mitra Sehat.</p>
        </div>
        <a class="kms-branch-add" href="{{ route('admin.branches.create') }}">+ Tambah Cabang</a>
    </div>

    <div class="kms-branch-stats">
        <div class="kms-branch-stat"><small>Total Cabang</small><strong>{{ $branches->count() }}</strong></div>
        <div class="kms-branch-stat"><small>Dengan Foto</small><strong>{{ $branches->filter(fn($branch) => filled($branch->photo))->count() }}</strong></div>
    </div>

    <div class="kms-table-card">
        <div class="kms-table-wrap">
            <table class="kms-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Cabang</th>
                        <th>Google Maps</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        @php
                            $photoUrl = $branch->photo
                                ? (filter_var($branch->photo, FILTER_VALIDATE_URL) ? $branch->photo : asset($branch->photo))
                                : null;
                        @endphp
                        <tr>
                            <td>
                                <div class="kms-photo-thumb">
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $branch->name }}">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <p class="kms-row-title">{{ $branch->name }}</p>
                                <p class="kms-row-desc">{{ \Illuminate\Support\Str::limit($branch->description ?: 'Belum ada deskripsi singkat.', 110) }}</p>
                            </td>
                            <td>
                                @if($branch->maps_url)
                                    <a class="kms-map-link" href="{{ $branch->maps_url }}" target="_blank" rel="noopener">Buka Google Maps ↗</a>
                                @else
                                    <span style="color:#9aa39d;font-size:11px">Belum ada link</span>
                                @endif
                            </td>
                            <td>
                                <div class="kms-actions-inline">
                                    <a class="kms-link kms-link-view" href="{{ route('information.branches.show', $branch) }}" target="_blank">Lihat Publik</a>
                                    <a class="kms-link kms-link-edit" href="{{ route('admin.branches.edit', $branch) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" onsubmit="return confirm('Hapus cabang {{ $branch->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="kms-link kms-link-delete" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;padding:38px;color:#8b958f">Belum ada data cabang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
