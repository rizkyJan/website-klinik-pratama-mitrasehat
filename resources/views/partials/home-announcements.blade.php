@if(isset($announcements) && $announcements->isNotEmpty())
<style>
    .ms-ann-home{margin:30px 18px 0;padding:21px 22px;border:1px solid #dfe9df;border-radius:20px;background:rgba(255,255,255,.78)}
    .ms-ann-home-head{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:13px}.ms-ann-home-kicker{margin:0;color:#1a5d3a;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em}.ms-ann-home-title{margin:3px 0 0;color:#2f754b;font-size:21px;font-weight:800}.ms-ann-home-all{color:#1a5d3a;font-size:11px;font-weight:800;text-decoration:none;white-space:nowrap}.ms-ann-home-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:11px}.ms-ann-card{display:block;padding:14px 15px;border:1px solid #e3e8e4;border-radius:13px;background:#fff;text-decoration:none}.ms-ann-card-top{display:flex;align-items:center;justify-content:space-between;gap:8px}.ms-ann-badge{display:inline-flex;min-height:22px;align-items:center;padding:0 8px;border-radius:999px;font-size:9px;font-weight:800}.ms-ann-badge-info{background:#eef5ff;color:#315f9c}.ms-ann-badge-important{background:#fff4d5;color:#896000}.ms-ann-badge-urgent{background:#feeceb;color:#a92b22}.ms-ann-date{color:#919991;font-size:9px}.ms-ann-card h3{margin:9px 0 0;color:#245e3d;font-size:13px;line-height:1.35;font-weight:800}.ms-ann-card p{margin:6px 0 0;color:#6f7972;font-size:10.5px;line-height:1.55}.ms-ann-pin{font-size:10px;color:#b18108}
    @media(max-width:850px){.ms-ann-home-grid{grid-template-columns:1fr 1fr}.ms-ann-card:last-child:nth-child(odd){grid-column:1/-1}}
    @media(max-width:560px){.ms-ann-home{margin:18px 0 0;padding:16px}.ms-ann-home-head{align-items:flex-start}.ms-ann-home-title{font-size:18px}.ms-ann-home-grid{grid-template-columns:1fr}.ms-ann-card:last-child:nth-child(odd){grid-column:auto}.ms-ann-home-all{font-size:10px}}
</style>

<section class="ms-ann-home" aria-labelledby="msAnnouncementTitle">
    <div class="ms-ann-home-head">
        <div>
            <p class="ms-ann-home-kicker">Pemberitahuan Klinik</p>
            <h2 class="ms-ann-home-title" id="msAnnouncementTitle">Pengumuman Terbaru</h2>
        </div>
        <a class="ms-ann-home-all" href="{{ route('information.announcements') }}">Lihat Semua →</a>
    </div>
    <div class="ms-ann-home-grid">
        @foreach($announcements as $announcement)
            @php
                $badgeClass = match($announcement->category) {
                    'urgent' => 'ms-ann-badge-urgent',
                    'important' => 'ms-ann-badge-important',
                    default => 'ms-ann-badge-info',
                };
            @endphp
            <a class="ms-ann-card" href="{{ route('information.announcements') }}#pengumuman-{{ $announcement->id }}">
                <div class="ms-ann-card-top">
                    <span class="ms-ann-badge {{ $badgeClass }}">{{ $announcement->category_label }}</span>
                    <span class="ms-ann-date">{{ $announcement->start_date->format('d/m/Y') }}</span>
                </div>
                <h3>@if($announcement->is_pinned)<span class="ms-ann-pin">★</span> @endif{{ $announcement->title }}</h3>
                <p>{{ \Illuminate\Support\Str::limit($announcement->content, 115) }}</p>
            </a>
        @endforeach
    </div>
</section>
@endif
