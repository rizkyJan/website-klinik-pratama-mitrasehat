@php
    $editing = isset($article);
    $oldPhoto = $editing ? ($article->photo ?? null) : null;
    $oldPhotoUrl = null;

    if ($oldPhoto) {
        $oldPhotoUrl = filter_var($oldPhoto, FILTER_VALIDATE_URL) ? $oldPhoto : asset($oldPhoto);
    }
@endphp

<div class="kms-form-grid">
    <section class="kms-card">
        <h2>Informasi Artikel</h2>
        <p class="kms-card-note">Data ini akan ditampilkan pada halaman artikel publik klinik.</p>

        <div class="kms-field">
            <label for="title" class="kms-label">Judul Artikel <span class="kms-required">*</span></label>
            <input id="title" class="kms-input" type="text" name="title" required maxlength="255"
                value="{{ old('title', $editing ? $article->title : '') }}"
                placeholder="Contoh: 5 Tips Menjaga Kesehatan Gigi">
            @error('title')<p class="kms-error">{{ $message }}</p>@enderror
        </div>

        <div class="kms-field">
            <label for="category" class="kms-label">Kategori <span class="kms-required">*</span></label>
            <input id="category" class="kms-input" type="text" name="category" required maxlength="100"
                value="{{ old('category', $editing ? $article->category : '') }}"
                placeholder="Contoh: Tips Kesehatan">
            <p class="kms-help">Gunakan kategori singkat agar mudah dibaca pada kartu artikel.</p>
            @error('category')<p class="kms-error">{{ $message }}</p>@enderror
        </div>

        <div class="kms-field">
            <label for="content" class="kms-label">Isi Artikel <span class="kms-required">*</span></label>
            <textarea id="content" class="kms-textarea" name="content" required
                placeholder="Tulis isi artikel lengkap di sini...">{{ old('content', $editing ? $article->content : '') }}</textarea>
            <p class="kms-help">Gunakan paragraf yang pendek agar nyaman dibaca di halaman publik.</p>
            @error('content')<p class="kms-error">{{ $message }}</p>@enderror
        </div>
    </section>

    <aside class="kms-aside">
        <section class="kms-card">
            <h2>Foto Artikel</h2>
            <p class="kms-card-note">Upload langsung dari folder komputer. JPG, JPEG, PNG, atau WebP. Maksimal 10 MB.</p>

            <div class="kms-photo-box">
                @if($oldPhotoUrl)
                    <img id="photoPreview" src="{{ $oldPhotoUrl }}" alt="{{ $editing ? $article->title : 'Foto artikel' }}">
                    <div id="photoPlaceholder" class="kms-photo-placeholder" style="display:none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                        Belum ada foto
                    </div>
                @else
                    <img id="photoPreview" src="" alt="Preview foto artikel" style="display:none;">
                    <div id="photoPlaceholder" class="kms-photo-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                        Belum ada foto
                    </div>
                @endif
            </div>

            <label class="kms-file-btn" for="photo">{{ $oldPhoto ? 'Ganti Foto dari Folder' : 'Pilih Foto dari Folder' }}</label>
            <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" style="display:none;">
            <p id="photoFileName" class="kms-file-name">{{ $oldPhoto ? 'Foto saat ini tetap dipakai jika tidak diganti' : 'Belum ada foto dipilih' }}</p>
            @error('photo')<p class="kms-error" style="text-align:center">{{ $message }}</p>@enderror

            <x-image-adjuster
                input="photo"
                previews="photoPreview,cardPhoto"
                placeholders="photoPlaceholder,cardPhotoEmpty"
                filename="photoFileName"
                :current="$oldPhotoUrl ?: ''"
                :editable="(bool) ($oldPhoto && ! filter_var($oldPhoto, FILTER_VALIDATE_URL))"
                title="Atur Foto Artikel"
                buttonlabel="Atur Foto Saat Ini"
                :aspectw="16"
                :aspecth="10"
                :outputw="1600"
                :outputh="1000"
                :maxmb="10"
            />
        </section>

        <section class="kms-card" style="margin-top:16px;">
            <h2>Preview Kartu Publik</h2>
            <p class="kms-card-note">Preview sederhana sebelum artikel disimpan.</p>

            <div class="kms-preview-card">
                <div class="kms-preview-image" id="cardPhotoWrap">
                    @if($oldPhotoUrl)
                        <img id="cardPhoto" src="{{ $oldPhotoUrl }}" alt="Preview artikel">
                    @else
                        <span id="cardPhotoEmpty">FOTO ARTIKEL</span>
                        <img id="cardPhoto" src="" alt="Preview artikel" style="display:none;">
                    @endif
                </div>
                <div class="kms-preview-content">
                    <span class="kms-preview-category" id="cardCategory">{{ old('category', $editing ? $article->category : 'Kategori') ?: 'Kategori' }}</span>
                    <h3 class="kms-preview-title" id="cardTitle">{{ old('title', $editing ? $article->title : 'Judul artikel akan tampil di sini') ?: 'Judul artikel akan tampil di sini' }}</h3>
                    <p class="kms-preview-text" id="cardText">Ringkasan isi artikel akan muncul otomatis dari tulisan Anda.</p>
                </div>
            </div>
        </section>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('title');
        const categoryInput = document.getElementById('category');
        const contentInput = document.getElementById('content');
        const cardTitle = document.getElementById('cardTitle');
        const cardCategory = document.getElementById('cardCategory');
        const cardText = document.getElementById('cardText');

        function cleanSummary(value) {
            const text = (value || '').replace(/\s+/g, ' ').trim();
            if (!text) return 'Ringkasan isi artikel akan muncul otomatis dari tulisan Anda.';
            return text.length > 110 ? text.substring(0, 110) + '…' : text;
        }

        function syncTextPreview() {
            cardTitle.textContent = titleInput.value.trim() || 'Judul artikel akan tampil di sini';
            cardCategory.textContent = categoryInput.value.trim() || 'Kategori';
            cardText.textContent = cleanSummary(contentInput.value);
        }

        titleInput.addEventListener('input', syncTextPreview);
        categoryInput.addEventListener('input', syncTextPreview);
        contentInput.addEventListener('input', syncTextPreview);
        syncTextPreview();

    });
</script>
