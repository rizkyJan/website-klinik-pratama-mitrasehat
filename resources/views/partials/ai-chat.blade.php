@php
    $aiName = $aiWidgetSettings['assistant_name'] ?? 'Asisten Klinik Mitra Sehat';
    $aiWelcome = $aiWidgetSettings['welcome_message'] ?? 'Halo! Saya Asisten Klinik Mitra Sehat. Ada yang bisa saya bantu?';
@endphp

<style>
    #clinicAiRoot{position:fixed;right:18px;bottom:18px;z-index:80;font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
    .clinic-ai-toggle{width:58px;height:58px;border:0;border-radius:999px;background:#1a5d3a;color:#fff;box-shadow:0 14px 34px rgba(26,93,58,.28);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:.2s ease}
    .clinic-ai-toggle:hover{transform:translateY(-2px);background:#154a2e}.clinic-ai-toggle svg{width:26px;height:26px}
    .clinic-ai-panel{display:none;position:absolute;right:0;bottom:72px;width:min(390px,calc(100vw - 28px));height:min(610px,calc(100vh - 110px));background:#fff;border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 24px 70px rgba(15,23,42,.22);overflow:hidden}
    .clinic-ai-panel.is-open{display:flex;flex-direction:column}
    .clinic-ai-header{background:#1a5d3a;color:#fff;padding:14px 15px;display:flex;align-items:center;justify-content:space-between;gap:12px}
    .clinic-ai-title-wrap{display:flex;align-items:center;gap:10px;min-width:0}.clinic-ai-avatar{width:38px;height:38px;border-radius:999px;background:#fff;color:#1a5d3a;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;flex:0 0 auto}
    .clinic-ai-title{font-size:14px;font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.clinic-ai-status{font-size:11px;color:#d1fae5;margin-top:2px}
    .clinic-ai-head-actions{display:flex;gap:6px}.clinic-ai-head-btn{width:32px;height:32px;border:0;border-radius:8px;background:rgba(255,255,255,.12);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:16px}.clinic-ai-head-btn:hover{background:rgba(255,255,255,.2)}
    .clinic-ai-messages{flex:1;overflow:auto;padding:16px;background:#f8faf8;scroll-behavior:smooth}.clinic-ai-row{display:flex;margin:0 0 11px}.clinic-ai-row.user{justify-content:flex-end}.clinic-ai-bubble{max-width:84%;padding:10px 12px;border-radius:15px;font-size:13px;line-height:1.55;white-space:pre-wrap;word-break:break-word}.clinic-ai-row.assistant .clinic-ai-bubble{background:#fff;color:#26352d;border:1px solid #e4ebe6;border-bottom-left-radius:5px;white-space:normal}.clinic-ai-row.user .clinic-ai-bubble{background:#1a5d3a;color:#fff;border-bottom-right-radius:5px}.clinic-ai-rich .ai-p{margin:0 0 8px}.clinic-ai-rich .ai-p:last-child{margin-bottom:0}.clinic-ai-rich .ai-title{font-weight:800;color:#184f33;margin:9px 0 5px}.clinic-ai-rich .ai-list{display:flex;gap:7px;align-items:flex-start;margin:4px 0}.clinic-ai-rich .ai-list-mark{min-width:16px;color:#1a5d3a;font-weight:800}.clinic-ai-rich hr{border:0;border-top:1px solid #e6ede8;margin:9px 0}.clinic-ai-rich strong{font-weight:800;color:#173f2a}
    .clinic-ai-suggestions{display:flex;gap:7px;overflow-x:auto;padding:0 14px 10px;background:#f8faf8;scrollbar-width:none}.clinic-ai-suggestions::-webkit-scrollbar{display:none}.clinic-ai-chip{flex:0 0 auto;border:1px solid #d9e7dc;background:#fff;color:#1a5d3a;border-radius:999px;padding:7px 10px;font-size:11px;font-weight:700;cursor:pointer}.clinic-ai-chip:hover{background:#eef8f1}
    .clinic-ai-note{padding:8px 14px;background:#fff8e6;border-top:1px solid #f5e5b5;color:#7c5b12;font-size:10px;line-height:1.4}
    .clinic-ai-form{display:flex;gap:8px;padding:10px;background:#fff;border-top:1px solid #e5e7eb}.clinic-ai-input{flex:1;resize:none;min-height:42px;max-height:90px;border:1px solid #d1d5db;border-radius:12px;padding:10px 11px;font:inherit;font-size:13px;outline:none}.clinic-ai-input:focus{border-color:#1a5d3a;box-shadow:0 0 0 3px rgba(26,93,58,.08)}.clinic-ai-send{width:42px;height:42px;border:0;border-radius:12px;background:#1a5d3a;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;flex:0 0 auto}.clinic-ai-send:disabled{opacity:.5;cursor:not-allowed}.clinic-ai-send svg{width:18px;height:18px}
    .clinic-ai-typing{display:inline-flex;gap:4px;align-items:center}.clinic-ai-typing span{width:6px;height:6px;border-radius:50%;background:#9ca3af;animation:clinicAiPulse 1.2s infinite}.clinic-ai-typing span:nth-child(2){animation-delay:.15s}.clinic-ai-typing span:nth-child(3){animation-delay:.3s}@keyframes clinicAiPulse{0%,70%,100%{opacity:.35;transform:translateY(0)}35%{opacity:1;transform:translateY(-2px)}}
    @media(max-width:520px){#clinicAiRoot{right:12px;bottom:12px}.clinic-ai-panel{position:fixed;right:10px;left:10px;bottom:82px;width:auto;height:min(620px,calc(100vh - 96px));border-radius:18px}.clinic-ai-toggle{width:54px;height:54px}}
</style>

<div id="clinicAiRoot">
    <div class="clinic-ai-panel" id="clinicAiPanel" aria-hidden="true">
        <div class="clinic-ai-header">
            <div class="clinic-ai-title-wrap">
                <div class="clinic-ai-avatar">MS</div>
                <div style="min-width:0">
                    <div class="clinic-ai-title">{{ $aiName }}</div>
                    <div class="clinic-ai-status" id="clinicAiStatus">AI lokal Klinik Mitra Sehat</div>
                </div>
            </div>
            <div class="clinic-ai-head-actions">
                <button type="button" class="clinic-ai-head-btn" id="clinicAiReset" title="Mulai percakapan baru" aria-label="Mulai percakapan baru">↻</button>
                <button type="button" class="clinic-ai-head-btn" id="clinicAiClose" title="Tutup" aria-label="Tutup">×</button>
            </div>
        </div>

        <div class="clinic-ai-messages" id="clinicAiMessages"></div>
        <div class="clinic-ai-suggestions" id="clinicAiSuggestions">
            <button type="button" class="clinic-ai-chip" data-message="Bagaimana cara daftar melalui Mobile JKN?">Pendaftaran JKN</button>
            <button type="button" class="clinic-ai-chip" data-message="Jadwal dokter hari ini bagaimana?">Jadwal dokter</button>
            <button type="button" class="clinic-ai-chip" data-message="Perut saya sakit di bagian atas, bisa bantu skrining awal?">Tanya keluhan</button>
        </div>
        <div class="clinic-ai-note">Asisten ini bukan pengganti dokter. Jangan kirim NIK, nomor BPJS, email, atau nomor telepon melalui chat.</div>
        <form class="clinic-ai-form" id="clinicAiForm">
            <textarea id="clinicAiInput" class="clinic-ai-input" rows="1" maxlength="1000" placeholder="Tulis pertanyaan tentang klinik atau kesehatan..." autocomplete="off"></textarea>
            <button type="submit" class="clinic-ai-send" id="clinicAiSend" aria-label="Kirim pesan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 2L11 13" stroke-width="2" stroke-linecap="round"/><path d="M22 2L15 22L11 13L2 9L22 2Z" stroke-width="2" stroke-linejoin="round"/></svg>
            </button>
        </form>
    </div>

    <button type="button" class="clinic-ai-toggle" id="clinicAiToggle" aria-label="Buka Asisten Klinik" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z" stroke-width="2" stroke-linejoin="round"/><path d="M8 9h8M8 13h5" stroke-width="2" stroke-linecap="round"/></svg>
    </button>
</div>

<script>
(function () {
    const panel = document.getElementById('clinicAiPanel');
    const toggle = document.getElementById('clinicAiToggle');
    const closeBtn = document.getElementById('clinicAiClose');
    const resetBtn = document.getElementById('clinicAiReset');
    const messages = document.getElementById('clinicAiMessages');
    const form = document.getElementById('clinicAiForm');
    const input = document.getElementById('clinicAiInput');
    const send = document.getElementById('clinicAiSend');
    const status = document.getElementById('clinicAiStatus');
    const suggestions = document.getElementById('clinicAiSuggestions');
    const endpoint = @json(route('ai.chat'));
    const csrf = @json(csrf_token());
    const welcome = @json($aiWelcome);
    const storageKey = 'mitra_sehat_ai_visitor_key';
    let busy = false;

    function visitorKey() {
        let key = localStorage.getItem(storageKey);
        if (!key) {
            key = (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : 'visitor-' + Date.now() + '-' + Math.random().toString(36).slice(2);
            localStorage.setItem(storageKey, key);
        }
        return key;
    }

    function scrollBottom() { messages.scrollTop = messages.scrollHeight; }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function inlineFormat(value) {
        let text = escapeHtml(value);
        text = text.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
        text = text.replace(/__([^_]+)__/g, '<strong>$1</strong>');
        return text;
    }

    // Renderer kecil dan aman untuk jawaban AI. HTML dari model selalu di-escape dulu.
    // Tujuannya supaya ###/**/--- dari model tidak tampil mentah di layar pasien.
    function renderAssistant(text) {
        const lines = String(text ?? '').replace(/\r\n/g, '\n').split('\n');
        const html = [];
        let paragraph = [];

        const flushParagraph = () => {
            if (!paragraph.length) return;
            html.push('<div class="ai-p">' + paragraph.map(inlineFormat).join('<br>') + '</div>');
            paragraph = [];
        };

        for (const raw of lines) {
            const line = raw.trimEnd();
            const trimmed = line.trim();

            if (!trimmed) {
                flushParagraph();
                continue;
            }

            if (/^---+$/.test(trimmed)) {
                flushParagraph();
                html.push('<hr>');
                continue;
            }

            const heading = trimmed.match(/^#{1,6}\s*(.+)$/);
            if (heading) {
                flushParagraph();
                html.push('<div class="ai-title">' + inlineFormat(heading[1]) + '</div>');
                continue;
            }

            const bullet = trimmed.match(/^[-*•]\s+(.+)$/);
            if (bullet) {
                flushParagraph();
                html.push('<div class="ai-list"><span class="ai-list-mark">•</span><span>' + inlineFormat(bullet[1]) + '</span></div>');
                continue;
            }

            const numbered = trimmed.match(/^(\d+)[.)]\s+(.+)$/);
            if (numbered) {
                flushParagraph();
                html.push('<div class="ai-list"><span class="ai-list-mark">' + numbered[1] + '.</span><span>' + inlineFormat(numbered[2]) + '</span></div>');
                continue;
            }

            paragraph.push(line);
        }

        flushParagraph();
        return html.join('');
    }

    function setAssistantText(bubble, text) {
        bubble.classList.add('clinic-ai-rich');
        bubble.innerHTML = renderAssistant(text);
    }

    function addMessage(role, text) {
        const row = document.createElement('div');
        row.className = 'clinic-ai-row ' + role;
        const bubble = document.createElement('div');
        bubble.className = 'clinic-ai-bubble';
        if (role === 'assistant') setAssistantText(bubble, text);
        else bubble.textContent = text;
        row.appendChild(bubble);
        messages.appendChild(row);
        scrollBottom();
        return bubble;
    }

    function addTyping() {
        const row = document.createElement('div');
        row.className = 'clinic-ai-row assistant';
        row.dataset.typing = '1';
        const bubble = document.createElement('div');
        bubble.className = 'clinic-ai-bubble';
        bubble.innerHTML = '<span class="clinic-ai-typing"><span></span><span></span><span></span></span>';
        row.appendChild(bubble);
        messages.appendChild(row);
        scrollBottom();
        return row;
    }

    function openPanel() {
        panel.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');
        toggle.setAttribute('aria-expanded', 'true');
        if (!messages.children.length) addMessage('assistant', welcome);
        setTimeout(() => input.focus(), 100);
    }

    function closePanel() {
        panel.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');
        toggle.setAttribute('aria-expanded', 'false');
    }

    function resetChat() {
        if (busy) return;
        localStorage.removeItem(storageKey);
        messages.innerHTML = '';
        addMessage('assistant', welcome);
        input.value = '';
        status.textContent = 'Percakapan baru';
    }

    async function sendMessage(text) {
        text = (text || '').trim();
        if (!text || busy) return;

        busy = true;
        input.disabled = true;
        send.disabled = true;
        suggestions.style.display = 'none';
        addMessage('user', text);
        input.value = '';
        status.textContent = 'Sedang menyiapkan jawaban...';
        const typingRow = addTyping();
        let assistantBubble = null;
        let buffer = '';
        let currentEvent = 'message';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'text/event-stream',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ message: text, visitor_key: visitorKey() })
            });

            if (!response.ok || !response.body) {
                throw new Error('HTTP ' + response.status);
            }

            const reader = response.body.getReader();
            const decoder = new TextDecoder('utf-8');

            while (true) {
                const { value, done } = await reader.read();
                if (done) break;
                buffer += decoder.decode(value, { stream: true });

                let boundary;
                while ((boundary = buffer.indexOf('\n\n')) !== -1) {
                    const block = buffer.slice(0, boundary);
                    buffer = buffer.slice(boundary + 2);
                    currentEvent = 'message';
                    let data = '';

                    block.split('\n').forEach(line => {
                        if (line.startsWith('event:')) currentEvent = line.slice(6).trim();
                        if (line.startsWith('data:')) data += line.slice(5).trim();
                    });

                    if (!data) continue;
                    let payload;
                    try { payload = JSON.parse(data); } catch (e) { continue; }

                    if (currentEvent === 'delta' && payload.content) {
                        if (!assistantBubble) {
                            typingRow.remove();
                            assistantBubble = addMessage('assistant', '');
                        }
                        assistantBubble.dataset.rawText = (assistantBubble.dataset.rawText || '') + payload.content;
                        setAssistantText(assistantBubble, assistantBubble.dataset.rawText);
                        scrollBottom();
                    }

                    if (currentEvent === 'done') status.textContent = 'Siap membantu';
                    if (currentEvent === 'error') status.textContent = 'AI sedang bermasalah';
                }
            }

            if (!assistantBubble) {
                typingRow.remove();
                addMessage('assistant', 'Mohon maaf, jawaban belum dapat ditampilkan. Silakan coba lagi.');
            }
        } catch (error) {
            typingRow.remove();
            addMessage('assistant', 'Mohon maaf, Asisten Klinik sedang tidak dapat dihubungi. Silakan coba beberapa saat lagi.');
            status.textContent = 'Tidak terhubung';
        } finally {
            busy = false;
            input.disabled = false;
            send.disabled = false;
            input.focus();
        }
    }

    toggle.addEventListener('click', () => panel.classList.contains('is-open') ? closePanel() : openPanel());
    closeBtn.addEventListener('click', closePanel);
    resetBtn.addEventListener('click', resetChat);
    form.addEventListener('submit', e => { e.preventDefault(); sendMessage(input.value); });
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); form.requestSubmit(); }
    });
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 90) + 'px';
    });
    document.querySelectorAll('.clinic-ai-chip').forEach(chip => chip.addEventListener('click', () => sendMessage(chip.dataset.message || '')));
})();
</script>
