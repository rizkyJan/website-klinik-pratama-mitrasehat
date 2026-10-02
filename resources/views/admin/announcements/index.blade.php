@extends('admin.layout')
@section('title', 'Kelola Pengumuman')

@section('content')
@include('admin.announcements._styles')

@php
    $activeCount = $announcements->filter(fn($item) => $item->status_label === 'Aktif')->count();
    $scheduledCount = $announcements->filter(fn($item) => $item->status_label === 'Terjadwal')->count();
    $endedCount = $announcements->filter(fn($item) => $item->status_label === 'Berakhir')->count();
@endphp

<div class="kms-ann-page">
    <div class="kms-ann-head">
        <div>
            <p class="kms-ann-eyebrow">Informasi Klinik</p>
            <h1 class="kms-ann-title">Kelola Pengumuman</h1>
            <p class="kms-ann-subtitle">Buat pemberitahuan yang tampil otomatis sesuai tanggal mulai dan selesai.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="kms-ann-add">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
            Tambah Pengumuman
        </a>
    </div>

    <div class="kms-stats">
        <div class="kms-stat"><small>Total</small><strong>{{ $announcements->count() }}</strong></div>
        <div class="kms-stat"><small>Sedang Tampil</small><strong>{{ $activeCount }}</strong></div>
        <div class="kms-stat"><small>Terjadwal</small><strong>{{ $scheduledCount }}</strong></div>
        <div class="kms-stat"><small>Berakhir</small><strong>{{ $endedCount }}</strong></div>
    </div>

    <div class="kms-table-card">
        <div class="kms-table-wrap">
            <table class="kms-table">
                <thead>
                    <tr>
                        <th>Pengumuman</th>
                        <th style="width:120px;">Kategori</th>
                        <th style="width:180px;">Periode</th>
                        <th style="width:110px;">Status</th>
                        <th style="width:190px;text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        @php
                            $categoryClass = match($announcement->category) {
                                'urgent' => 'kms-badge-urgent',
                                'important' => 'kms-badge-important',
                                default => 'kms-badge-info',
                            };
                            $statusClass = match($announcement->status_label) {
                                'Aktif' => 'kms-badge-active',
                                'Terjadwal' => 'kms-badge-scheduled',
                                'Berakhir' => 'kms-badge-ended',
                                default => 'kms-badge-off',
                            };
                        @endphp
                        <tr>
                            <td>
                                <p class="kms-row-title">{{ $announcement->title }}</p>
                                <p class="kms-row-content">{{ \Illuminate\Support\Str::limit($announcement->content, 105) }}</p>
                                @if($announcement->is_pinned)<span class="kms-pin">★ Diprioritaskan</span>@endif
                            </td>
                            <td><span class="kms-badge {{ $categoryClass }}">{{ $announcement->category_label }}</span></td>
                            <td class="kms-date">
                                {{ $announcement->start_date->format('d/m/Y') }}<br>
                                <span style="color:#8a958e;font-size:10px;">s.d. {{ $announcement->end_date?->format('d/m/Y') ?? 'tanpa batas' }}</span>
                            </td>
                            <td><span class="kms-badge {{ $statusClass }}">{{ $announcement->status_label }}</span></td>
                            <td>
                                <div class="kms-actions-inline">
                                    @if($announcement->isCurrentlyVisible())
                                        <a class="kms-link kms-link-view" href="{{ route('information.announcements') }}" target="_blank">Lihat Publik</a>
                                    @endif
                                    <a class="kms-link kms-link-edit" href="{{ route('admin.announcements.edit', $announcement) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="kms-link kms-link-delete" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="kms-empty" colspan="5">Belum ada pengumuman. Klik “Tambah Pengumuman” untuk membuat yang pertama.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
