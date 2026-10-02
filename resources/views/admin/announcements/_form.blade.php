@php
    $item = $announcement ?? null;
    $startDate = old('start_date', $item?->start_date?->format('Y-m-d') ?? now()->format('Y-m-d'));
    $endDate = old('end_date', $item?->end_date?->format('Y-m-d'));
    $activeChecked = old('is_active', $item ? $item->is_active : true);
    $pinnedChecked = old('is_pinned', $item ? $item->is_pinned : false);
@endphp

<div class="kms-form-card">
    <div class="kms-form-grid">
        <div class="kms-field-full">
            <label class="kms-label" for="title">Judul Pengumuman</label>
            <input class="kms-input" id="title" name="title" type="text" maxlength="255" required value="{{ old('title', $item?->title) }}" placeholder="Contoh: Perubahan Jam Pelayanan">
        </div>

        <div>
            <label class="kms-label" for="category">Kategori</label>
            <select class="kms-select" id="category" name="category" required>
                <option value="information" @selected(old('category', $item?->category ?? 'information') === 'information')>Informasi</option>
                <option value="important" @selected(old('category', $item?->category) === 'important')>Penting</option>
                <option value="urgent" @selected(old('category', $item?->category) === 'urgent')>Darurat</option>
            </select>
            <p class="kms-help">Kategori hanya memengaruhi label/tampilan, bukan logika fitur lain.</p>
        </div>

        <div class="kms-toggle-row">
            <label class="kms-toggle">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked($activeChecked)>
                <span><strong>Aktifkan</strong><span>Pengumuman boleh tampil selama masih dalam periode.</span></span>
            </label>
            <label class="kms-toggle">
                <input type="hidden" name="is_pinned" value="0">
                <input type="checkbox" name="is_pinned" value="1" @checked($pinnedChecked)>
                <span><strong>Prioritaskan</strong><span>Tampil lebih dulu dibanding pengumuman biasa.</span></span>
            </label>
        </div>

        <div>
            <label class="kms-label" for="start_date">Mulai Ditampilkan</label>
            <input class="kms-input" id="start_date" name="start_date" type="date" required value="{{ $startDate }}">
        </div>

        <div>
            <label class="kms-label" for="end_date">Selesai Ditampilkan</label>
            <input class="kms-input" id="end_date" name="end_date" type="date" value="{{ $endDate }}">
            <p class="kms-help">Boleh dikosongkan jika pengumuman tidak memiliki tanggal berakhir.</p>
        </div>

        <div class="kms-field-full">
            <label class="kms-label" for="content">Isi Pengumuman</label>
            <textarea class="kms-textarea" id="content" name="content" maxlength="5000" required placeholder="Tulis isi pengumuman di sini...">{{ old('content', $item?->content) }}</textarea>
            <p class="kms-help">Maksimal 5.000 karakter. Teks akan ditampilkan aman sebagai teks biasa pada website.</p>
        </div>
    </div>

    <div class="kms-preview-note">
        <strong>Otomatis:</strong> pengumuman yang tanggal mulainya belum tiba berstatus <b>Terjadwal</b>; setelah tanggal selesai berstatus <b>Berakhir</b> dan tidak lagi tampil di halaman publik.
    </div>
</div>
