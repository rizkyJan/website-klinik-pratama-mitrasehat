@extends('layouts.app')
@section('pageTitle', 'Pengumuman Klinik - Klinik Pratama Mitra Sehat')

@section('content')
<style>
    .ann-page{min-height:70vh;padding:42px 0 54px;background:#fbf7e9;color:#4d5c53}.ann-wrap{width:min(980px,calc(100% - 40px));margin:0 auto}.ann-head{text-align:center;margin-bottom:28px}.ann-kicker{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border-radius:999px;background:#e7f3e9;color:#1a5d3a;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.06em}.ann-kicker i{width:7px;height:7px;border-radius:50%;background:#1a5d3a}.ann-title{margin:11px 0 0;color:#1a5d3a;font-size:clamp(31px,4vw,44px);font-weight:800;letter-spacing:-.03em}.ann-subtitle{max-width:620px;margin:8px auto 0;color:#707b74;font-size:13px;line-height:1.65}.ann-list{display:grid;gap:14px}.ann-card{padding:20px 21px;border:1px solid #e0e7e1;border-radius:18px;background:#fff;box-shadow:0 6px 17px rgba(31,75,50,.04);scroll-margin-top:90px}.ann-card-top{display:flex;align-items:center;justify-content:space-between;gap:12px}.ann-meta{display:flex;align-items:center;gap:8px;flex-wrap:wrap}.ann-badge{display:inline-flex;align-items:center;min-height:24px;padding:0 9px;border-radius:999px;font-size:9px;font-weight:800}.ann-badge-info{background:#eef5ff;color:#315f9c}.ann-badge-important{background:#fff4d5;color:#896000}.ann-badge-urgent{background:#feeceb;color:#a92b22}.ann-pin{display:inline-flex;color:#8c6500;font-size:9px;font-weight:800}.ann-date{color:#929a94;font-size:10px;white-space:nowrap}.ann-card h2{margin:12px 0 0;color:#245b3b;font-size:18px;line-height:1.35;font-weight:800}.ann-content{margin:9px 0 0;color:#606d64;font-size:12.5px;line-height:1.75;white-space:pre-line}.ann-period{margin-top:14px;padding-top:11px;border-top:1px solid #eef1ee;color:#879089;font-size:10px}.ann-empty{padding:42px 22px;text-align:center;border:1px dashed #d7e2d8;border-radius:18px;background:#fff}.ann-empty strong{display:block;color:#285f3f;font-size:16px}.ann-empty p{margin:7px 0 0;color:#7d8881;font-size:12px}.ann-back{display:flex;justify-content:center;margin-top:24px}.ann-back a{display:inline-flex;min-height:40px;align-items:center;padding:0 15px;border-radius:9px;background:#1a5d3a;color:#fff;text-decoration:none;font-size:11px;font-weight:800}
    @media(max-width:600px){.ann-page{padding-top:28px}.ann-wrap{width:calc(100% - 22px)}.ann-card{padding:17px}.ann-card-top{align-items:flex-start;flex-direction:column}.ann-title{font-size:30px}.ann-card h2{font-size:16px}.ann-content{font-size:12px}}
</style>

<section class="ann-page">
    <div class="ann-wrap">
        <header class="ann-head">
            <div class="ann-kicker"><i></i> Informasi Resmi Klinik</div>
            <h1 class="ann-title">Pengumuman Klinik</h1>
            <p class="ann-subtitle">Pemberitahuan terbaru mengenai pelayanan, perubahan jadwal, dan informasi penting Klinik Pratama Mitra Sehat.</p>
        </header>

        <div class="ann-list">
            @forelse($announcements as $announcement)
                @php
                    $badgeClass = match($announcement->category) {
                        'urgent' => 'ann-badge-urgent',
                        'important' => 'ann-badge-important',
                        default => 'ann-badge-info',
                    };
                @endphp
                <article class="ann-card" id="pengumuman-{{ $announcement->id }}">
                    <div class="ann-card-top">
                        <div class="ann-meta">
                            <span class="ann-badge {{ $badgeClass }}">{{ $announcement->category_label }}</span>
                            @if($announcement->is_pinned)<span class="ann-pin">★ Pengumuman Prioritas</span>@endif
                        </div>
                        <time class="ann-date" datetime="{{ $announcement->start_date->format('Y-m-d') }}">{{ $announcement->start_date->format('d/m/Y') }}</time>
                    </div>
                    <h2>{{ $announcement->title }}</h2>
                    <div class="ann-content">{{ $announcement->content }}</div>
                    <div class="ann-period">
                        Ditampilkan mulai {{ $announcement->start_date->format('d/m/Y') }}
                        @if($announcement->end_date)
                            sampai {{ $announcement->end_date->format('d/m/Y') }}.
                        @else
                            tanpa tanggal berakhir.
                        @endif
                    </div>
                </article>
            @empty
                <div class="ann-empty">
                    <strong>Belum ada pengumuman aktif.</strong>
                    <p>Informasi terbaru dari klinik akan ditampilkan di halaman ini.</p>
                </div>
            @endforelse
        </div>

        <div class="ann-back"><a href="{{ route('information') }}">← Kembali ke Informasi</a></div>
    </div>
</section>
@endsection
