<style>
/* Glassmorphism theme. Indigo / violet / teal base over a soft aurora backdrop.
   Layout sizing and widget behavior stay in the base theme. */
:root {
    --md-primary: #4257c9;
    --md-primary-hover: #3345a8;
    --md-primary-soft: rgba(66, 87, 201, .12);
    --md-accent: #7a5af0;
    --md-teal: #2fa9b0;
    --md-text: #1f2a44;
    --md-muted: #5f6c87;
    --md-canvas: #e9eefb;
    --md-radius: 18px;
    --md-gradient: linear-gradient(125deg, #3f5bd0 0%, #7a5af0 100%);

    /* Glass tokens */
    --glass-bg: rgba(255, 255, 255, .58);
    --glass-bg-strong: rgba(255, 255, 255, .78);
    --glass-bg-soft: rgba(255, 255, 255, .34);
    --glass-border: rgba(255, 255, 255, .72);
    --glass-outline: rgba(96, 112, 170, .2);
    --glass-blur: blur(18px) saturate(165%);
    --glass-shadow: 0 8px 32px rgba(38, 52, 110, .12), inset 0 1px 0 rgba(255, 255, 255, .7);
    --glass-dark: rgba(28, 42, 92, .68);

    --md-surface: var(--glass-bg);
    --md-outline: var(--glass-outline);
    --md-shadow: var(--glass-shadow);
    --bs-primary: #4257c9;
    --bs-primary-rgb: 66, 87, 201;
    --bs-body-color: var(--md-text);
    --bs-body-bg: var(--md-canvas);
    --bs-border-color: var(--glass-outline);
}
body {
    background: var(--md-canvas);
    color: var(--md-text);
    font-family: "Inter", "Segoe UI", "Noto Sans Bengali", sans-serif;
    line-height: 1.55;
    -webkit-font-smoothing: antialiased;
}
/* Aurora backdrop that the glass surfaces blur. */
body:not(.authentication-bg)::before {
    content: "";
    position: fixed;
    inset: 0;
    z-index: -1;
    pointer-events: none;
    background:
        radial-gradient(600px 460px at 8% 12%, rgba(122, 90, 240, .32), transparent 70%),
        radial-gradient(640px 520px at 92% 8%, rgba(47, 169, 176, .28), transparent 70%),
        radial-gradient(700px 560px at 78% 96%, rgba(66, 87, 201, .30), transparent 70%),
        radial-gradient(520px 420px at 6% 92%, rgba(236, 130, 200, .20), transparent 70%),
        linear-gradient(135deg, #e6ecfb 0%, #efeafb 50%, #e3f3f6 100%);
}
#layout-wrapper, .main-content, .page-content { background: transparent; }
a { color: var(--md-primary); }
a:hover { color: var(--md-primary-hover); }
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 { color: var(--md-text); }
.text-muted { color: var(--md-muted) !important; }
::selection { background: rgba(122, 90, 240, .28); color: #1d2450; }

/* Reusable glass recipe. */
.card, .shadow-card, .modal-content, .dropdown-menu, .select2-dropdown, .account-pages .card {
    -webkit-backdrop-filter: var(--glass-blur);
    backdrop-filter: var(--glass-blur);
}

/* App shell: frosted dark-indigo topbar and sidebar. */
#page-topbar {
    background: var(--glass-dark);
    -webkit-backdrop-filter: blur(20px) saturate(170%);
    backdrop-filter: blur(20px) saturate(170%);
    border-bottom: 1px solid rgba(255, 255, 255, .16);
    box-shadow: 0 6px 28px rgba(28, 42, 92, .18);
}
#page-topbar::after {
    content: "";
    position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(214, 226, 255, .7), transparent);
    pointer-events: none;
}
#page-topbar .header-item,
#page-topbar .header-item > i { color: #f2f5ff; }
#page-topbar .header-item:hover,
#page-topbar .header-item:focus-visible,
#page-topbar .header-item[aria-expanded="true"] { color: #fff; background: rgba(255, 255, 255, .16); }
#page-topbar .header-item:focus-visible { outline-color: #c9dbff; outline-offset: -4px; }
#page-topbar .header-profile-user { border: 2px solid rgba(255, 255, 255, .7); box-shadow: 0 0 0 3px rgba(255, 255, 255, .1); }
#page-topbar .dropdown-menu { color: var(--md-text); }

