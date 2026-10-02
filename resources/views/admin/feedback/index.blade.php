@extends('admin.layout')
@section('title', 'Kritik & Saran')

@section('content')
<style>
    .f-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:18px}.f-title{margin:0;color:#1f2937;font-size:22px;font-weight:800}.f-sub{margin:5px 0 0;color:#6b7280;font-size:13px}.f-email{padding:11px 14px;border-radius:12px;border:1px solid #dbe6de;background:#fff;font-size:12px}.f-email.ok{border-color:#b8dcc2;background:#f1faf3;color:#166534}.f-email.bad{border-color:#f0d7a7;background:#fff9eb;color:#8a5a08}.f-filter{display:grid;grid-template-columns:1fr 160px 160px auto;gap:10px;margin-bottom:14px;padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#fff}.f-input{width:100%;height:42px;border:1px solid #d1d5db;border-radius:10px;padding:0 11px;background:#fff;font-size:12px;outline:none}.f-input:focus{border-color:#1a5d3a;box-shadow:0 0 0 3px rgba(26,93,58,.08)}.f-btn{height:42px;border:0;border-radius:10px;padding:0 16px;background:#1a5d3a;color:#fff;font-size:12px;font-weight:800;cursor:pointer}.f-table-wrap{overflow:auto;border:1px solid #e5e7eb;border-radius:14px;background:#fff}.f-table{width:100%;border-collapse:collapse;min-width:850px}.f-table th{padding:12px 14px;background:#f8faf9;color:#66756c;font-size:10px;text-align:left;text-transform:uppercase;letter-spacing:.06em}.f-table td{padding:14px;border-top:1px solid #eef1ef;color:#374151;font-size:12px;vertical-align:top}.f-name{font-weight:800;color:#263f31}.f-mail{margin-top:3px;color:#7b8780;font-size:11px}.f-message{max-width:350px;color:#66756c;line-height:1.5}.f-badge{display:inline-flex;padding:5px 8px;border-radius:999px;font-size:10px;font-weight:800}.f-badge.new{background:#fff1f1;color:#b42318}.f-badge.read{background:#eef4ff;color:#3159a5}.f-badge.replied{background:#edf8f0;color:#17713e}.f-type{display:inline-flex;padding:5px 8px;border-radius:999px;background:#f3f6f4;color:#475b4e;font-size:10px;font-weight:700}.f-link{color:#17613d;font-weight:800;text-decoration:none}.f-empty{padding:42px 20px;text-align:center;color:#7c8981;font-size:13px}.f-pages{margin-top:14px}.f-setting-link{color:inherit;font-weight:800;text-decoration:underline}.f-reset{display:inline-flex;height:42px;align-items:center;padding:0 12px;color:#5d6c63;text-decoration:none;font-size:11px;font-weight:700}@media(max-width:850px){.f-head{display:block}.f-email{margin-top:12px}.f-filter{grid-template-columns:1fr 1fr}.f-filter .search{grid-column:1/-1}}@media(max-width:540px){.f-filter{grid-template-columns:1fr}.f-filter .search{grid-column:auto}.f-btn,.f-reset{width:100%;justify-content:center}}
</style>

<div class="f-head">
    <div>
        <h1 class="f-title">Kritik, Saran &amp; Apresiasi</h1>
        <p class="f-sub">Pesan dari pengunjung hanya dapat dibaca melalui area admin ini.</p>
    </div>
    @if($emailSetting && $emailSetting->isConnected())
        <div class="f-email ok">● Email tersambung: <strong>{{ $emailSetting->email }}</strong></div>
    @else
        <div class="f-email bad">● Email belum tersambung — <a class="f-setting-link" href="{{ route('admin.email-settings.edit') }}">atur sekarang</a></div>
    @endif
</div>

<form method="GET" class="f-filter">
    <input class="f-input search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, judul, atau isi pesan...">
    <select class="f-input" name="type">
        <option value="">Semua jenis</option>
        <option value="kritik" @selected(request('type') === 'kritik')>Kritik</option>
        <option value="saran" @selected(request('type') === 'saran')>Saran</option>
        <option value="apresiasi" @selected(request('type') === 'apresiasi')>Apresiasi</option>
        <option value="lainnya" @selected(request('type') === 'lainnya')>Lainnya</option>
    </select>
    <select class="f-input" name="status">
        <option value="">Semua status</option>
        <option value="new" @selected(request('status') === 'new')>Baru</option>
        <option value="read" @selected(request('status') === 'read')>Dibaca</option>
        <option value="replied" @selected(request('status') === 'replied')>Dibalas</option>
    </select>
    <div style="display:flex;gap:5px"><button class="f-btn" type="submit">Filter</button><a class="f-reset" href="{{ route('admin.feedback.index') }}">Reset</a></div>
</form>

<div class="f-table-wrap">
    @if($feedbacks->count())
        <table class="f-table">
            <thead><tr><th>Pengirim</th><th>Jenis</th><th>Pesan</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($feedbacks as $feedback)
                <tr>
                    <td><div class="f-name">{{ $feedback->name }}</div><div class="f-mail">{{ $feedback->email }}</div></td>
                    <td><span class="f-type">{{ $feedback->type_label }}</span></td>
                    <td><div class="f-name">{{ $feedback->subject ?: 'Tanpa judul' }}</div><div class="f-message">{{ \Illuminate\Support\Str::limit($feedback->message, 115) }}</div></td>
                    <td>{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="f-badge {{ $feedback->status }}">{{ $feedback->status === 'new' ? 'Baru' : ($feedback->status === 'read' ? 'Dibaca' : 'Dibalas') }}</span></td>
                    <td><a class="f-link" href="{{ route('admin.feedback.show', $feedback) }}">Buka →</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="f-empty">Belum ada pesan yang sesuai dengan filter.</div>
    @endif
</div>

<div class="f-pages">{{ $feedbacks->links() }}</div>
@endsection
