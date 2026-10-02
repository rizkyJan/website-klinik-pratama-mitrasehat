@php
    $editing = isset($gallery);
    $oldPhoto = $editing ? ($gallery->photo ?? null) : null;
    $oldPhotoUrl = null;

    if ($oldPhoto) {
        $oldPhotoUrl = filter_var($oldPhoto, FILTER_VALIDATE_URL) ? $oldPhoto : asset($oldPhoto);
    }
@endphp

<div class="kms-form-grid">
    <section class="kms-card">
        <h2>Informasi Galeri</h2>
        <p class="kms-card-note">Kategori dan caption akan membantu pengunjung memahami dokumentasi yang ditampilkan.</p>

        <div class="kms-field">
            <label for="category" class="kms-label">Kategori <span class="kms-required">*</span></label>
            <input id="category" class="kms-input" type="text" name="category" required maxlength="255"
                value="{{ old('category', $editing ? $gallery->category : '') }}"
                placeholder="Contoh: Pelayanan, Fasilitas, Event Kesehatan">
            <p class="kms-help">Gunakan kategori yang konsisten agar galeri publik mudah difilter.</p>
            @error('category')<p class="kms-error">{{ $message }}</p>@enderror
        </div>

        <div class="kms-field">
            <label for="caption" class="kms-label">Caption <span style="color:#9aa29d;font-weight:600;">(opsional)</span></label>
            <textarea id="caption" class="kms-textarea" name="caption" maxlength="1000"
                placeholder="Contoh: Pemeriksaan kesehatan rutin oleh tim Klinik Pratama Mitra Sehat.">{{ old('caption', $editing ? $gallery->caption : '') }}</textarea>
            <p class="kms-help"><span id="captionCount">0</span>/1000 karakter. Caption singkat 1–2 kalimat biasanya paling rapi.</p>
            @error('caption')<p class="kms-error">{{ $message }}</p>@enderror
        </div>
    </section>

    <aside class="kms-aside">
        <section class="kms-card">
            <h2>Foto Galeri</h2>
            <p class="kms-card-note">Upload dari folder komputer. JPG, JPEG, PNG, atau WebP. Maksimal 10 MB.</p>

            <div class="kms-photo-box">
                @if($oldPhotoUrl)
                    <img id="galleryPreview" src="{{ $oldPhotoUrl }}" alt="{{ $editing ? $gallery->category : 'Foto galeri' }}">
                    <div id="galleryPlaceholder" class="kms-photo-placeholder" style="display:none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                        Belum ada foto
                    </div>
                @else
                    <img id="galleryPreview" src="" alt="Preview foto galeri" style="display:none;">
                    <div id="galleryPlaceholder" class="kms-photo-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                        Belum ada foto
                    </div>
                @endif
            </div>

            <label class="kms-file-btn" for="photo">{{ $oldPhoto ? 'Ganti Foto dari Folder' : 'Pilih Foto dari Folder' }}</label>
            <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" style="display:none;">
            <p id="galleryFileName" class="kms-file-name">{{ $oldPhoto ? 'Foto saat ini tetap dipakai jika tidak diganti' : 'Belum ada foto dipilih' }}</p>
            @error('photo')<p class="kms-error" style="text-align:center">{{ $message }}</p>@enderror

            <x-image-adjuster
                input="photo"
                previews="galleryPreview,cardGalleryPreview"
                placeholders="galleryPlaceholder,cardGalleryEmpty"
                filename="galleryFileName"
                :current="$oldPhotoUrl ?: ''"
                :editable="(bool) ($oldPhoto && ! filter_var($oldPhoto, FILTER_VALIDATE_URL))"
                title="Atur Foto Galeri"
                buttonlabel="Atur Foto Saat Ini"
                :aspectw="4"
                :aspecth="3"
                :outputw="1600"
                :outputh="1200"
                :maxmb="10"
            />
        </section>

        <section class="kms-card" style="margin-top:16px;">
            <h2>Preview Galeri Publik</h2>
            <p class="kms-card-note">Preview sederhana tampilan kartu di halaman galeri.</p>

            <div class="kms-preview-card">
                <div class="kms-preview-image">
                    @if($oldPhotoUrl)
                        <img id="cardGalleryPreview" src="{{ $oldPhotoUrl }}" alt="Preview galeri">
                        <span id="cardGalleryEmpty" style="display:none;">FOTO GALERI</span>
                    @else
                        <span id="cardGalleryEmpty">FOTO GALERI</span>
                        <img id="cardGalleryPreview" src="" alt="Preview galeri" style="display:none;">
                    @endif
                    <span class="kms-preview-badge" id="cardCategory">{{ old('category', $editing ? $gallery->category : 'Kategori') ?: 'Kategori' }}</span>
                </div>
                <div class="kms-preview-content">
                    <p id="cardCaption" class="{{ old('caption', $editing ? $gallery->caption : '') ? 'kms-preview-caption' : 'kms-preview-empty' }}">{{ old('caption', $editing ? $gallery->caption : '') ?: 'Caption foto akan tampil di sini bila diisi.' }}</p>
                </div>
            </div>
        </section>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoryInput = document.getElementById('category');
        const captionInput = document.getElementById('caption');
        const cardCategory = document.getElementById('cardCategory');
        const cardCaption = document.getElementById('cardCaption');
        const captionCount = document.getElementById('captionCount');

        function syncTextPreview() {
            const category = categoryInput.value.trim();
            const caption = captionInput.value.trim();

            cardCategory.textContent = category || 'Kategori';
            captionCount.textContent = captionInput.value.length;

            if (caption) {
                cardCaption.textContent = caption;
                cardCaption.className = 'kms-preview-caption';
            } else {
                cardCaption.textContent = 'Caption foto akan tampil di sini bila diisi.';
                cardCaption.className = 'kms-preview-empty';
            }
        }

        categoryInput.addEventListener('input', syncTextPreview);
        captionInput.addEventListener('input', syncTextPreview);
        syncTextPreview();
    });
</script>
