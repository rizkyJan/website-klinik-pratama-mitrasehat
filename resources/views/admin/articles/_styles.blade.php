<style>
    .kms-article-page{color:#102a1d}
    .kms-article-head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:22px}
    .kms-article-eyebrow{margin:0 0 5px;color:#1a5d3a;font-size:11px;font-weight:800;letter-spacing:.09em;text-transform:uppercase}
    .kms-article-title{margin:0;color:#102a1d;font-size:28px;line-height:1.1;font-weight:800;letter-spacing:-.02em}
    .kms-article-subtitle{margin:8px 0 0;color:#6b7280;font-size:13px;line-height:1.6}
    .kms-article-add,.kms-btn{min-height:42px;display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:0 16px;border:0;border-radius:12px;text-decoration:none;font-size:13px;font-weight:800;cursor:pointer}
    .kms-article-add,.kms-btn-primary{background:#1a5d3a;color:#fff;box-shadow:0 8px 18px rgba(26,93,58,.13)}
    .kms-article-add:hover,.kms-btn-primary:hover{background:#154a2e}
    .kms-article-add svg{width:16px;height:16px}
    .kms-btn-secondary{background:#fff;color:#33443a;border:1px solid #dce4de}
    .kms-btn-danger{background:#fff3f2;color:#c92d22;border:1px solid #ffd8d3}

    .kms-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:18px}
    .kms-stat{padding:16px 18px;border:1px solid #e6ece7;border-radius:16px;background:#fff;box-shadow:0 4px 12px rgba(31,71,48,.035)}
    .kms-stat small{display:block;color:#7b867f;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em}
    .kms-stat strong{display:block;margin-top:5px;color:#173e2a;font-size:23px;line-height:1}

    .kms-table-card{overflow:hidden;border:1px solid #e6ece7;border-radius:18px;background:#fff;box-shadow:0 8px 24px rgba(29,61,42,.05)}
    .kms-table-wrap{overflow-x:auto}
    .kms-table{width:100%;border-collapse:collapse;min-width:860px}
    .kms-table th{padding:14px 16px;background:#f8faf8;color:#667269;font-size:11px;text-align:left;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #edf1ee}
    .kms-table td{padding:13px 16px;color:#435047;font-size:13px;border-bottom:1px solid #f0f3f1;vertical-align:middle}
    .kms-table tr:last-child td{border-bottom:0}
    .kms-thumb{width:70px;height:52px;border-radius:10px;overflow:hidden;background:linear-gradient(135deg,#edf7ef,#f8fbf8);border:1px solid #e0ebe3;display:flex;align-items:center;justify-content:center;color:#81a68f}
    .kms-thumb img{width:100%;height:100%;object-fit:cover;display:block}
    .kms-thumb svg{width:22px;height:22px}
    .kms-row-title{margin:0;color:#173e2a;font-size:13px;font-weight:800}
    .kms-row-slug{margin:3px 0 0;color:#96a09a;font-size:10px}
    .kms-chip{display:inline-flex;align-items:center;padding:5px 9px;border-radius:999px;background:#edf7ee;color:#1a5d3a;font-size:10px;font-weight:800}
    .kms-content-preview{max-width:360px;color:#707b74;line-height:1.5}
    .kms-actions-inline{display:flex;justify-content:flex-end;gap:8px;white-space:nowrap}
    .kms-link{display:inline-flex;align-items:center;justify-content:center;padding:7px 10px;border-radius:9px;font-size:11px;font-weight:800;text-decoration:none;border:0;cursor:pointer}
    .kms-link-edit{background:#eef8f1;color:#17623d}
    .kms-link-view{background:#f1f5ff;color:#3456a3}
    .kms-link-delete{background:#fff2f1;color:#c73027}

    .kms-form-grid{display:grid;grid-template-columns:minmax(0,1fr) 330px;gap:20px;align-items:start}
    .kms-card{padding:22px;border:1px solid #e6ece7;border-radius:18px;background:#fff;box-shadow:0 8px 22px rgba(31,71,48,.05)}
    .kms-card h2{margin:0;color:#102a1d;font-size:16px;font-weight:800}
    .kms-card-note{margin:6px 0 0;color:#7c8780;font-size:11px;line-height:1.55}
    .kms-field{margin-top:18px}
    .kms-label{display:block;margin-bottom:7px;color:#263f31;font-size:12px;font-weight:800}
    .kms-required{color:#e14a3b}
    .kms-input,.kms-textarea{width:100%;box-sizing:border-box;border:1px solid #dce4df;border-radius:12px;background:#fff;color:#25372d;font:inherit;font-size:13px;outline:none;transition:border-color .2s,box-shadow .2s}
    .kms-input{min-height:44px;padding:0 13px}
    .kms-textarea{min-height:250px;padding:13px;resize:vertical;line-height:1.7}
    .kms-input:focus,.kms-textarea:focus{border-color:#65a47e;box-shadow:0 0 0 3px rgba(31,111,67,.08)}
    .kms-help{margin:6px 0 0;color:#97a09a;font-size:10px;line-height:1.45}
    .kms-error{margin:6px 0 0;color:#d9342b;font-size:10px;font-weight:700}
    .kms-alert{margin-bottom:18px;padding:13px 15px;border-radius:12px;font-size:12px;line-height:1.55}
    .kms-alert-success{background:#edf9f0;border:1px solid #bfe7c9;color:#1d7444}
    .kms-alert-error{background:#fff1f0;border:1px solid #ffc9c5;color:#c73027}
    .kms-alert-error ul{margin:6px 0 0;padding-left:18px}

    .kms-photo-box{aspect-ratio:16/10;height:auto;margin-top:16px;border:1px dashed #cfdcd3;border-radius:16px;background:linear-gradient(135deg,#f6faf7,#edf7ef);overflow:hidden;display:flex;align-items:center;justify-content:center;color:#89a793}
    .kms-photo-box img{width:100%;height:100%;object-fit:cover;display:block}
    .kms-photo-placeholder{text-align:center;font-size:11px;font-weight:700}
    .kms-photo-placeholder svg{width:34px;height:34px;display:block;margin:0 auto 8px}
    .kms-file-btn{width:100%;box-sizing:border-box;margin-top:12px;min-height:42px;display:flex;align-items:center;justify-content:center;border:1px solid #bfe7ca;border-radius:12px;background:#edfaf1;color:#17653e;font-size:12px;font-weight:800;cursor:pointer}
    .kms-file-name{margin:8px 0 0;text-align:center;color:#98a19b;font-size:10px;word-break:break-all}
    .kms-preview-card{margin-top:16px;border:1px solid #e4ebe6;border-radius:16px;overflow:hidden;background:#fff}
    .kms-preview-image{height:135px;background:linear-gradient(135deg,#edf8ef,#f8fbf8);display:flex;align-items:center;justify-content:center;color:#85a98f;overflow:hidden}
    .kms-preview-image img{width:100%;height:100%;object-fit:cover;display:block}
    .kms-preview-content{padding:15px}
    .kms-preview-category{display:inline-flex;padding:5px 8px;border-radius:999px;background:#edf7ee;color:#1a5d3a;font-size:9px;font-weight:800}
    .kms-preview-title{margin:9px 0 0;color:#173e2a;font-size:15px;font-weight:800;line-height:1.35}
    .kms-preview-text{margin:7px 0 0;color:#78837c;font-size:10.5px;line-height:1.55}
    .kms-form-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:18px}

    @media(max-width:980px){.kms-form-grid{grid-template-columns:1fr}.kms-stats{grid-template-columns:1fr 1fr}.kms-aside{display:grid;grid-template-columns:1fr 1fr;gap:16px}.kms-aside .kms-card{height:100%}}
    @media(max-width:700px){.kms-article-head{align-items:stretch;flex-direction:column}.kms-article-add{width:100%;box-sizing:border-box}.kms-stats{grid-template-columns:1fr}.kms-aside{display:block}.kms-aside .kms-card+.kms-card{margin-top:16px}.kms-form-actions{flex-direction:column-reverse}.kms-form-actions .kms-btn{width:100%;box-sizing:border-box}.kms-card{padding:17px}.kms-article-title{font-size:24px}}
</style>
