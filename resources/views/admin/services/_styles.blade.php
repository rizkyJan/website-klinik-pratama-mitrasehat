<style>
    .kms-form-page { color:#102a1d; }
    .kms-back { display:inline-flex; align-items:center; gap:6px; margin-bottom:16px; color:#1a5d3a; font-size:12px; font-weight:800; text-decoration:none; }
    .kms-back:hover { text-decoration:underline; }
    .kms-form-head { display:flex; align-items:flex-end; justify-content:space-between; gap:18px; margin-bottom:20px; }
    .kms-eyebrow { margin:0 0 5px; color:#1a5d3a; font-size:11px; font-weight:800; letter-spacing:.09em; text-transform:uppercase; }
    .kms-title { margin:0; color:#102a1d; font-size:28px; line-height:1.1; font-weight:800; letter-spacing:-.02em; }
    .kms-subtitle { margin:8px 0 0; color:#6b7280; font-size:13px; line-height:1.6; }
    .kms-live-status { display:inline-flex; align-items:center; gap:7px; padding:7px 10px; border-radius:999px; background:#eaf8ee; color:#176d3a; font-size:10px; font-weight:800; white-space:nowrap; }
    .kms-live-status.off { background:#f3f4f6; color:#6b7280; }
    .kms-live-status::before { content:''; width:6px; height:6px; border-radius:50%; background:currentColor; }

    .kms-errors { margin-bottom:18px; padding:14px 16px; border:1px solid #fecaca; border-radius:14px; background:#fff1f2; color:#b42318; font-size:12px; }
    .kms-errors strong { display:block; margin-bottom:5px; }
    .kms-errors ul { margin:0; padding-left:18px; }

    .kms-form-grid { display:grid; grid-template-columns:minmax(0,1.55fr) minmax(300px,.75fr); gap:18px; align-items:start; }
    .kms-card { padding:22px; border:1px solid #e4ebe5; border-radius:18px; background:#fff; box-shadow:0 7px 20px rgba(31,71,48,.045); }
    .kms-card + .kms-card { margin-top:16px; }
    .kms-card h2 { margin:0; color:#17251d; font-size:16px; font-weight:800; }
    .kms-card-note { margin:6px 0 18px; color:#7a837d; font-size:11px; line-height:1.5; }
    .kms-field { margin-bottom:16px; }
    .kms-field:last-child { margin-bottom:0; }
    .kms-label { display:block; margin-bottom:7px; color:#2c3d33; font-size:11px; font-weight:800; }
    .kms-required { color:#dc2626; }
    .kms-input, .kms-textarea { width:100%; box-sizing:border-box; border:1px solid #d6ddd8; border-radius:11px; background:#fff; color:#1f2937; font:inherit; font-size:12px; outline:none; transition:border-color .15s, box-shadow .15s; }
    .kms-input { height:43px; padding:0 12px; }
    .kms-textarea { min-height:105px; padding:11px 12px; resize:vertical; line-height:1.6; }
    .kms-textarea.detail { min-height:170px; }
    .kms-input:focus, .kms-textarea:focus { border-color:#4d916b; box-shadow:0 0 0 3px rgba(26,93,58,.08); }
    .kms-help { margin:6px 0 0; color:#98a19a; font-size:9.5px; line-height:1.45; }
    .kms-error { margin:6px 0 0; color:#dc2626; font-size:10px; }
    .kms-counter { float:right; color:#a0a8a2; font-weight:500; }

    .kms-preview { position:sticky; top:18px; }
    .kms-preview-card { position:relative; overflow:hidden; min-height:185px; display:flex; flex-direction:column; padding:20px; border-radius:20px; border:1px solid #e0e9e1; background:#fff; box-shadow:0 8px 22px rgba(32,79,54,.06); }
    .kms-preview-card::after { content:''; position:absolute; width:100px; height:100px; right:-38px; bottom:-38px; border-radius:50%; background:#f0f8f0; }
    .kms-preview-top { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; }
    .kms-preview-icon { width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:15px; background:#eaf7ec; color:#1a5d3a; }
    .kms-preview-icon svg { width:24px; height:24px; }
    .kms-preview-badge { padding:6px 9px; border-radius:999px; background:#f5f8f5; border:1px solid #e7ece8; color:#7a847d; font-size:9px; font-weight:800; }
    .kms-preview-name { position:relative; z-index:1; margin:15px 0 0; color:#145f3a; font-size:18px; line-height:1.25; font-weight:800; }
    .kms-preview-desc { position:relative; z-index:1; margin:7px 0 0; color:#6a746d; font-size:11.5px; line-height:1.55; }
    .kms-preview-footer { position:relative; z-index:1; margin-top:auto; padding-top:16px; color:#1a5d3a; font-size:10px; font-weight:800; }

    .kms-two { display:grid; grid-template-columns:1fr 1.25fr; gap:12px; align-items:end; }
    .kms-toggle-row { min-height:43px; box-sizing:border-box; display:flex; align-items:center; justify-content:space-between; gap:14px; padding:8px 10px 8px 12px; border:1px solid #dfe5e0; border-radius:11px; background:#fbfcfb; }
    .kms-toggle-title { margin:0; color:#27382e; font-size:10.5px; font-weight:800; }
    .kms-toggle-note { margin:2px 0 0; color:#9aa29c; font-size:8.5px; }
    .kms-switch { position:relative; display:inline-block; width:39px; height:22px; flex:none; }
    .kms-switch input { opacity:0; width:0; height:0; }
    .kms-switch-ui { position:absolute; inset:0; border-radius:999px; background:#cfd6d1; cursor:pointer; transition:.2s; }
    .kms-switch-ui::before { content:''; position:absolute; width:16px; height:16px; left:3px; top:3px; border-radius:50%; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.17); transition:.2s; }
    .kms-switch input:checked + .kms-switch-ui { background:#1a5d3a; }
    .kms-switch input:checked + .kms-switch-ui::before { transform:translateX(17px); }

    .kms-icon-card { margin-top:18px; }
    .kms-icon-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; }
    .kms-icon-choice { position:relative; display:block; cursor:pointer; }
    .kms-icon-choice input { position:absolute; opacity:0; pointer-events:none; }
    .kms-icon-option { min-height:82px; box-sizing:border-box; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; padding:10px; border:1px solid #dfe6e0; border-radius:13px; background:#fbfcfb; color:#617066; text-align:center; transition:.15s; }
    .kms-icon-option:hover { border-color:#a9c9b3; background:#f6fbf7; }
    .kms-icon-choice input:checked + .kms-icon-option { border-color:#1a5d3a; background:#eaf7ec; color:#155c37; box-shadow:0 0 0 2px rgba(26,93,58,.08); }
    .kms-icon-shape { width:29px; height:29px; display:flex; align-items:center; justify-content:center; }
    .kms-icon-shape svg { width:25px; height:25px; }
    .kms-icon-name { font-size:9.5px; font-weight:800; line-height:1.2; }

    .kms-actions { display:flex; justify-content:flex-end; gap:9px; margin-top:18px; }
    .kms-btn { min-height:40px; display:inline-flex; align-items:center; justify-content:center; padding:0 15px; border-radius:11px; border:1px solid transparent; font-size:11px; font-weight:800; text-decoration:none; cursor:pointer; }
    .kms-btn-secondary { color:#39483f; background:#fff; border-color:#d8ded9; }
    .kms-btn-primary { color:#fff; background:#1a5d3a; border-color:#1a5d3a; }
    .kms-btn-primary:hover { background:#154a2e; }

    @media (max-width: 950px) {
        .kms-form-grid { grid-template-columns:1fr; }
        .kms-preview { position:static; }
        .kms-icon-grid { grid-template-columns:repeat(3,minmax(0,1fr)); }
    }
    @media (max-width: 650px) {
        .kms-form-head { align-items:flex-start; flex-direction:column; }
        .kms-title { font-size:24px; }
        .kms-card { padding:17px; border-radius:15px; }
        .kms-two { grid-template-columns:1fr; }
        .kms-icon-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .kms-actions { flex-direction:column-reverse; }
        .kms-btn { width:100%; box-sizing:border-box; }
    }
</style>