.vertical-menu {
    background: linear-gradient(165deg, rgba(28, 42, 92, .78) 0%, rgba(52, 62, 126, .72) 55%, rgba(80, 62, 130, .70) 100%);
    -webkit-backdrop-filter: blur(22px) saturate(170%);
    backdrop-filter: blur(22px) saturate(170%);
    border-right: 1px solid rgba(255, 255, 255, .14);
    box-shadow: 6px 0 32px rgba(28, 42, 92, .16);
}
.vertical-menu .navbar-brand-box { background: rgba(255, 255, 255, .06); border-bottom: 1px solid rgba(255, 255, 255, .14); display: flex; align-items: center; }
.vertical-menu .navbar-brand-box img {
    background: #fff; border-radius: 10px; padding: 6px 10px; object-fit: contain;
    box-shadow: 0 4px 14px rgba(15, 23, 42, .28), 0 0 0 1px rgba(255, 255, 255, .08);
}
body[data-sidebar-size="sm"] .vertical-menu .navbar-brand-box img { padding: 5px; border-radius: 10px; }
.vertical-menu .vertical-menu-btn { color: #edf2ff; }
#sidebar-menu { padding: 16px 10px; }
#sidebar-menu ul li a {
    color: #cad4f5; border-radius: 12px; margin: 3px 0; padding-left: 14px; position: relative;
    transition: background-color .18s ease, color .18s ease, box-shadow .18s ease, padding-left .18s ease;
}
#sidebar-menu ul li a::before {
    content: ""; position: absolute; left: 0; top: 12%; bottom: 12%; width: 3px; border-radius: 3px;
    background: linear-gradient(180deg, #7ee0ff, #7a5af0); opacity: 0; transform: scaleY(.4); transition: opacity .18s ease, transform .18s ease;
}
#sidebar-menu ul li a i { color: #c2cff2; transition: transform .2s ease, color .2s ease; width: 20px; text-align: center; }
#sidebar-menu .menu-title { color: #bdcbed; letter-spacing: .08em; font-size: 11px; }
#sidebar-menu ul li a:hover,
#sidebar-menu ul li a:focus-visible {
    background: linear-gradient(90deg, rgba(126, 224, 255, .14), rgba(122, 90, 240, .07) 70%, transparent);
    color: #fff !important; box-shadow: none; padding-left: 18px;
}
#sidebar-menu ul li a:hover::before,
#sidebar-menu ul li a:focus-visible::before { opacity: .55; transform: scaleY(.7); }
#sidebar-menu ul li a:hover i { transform: translateY(-1px); color: #7ee0ff; }
#sidebar-menu ul li.mm-active > a,
#sidebar-menu ul li a.active {
    background: linear-gradient(90deg, rgba(126, 224, 255, .22), rgba(122, 90, 240, .12) 70%, transparent);
    color: #fff !important;
    font-weight: 600;
    box-shadow: none;
    padding-left: 18px;
}
#sidebar-menu ul li.mm-active > a::before,
#sidebar-menu ul li a.active::before { opacity: 1; transform: scaleY(1); }
#sidebar-menu ul li.mm-active > a i,
#sidebar-menu ul li a.active i,
#sidebar-menu ul li a:hover i,
#sidebar-menu ul li a:focus-visible i,
#sidebar-menu ul li a:hover .has-arrow::after,
#sidebar-menu ul li a:focus-visible .has-arrow::after,
#sidebar-menu ul li.mm-active > a.has-arrow::after { color: #7ee0ff !important; }
#sidebar-menu a:focus-visible { outline-color: #c9dbff; outline-offset: -3px; }
.vertical-menu .simplebar-scrollbar::before { background: #c2cff2; }
body[data-sidebar-size="sm"] #sidebar-menu > ul > li:hover > a,
body[data-sidebar-size="sm"] #sidebar-menu > ul > li:hover > ul {
    background: rgba(40, 54, 112, .92);
    -webkit-backdrop-filter: var(--glass-blur);
    backdrop-filter: var(--glass-blur);
    color: #fff;
}
/* Nested menu labels stay legible on the dark glass. */
#sidebar-menu ul li ul.sub-menu li > a { color: #f5f7ff !important; opacity: 1; }
#sidebar-menu ul li ul.sub-menu li > a:hover,
#sidebar-menu ul li ul.sub-menu li > a:focus-visible,
#sidebar-menu ul li ul.sub-menu li.mm-active > a,
#sidebar-menu ul li ul.sub-menu li > a.active { color: #fff !important; background: linear-gradient(90deg, rgba(126, 224, 255, .18), transparent 75%); }
#sidebar-menu ul.sub-menu a i,
#sidebar-menu ul.sub-menu .has-arrow::after { color: #f5f7ff !important; opacity: 1; }
#sidebar-menu ul.sub-menu li:hover > a i,
#sidebar-menu ul.sub-menu li.mm-active > a i { color: #7ee0ff !important; }

