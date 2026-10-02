@php
    $serviceItem = $service ?? null;
    $selectedIcon = old('icon', $serviceItem?->icon ?: 'medical-cross');
    $activeValue = old('is_active', $serviceItem ? (bool) $serviceItem->is_active : true);
    $iconOptions = [
        'medical-cross' => 'Medis Umum',
        'stethoscope' => 'Stetoskop',
        'tooth' => 'Gigi',
        'mother-child' => 'Ibu & Anak',
        'laboratory' => 'Laboratorium',
        'physiotherapy' => 'Fisioterapi',
        'acupuncture' => 'Akupuntur',
        'pharmacy' => 'Farmasi',
        'health-check' => 'Cek Kesehatan',
        'vitamin' => 'Vitamin',
        'heart' => 'Kesehatan',
        'clinic' => 'Klinik',
    ];
@endphp

<div class="kms-form-grid">
    <div>
        <section class="kms-card">
            <h2>Informasi Layanan</h2>
            <p class="kms-card-note">Informasi ini akan digunakan pada kartu layanan dan halaman detail di website.</p>

            <div class="kms-field">
                <label for="name" class="kms-label">Nama Layanan <span class="kms-required">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name', $serviceItem?->name) }}" required maxlength="255" class="kms-input" placeholder="Contoh: Poli Umum">
                @error('name')<p class="kms-error">{{ $message }}</p>@enderror
            </div>

            <div class="kms-field">
                <label for="description" class="kms-label">
                    Deskripsi Singkat
                    <span class="kms-counter"><span id="descriptionCount">0</span>/500</span>
                </label>
                <textarea id="description" name="description" maxlength="500" class="kms-textarea" placeholder="Ringkasan singkat yang tampil pada kartu layanan.">{{ old('description', $serviceItem?->description) }}</textarea>
                <p class="kms-help">Sebaiknya 1 kalimat singkat agar kartu layanan tetap rapi.</p>
                @error('description')<p class="kms-error">{{ $message }}</p>@enderror
            </div>

            <div class="kms-field">
                <label for="detail" class="kms-label">Detail Lengkap</label>
                <textarea id="detail" name="detail" maxlength="5000" class="kms-textarea detail" placeholder="Jelaskan layanan, jenis pemeriksaan/perawatan, dan informasi penting lainnya.">{{ old('detail', $serviceItem?->detail) }}</textarea>
                <p class="kms-help">Teks ini tampil di halaman detail layanan. Jika kosong, website dapat memakai deskripsi singkat.</p>
                @error('detail')<p class="kms-error">{{ $message }}</p>@enderror
            </div>
        </section>

        <section class="kms-card kms-icon-card">
            <h2>Pilih Ikon Layanan</h2>
            <p class="kms-card-note">Klik salah satu ikon. Nilai ikon akan disimpan ke database dan dipakai di website.</p>

            <div class="kms-icon-grid">
                @foreach($iconOptions as $iconValue => $iconLabel)
                    <label class="kms-icon-choice">
                        <input type="radio" name="icon" value="{{ $iconValue }}" {{ $selectedIcon === $iconValue ? 'checked' : '' }}>
                        <span class="kms-icon-option">
                            <span class="kms-icon-shape"><x-service-icon :name="$iconValue" /></span>
                            <span class="kms-icon-name">{{ $iconLabel }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('icon')<p class="kms-error">{{ $message }}</p>@enderror
        </section>
    </div>

    <div class="kms-preview">
        <section class="kms-card">
            <h2>Preview di Website</h2>
            <p class="kms-card-note">Preview akan berubah langsung saat nama, deskripsi, atau ikon diubah.</p>

            <div class="kms-preview-card">
                <div class="kms-preview-top">
                    <div id="previewIcon" class="kms-preview-icon">
                        <x-service-icon :name="$selectedIcon" />
                    </div>
                    <span class="kms-preview-badge">LAYANAN</span>
                </div>
                <h3 id="previewName" class="kms-preview-name">{{ old('name', $serviceItem?->name) ?: 'Nama Layanan' }}</h3>
                <p id="previewDescription" class="kms-preview-desc">{{ old('description', $serviceItem?->description) ?: 'Deskripsi singkat layanan akan tampil di bagian ini.' }}</p>
                <div class="kms-preview-footer">Lihat Detail Layanan →</div>
            </div>
        </section>

        <section class="kms-card">
            <h2>Pengaturan</h2>
            <p class="kms-card-note">Atur posisi dan visibilitas layanan di website.</p>

            <div class="kms-two">
                <div class="kms-field">
                    <label for="sort_order" class="kms-label">Urutan Tampil</label>
                    <input id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $serviceItem?->sort_order ?? 0) }}" class="kms-input">
                    <p class="kms-help">Angka lebih kecil tampil lebih dulu.</p>
                    @error('sort_order')<p class="kms-error">{{ $message }}</p>@enderror
                </div>

                <div class="kms-field">
                    <span class="kms-label">Status Website</span>
                    <div class="kms-toggle-row">
                        <div>
                            <p class="kms-toggle-title">Tampilkan layanan</p>
                            <p class="kms-toggle-note">Matikan untuk menyembunyikan.</p>
                        </div>
                        <label class="kms-switch" aria-label="Tampilkan layanan di website">
                            <input type="hidden" name="is_active" value="0">
                            <input id="is_active" type="checkbox" name="is_active" value="1" {{ $activeValue ? 'checked' : '' }}>
                            <span class="kms-switch-ui"></span>
                        </label>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="kms-actions">
    <a href="{{ route('admin.services.index') }}" class="kms-btn kms-btn-secondary">Kembali ke Daftar</a>
    <button type="submit" class="kms-btn kms-btn-primary">{{ $submitLabel }}</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        const descriptionCount = document.getElementById('descriptionCount');
        const previewName = document.getElementById('previewName');
        const previewDescription = document.getElementById('previewDescription');
        const previewIcon = document.getElementById('previewIcon');
        const statusInput = document.getElementById('is_active');
        const liveStatus = document.getElementById('liveStatus');
        const iconInputs = document.querySelectorAll('input[name="icon"]');

        function syncText() {
            previewName.textContent = nameInput.value.trim() || 'Nama Layanan';
            previewDescription.textContent = descriptionInput.value.trim() || 'Deskripsi singkat layanan akan tampil di bagian ini.';
            descriptionCount.textContent = descriptionInput.value.length;
        }

        function syncIcon() {
            const checked = document.querySelector('input[name="icon"]:checked');
            if (!checked) return;
            const option = checked.nextElementSibling;
            const shape = option ? option.querySelector('.kms-icon-shape') : null;
            if (shape) previewIcon.innerHTML = shape.innerHTML;
        }

        function syncStatus() {
            if (!liveStatus || !statusInput) return;
            if (statusInput.checked) {
                liveStatus.textContent = 'Tampil di Website';
                liveStatus.classList.remove('off');
            } else {
                liveStatus.textContent = 'Disembunyikan';
                liveStatus.classList.add('off');
            }
        }

        nameInput.addEventListener('input', syncText);
        descriptionInput.addEventListener('input', syncText);
        iconInputs.forEach(function (input) { input.addEventListener('change', syncIcon); });
        statusInput.addEventListener('change', syncStatus);

        syncText();
        syncIcon();
        syncStatus();
    });
</script>
