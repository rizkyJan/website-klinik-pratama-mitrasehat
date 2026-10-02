@extends('admin.layout')
@section('title', 'Kelola Galeri')

@section('content')
@include('admin.galleries._styles')

<div class="kms-gallery-page">
    @if(session('success'))
        <div class="kms-alert kms-alert-success">{{ session('success') }}</div>
    @endif

    <div class="kms-gallery-head">
        <div>
            <p class="kms-gallery-eyebrow">Manajemen Galeri</p>
            <h1 class="kms-gallery-title">Kelola Galeri</h1>
            <p class="kms-gallery-subtitle">Kelola dokumentasi pelayanan, fasilitas, kegiatan, dan momen klinik yang tampil di website.</p>
        </div>
        <a href="{{ route('admin.galleries.create') }}" class="kms-gallery-add">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
            Tambah Foto
        </a>
    </div>

    <div class="kms-gallery-stats">
        <div class="kms-gallery-stat"><small>Total Galeri</small><strong>{{ $galleries->count() }}</strong></div>
        <div class="kms-gallery-stat"><small>Dengan Foto</small><strong>{{ $galleries->filter(fn($item) => filled($item->photo))->count() }}</strong></div>
        <div class="kms-gallery-stat"><small>Kategori</small><strong>{{ $galleries->pluck('category')->filter()->unique()->count() }}</strong></div>
    </div>

    <div class="kms-table-card">
        <div class="kms-table-wrap">
            <table class="kms-table">
                <thead>
                    <tr>
                        <th style="width:110px;">Foto</th>
                        <th style="width:190px;">Kategori</th>
                        <th>Caption</th>
                        <th style="width:155px;text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($galleries as $gallery)
                        @php
                            $photoUrl = null;
                            if ($gallery->photo) {
                                $photoUrl = filter_var($gallery->photo, FILTER_VALIDATE_URL)
                                    ? $gallery->photo
                                    : asset($gallery->photo);
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="kms-gallery-thumb">
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $gallery->category }}">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                                    @endif
                                </div>
                            </td>
                            <td><span class="kms-chip">{{ $gallery->category ?: 'Tanpa kategori' }}</span></td>
                            <td>
                                <p class="kms-row-title">{{ $gallery->caption ? \Illuminate\Support\Str::limit($gallery->caption, 70) : 'Tanpa caption' }}</p>
                                <p class="kms-row-caption">{{ $gallery->photo ? 'Foto siap ditampilkan pada galeri publik.' : 'Belum ada foto pada item ini.' }}</p>
                            </td>
                            <td>
                                <div class="kms-actions-inline">
                                    <a class="kms-link kms-link-edit" href="{{ route('admin.galleries.edit', $gallery) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" onsubmit="return confirm('Hapus foto galeri ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="kms-link kms-link-delete" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;padding:38px;color:#8b958f;">Belum ada foto galeri.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
