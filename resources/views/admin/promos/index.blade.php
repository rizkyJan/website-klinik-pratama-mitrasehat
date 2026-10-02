@extends('admin.layout')
@section('title', 'Kelola Promo')

@section('content')
@include('admin.promos._styles')

<div class="kms-promo-page">
    @if(session('success'))
        <div class="kms-alert kms-alert-success">{{ session('success') }}</div>
    @endif

    <div class="kms-promo-head">
        <div>
            <p class="kms-promo-eyebrow">Manajemen Promo</p>
            <h1 class="kms-promo-title">Kelola Promo</h1>
            <p class="kms-promo-subtitle">Atur judul, periode, harga, dan poster promo yang tampil pada website klinik.</p>
        </div>
        <a href="{{ route('admin.promos.create') }}" class="kms-promo-add">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
            Tambah Promo
        </a>
    </div>

    <div class="kms-promo-stats">
        <div class="kms-promo-stat"><small>Total Promo</small><strong>{{ $promos->count() }}</strong></div>
        <div class="kms-promo-stat"><small>Dengan Poster</small><strong>{{ $promos->filter(fn($item) => filled($item->poster))->count() }}</strong></div>
        <div class="kms-promo-stat"><small>Periode</small><strong>{{ $promos->pluck('period')->filter()->unique()->count() }}</strong></div>
    </div>

    <div class="kms-table-card">
        <div class="kms-table-wrap">
            <table class="kms-table">
                <thead>
                    <tr>
                        <th style="width:100px;">Poster</th>
                        <th>Promo</th>
                        <th style="width:190px;">Periode</th>
                        <th style="width:150px;">Harga</th>
                        <th style="width:155px;text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $promo)
                        @php
                            $posterUrl = null;
                            if ($promo->poster) {
                                $posterUrl = filter_var($promo->poster, FILTER_VALIDATE_URL)
                                    ? $promo->poster
                                    : asset($promo->poster);
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="kms-poster-thumb">
                                    @if($posterUrl)
                                        <img src="{{ $posterUrl }}" alt="{{ $promo->title }}">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <p class="kms-row-title">{{ $promo->title }}</p>
                                <p class="kms-row-meta">Promo Klinik Pratama Mitra Sehat</p>
                            </td>
                            <td><span class="kms-chip">{{ $promo->period ?: '-' }}</span></td>
                            <td><span class="kms-price">{{ $promo->price ?: '-' }}</span></td>
                            <td>
                                <div class="kms-actions-inline">
                                    <a class="kms-link kms-link-edit" href="{{ route('admin.promos.edit', $promo) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.promos.destroy', $promo) }}" onsubmit="return confirm('Hapus promo ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="kms-link kms-link-delete" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:38px;color:#8b958f;">Belum ada promo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
