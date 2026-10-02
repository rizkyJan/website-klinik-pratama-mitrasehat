@props([
    'input' => '',
    'previews' => '',
    'placeholders' => '',
    'filename' => '',
    'current' => '',
    'editable' => false,
    'title' => 'Atur Gambar',
    'buttonlabel' => 'Atur Gambar Saat Ini',
    'aspectw' => 1,
    'aspecth' => 1,
    'outputw' => 1200,
    'outputh' => 1200,
    'maxmb' => 10,
])

@php
    $uid = 'kms-adjust-' . preg_replace('/[^A-Za-z0-9_-]/', '-', $input);

    // Diproses di PHP terlebih dahulu agar Blade tidak perlu membaca
    // ekspresi PHP kompleks di dalam directive @json.
    $previewIdList = array_values(array_filter(array_map('trim', explode(',', (string) $previews))));
    $placeholderIdList = array_values(array_filter(array_map('trim', explode(',', (string) $placeholders))));

    $adjusterConfig = [
        'inputId' => (string) $input,
        'modalId' => $uid . '-modal',
        'canvasId' => $uid . '-canvas',
        'zoomId' => $uid . '-zoom',
        'zoomValueId' => $uid . '-zoom-value',
        'applyId' => $uid . '-apply',
        'currentButtonId' => $uid . '-current',
        'previewIds' => $previewIdList,
        'placeholderIds' => $placeholderIdList,
        'filenameId' => (string) $filename,
        'currentUrl' => (string) $current,
        'aspectW' => (float) $aspectw,
        'aspectH' => (float) $aspecth,
        'outputW' => (int) $outputw,
        'outputH' => (int) $outputh,
        'maxMb' => (float) $maxmb,
    ];
@endphp

<div class="kms-image-adjuster-tools" id="{{ $uid }}-tools">
    @if($current && $editable)
        <button type="button" class="kms-image-adjuster-current" id="{{ $uid }}-current">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M4 16.5V20h3.5L18.2 9.3l-3.5-3.5L4 16.5ZM13.6 6.9l3.5 3.5M15.7 4.8l1.1-1.1a1.6 1.6 0 0 1 2.3 0l1.2 1.2a1.6 1.6 0 0 1 0 2.3l-1.1 1.1"/>
            </svg>
            {{ $buttonlabel }}
        </button>
    @endif

    <p class="kms-image-adjuster-hint">
        Setelah pilih gambar, kamu bisa geser dan zoom sebelum disimpan.
    </p>
</div>

<div class="kms-image-adjuster-modal" id="{{ $uid }}-modal" hidden>
    <div class="kms-image-adjuster-backdrop" data-kms-adjust-close></div>

    <section
        class="kms-image-adjuster-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $uid }}-title"
    >
        <header class="kms-image-adjuster-head">
            <div>
                <p class="kms-image-adjuster-eyebrow">EDITOR GAMBAR</p>
                <h3 id="{{ $uid }}-title">{{ $title }}</h3>
                <p>Geser gambar langsung pada area preview. Gunakan zoom agar bagian penting pas di frame.</p>
            </div>

            <button
                type="button"
                class="kms-image-adjuster-x"
                data-kms-adjust-close
                aria-label="Tutup"
            >&times;</button>
        </header>

        <div class="kms-image-adjuster-body">
            <div class="kms-image-adjuster-canvas-wrap">
                <canvas id="{{ $uid }}-canvas"></canvas>
                <div class="kms-image-adjuster-guide" aria-hidden="true">
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>

            <div class="kms-image-adjuster-controls">
                <div class="kms-image-adjuster-control-row">
                    <label for="{{ $uid }}-zoom">Zoom</label>
                    <strong id="{{ $uid }}-zoom-value">100%</strong>
                </div>

                <input
                    id="{{ $uid }}-zoom"
                    class="kms-image-adjuster-range"
                    type="range"
                    min="100"
                    max="300"
                    step="1"
                    value="100"
                >

                <div class="kms-image-adjuster-quick">
                    <button type="button" data-kms-position="top">Atas</button>
                    <button type="button" data-kms-position="center">Tengah</button>
                    <button type="button" data-kms-position="bottom">Bawah</button>
                    <button type="button" data-kms-reset>Reset</button>
                </div>

                <div class="kms-image-adjuster-note">
                    <strong>Tips:</strong>
                    fokuskan wajah, produk, atau bagian terpenting di tengah frame agar tetap bagus di desktop dan HP.
                </div>
            </div>
        </div>

        <footer class="kms-image-adjuster-footer">
            <button
                type="button"
                class="kms-image-adjuster-btn secondary"
                data-kms-adjust-close
            >Batal</button>

            <button
                type="button"
                class="kms-image-adjuster-btn primary"
                id="{{ $uid }}-apply"
            >Terapkan Gambar</button>
        </footer>
    </section>
