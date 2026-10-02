@php
    $editing = isset($promo);
    $oldPoster = $editing ? ($promo->poster ?? null) : null;
    $oldPosterUrl = null;

    if ($oldPoster) {
        $oldPosterUrl = filter_var($oldPoster, FILTER_VALIDATE_URL) ? $oldPoster : asset($oldPoster);
    }
@endphp

<div class="kms-form-grid">
    <section class="kms-card">
        <h2>Informasi Promo</h2>
        <p class="kms-card-note">Data ini akan digunakan pada kartu promo di halaman publik klinik.</p>

        <div class="kms-field">
            <label for="title" class="kms-label">Judul Promo <span class="kms-required">*</span></label>
            <input id="title" class="kms-input" type="text" name="title" required maxlength="255"
                value="{{ old('title', $editing ? $promo->title : '') }}"
                placeholder="Contoh: Paket Cek Sehat">
            @error('title')<p class="kms-error">{{ $message }}</p>@enderror
        </div>

        <div class="kms-two">
            <div class="kms-field">
                <label for="period" class="kms-label">Periode <span class="kms-required">*</span></label>
                <input id="period" class="kms-input" type="text" name="period" required maxlength="150"
                    value="{{ old('period', $editing ? $promo->period : '') }}"
                    placeholder="Contoh: Januari 2026">
                <p class="kms-help">Tuliskan periode seperti yang ingin ditampilkan di website.</p>
                @error('period')<p class="kms-error">{{ $message }}</p>@enderror
            </div>

            <div class="kms-field">
                <label for="price" class="kms-label">Harga <span class="kms-required">*</span></label>
                <input id="price" class="kms-input" type="text" name="price" required maxlength="100"
                    value="{{ old('price', $editing ? $promo->price : '') }}"
                    placeholder="Contoh: Rp 150.000">
                <p class="kms-help">Biarkan format harga mengikuti data yang selama ini sudah dipakai.</p>
                @error('price')<p class="kms-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>

    <aside class="kms-aside">
        <section class="kms-card">
            <h2>Poster Promo</h2>
            <p class="kms-card-note">Upload langsung dari folder komputer. JPG, JPEG, PNG, atau WebP. Maksimal 10 MB.</p>

            <div class="kms-photo-box">
                @if($oldPosterUrl)
                    <img id="posterPreview" src="{{ $oldPosterUrl }}" alt="{{ $editing ? $promo->title : 'Poster promo' }}">
                    <div id="posterPlaceholder" class="kms-photo-placeholder" style="display:none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                        Belum ada poster
                    </div>
                @else
                    <img id="posterPreview" src="" alt="Preview poster promo" style="display:none;">
                    <div id="posterPlaceholder" class="kms-photo-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.7" d="M4 5h16v14H4zM7 15l3-3 3 3 2-2 3 3M9 9h.01"/></svg>
                        Belum ada poster
                    </div>
                @endif
            </div>

            <label class="kms-file-btn" for="poster">{{ $oldPoster ? 'Ganti Poster dari Folder' : 'Pilih Poster dari Folder' }}</label>
            <input id="poster" type="file" name="poster" accept="image/jpeg,image/png,image/webp" style="display:none;">
            <p id="posterFileName" class="kms-file-name">{{ $oldPoster ? 'Poster saat ini tetap dipakai jika tidak diganti' : 'Belum ada poster dipilih' }}</p>
            @error('poster')<p class="kms-error" style="text-align:center">{{ $message }}</p>@enderror

            <x-image-adjuster
                input="poster"
                previews="posterPreview,cardPoster"
                placeholders="posterPlaceholder,cardPosterEmpty"
                filename="posterFileName"
                :current="$oldPosterUrl ?: ''"
                :editable="(bool) ($oldPoster && ! filter_var($oldPoster, FILTER_VALIDATE_URL))"
                title="Atur Poster Promo"
                buttonlabel="Atur Poster Saat Ini"
                :aspectw="16"
                :aspecth="10"
                :outputw="1600"
                :outputh="1000"
                :maxmb="10"
            />
        </section>

        <section class="kms-card" style="margin-top:16px;">
            <h2>Preview Kartu Publik</h2>
            <p class="kms-card-note">Preview sederhana sebelum promo disimpan.</p>

            <div class="kms-preview-card">
                <div class="kms-preview-image">
                    @if($oldPosterUrl)
                        <img id="cardPoster" src="{{ $oldPosterUrl }}" alt="Preview promo">
                        <span id="cardPosterEmpty" style="display:none;">POSTER PROMO</span>
                    @else
                        <span id="cardPosterEmpty">POSTER PROMO</span>
                        <img id="cardPoster" src="" alt="Preview promo" style="display:none;">
                    @endif
                    <span class="kms-preview-badge">Promo</span>
                </div>
                <div class="kms-preview-content">
                    <h3 class="kms-preview-title" id="cardTitle">{{ old('title', $editing ? $promo->title : 'Judul promo akan tampil di sini') ?: 'Judul promo akan tampil di sini' }}</h3>
                    <div class="kms-preview-info">
                        <div><small>Periode</small><strong id="cardPeriod">{{ old('period', $editing ? $promo->period : 'Periode promo') ?: 'Periode promo' }}</strong></div>
                        <div><small>Harga</small><strong id="cardPrice">{{ old('price', $editing ? $promo->price : 'Harga promo') ?: 'Harga promo' }}</strong></div>
                    </div>
                </div>
            </div>
        </section>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('title');
        const periodInput = document.getElementById('period');
        const priceInput = document.getElementById('price');
        const cardTitle = document.getElementById('cardTitle');
        const cardPeriod = document.getElementById('cardPeriod');
        const cardPrice = document.getElementById('cardPrice');

        function syncPreview() {
            cardTitle.textContent = titleInput.value.trim() || 'Judul promo akan tampil di sini';
            cardPeriod.textContent = periodInput.value.trim() || 'Periode promo';
            cardPrice.textContent = priceInput.value.trim() || 'Harga promo';
        }

        titleInput.addEventListener('input', syncPreview);
        periodInput.addEventListener('input', syncPreview);
        priceInput.addEventListener('input', syncPreview);
        syncPreview();

    });
</script>