.footer { background: transparent; color: var(--md-muted); border-top: 1px solid var(--glass-outline); }
.bread-cum { margin: 8px 0 22px; }
.page-title, .page-title-box h4 { font-weight: 700; letter-spacing: -.025em; color: var(--md-text); }
.breadcrumb, .breadcrumb-item.active { color: var(--md-muted); }

/* Glass cards, forms and dialogs. */
.card, .shadow-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--md-radius);
    box-shadow: var(--glass-shadow);
}
.card.rounded, .card.rounded-0 { border-radius: var(--md-radius) !important; }
.card-border { border-top: 3px solid var(--md-accent); }
.card-header {
    background: linear-gradient(110deg, rgba(255, 255, 255, .5), rgba(255, 255, 255, .18));
    border-bottom: 1px solid rgba(255, 255, 255, .6);
    padding: 18px 24px;
}
.card-header:first-child { border-radius: var(--md-radius) var(--md-radius) 0 0; }
.card-footer { background: var(--glass-bg-soft); border-top: 1px solid rgba(255, 255, 255, .6); }
.page-fragment-bar {
    background: linear-gradient(110deg, rgba(66, 87, 201, .16), rgba(122, 90, 240, .12));
    color: #2f3f94;
    padding: 14px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, .6);
    border-radius: 14px 14px 0 0;
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
}
.page-fragment-bar * { font-size: 14px; font-weight: 600; }
.modal-content {
    background: var(--glass-bg-strong);
    border: 1px solid var(--glass-border);
    border-radius: 22px;
    box-shadow: 0 28px 80px rgba(28, 40, 100, .28), inset 0 1px 0 rgba(255, 255, 255, .8);
}
.modal-backdrop.show { opacity: .42; background: #1c2a5c; }
.modal-header, .modal-footer { border-color: rgba(96, 112, 170, .14); padding: 18px 24px; }
.modal-body { padding: 24px; }
.modal-title { font-weight: 650; }
.dropdown-menu {
    background: var(--glass-bg-strong);
    border: 1px solid var(--glass-border);
    border-radius: 14px;
    padding: 7px;
    box-shadow: 0 16px 44px rgba(28, 40, 100, .2);
}
.dropdown-item { border-radius: 8px; padding: 8px 12px; color: var(--md-text); }
.dropdown-item:hover, .dropdown-item:focus { background: var(--md-primary-soft); color: var(--md-primary); }
.dropdown-item.active, .dropdown-item:active { background: var(--md-gradient); color: #fff; }

/* Controls: frosted fields with a clear focus ring. */
.form-label, label { color: #3d4a66; font-weight: 500; }
.form-control, .form-select, .custom-select {
    background-color: rgba(255, 255, 255, .55);
    color: var(--md-text);
    border: 1px solid rgba(96, 112, 170, .28);
    border-radius: 11px;
    padding: .65rem .85rem;
    box-shadow: inset 0 1px 2px rgba(38, 52, 110, .04);
    transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
}
.form-control::placeholder { color: #7d89a3; opacity: 1; }
.form-control:hover:not(:disabled), .form-select:hover:not(:disabled) { border-color: rgba(66, 87, 201, .5); background-color: rgba(255, 255, 255, .7); }
.form-control:focus, .form-select:focus, .custom-select:focus {
    border-color: var(--md-primary);
    box-shadow: 0 0 0 4px rgba(66, 87, 201, .16);
    background-color: rgba(255, 255, 255, .88);
    color: var(--md-text);
}
.form-control.is-invalid, .form-select.is-invalid { border-color: #c23f4e; }
.form-control.is-valid, .form-select.is-valid { border-color: #258366; }
.form-control[readonly], .form-control[disabled], .form-select:disabled { background: rgba(200, 208, 228, .4) !important; color: var(--md-muted); }
.form-control-sm, .form-select-sm { padding: .4rem .65rem; }
.input-group-text { background: rgba(255, 255, 255, .4); color: var(--md-muted); border-color: rgba(96, 112, 170, .28); border-radius: 11px; }
.input-group > .form-control:not(:last-child), .input-group > .input-group-text:not(:last-child) { border-top-right-radius: 0; border-bottom-right-radius: 0; }
.input-group > .form-control:not(:first-child), .input-group > .input-group-text:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
.form-check-input { background-color: rgba(255, 255, 255, .7); border-color: #8f9dbb; cursor: pointer; }
.form-check-input:checked { background-color: var(--md-primary); border-color: var(--md-primary); }
.form-check-input:focus { border-color: var(--md-primary); box-shadow: 0 0 0 3px rgba(66, 87, 201, .18); }
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple {
    background: rgba(255, 255, 255, .55);
    border: 1px solid rgba(96, 112, 170, .28);
    border-radius: 11px;
    min-height: 42px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 40px; color: var(--md-text); }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; }
.select2-dropdown { background: var(--glass-bg-strong); border: 1px solid var(--glass-border); border-radius: 12px; box-shadow: 0 16px 44px rgba(28, 40, 100, .2); overflow: hidden; }
.select2-container--default .select2-results__option--highlighted[aria-selected] { background: var(--md-primary); }

.btn { border-radius: 11px; font-weight: 600; padding: .6rem 1rem; transition: background-color .18s ease, box-shadow .18s ease, transform .18s ease; }
.btn-sm { padding: .35rem .7rem; border-radius: 8px; }
.btn-lg { padding: .8rem 1.4rem; border-radius: 14px; }
.btn-primary {
    background: var(--md-gradient);
    border-color: rgba(255, 255, 255, .28);
    box-shadow: 0 6px 18px rgba(66, 87, 201, .3), inset 0 1px 0 rgba(255, 255, 255, .3);
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active {
    background: linear-gradient(125deg, #3345a8 0%, #6a48df 100%) !important;
    border-color: rgba(255, 255, 255, .28) !important;
    box-shadow: 0 8px 22px rgba(74, 70, 190, .36);
}
.btn-outline-primary { color: var(--md-primary); border-color: rgba(66, 87, 201, .45); background: rgba(255, 255, 255, .3); }
.btn-outline-primary:hover, .btn-outline-primary.active { background: var(--md-gradient); border-color: transparent; color: #fff; }
/* every solid coloured button has white text (bootstrap keeps warning and info dark), the fills are darkened a little so white stays readable */
.btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-secondary, .btn-dark,
.btn-primary:hover, .btn-success:hover, .btn-danger:hover, .btn-warning:hover, .btn-info:hover, .btn-secondary:hover, .btn-dark:hover,
.btn-primary:focus, .btn-success:focus, .btn-danger:focus, .btn-warning:focus, .btn-info:focus, .btn-secondary:focus, .btn-dark:focus,
.btn-primary:active, .btn-success:active, .btn-danger:active, .btn-warning:active, .btn-info:active, .btn-secondary:active, .btn-dark:active,
.btn-primary:disabled, .btn-success:disabled, .btn-danger:disabled, .btn-warning:disabled, .btn-info:disabled, .btn-secondary:disabled { color: #fff; }
.btn-warning { --bs-btn-bg: #e08a00; --bs-btn-border-color: #e08a00; --bs-btn-hover-bg: #c67800; --bs-btn-hover-border-color: #c67800; --bs-btn-active-bg: #b56d00; --bs-btn-active-border-color: #b56d00; --bs-btn-disabled-bg: #e08a00; --bs-btn-disabled-border-color: #e08a00; background-color: #e08a00; border-color: #e08a00; }
.btn-warning:hover, .btn-warning:focus, .btn-warning:active { background-color: #c67800; border-color: #c67800; }
.btn-info { --bs-btn-bg: #0e8fb0; --bs-btn-border-color: #0e8fb0; --bs-btn-hover-bg: #0b7896; --bs-btn-hover-border-color: #0b7896; --bs-btn-active-bg: #0a6a85; --bs-btn-active-border-color: #0a6a85; --bs-btn-disabled-bg: #0e8fb0; --bs-btn-disabled-border-color: #0e8fb0; background-color: #0e8fb0; border-color: #0e8fb0; }
.btn-info:hover, .btn-info:focus, .btn-info:active { background-color: #0b7896; border-color: #0b7896; }
/* outline buttons fill in on hover, their text must turn white too */
.btn-outline-secondary:hover, .btn-outline-success:hover, .btn-outline-danger:hover, .btn-outline-warning:hover, .btn-outline-info:hover, .btn-outline-dark:hover,
.btn-outline-secondary.active, .btn-outline-success.active, .btn-outline-danger.active, .btn-outline-warning.active, .btn-outline-info.active { color: #fff; }
.btn-light { background: rgba(255, 255, 255, .55); border-color: rgba(255, 255, 255, .8); color: #3d4a66; }
.btn-light:hover { background: rgba(255, 255, 255, .85); }
.btn:disabled, .btn.disabled { box-shadow: none; }
a:focus-visible, button:focus-visible, .btn:focus-visible, [tabindex]:focus-visible { outline: 3px solid #8ea3ee; outline-offset: 3px; }

/* Tables stay horizontally scrollable; rows are see-through so the card glass shows. */
table.dataTable { border-radius: 0; overflow: visible; box-shadow: none; background: transparent; }
.table { --bs-table-color: var(--md-text); --bs-table-bg: transparent; --bs-table-hover-bg: rgba(66, 87, 201, .08); color: var(--md-text); }
.table > :not(caption) > * > * { color: var(--md-text); border-bottom-color: rgba(96, 112, 170, .14); padding: 13px 14px; vertical-align: middle; background-color: transparent; }
table.dataTable thead th, .table thead th {
    background: rgba(66, 87, 201, .1);
    color: #43537a;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .035em;
    padding: 14px;
    border-bottom: 1px solid rgba(96, 112, 170, .22) !important;
}
table.dataTable tbody tr.odd td, table.dataTable tbody tr.even td { background: transparent; color: var(--md-text); border-bottom: 1px solid rgba(96, 112, 170, .14) !important; }
table.dataTable tbody tr:hover td { background: rgba(255, 255, 255, .5) !important; }
table.dataTable tbody tr.selected td { background: rgba(66, 87, 201, .16) !important; }
.dataTables_wrapper .dataTables_filter input, .dataTables_wrapper .dataTables_length select {
    border: 1px solid rgba(96, 112, 170, .28); border-radius: 9px; background: rgba(255, 255, 255, .55); padding: 7px 10px; color: var(--md-text);
}
.dataTables_wrapper .dataTables_info { color: var(--md-muted); font-size: 12px; }
.page-link { color: var(--md-primary); background: rgba(255, 255, 255, .5); border-color: rgba(255, 255, 255, .8); padding: .5rem .8rem; }
.page-item.active .page-link { background: var(--md-gradient); border-color: transparent; box-shadow: 0 4px 12px rgba(66, 87, 201, .3); }
.page-link:hover { background: var(--md-primary-soft); color: var(--md-primary); }
.nav-tabs { border-color: var(--glass-outline); gap: 4px; }
.nav-tabs .nav-link { color: var(--md-muted); border-radius: 10px 10px 0 0; }
.nav-tabs .nav-link.active { color: var(--md-primary); background: var(--glass-bg); border-color: transparent transparent var(--md-primary); }
.nav-pills .nav-link.active { background: var(--md-gradient); }
.p-link.p-link-active { color: var(--md-primary) !important; border-top-color: var(--md-primary); }
.alert { border-radius: 14px; border: 1px solid var(--glass-border); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); }
.badge { font-weight: 600; letter-spacing: .01em; padding: .4em .65em; }
.progress { border-radius: 20px; background: rgba(255, 255, 255, .5); }
.account-pages .card { border-radius: 24px !important; box-shadow: 0 20px 60px rgba(28, 40, 100, .18), inset 0 1px 0 rgba(255, 255, 255, .8); }
.account-pages .logo { max-width: 100%; height: auto; }

/* Auth screens: glass form panel beside the brand panel. */
.auth-split { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); min-height: 100vh; min-height: 100dvh; background: #e9eefb; }
.auth-brand-panel { position: relative; isolation: isolate; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: clamp(32px, 5vw, 80px); background: linear-gradient(145deg, #233d89 0%, #5141a0 58%, #8460be 100%); color: white; }
.auth-recovery .auth-brand-panel { background: linear-gradient(145deg, #174966 0%, #296e86 55%, #4e589f 100%); }
.auth-brand-panel::before, .auth-brand-panel::after { content: ""; position: absolute; z-index: -1; width: 460px; height: 460px; border: 1px solid rgba(255,255,255,.15); border-radius: 50%; pointer-events: none; }
.auth-brand-panel::before { top: -240px; right: -130px; box-shadow: 0 0 0 65px rgba(255,255,255,.035), 0 0 0 130px rgba(255,255,255,.025); }
.auth-brand-panel::after { bottom: -320px; left: -110px; box-shadow: 0 0 0 80px rgba(255,255,255,.035); }
.auth-brand-logo { align-self: flex-start; padding: 14px 20px; background: rgba(255, 255, 255, .92); border-radius: 14px; }
.auth-brand-logo img { width: auto; max-width: min(230px, 60vw); max-height: 44px; object-fit: contain; }
.auth-brand-content { max-width: 510px; margin: 64px 0; }
.auth-feature-icon { display: inline-grid; place-items: center; width: 76px; height: 76px; margin-bottom: 28px; border: 1px solid rgba(255,255,255,.3); border-radius: 24px; background: rgba(255,255,255,.14); -webkit-backdrop-filter: blur(12px); backdrop-filter: blur(12px); font-size: 38px; box-shadow: 0 12px 30px rgba(20,20,60,.16); }
.auth-brand-content .auth-eyebrow { font-size: 12px; letter-spacing: .14em; text-transform: uppercase; font-weight: 600; color: #e4e9ff; }
.auth-brand-content h1 { color: white; font-size: clamp(32px, 4vw, 58px); line-height: 1.14; letter-spacing: -.035em; font-weight: 700; margin-bottom: 24px; }
.auth-brand-content p { font-size: 17px; line-height: 1.8; color: #e5e9ff; }
.auth-brand-footer { display: flex; align-items: center; gap: 10px; color: #e5e9ff; font-size: 13px; }
.auth-brand-footer i { font-size: 20px; }
.auth-form-panel {
    display: flex; justify-content: center; align-items: center; padding: 48px 32px;
    background:
        radial-gradient(420px 360px at 90% 10%, rgba(122, 90, 240, .25), transparent 70%),
        radial-gradient(440px 380px at 10% 95%, rgba(47, 169, 176, .22), transparent 70%),
        linear-gradient(135deg, #e6ecfb, #efeafb);
}
.auth-form-content {
    width: 100%; max-width: 420px; padding: 36px 32px;
    background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 24px;
    -webkit-backdrop-filter: var(--glass-blur); backdrop-filter: var(--glass-blur);
    box-shadow: 0 20px 60px rgba(28, 40, 100, .16), inset 0 1px 0 rgba(255, 255, 255, .8);
}
.auth-form-icon { display: grid; place-items: center; height: 54px; width: 54px; border-radius: 17px; color: #5b4caa; background: rgba(122, 90, 240, .14); font-size: 28px; margin-bottom: 24px; }
.auth-form-title { font-size: 28px; font-weight: 700; letter-spacing: -.025em; margin-bottom: 12px; }
.auth-form-panel .form-control { min-height: 48px; }
.auth-form-panel .btn[type="submit"] { width: 100%; min-height: 48px; margin-top: 12px; }
.auth-form-panel label { font-size: 13px; }
.auth-back-link { display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; font-weight: 600; }

/* Browsers without backdrop-filter get more opaque surfaces so text stays readable. */
@supports not ((backdrop-filter: blur(1px)) or (-webkit-backdrop-filter: blur(1px))) {
    :root { --glass-bg: rgba(255, 255, 255, .92); --glass-bg-strong: rgba(255, 255, 255, .97); --glass-dark: #26397a; }
    .vertical-menu { background: #26397a; }
}

@media (prefers-reduced-motion: no-preference) {
    .auth-form-content { animation: auth-reveal .45s ease-out both; }
    .auth-feature-icon { animation: auth-reveal .65s ease-out both; }
    @keyframes auth-reveal { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
}
@media (max-width: 991.98px) {
    .auth-split { grid-template-columns: 1fr; }
    .auth-brand-panel { padding: 28px; }
    .auth-brand-content { margin: 28px 0 0; max-width: 650px; }
    .auth-brand-content h1 { font-size: 32px; margin-bottom: 12px; }
    .auth-feature-icon, .auth-brand-footer { display: none; }
    .auth-brand-content p { font-size: 14px; }
    .auth-form-panel { padding: 36px 20px; }
    .auth-form-content { padding: 28px 22px; }
}
@media (max-width: 767.98px) {
    .card-body, .modal-body { padding: 16px; }
    .modal-header, .modal-footer, .card-header { padding: 14px 16px; }
    .page-fragment-bar { padding: 12px 16px; }
    .dataTables_wrapper .dataTables_filter { text-align: start; }
    .dataTables_wrapper .dataTables_filter input { max-width: 100%; }
    .bread-cum { margin-bottom: 16px; }
}
@media (prefers-reduced-motion: reduce) {
    .btn, .form-control, .form-select, #sidebar-menu ul li a, #sidebar-menu ul li a i { transition: none; }
    #sidebar-menu ul li a:hover i { transform: none; }
}
@media print {
    body, body:not(.authentication-bg) { background: white !important; color: black !important; }
    body::before { display: none !important; }
    .card, .shadow-card, .modal-content { box-shadow: none !important; background: white !important; -webkit-backdrop-filter: none !important; backdrop-filter: none !important; }
    .table > :not(caption) > * > * { background: white !important; color: black !important; }
}
</style>
