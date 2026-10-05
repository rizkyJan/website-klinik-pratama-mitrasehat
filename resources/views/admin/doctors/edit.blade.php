@extends('admin.layout')
@section('title', 'Edit Dokter')

@section('content')
@php
    $dayOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
    $sortedSchedules = $doctor->schedules->sortBy(function ($schedule) use ($dayOrder) {
        $day = str_pad((string) ($dayOrder[$schedule->day] ?? 99), 2, '0', STR_PAD_LEFT);
        $time = $schedule->is_off ? '99:99' : substr((string) $schedule->start_time, 0, 5);
        return $day.'-'.$time;
    })->values();
    $savedDayCount = $doctor->schedules->pluck('day')->unique()->count();
    $scheduleTotalsByDay = $sortedSchedules->countBy('day');
    $scheduleSeenByDay = [];
@endphp

<style>
    .kms-edit-page, .kms-edit-page * { box-sizing:border-box; }
    .kms-edit-page { max-width:1080px; margin:0 auto; color:#111827; }
    .kms-back { display:inline-flex; color:#1a5d3a; font-size:14px; font-weight:650; text-decoration:none; margin-bottom:14px; }
    .kms-head-row { display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:22px; }
    .kms-eyebrow { margin:0 0 5px; color:#1a5d3a; font-size:12px; font-weight:750; text-transform:uppercase; letter-spacing:.12em; }
    .kms-title { margin:0; font-size:28px; line-height:1.2; font-weight:800; }
    .kms-subtitle { margin:7px 0 0; color:#6b7280; font-size:14px; }
    .kms-status { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:11px; font-weight:750; }
    .kms-status.on { background:#ecfdf3; color:#166534; }
    .kms-status.off { background:#f3f4f6; color:#4b5563; }
    .kms-dot { width:7px; height:7px; border-radius:50%; background:currentColor; }
    .kms-alert { padding:14px 16px; border-radius:12px; background:#fff1f2; border:1px solid #fecdd3; color:#b91c1c; font-size:13px; margin-bottom:18px; }
    .kms-alert strong { display:block; margin-bottom:5px; }
    .kms-alert ul { margin:0; padding-left:18px; }
    .kms-grid { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:20px; align-items:start; }
    .kms-card { background:#fff; border:1px solid #edf0f2; border-radius:16px; padding:22px; box-shadow:0 3px 14px rgba(17,24,39,.05); }
    .kms-card h2 { margin:0; font-size:17px; font-weight:800; }
    .kms-card-note { margin:5px 0 18px; color:#6b7280; font-size:12px; }
    .kms-field { margin-bottom:17px; }
    .kms-field:last-child { margin-bottom:0; }
    .kms-label { display:block; margin-bottom:7px; color:#374151; font-size:13px; font-weight:700; }
    .kms-input, .kms-select { width:100%; min-height:43px; border:1px solid #d1d5db; border-radius:11px; background:#fff; padding:9px 12px; font-size:14px; color:#111827; outline:none; transition:.15s ease; }
    .kms-input:focus, .kms-select:focus { border-color:#1a5d3a; box-shadow:0 0 0 3px rgba(26,93,58,.10); }
    .kms-two { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .kms-help { margin:6px 0 0; color:#9ca3af; font-size:11px; }
    .kms-error { margin:6px 0 0; color:#dc2626; font-size:11px; }
    .kms-toggle-row { min-height:70px; border:1px solid #e5e7eb; border-radius:11px; padding:11px 12px; display:flex; align-items:center; justify-content:space-between; gap:14px; }
    .kms-toggle-title { margin:0; font-size:13px; font-weight:700; color:#374151; }
    .kms-toggle-note { margin:3px 0 0; font-size:11px; color:#9ca3af; }
    .kms-switch { position:relative; width:44px; height:24px; flex:0 0 44px; }
    .kms-switch input { position:absolute; opacity:0; pointer-events:none; }
    .kms-switch-ui { position:absolute; inset:0; background:#d1d5db; border-radius:999px; transition:.2s; cursor:pointer; }
    .kms-switch-ui:after { content:""; position:absolute; width:20px; height:20px; left:2px; top:2px; background:#fff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,.2); transition:.2s; }
    .kms-switch input:checked + .kms-switch-ui { background:#1a5d3a; }
    .kms-switch input:checked + .kms-switch-ui:after { transform:translateX(20px); }
    .kms-photo-box { width:100%; max-width:230px; aspect-ratio:4 / 5; margin:16px auto 0; overflow:hidden; border:1px solid #dbe0e5; border-radius:16px; background:#f8fafc; display:flex; align-items:center; justify-content:center; }
    .kms-photo-box img { width:100%; height:100%; object-fit:cover; object-position:top; display:block; }
    .kms-photo-placeholder { padding:18px; text-align:center; color:#94a3b8; font-size:12px; }
    .kms-file-btn { width:100%; margin-top:14px; min-height:42px; border:1px solid #bbf7d0; border-radius:11px; background:#ecfdf3; color:#166534; font-size:13px; font-weight:750; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .kms-file-name { margin:7px 0 0; text-align:center; color:#9ca3af; font-size:11px; word-break:break-word; }
    .kms-actions { margin-top:20px; display:flex; justify-content:flex-end; gap:10px; }
    .kms-btn { min-height:42px; padding:10px 16px; border-radius:11px; border:0; font-size:13px; font-weight:750; text-decoration:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; }
    .kms-btn-secondary { border:1px solid #d1d5db; background:#fff; color:#4b5563; }
    .kms-btn-primary { background:#1a5d3a; color:#fff; }
    .kms-btn-primary:hover { background:#154a2e; }
    .kms-btn-danger { background:#fff1f2; color:#dc2626; border:1px solid #fecdd3; }
    .kms-session-badge { display:inline-flex; align-items:center; margin-left:7px; padding:2px 7px; border-radius:999px; background:#ecfdf3; color:#166534; font-size:10px; font-weight:800; vertical-align:middle; }
    .kms-schedule-card { margin-top:22px; background:#fff; border:1px solid #edf0f2; border-radius:16px; overflow:hidden; box-shadow:0 3px 14px rgba(17,24,39,.05); }
    .kms-schedule-head { padding:18px 22px; border-bottom:1px solid #edf0f2; display:flex; justify-content:space-between; align-items:center; gap:14px; }
    .kms-schedule-head h2 { margin:0; font-size:18px; font-weight:800; }
    .kms-schedule-head p { margin:5px 0 0; color:#6b7280; font-size:12px; }
    .kms-count { padding:6px 10px; border-radius:999px; background:#ecfdf3; color:#166534; font-size:11px; font-weight:750; white-space:nowrap; }
    .kms-schedule-body { padding:20px 22px; }
    .kms-schedule-form { display:grid; grid-template-columns:1fr 1fr 1fr auto auto; gap:12px; align-items:end; padding:14px; border:1px solid #e5e7eb; border-radius:14px; background:#f8fafc; }
    .kms-small-label { display:block; margin-bottom:6px; color:#64748b; font-size:11px; font-weight:700; }
    .kms-off-label { min-height:43px; display:flex; align-items:center; gap:8px; padding:9px 12px; border:1px solid #d1d5db; border-radius:11px; background:#fff; font-size:13px; color:#475569; }
    .kms-schedule-list { display:grid; gap:10px; margin-top:16px; }
    .kms-schedule-item { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:13px 15px; border:1px solid #edf0f2; border-radius:13px; }
    .kms-day { margin:0; font-size:14px; font-weight:750; }
    .kms-time { margin:3px 0 0; color:#6b7280; font-size:12px; }
    .kms-time.off { color:#dc2626; font-weight:750; }
    .kms-empty { padding:28px 10px; text-align:center; color:#9ca3af; font-size:13px; border:1px dashed #d1d5db; border-radius:13px; }
    @media (max-width: 980px) { .kms-grid { grid-template-columns:1fr; } .kms-photo-box { max-width:280px; } .kms-schedule-form { grid-template-columns:1fr 1fr; } .kms-schedule-form .kms-btn-primary { width:100%; } }
    @media (max-width: 600px) { .kms-head-row { align-items:flex-start; flex-direction:column; } .kms-title { font-size:24px; } .kms-two, .kms-schedule-form { grid-template-columns:1fr; } .kms-actions { flex-direction:column-reverse; } .kms-actions .kms-btn { width:100%; } .kms-card, .kms-schedule-body { padding:17px; } .kms-schedule-head { padding:16px 17px; align-items:flex-start; flex-direction:column; } .kms-schedule-item { align-items:stretch; flex-direction:column; } .kms-schedule-item .kms-btn-danger { width:100%; } }
</style>

<div class="kms-edit-page">
    <a href="{{ route('admin.doctors.index') }}" class="kms-back">&larr; Kembali</a>

    <div class="kms-head-row">
        <div>
            <p class="kms-eyebrow">Manajemen Dokter</p>
            <h1 class="kms-title">Edit Dokter</h1>
            <p class="kms-subtitle">Perbarui profil dokter dan jadwal praktik.</p>
        </div>
        @if($doctor->is_active)
            <span class="kms-status on"><span class="kms-dot"></span>Tampil di Website</span>
        @else
            <span class="kms-status off"><span class="kms-dot"></span>Tidak Ditampilkan</span>
        @endif
    </div>

    @if($errors->any())
        <div class="kms-alert">
            <strong>Ada data yang perlu diperbaiki:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="kms-grid">
            <section class="kms-card">
                <h2>Informasi Dokter</h2>
                <p class="kms-card-note">Perubahan data akan dipakai pada website klinik.</p>

                <div class="kms-field">
                    <label for="name" class="kms-label">Nama Dokter <span style="color:#dc2626;">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $doctor->name) }}" required class="kms-input">
                    @error('name')<p class="kms-error">{{ $message }}</p>@enderror
                </div>

                <div class="kms-field">
                    <label for="specialization" class="kms-label">Spesialisasi <span style="color:#dc2626;">*</span></label>
                    <input id="specialization" type="text" name="specialization" value="{{ old('specialization', $doctor->specialization) }}" required class="kms-input">
                    @error('specialization')<p class="kms-error">{{ $message }}</p>@enderror
                </div>

                <div class="kms-two">
                    <div class="kms-field">
                        <label for="sort_order" class="kms-label">Urutan Tampil</label>
                        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $doctor->sort_order) }}" min="0" max="9999" class="kms-input">
                        <p class="kms-help">Angka lebih kecil tampil lebih dulu.</p>
                        @error('sort_order')<p class="kms-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="kms-field">
                        <span class="kms-label">Status Website</span>
                        <div class="kms-toggle-row">
                            <div>
                                <p class="kms-toggle-title">Tampilkan dokter</p>
                                <p class="kms-toggle-note">Nonaktifkan untuk menyembunyikan.</p>
                            </div>
                            <label class="kms-switch" aria-label="Tampilkan dokter di website">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}>
                                <span class="kms-switch-ui"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="kms-card">
                <h2>Foto Dokter</h2>
                <p class="kms-card-note">JPG, JPEG, PNG, atau WebP. Maksimal 10 MB.</p>

                <div class="kms-photo-box">
                    @if($doctor->photo)
                        <img id="photoPreview" src="{{ asset($doctor->photo) }}" alt="{{ $doctor->name }}">
                        <div id="photoPlaceholder" class="kms-photo-placeholder" style="display:none;">Belum ada foto</div>
                    @else
                        <img id="photoPreview" src="" alt="Preview foto dokter" style="display:none;">
                        <div id="photoPlaceholder" class="kms-photo-placeholder">Belum ada foto</div>
                    @endif
                </div>

                <label for="photo" class="kms-file-btn">{{ $doctor->photo ? 'Ganti Foto dari Folder' : 'Pilih Foto dari Folder' }}</label>
                <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" style="display:none;">
                <p id="photoFileName" class="kms-file-name">{{ $doctor->photo ? 'Foto saat ini tetap dipakai jika tidak diganti' : 'Belum ada foto dipilih' }}</p>
                @error('photo')<p class="kms-error" style="text-align:center;">{{ $message }}</p>@enderror

                <x-image-adjuster
                    input="photo"
                    previews="photoPreview"
                    placeholders="photoPlaceholder"
                    filename="photoFileName"
                    :current="$doctor->photo ? asset($doctor->photo) : ''"
                    :editable="(bool) ($doctor->photo && ! filter_var($doctor->photo, FILTER_VALIDATE_URL))"
                    title="Atur Foto Dokter"
                    buttonlabel="Atur Foto Saat Ini"
                    :aspectw="4"
                    :aspecth="5"
                    :outputw="1000"
                    :outputh="1250"
                    :maxmb="10"
                />
            </aside>
        </div>

        <div class="kms-actions">
            <a href="{{ route('admin.doctors.index') }}" class="kms-btn kms-btn-secondary">Kembali ke Daftar</a>
            <button type="submit" class="kms-btn kms-btn-primary">Simpan Perubahan</button>
        </div>
    </form>

    <section class="kms-schedule-card">
        <div class="kms-schedule-head">
            <div>
                <h2>Jadwal Praktik</h2>
                <p>Satu hari dapat memiliki lebih dari satu sesi praktik. Tambahkan jam berikutnya dengan memilih hari yang sama.</p>
            </div>
            <span class="kms-count">{{ $savedDayCount }} hari · {{ $doctor->schedules->count() }} jadwal</span>
        </div>

        <div class="kms-schedule-body">
            <form method="POST" action="{{ route('admin.doctors.schedules.store', $doctor) }}" class="kms-schedule-form">
                @csrf
                <div>
                    <label for="day" class="kms-small-label">Hari</label>
                    <select id="day" name="day" required class="kms-select">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                            <option value="{{ $day }}" {{ old('day') === $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="start_time" class="kms-small-label">Mulai</label>
                    <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" class="kms-input">
                </div>
                <div>
                    <label for="end_time" class="kms-small-label">Selesai</label>
                    <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" class="kms-input">
                </div>
                <label class="kms-off-label">
                    <input id="is_off" type="checkbox" name="is_off" value="1" {{ old('is_off') ? 'checked' : '' }}>
                    Libur
                </label>
                <button type="submit" class="kms-btn kms-btn-primary">Simpan Jadwal</button>
            </form>

            <div class="kms-schedule-list">
                @forelse($sortedSchedules as $schedule)
                    @php
                        $scheduleSeenByDay[$schedule->day] = ($scheduleSeenByDay[$schedule->day] ?? 0) + 1;
                        $sessionNumber = $scheduleSeenByDay[$schedule->day];
                        $hasMultipleSessions = ($scheduleTotalsByDay[$schedule->day] ?? 0) > 1;
                    @endphp
                    <div class="kms-schedule-item">
                        <div>
                            <p class="kms-day">
                                {{ $schedule->day }}
                                @if($hasMultipleSessions && ! $schedule->is_off)
                                    <span class="kms-session-badge">Sesi {{ $sessionNumber }}</span>
                                @endif
                            </p>
                            @if($schedule->is_off)
                                <p class="kms-time off">LIBUR</p>
                            @else
                                <p class="kms-time">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }} WIB</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('admin.doctors.schedules.destroy', [$doctor, $schedule]) }}" onsubmit="return confirm('Hapus {{ $schedule->is_off ? 'status libur' : 'sesi '.substr($schedule->start_time, 0, 5).' - '.substr($schedule->end_time, 0, 5) }} hari {{ $schedule->day }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="kms-btn kms-btn-danger">Hapus Jadwal</button>
                        </form>
                    </div>
                @empty
                    <div class="kms-empty">Belum ada jadwal praktik. Tambahkan jadwal melalui form di atas.</div>
                @endforelse
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isOff = document.getElementById('is_off');
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');


        function syncScheduleInputs() {
            const disabled = isOff.checked;
            startTime.disabled = disabled;
            endTime.disabled = disabled;
            if (disabled) {
                startTime.value = '';
                endTime.value = '';
                startTime.style.background = '#f3f4f6';
                endTime.style.background = '#f3f4f6';
            } else {
                startTime.style.background = '#fff';
                endTime.style.background = '#fff';
            }
        }

        isOff.addEventListener('change', syncScheduleInputs);
        syncScheduleInputs();
    });
</script>
@endsection