</div>

<style>
    .kms-image-adjuster-tools{margin-top:9px;text-align:center}
    .kms-image-adjuster-current{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:38px;padding:0 13px;border:1px solid #d7e7dc;border-radius:11px;background:#fff;color:#17623d;font:inherit;font-size:11px;font-weight:800;cursor:pointer}
    .kms-image-adjuster-current:hover{background:#f4faf6}
    .kms-image-adjuster-current svg{width:15px;height:15px}
    .kms-image-adjuster-hint{margin:7px 0 0;color:#8b9790;font-size:10px;line-height:1.45}
    .kms-image-adjuster-modal[hidden]{display:none!important}
    .kms-image-adjuster-modal{position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;padding:18px}
    .kms-image-adjuster-backdrop{position:absolute;inset:0;background:rgba(10,28,18,.68);backdrop-filter:blur(3px)}
    .kms-image-adjuster-dialog{position:relative;width:min(920px,100%);max-height:calc(100vh - 36px);overflow:auto;border:1px solid rgba(255,255,255,.65);border-radius:22px;background:#fff;box-shadow:0 28px 80px rgba(8,31,18,.28)}
    .kms-image-adjuster-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;padding:20px 22px;border-bottom:1px solid #e8eee9}
    .kms-image-adjuster-eyebrow{margin:0 0 4px;color:#1a5d3a;font-size:10px;font-weight:900;letter-spacing:.1em}
    .kms-image-adjuster-head h3{margin:0;color:#102a1d;font-size:20px;line-height:1.25;font-weight:850}
    .kms-image-adjuster-head p:not(.kms-image-adjuster-eyebrow){margin:5px 0 0;color:#78847c;font-size:11px;line-height:1.5}
    .kms-image-adjuster-x{width:36px;height:36px;flex:0 0 36px;border:1px solid #e0e7e2;border-radius:11px;background:#f7faf8;color:#536259;font-size:24px;line-height:1;cursor:pointer}
    .kms-image-adjuster-body{display:grid;grid-template-columns:minmax(0,1fr) 250px;gap:20px;padding:22px}
    .kms-image-adjuster-canvas-wrap{position:relative;min-height:340px;display:flex;align-items:center;justify-content:center;padding:12px;border-radius:18px;background:#102a1d;overflow:hidden;touch-action:none}
    .kms-image-adjuster-canvas-wrap canvas{display:block;max-width:100%;max-height:58vh;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,.25);cursor:grab;touch-action:none}
    .kms-image-adjuster-canvas-wrap canvas:active{cursor:grabbing}
    .kms-image-adjuster-guide{pointer-events:none;position:absolute;inset:12px;display:none}
    .kms-image-adjuster-controls{align-self:start}
    .kms-image-adjuster-control-row{display:flex;align-items:center;justify-content:space-between;gap:12px;color:#294336;font-size:12px;font-weight:800}
    .kms-image-adjuster-control-row strong{color:#1a5d3a;font-size:11px}
    .kms-image-adjuster-range{width:100%;margin:13px 0 18px;accent-color:#1a5d3a}
    .kms-image-adjuster-quick{display:grid;grid-template-columns:1fr 1fr;gap:8px}
    .kms-image-adjuster-quick button{min-height:38px;border:1px solid #dce5df;border-radius:10px;background:#f8faf8;color:#435248;font:inherit;font-size:10px;font-weight:800;cursor:pointer}
    .kms-image-adjuster-quick button:hover{border-color:#afd1ba;background:#eff8f1;color:#17623d}
    .kms-image-adjuster-note{margin-top:16px;padding:12px;border:1px solid #dcebe1;border-radius:12px;background:#f2faf4;color:#627169;font-size:10px;line-height:1.55}
    .kms-image-adjuster-note strong{color:#1b6540}
    .kms-image-adjuster-footer{display:flex;justify-content:flex-end;gap:9px;padding:16px 22px;border-top:1px solid #e8eee9;background:#fbfcfb}
    .kms-image-adjuster-btn{min-height:40px;padding:0 16px;border-radius:11px;font:inherit;font-size:11px;font-weight:850;cursor:pointer}
    .kms-image-adjuster-btn.secondary{border:1px solid #dce3de;background:#fff;color:#4e5b53}
    .kms-image-adjuster-btn.primary{border:1px solid #1a5d3a;background:#1a5d3a;color:#fff}
    body.kms-image-adjuster-lock{overflow:hidden}

    @media(max-width:760px){
        .kms-image-adjuster-modal{padding:8px}
        .kms-image-adjuster-dialog{max-height:calc(100vh - 16px);border-radius:17px}
        .kms-image-adjuster-body{grid-template-columns:1fr;padding:14px}
        .kms-image-adjuster-canvas-wrap{min-height:270px}
        .kms-image-adjuster-head{padding:16px}
        .kms-image-adjuster-footer{padding:14px 16px}
        .kms-image-adjuster-controls{padding:0 3px}
    }
</style>

<script>
(function () {
    const config = {{ \Illuminate\Support\Js::from($adjusterConfig) }};

    const boot = function () {
        const input = document.getElementById(config.inputId);
        const modal = document.getElementById(config.modalId);
        const canvas = document.getElementById(config.canvasId);
        const zoomRange = document.getElementById(config.zoomId);
        const zoomValue = document.getElementById(config.zoomValueId);
        const applyButton = document.getElementById(config.applyId);
        const currentButton = document.getElementById(config.currentButtonId);

        if (!input || !modal || !canvas || !zoomRange || !applyButton) {
            return;
        }

        const ctx = canvas.getContext('2d');
        const previewIds = Array.isArray(config.previewIds) ? config.previewIds : [];
        const placeholderIds = Array.isArray(config.placeholderIds) ? config.placeholderIds : [];
        const fileNameEl = config.filenameId
            ? document.getElementById(config.filenameId)
            : null;
        const currentUrl = config.currentUrl || '';
        const aspectW = Math.max(1, Number(config.aspectW) || 1);
        const aspectH = Math.max(1, Number(config.aspectH) || 1);
        const outputW = Math.max(300, Number(config.outputW) || 1200);
        const outputH = Math.max(300, Number(config.outputH) || 1200);
        const maxMb = Math.max(1, Number(config.maxMb) || 10);
        const maxBytes = maxMb * 1024 * 1024;

        canvas.width = 900;
        canvas.height = Math.round(900 * aspectH / aspectW);

        let image = null;
        let sourceName = 'gambar.jpg';
        let sourceMime = 'image/jpeg';
        let baseScale = 1;
        let zoom = 1;
        let offsetX = 0;
        let offsetY = 0;
        let dragging = false;
        let lastX = 0;
        let lastY = 0;
        let objectUrl = null;
        let openingNewFile = false;

        function getPreviewElements() {
            return previewIds
                .map(function (id) { return document.getElementById(id); })
                .filter(Boolean);
        }

        function getPlaceholderElements() {
            return placeholderIds
                .map(function (id) { return document.getElementById(id); })
                .filter(Boolean);
        }

        function setModal(open) {
            modal.hidden = !open;
            document.body.classList.toggle('kms-image-adjuster-lock', open);
        }

        function clampOffsets() {
            if (!image) return;

            const scale = baseScale * zoom;
            const drawWidth = image.naturalWidth * scale;
            const drawHeight = image.naturalHeight * scale;
            const minX = canvas.width - drawWidth;
            const minY = canvas.height - drawHeight;

            offsetX = Math.min(0, Math.max(minX, offsetX));
            offsetY = Math.min(0, Math.max(minY, offsetY));
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            if (!image) return;

            const scale = baseScale * zoom;
            const drawWidth = image.naturalWidth * scale;
            const drawHeight = image.naturalHeight * scale;

            clampOffsets();
            ctx.drawImage(image, offsetX, offsetY, drawWidth, drawHeight);
        }

        function centerImage(verticalPosition) {
            if (!image) return;

            const scale = baseScale * zoom;
            const drawWidth = image.naturalWidth * scale;
            const drawHeight = image.naturalHeight * scale;

            offsetX = (canvas.width - drawWidth) / 2;

            if (verticalPosition === 'top') {
                offsetY = 0;
            } else if (verticalPosition === 'bottom') {
                offsetY = canvas.height - drawHeight;
            } else {
                offsetY = (canvas.height - drawHeight) / 2;
            }

            clampOffsets();
            draw();
        }

        function resetImage() {
            if (!image) return;

            baseScale = Math.max(
                canvas.width / image.naturalWidth,
                canvas.height / image.naturalHeight
            );

            zoom = 1;
            zoomRange.value = '100';

            if (zoomValue) {
                zoomValue.textContent = '100%';
            }

            centerImage('center');
        }

        function loadSource(src, name, mime, isNewFile) {
            const nextImage = new Image();

            if (!isNewFile) {
                nextImage.crossOrigin = 'anonymous';
            }

            nextImage.onload = function () {
                image = nextImage;
                sourceName = name || 'gambar.jpg';
                sourceMime = ['image/jpeg', 'image/png', 'image/webp'].includes(mime)
                    ? mime
                    : 'image/jpeg';
                openingNewFile = Boolean(isNewFile);

                resetImage();
                setModal(true);
            };

            nextImage.onerror = function () {
                if (isNewFile) {
                    input.value = '';
                }

                alert('Gambar tidak dapat dibuka. Silakan pilih file gambar lain.');
            };

            nextImage.src = src;
        }

        function openFile(file) {
            if (!file) return;

            if (file.size > maxBytes) {
                input.value = '';

                if (fileNameEl) {
                    fileNameEl.textContent = 'File terlalu besar. Maksimal ' + maxMb + ' MB.';
                }

                alert('Ukuran gambar maksimal ' + maxMb + ' MB.');
                return;
            }

            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                input.value = '';
                alert('Format gambar harus JPG, JPEG, PNG, atau WebP.');
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                loadSource(event.target.result, file.name, file.type, true);
            };

            reader.readAsDataURL(file);
        }

        input.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (file) {
                openFile(file);
            }
        });

        if (currentButton && currentUrl) {
            currentButton.addEventListener('click', function () {
                const path = currentUrl.split('?')[0];
                let mime = 'image/jpeg';

                if (/\.png$/i.test(path)) {
                    mime = 'image/png';
                } else if (/\.webp$/i.test(path)) {
                    mime = 'image/webp';
                }

                const extension = mime === 'image/png'
                    ? 'png'
                    : (mime === 'image/webp' ? 'webp' : 'jpg');

                loadSource(
                    currentUrl,
                    'gambar-saat-ini.' + extension,
                    mime,
                    false
                );
            });
        }

        modal.querySelectorAll('[data-kms-adjust-close]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (openingNewFile) {
                    input.value = '';
                }

                setModal(false);
            });
        });

        modal.querySelectorAll('[data-kms-position]').forEach(function (button) {
            button.addEventListener('click', function () {
                centerImage(this.getAttribute('data-kms-position') || 'center');
            });
        });

        const resetButton = modal.querySelector('[data-kms-reset]');

        if (resetButton) {
            resetButton.addEventListener('click', resetImage);
        }

        zoomRange.addEventListener('input', function () {
            if (!image) return;

            const oldScale = baseScale * zoom;
            const centerSourceX = (canvas.width / 2 - offsetX) / oldScale;
            const centerSourceY = (canvas.height / 2 - offsetY) / oldScale;

            zoom = Number(this.value) / 100;

            const newScale = baseScale * zoom;
            offsetX = canvas.width / 2 - centerSourceX * newScale;
            offsetY = canvas.height / 2 - centerSourceY * newScale;

            if (zoomValue) {
                zoomValue.textContent = this.value + '%';
            }

            draw();
        });

        canvas.addEventListener('pointerdown', function (event) {
            if (!image) return;

            dragging = true;

            const rect = canvas.getBoundingClientRect();
            lastX = event.clientX * canvas.width / rect.width;
            lastY = event.clientY * canvas.height / rect.height;

            canvas.setPointerCapture(event.pointerId);
        });

        canvas.addEventListener('pointermove', function (event) {
            if (!dragging || !image) return;

            const rect = canvas.getBoundingClientRect();
            const x = event.clientX * canvas.width / rect.width;
            const y = event.clientY * canvas.height / rect.height;

            offsetX += x - lastX;
            offsetY += y - lastY;

            lastX = x;
            lastY = y;

            draw();
        });

        function stopDragging(event) {
            dragging = false;

            try {
                canvas.releasePointerCapture(event.pointerId);
            } catch (error) {
                // Pointer capture mungkin sudah dilepas browser.
            }
        }

        canvas.addEventListener('pointerup', stopDragging);
        canvas.addEventListener('pointercancel', stopDragging);

        applyButton.addEventListener('click', function () {
            if (!image) return;

            const scale = baseScale * zoom;
            const sourceX = Math.max(0, -offsetX / scale);
            const sourceY = Math.max(0, -offsetY / scale);
            const sourceWidth = Math.min(
                image.naturalWidth - sourceX,
                canvas.width / scale
            );
            const sourceHeight = Math.min(
                image.naturalHeight - sourceY,
                canvas.height / scale
            );

            const output = document.createElement('canvas');
            output.width = outputW;
            output.height = outputH;

            const outputCtx = output.getContext('2d');
            outputCtx.imageSmoothingEnabled = true;
            outputCtx.imageSmoothingQuality = 'high';
            outputCtx.drawImage(
                image,
                sourceX,
                sourceY,
                sourceWidth,
                sourceHeight,
                0,
                0,
                outputW,
                outputH
            );

            const mime = sourceMime === 'image/png'
                ? 'image/png'
                : (sourceMime === 'image/webp' ? 'image/webp' : 'image/jpeg');

            const quality = mime === 'image/png' ? undefined : 0.92;

            output.toBlob(function (blob) {
                if (!blob) {
                    alert('Gagal memproses gambar. Silakan coba lagi.');
                    return;
                }

                if (blob.size > maxBytes) {
                    alert('Hasil gambar masih lebih dari ' + maxMb + ' MB. Coba gunakan gambar yang lebih kecil.');
                    return;
                }

                const extension = mime === 'image/png'
                    ? 'png'
                    : (mime === 'image/webp' ? 'webp' : 'jpg');

                const baseName = (sourceName || 'gambar')
                    .replace(/\.[^.]+$/, '')
                    .replace(/[^A-Za-z0-9_-]+/g, '-')
                    .replace(/^-+|-+$/g, '') || 'gambar';

                const adjustedFile = new File(
                    [blob],
                    baseName + '-diatur.' + extension,
                    {
                        type: mime,
                        lastModified: Date.now()
                    }
                );

                const transfer = new DataTransfer();
                transfer.items.add(adjustedFile);
                input.files = transfer.files;

                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                }

                objectUrl = URL.createObjectURL(adjustedFile);

                getPreviewElements().forEach(function (element) {
                    element.src = objectUrl;
                    element.style.display = 'block';
                });

                getPlaceholderElements().forEach(function (element) {
                    element.style.display = 'none';
                });

                if (fileNameEl) {
                    fileNameEl.textContent = adjustedFile.name + ' - sudah diatur';
                }

                openingNewFile = false;
                setModal(false);

                input.dispatchEvent(new CustomEvent('kms:image-adjusted', {
                    bubbles: true,
                    detail: {
                        url: objectUrl,
                        file: adjustedFile
                    }
                }));
            }, mime, quality);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.hidden) {
                if (openingNewFile) {
                    input.value = '';
                }

                setModal(false);
            }
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
</script>
