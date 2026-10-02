@php
    $editing = isset($branch);
    $oldPhoto = $editing ? ($branch->photo ?? null) : null;
    $oldPhotoUrl = null;

    if ($oldPhoto) {
        $oldPhotoUrl = filter_var($oldPhoto, FILTER_VALIDATE_URL) ? $oldPhoto : asset($oldPhoto);
    }
@endphp

<div class="kms-form-grid">
    <section class="kms-card">
        <h2>Informasi Cabang</h2>
        <p class="kms-card-note">Informasi ini akan tampil pada halaman Cabang di website klinik.</p>

        <div class="kms-field">
            <label for="name" class="kms-label">Nama Cabang <span class="kms-required">*</span></label>
            <input id="name" class="kms-input" type="text" name="name" required maxlength="255"
                value="{{ old('name', $editing ? $branch->name : '') }}"
                placeholder="Contoh: Klinik Pratama Mitra Sehat Sukoharjo">
            @error('name')<p class="kms-error">{{ $message }}</p>@enderror
        </div>

        <div class="kms-field">
            <label for="maps_url" class="kms-label">Alamat / Link Google Maps</label>
            <input id="maps_url" class="kms-input" type="url" name="maps_url" maxlength="2000"
                value="{{ old('maps_url', $editing ? $branch->maps_url : '') }}"
                placeholder="https://maps.app.goo.gl/xxxxxxxx">
            <p class="kms-help">Buka lokasi di Google Maps → Bagikan → Salin link → tempel di sini.</p>
            @error('maps_url')<p class="kms-error">{{ $message }}</p>@enderror
        </div>

        <div class="kms-field">
            <label for="description" class="kms-label">Deskripsi Singkat</label>
            <textarea id="description" class="kms-textarea" name="description" maxlength="1000" placeholder="Contoh: Cabang pelayanan Klinik Mitra Sehat yang melayani pemeriksaan umum, konsultasi, dan layanan kesehatan lainnya.">{{ old('description', $editing ? $branch->description : '') }}</textarea>
            <p class="kms-help"><span id="descriptionCount">0</span>/1000 karakter.</p>
            @error('description')<p class="kms-error">{{ $message }}</p>@enderror
        </div>
    </section>

    <aside>
        <section class="kms-card">
            <h2>Foto Cabang</h2>
            <p class="kms-card-note">Upload JPG, JPEG, PNG, atau WebP. Maksimal 10 MB. Foto bisa digeser dan di-zoom sebelum disimpan.</p>

            <div class="kms-photo-box">
                @if($oldPhotoUrl)
                    <img id="branchPhotoPreview" src="{{ $oldPhotoUrl }}" alt="{{ $editing ? $branch->name : 'Foto cabang' }}">
                    <div id="branchPhotoPlaceholder" class="kms-photo-placeholder" style="display:none">Belum ada foto</div>
                @else
                    <img id="branchPhotoPreview" src="" alt="Preview foto cabang" style="display:none">
                    <div id="branchPhotoPlaceholder" class="kms-photo-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                        Belum ada foto
                    </div>
                @endif
            </div>

            <label class="kms-file-btn" for="photo">{{ $oldPhoto ? 'Ganti Foto dari Folder' : 'Pilih Foto dari Folder' }}</label>
            <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" style="display:none">
            <p id="branchPhotoFileName" class="kms-file-name">{{ $oldPhoto ? 'Foto saat ini tetap dipakai jika tidak diganti' : 'Belum ada foto dipilih' }}</p>
            @error('photo')<p class="kms-error" style="text-align:center">{{ $message }}</p>@enderror

            <x-image-adjuster
                input="photo"
                previews="branchPhotoPreview,branchCardPhoto"
                placeholders="branchPhotoPlaceholder,branchCardPhotoEmpty"
                filename="branchPhotoFileName"
                :current="$oldPhotoUrl ?: ''"
                :editable="(bool) ($oldPhoto && ! filter_var($oldPhoto, FILTER_VALIDATE_URL))"
                title="Atur Foto Cabang"
                buttonlabel="Atur Foto Saat Ini"
                :aspectw="16"
                :aspecth="10"
                :outputw="1600"
                :outputh="1000"
                :maxmb="10"
            />
        </section>

        <section class="kms-card" style="margin-top:16px">
            <h2>Preview Kartu Publik</h2>
            <p class="kms-card-note">Preview sederhana sebelum cabang disimpan.</p>
            <div class="kms-preview-card">
                <div class="kms-preview-image">
                    @if($oldPhotoUrl)
                        <img id="branchCardPhoto" src="{{ $oldPhotoUrl }}" alt="Preview cabang">
                        <span id="branchCardPhotoEmpty" style="display:none">FOTO CABANG</span>
                    @else
                        <span id="branchCardPhotoEmpty">FOTO CABANG</span>
                        <img id="branchCardPhoto" src="" alt="Preview cabang" style="display:none">
                    @endif
                </div>
                <div class="kms-preview-content">
                    <h3 class="kms-preview-title" id="branchCardTitle">{{ old('name', $editing ? $branch->name : 'Nama cabang') ?: 'Nama cabang' }}</h3>
                    <p class="kms-preview-desc" id="branchCardDescription">{{ old('description', $editing ? $branch->description : 'Deskripsi singkat cabang akan tampil di sini.') ?: 'Deskripsi singkat cabang akan tampil di sini.' }}</p>
                    <span class="kms-preview-map">⌖ Lihat Google Maps</span>
                </div>
            </div>
        </section>
    </aside>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const titlePreview = document.getElementById('branchCardTitle');
    const descriptionPreview = document.getElementById('branchCardDescription');
    const descriptionCount = document.getElementById('descriptionCount');

    function syncBranchPreview() {
        titlePreview.textContent = nameInput.value.trim() || 'Nama cabang';
        descriptionPreview.textContent = descriptionInput.value.trim() || 'Deskripsi singkat cabang akan tampil di sini.';
        descriptionCount.textContent = descriptionInput.value.length;
    }

    nameInput.addEventListener('input', syncBranchPreview);
    descriptionInput.addEventListener('input', syncBranchPreview);
    syncBranchPreview();
});
</script>
