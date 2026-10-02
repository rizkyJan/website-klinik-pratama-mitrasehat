@extends('admin.layout')
@section('title', 'Dashboard')

@push('styles')
<style>
    .dash-shell { display:grid; gap:20px; }
    .dash-hero { position:relative; overflow:hidden; border-radius:22px; padding:24px; background:linear-gradient(135deg,#155b39 0%,#1f7a4d 58%,#3a9667 100%); color:#fff; box-shadow:0 18px 45px rgba(21,91,57,.18); }
    .dash-hero:before,.dash-hero:after { content:""; position:absolute; border-radius:999px; background:rgba(255,255,255,.08); pointer-events:none; }
    .dash-hero:before { width:250px; height:250px; right:-65px; top:-145px; }
    .dash-hero:after { width:170px; height:170px; right:110px; bottom:-130px; }
    .dash-hero-inner { position:relative; z-index:1; display:flex; align-items:flex-end; justify-content:space-between; gap:20px; }
    .dash-kicker { display:inline-flex; align-items:center; gap:7px; font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; color:#c7f3d8; }
    .dash-kicker-dot { width:7px; height:7px; border-radius:50%; background:#86efac; box-shadow:0 0 0 5px rgba(134,239,172,.14); }
    .dash-title { margin-top:8px; font-size:26px; line-height:1.15; font-weight:800; letter-spacing:-.02em; }
    .dash-subtitle { margin-top:7px; max-width:620px; color:#dcfce7; font-size:13px; line-height:1.65; }
    .dash-date { flex:0 0 auto; min-width:185px; padding:12px 14px; border:1px solid rgba(255,255,255,.16); border-radius:15px; background:rgba(255,255,255,.09); backdrop-filter:blur(5px); }
    .dash-date-label { font-size:10px; text-transform:uppercase; letter-spacing:.08em; color:#bbf7d0; font-weight:800; }
    .dash-date-value { margin-top:4px; font-size:13px; font-weight:800; }

    .metric-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; }
    .metric-card { position:relative; overflow:hidden; min-height:150px; padding:18px; border-radius:18px; background:#fff; border:1px solid #edf1ee; box-shadow:0 8px 25px rgba(15,23,42,.045); }
    .metric-top { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
    .metric-icon { display:flex; width:42px; height:42px; align-items:center; justify-content:center; border-radius:13px; background:#ecf8f1; color:#1a6b43; }
    .metric-icon svg { width:20px; height:20px; }
    .metric-label { margin-top:14px; color:#64748b; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.055em; }
    .metric-value { margin-top:2px; font-size:30px; line-height:1.15; font-weight:900; letter-spacing:-.04em; color:#163b2a; }
    .metric-foot { margin-top:7px; font-size:11px; color:#94a3b8; }
    .metric-trend { display:inline-flex; align-items:center; gap:4px; padding:5px 8px; border-radius:999px; font-size:10px; font-weight:800; white-space:nowrap; }
    .metric-trend.up { color:#166534; background:#dcfce7; }
    .metric-trend.down { color:#b91c1c; background:#fee2e2; }
    .metric-trend.flat { color:#475569; background:#f1f5f9; }

    .quick-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; }
    .quick-card { display:flex; align-items:center; gap:12px; min-height:82px; padding:14px 15px; border:1px solid #edf1ee; border-radius:16px; background:#fff; text-decoration:none; transition:.16s ease; }
    .quick-card:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(15,23,42,.07); border-color:#d8e8de; }
    .quick-bubble { width:40px; height:40px; flex:0 0 auto; display:flex; align-items:center; justify-content:center; border-radius:12px; background:#f0f8f3; color:#236b48; }
    .quick-bubble svg { width:19px; height:19px; }
    .quick-value { font-size:20px; line-height:1; color:#173d2b; font-weight:900; }
    .quick-label { margin-top:4px; font-size:11px; color:#64748b; font-weight:700; }

    .dash-two { display:grid; grid-template-columns:minmax(0,1.65fr) minmax(280px,.75fr); gap:16px; }
    .dash-card { border-radius:18px; border:1px solid #edf1ee; background:#fff; box-shadow:0 8px 25px rgba(15,23,42,.035); }
    .dash-card-head { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; padding:18px 18px 10px; }
    .dash-card-title { color:#173d2b; font-size:14px; font-weight:900; }
    .dash-card-sub { margin-top:3px; color:#94a3b8; font-size:11px; line-height:1.5; }
    .range-tabs { display:inline-flex; gap:4px; padding:4px; border-radius:12px; background:#f5f8f6; }
    .range-tab { padding:6px 9px; border-radius:8px; text-decoration:none; color:#64748b; font-size:10px; font-weight:800; }
    .range-tab:hover,.range-tab.active { color:#155b39; background:#fff; box-shadow:0 2px 7px rgba(15,23,42,.06); }
    .chart-wrap { padding:2px 16px 16px; }
    .chart-legend { display:flex; align-items:center; gap:16px; margin:4px 0 8px 6px; color:#64748b; font-size:10px; font-weight:700; }
    .legend-item { display:flex; align-items:center; gap:6px; }
    .legend-dot { width:8px; height:8px; border-radius:50%; }
    .chart-svg { width:100%; height:auto; min-height:235px; }
    .chart-grid { stroke:#edf2ef; stroke-width:1; }
    .chart-line-visitors { fill:none; stroke:#1a6b43; stroke-width:3; stroke-linecap:round; stroke-linejoin:round; }
    .chart-line-views { fill:none; stroke:#55a886; stroke-width:2.5; stroke-linecap:round; stroke-linejoin:round; stroke-dasharray:6 5; }
    .chart-point { fill:#fff; stroke:#1a6b43; stroke-width:2; }
    .chart-label { fill:#94a3b8; font-size:9px; font-family:ui-sans-serif,system-ui,sans-serif; }
    .chart-number { fill:#94a3b8; font-size:8px; font-family:ui-sans-serif,system-ui,sans-serif; }
    .empty-chart { display:flex; min-height:235px; align-items:center; justify-content:center; text-align:center; color:#94a3b8; font-size:12px; padding:24px; }

    .top-pages { padding:2px 18px 18px; display:grid; gap:13px; }
    .page-row { display:grid; gap:6px; }
    .page-meta { display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .page-name { min-width:0; color:#334155; font-size:11px; font-weight:800; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .page-count { color:#155b39; font-size:10px; font-weight:900; }
    .page-bar { height:6px; overflow:hidden; border-radius:999px; background:#f0f4f1; }
    .page-fill { height:100%; border-radius:inherit; background:linear-gradient(90deg,#1b6b45,#6bb493); }

    .dash-bottom { display:grid; grid-template-columns:minmax(0,1.18fr) minmax(0,.82fr); gap:16px; }
    .activity-list { padding:0 18px 18px; }
    .activity-item { display:grid; grid-template-columns:38px minmax(0,1fr) auto; gap:11px; align-items:center; padding:12px 0; border-top:1px solid #f1f5f3; text-decoration:none; }
    .activity-item:first-child { border-top:0; }
    .activity-icon { width:36px; height:36px; display:flex; align-items:center; justify-content:center; border-radius:11px; background:#f2f8f4; color:#226946; }
    .activity-icon svg { width:17px; height:17px; }
    .activity-title { color:#334155; font-size:11px; font-weight:900; }
    .activity-desc { margin-top:2px; color:#94a3b8; font-size:10px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .activity-time { color:#94a3b8; font-size:9px; font-weight:700; white-space:nowrap; }

    .content-grid { padding:2px 18px 18px; display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; }
    .content-link { padding:11px; border-radius:12px; border:1px solid #eef2ef; text-decoration:none; background:#fbfcfb; }
    .content-link:hover { border-color:#d8e8de; background:#f7fbf8; }
    .content-number { color:#173d2b; font-size:17px; font-weight:900; }
    .content-label { margin-top:2px; color:#64748b; font-size:10px; font-weight:700; }
    .analytics-note { margin-top:12px; padding:11px 13px; border-radius:12px; background:#f7faf8; border:1px dashed #dfe9e2; color:#64748b; font-size:10px; line-height:1.55; }

    @media (max-width: 1050px) {
        .metric-grid,.quick-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .dash-two,.dash-bottom { grid-template-columns:1fr; }
    }
    @media (max-width: 640px) {
        .dash-hero { padding:20px; border-radius:18px; }
        .dash-hero-inner { align-items:flex-start; flex-direction:column; }
        .dash-title { font-size:22px; }
        .dash-date { min-width:0; width:100%; }
        .metric-grid,.quick-grid { grid-template-columns:1fr; }
        .metric-card { min-height:135px; }
        .dash-card-head { align-items:flex-start; flex-direction:column; }
        .range-tabs { width:100%; }
        .range-tab { flex:1; text-align:center; }
        .activity-item { grid-template-columns:36px minmax(0,1fr); }
        .activity-time { grid-column:2; }
    }
</style>
@endpush

@section('content')
@php
    $localeDate = $today->copy()->locale('id')->translatedFormat('l, d F Y');
    $trend = $analytics['visitor_change'];
    $chartCount = count($chart['labels']);
    $svgW = 760;
    $svgH = 250;
    $padL = 34;
    $padR = 18;
    $padT = 18;
    $padB = 36;
    $plotW = $svgW - $padL - $padR;
    $plotH = $svgH - $padT - $padB;
    $maxValue = max(1, $chart['max']);
    $visPoints = [];
    $viewPoints = [];
    foreach ($chart['visitors'] as $i => $value) {
        $x = $padL + ($chartCount > 1 ? ($i / ($chartCount - 1)) * $plotW : $plotW / 2);
        $y = $padT + $plotH - (($value / $maxValue) * $plotH);
        $visPoints[] = round($x, 1).','.round($y, 1);
    }
    foreach ($chart['page_views'] as $i => $value) {
        $x = $padL + ($chartCount > 1 ? ($i / ($chartCount - 1)) * $plotW : $plotW / 2);
        $y = $padT + $plotH - (($value / $maxValue) * $plotH);
        $viewPoints[] = round($x, 1).','.round($y, 1);
    }
    $labelStep = $chartCount > 12 ? (int) ceil($chartCount / 8) : 1;
    $topMax = max(1, (int) ($topPages->max('total_views') ?? 1));
@endphp

<div class="dash-shell">
    <section class="dash-hero">
        <div class="dash-hero-inner">
            <div>
                <div class="dash-kicker"><span class="dash-kicker-dot"></span> Dashboard Klinik Mitra Sehat</div>
                <h1 class="dash-title">Selamat datang, {{ auth()->user()->name }} 👋</h1>
                <p class="dash-subtitle">Pantau kunjungan website, masukan pasien, aktivitas Asisten Klinik, dan konten website dalam satu tampilan.</p>
            </div>
            <div class="dash-date">
                <div class="dash-date-label">Hari ini</div>
                <div class="dash-date-value">{{ $localeDate }}</div>
            </div>
        </div>
    </section>

    <section class="metric-grid">
        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                @if($trend === null)
                    <span class="metric-trend up">Hari pertama</span>
                @elseif($trend > 0)
                    <span class="metric-trend up">↑ {{ number_format(abs($trend), 1, ',', '.') }}%</span>
                @elseif($trend < 0)
                    <span class="metric-trend down">↓ {{ number_format(abs($trend), 1, ',', '.') }}%</span>
                @else
                    <span class="metric-trend flat">Tetap</span>
                @endif
            </div>
            <div class="metric-label">Pengunjung hari ini</div>
            <div class="metric-value">{{ number_format($analytics['visitors_today'], 0, ',', '.') }}</div>
            <div class="metric-foot">Browser unik hari ini · kemarin {{ number_format($analytics['visitors_yesterday'], 0, ',', '.') }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14.8 14.8 0 0 1 4 9 14.8 14.8 0 0 1-4 9 14.8 14.8 0 0 1-4-9 14.8 14.8 0 0 1 4-9z"/></svg>
                </div>
            </div>
            <div class="metric-label">Total pengunjung</div>
            <div class="metric-value">{{ number_format($analytics['visitors_total'], 0, ',', '.') }}</div>
            <div class="metric-foot">Browser unik sejak statistik diaktifkan</div>
        </div>

        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/><circle cx="12" cy="12" r="2.5"/></svg>
                </div>
            </div>
            <div class="metric-label">Page view hari ini</div>
            <div class="metric-value">{{ number_format($analytics['page_views_today'], 0, ',', '.') }}</div>
            <div class="metric-foot">Total halaman publik yang dibuka hari ini</div>
        </div>

        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19V9M10 19V5M16 19v-7M22 19H2"/></svg>
                </div>
            </div>
            <div class="metric-label">Total page view</div>
            <div class="metric-value">{{ number_format($analytics['page_views_total'], 0, ',', '.') }}</div>
            <div class="metric-foot">Akumulasi seluruh halaman yang pernah dilihat</div>
        </div>
    </section>

    <section class="quick-grid">
        <a class="quick-card" href="{{ route('admin.feedback.index') }}">
            <div class="quick-bubble"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg></div>
            <div><div class="quick-value">{{ $quickStats['new_feedback'] }}</div><div class="quick-label">Masukan baru</div></div>
        </a>
        <a class="quick-card" href="{{ route('admin.ai.unanswered.index') }}">
            <div class="quick-bubble"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2a8 8 0 0 0-8 8v3a4 4 0 0 0 4 4h1v-6H6v-1a6 6 0 0 1 12 0v1h-3v6h1a4 4 0 0 0 4-4v-3a8 8 0 0 0-8-8z"/><path d="M12 18v2M9 22h6"/></svg></div>
            <div><div class="quick-value">{{ $quickStats['pending_ai'] }}</div><div class="quick-label">AI belum terjawab</div></div>
        </a>
        <a class="quick-card" href="{{ route('admin.announcements.index') }}">
            <div class="quick-bubble"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 11h4l9-5v12l-9-5H3z"/><path d="M7 13l1.5 5h3"/></svg></div>
            <div><div class="quick-value">{{ $quickStats['active_announcements'] }}</div><div class="quick-label">Pengumuman aktif</div></div>
        </a>
        <a class="quick-card" href="{{ route('admin.doctors.index') }}">
            <div class="quick-bubble"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="7" r="4"/><path d="M5 21a7 7 0 0 1 14 0M19 8v4M17 10h4"/></svg></div>
            <div><div class="quick-value">{{ $quickStats['doctors'] }}</div><div class="quick-label">Data dokter</div></div>
        </a>
    </section>

    <section class="dash-two">
        <div class="dash-card">
            <div class="dash-card-head">
                <div>
                    <div class="dash-card-title">Statistik Kunjungan</div>
                    <div class="dash-card-sub">Pengunjung unik dan page view · {{ $chart['title'] }}</div>
                </div>
                <div class="range-tabs">
                    <a class="range-tab {{ $range === '7' ? 'active' : '' }}" href="{{ route('admin.dashboard', ['range' => '7']) }}">7 Hari</a>
                    <a class="range-tab {{ $range === '30' ? 'active' : '' }}" href="{{ route('admin.dashboard', ['range' => '30']) }}">30 Hari</a>
                    <a class="range-tab {{ $range === 'year' ? 'active' : '' }}" href="{{ route('admin.dashboard', ['range' => 'year']) }}">Tahun Ini</a>
                </div>
            </div>

            @if($analyticsReady && $chartCount > 0)
                <div class="chart-wrap">
                    <div class="chart-legend">
                        <span class="legend-item"><span class="legend-dot" style="background:#1a6b43"></span>Pengunjung unik</span>
                        <span class="legend-item"><span class="legend-dot" style="background:#55a886"></span>Page view</span>
                    </div>
                    <svg class="chart-svg" viewBox="0 0 {{ $svgW }} {{ $svgH }}" preserveAspectRatio="none" role="img" aria-label="Grafik statistik kunjungan">
                        @for($g = 0; $g <= 4; $g++)
                            @php
                                $gy = $padT + ($g / 4) * $plotH;
                                $gValue = (int) round($maxValue - (($g / 4) * $maxValue));
                            @endphp
                            <line class="chart-grid" x1="{{ $padL }}" y1="{{ $gy }}" x2="{{ $svgW - $padR }}" y2="{{ $gy }}" />
                            <text class="chart-number" x="1" y="{{ $gy + 3 }}">{{ $gValue }}</text>
                        @endfor
                        @if(count($viewPoints) > 1)<polyline class="chart-line-views" points="{{ implode(' ', $viewPoints) }}" />@endif
                        @if(count($visPoints) > 1)<polyline class="chart-line-visitors" points="{{ implode(' ', $visPoints) }}" />@endif
                        @foreach($chart['visitors'] as $i => $value)
                            @php
                                $x = $padL + ($chartCount > 1 ? ($i / ($chartCount - 1)) * $plotW : $plotW / 2);
                                $y = $padT + $plotH - (($value / $maxValue) * $plotH);
                            @endphp
                            @if($chartCount <= 12)<circle class="chart-point" cx="{{ $x }}" cy="{{ $y }}" r="3" />@endif
                            @if($i % $labelStep === 0 || $i === $chartCount - 1)
                                <text class="chart-label" x="{{ $x }}" y="{{ $svgH - 10 }}" text-anchor="middle">{{ $chart['labels'][$i] }}</text>
                            @endif
                        @endforeach
                    </svg>
                </div>
            @else
                <div class="empty-chart">Statistik mulai terisi setelah website publik dikunjungi setelah migration dijalankan.</div>
            @endif
        </div>

        <div class="dash-card">
            <div class="dash-card-head">
                <div>
                    <div class="dash-card-title">Halaman Terpopuler</div>
                    <div class="dash-card-sub">Berdasarkan page view {{ strtolower($chart['title']) }}</div>
                </div>
            </div>
            <div class="top-pages">
                @forelse($topPages as $page)
                    <div class="page-row">
                        <div class="page-meta"><span class="page-name">{{ $page->page_name }}</span><span class="page-count">{{ number_format($page->total_views, 0, ',', '.') }}</span></div>
                        <div class="page-bar"><div class="page-fill" style="width:{{ max(4, round(($page->total_views / $topMax) * 100, 1)) }}%"></div></div>
                    </div>
                @empty
                    <div class="dash-card-sub">Belum ada page view untuk periode ini.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="dash-bottom">
        <div class="dash-card">
            <div class="dash-card-head">
                <div>
                    <div class="dash-card-title">Aktivitas Terbaru</div>
                    <div class="dash-card-sub">Hal yang perlu diperhatikan admin.</div>
                </div>
            </div>
            <div class="activity-list">
                @forelse($activities as $activity)
                    <a class="activity-item" href="{{ $activity['url'] }}">
                        <span class="activity-icon">
                            @if($activity['type'] === 'ai')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2a8 8 0 0 0-8 8v3a4 4 0 0 0 4 4h1v-6H6v-1a6 6 0 0 1 12 0v1h-3v6h1a4 4 0 0 0 4-4v-3a8 8 0 0 0-8-8z"/></svg>
                            @elseif($activity['type'] === 'announcement')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 11h4l9-5v12l-9-5H3z"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>
                            @endif
                        </span>
                        <span style="min-width:0"><div class="activity-title">{{ $activity['title'] }}</div><div class="activity-desc">{{ $activity['description'] }}</div></span>
                        <span class="activity-time">{{ $activity['time']->locale('id')->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="dash-card-sub" style="padding:8px 0">Belum ada aktivitas terbaru.</div>
                @endforelse
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-head">
                <div>
                    <div class="dash-card-title">Konten Website</div>
                    <div class="dash-card-sub">Ringkasan data yang dapat dikelola.</div>
                </div>
            </div>
            <div class="content-grid">
                <a class="content-link" href="{{ route('admin.services.index') }}"><div class="content-number">{{ $contentStats['services'] }}</div><div class="content-label">Layanan</div></a>
                <a class="content-link" href="{{ route('admin.faqs.index') }}"><div class="content-number">{{ $contentStats['faqs'] }}</div><div class="content-label">FAQ</div></a>
                <a class="content-link" href="{{ route('admin.articles.index') }}"><div class="content-number">{{ $contentStats['articles'] }}</div><div class="content-label">Artikel</div></a>
                <a class="content-link" href="{{ route('admin.galleries.index') }}"><div class="content-number">{{ $contentStats['galleries'] }}</div><div class="content-label">Galeri</div></a>
                <a class="content-link" href="{{ route('admin.promos.index') }}"><div class="content-number">{{ $contentStats['promos'] }}</div><div class="content-label">Promo</div></a>
                <a class="content-link" href="{{ route('admin.branches.index') }}"><div class="content-number">{{ $contentStats['branches'] }}</div><div class="content-label">Cabang</div></a>
            </div>
            <div style="padding:0 18px 18px">
                <div class="analytics-note">Statistik pengunjung menggunakan ID anonim pada cookie browser. Sistem tidak menyimpan nama, NIK, email, atau alamat IP pengunjung untuk perhitungan ini. Satu browser dihitung satu pengunjung unik.</div>
            </div>
        </div>
    </section>
</div>
@endsection
