<style>
/* Shared Material-inspired tokens. Keep layout sizing and widget behavior in the base theme. */
:root {
    --md-primary: #3957b8;
    --md-primary-hover: #2d4597;
    --md-primary-soft: #eaf0ff;
    --md-surface: #ffffff;
    --md-canvas: #f4f6fb;
    --md-text: #243047;
    --md-muted: #65728a;
    --md-outline: #dce2ed;
    --md-radius: 16px;
    --md-shadow: 0 2px 4px rgba(29, 43, 76, .03), 0 8px 28px rgba(29, 43, 76, .06);
    --bs-primary: #3957b8;
    --bs-primary-rgb: 57, 87, 184;
    --bs-body-color: var(--md-text);
    --bs-body-bg: var(--md-canvas);
    --bs-border-color: var(--md-outline);
}
body {
    background: var(--md-canvas);
    color: var(--md-text);
    font-family: "Inter", "Segoe UI", "Noto Sans Bengali", sans-serif;
    line-height: 1.55;
    -webkit-font-smoothing: antialiased;
}
a { color: var(--md-primary); }
a:hover { color: var(--md-primary-hover); }
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 { color: var(--md-text); }
.text-muted { color: var(--md-muted) !important; }
::selection { background: #dbe4ff; color: #243775; }

/* App shell: quiet surfaces, rounded navigation and clear selected states. */
#page-topbar {
    background: var(--md-surface);
    border-bottom: 1px solid var(--md-outline);
    box-shadow: 0 2px 12px rgba(29, 43, 76, .035);
}
.navbar-header .header-item { color: var(--md-muted); }
.navbar-header .header-item:hover { background: var(--md-primary-soft); color: var(--md-primary); }
.vertical-menu { background: var(--md-surface); border-right: 1px solid var(--md-outline); box-shadow: none; }
.navbar-brand-box { background: var(--md-surface); }
#sidebar-menu { padding: 16px 10px; }
#sidebar-menu ul li a {
    color: #526078;
    border-radius: 12px;
    margin: 3px 0;
    transition: background-color .18s ease, color .18s ease;
}
#sidebar-menu ul li a i { color: #7b88a1; }
#sidebar-menu ul li a:hover { color: var(--md-primary); background: #f2f5fc; }
#sidebar-menu ul li.mm-active > a,
#sidebar-menu ul li a.active { background: var(--md-primary-soft); color: var(--md-primary) !important; font-weight: 600; }
#sidebar-menu ul li.mm-active > a i,
#sidebar-menu ul li a.active i { color: var(--md-primary) !important; }
#sidebar-menu .menu-title { color: var(--md-muted); letter-spacing: .08em; font-size: 11px; }
.footer { background: transparent; color: var(--md-muted); border-top: 1px solid var(--md-outline); }
.bread-cum { margin: 8px 0 22px; }
.page-title, .page-title-box h4 { font-weight: 700; letter-spacing: -.025em; color: var(--md-text); }
.breadcrumb { color: var(--md-muted); }
.breadcrumb-item.active { color: var(--md-muted); }

/* Raised surfaces, including shared CRUD forms and dialogs. */
.card, .shadow-card {
    background: var(--md-surface);
    border: 1px solid #e5e9f2;
    border-radius: var(--md-radius);
    box-shadow: var(--md-shadow);
}
.card.rounded, .card.rounded-0 { border-radius: var(--md-radius) !important; }
.card-border { border-top: 3px solid var(--md-primary); }
.card-header { background: transparent; border-bottom: 1px solid #edf0f6; padding: 18px 24px; }
.card-header:first-child { border-radius: var(--md-radius) var(--md-radius) 0 0; }
.card-footer { background: #fafbfe; border-top: 1px solid #edf0f6; }
.page-fragment-bar {
    background: #edf2ff;
    color: #344d97;
    padding: 14px 20px;
    border-bottom: 1px solid #dfe7fa;
    border-radius: 14px 14px 0 0;
}
.page-fragment-bar * { font-size: 14px; font-weight: 600; }
.modal-content { border: 1px solid var(--md-outline); border-radius: 20px; box-shadow: 0 24px 72px rgba(20, 35, 66, .22); }
.modal-header, .modal-footer { border-color: #edf0f6; padding: 18px 24px; }
.modal-body { padding: 24px; }
.modal-title { font-weight: 650; }
.dropdown-menu { border: 1px solid #e5e9f2; border-radius: 12px; padding: 7px; box-shadow: 0 12px 36px rgba(29, 43, 76, .14); }
.dropdown-item { border-radius: 7px; padding: 8px 12px; color: var(--md-text); }
.dropdown-item:hover, .dropdown-item:focus { background: var(--md-primary-soft); color: var(--md-primary); }
.dropdown-item.active, .dropdown-item:active { background: var(--md-primary); color: white; }

/* Material controls: outlined fields and visible keyboard focus. */
.form-label, label { color: #46536c; font-weight: 500; }
.form-control, .form-select, .custom-select {
    background-color: #fff;
    color: var(--md-text);
    border: 1px solid #cdd5e4;
    border-radius: 9px;
    padding: .65rem .85rem;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}
.form-control::placeholder { color: #8590a4; opacity: 1; }
.form-control:hover:not(:disabled), .form-select:hover:not(:disabled) { border-color: #98a8c4; }
.form-control:focus, .form-select:focus, .custom-select:focus {
    border-color: var(--md-primary);
    box-shadow: 0 0 0 3px rgba(57, 87, 184, .14);
    background-color: #fff;
    color: var(--md-text);
}
.form-control.is-invalid, .form-select.is-invalid { border-color: #c23f4e; }
.form-control.is-valid, .form-select.is-valid { border-color: #258366; }
.form-control[readonly], .form-control[disabled], .form-select:disabled { background: #eef1f6 !important; color: var(--md-muted); }
.form-control-sm, .form-select-sm { padding: .4rem .65rem; }
.input-group-text { background: #f2f5fb; color: var(--md-muted); border-color: #cdd5e4; border-radius: 9px; }
.input-group > .form-control:not(:last-child), .input-group > .input-group-text:not(:last-child) { border-top-right-radius: 0; border-bottom-right-radius: 0; }
.input-group > .form-control:not(:first-child), .input-group > .input-group-text:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
.form-check-input { border-color: #9aa8bf; cursor: pointer; }
.form-check-input:checked { background-color: var(--md-primary); border-color: var(--md-primary); }
.form-check-input:focus { border-color: var(--md-primary); box-shadow: 0 0 0 3px rgba(57, 87, 184, .16); }
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple { border: 1px solid #cdd5e4; border-radius: 9px; min-height: 42px; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 40px; color: var(--md-text); }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; }
.select2-dropdown { border-color: var(--md-outline); border-radius: 9px; box-shadow: var(--md-shadow); }
.select2-container--default .select2-results__option--highlighted[aria-selected] { background: var(--md-primary); }

.btn { border-radius: 9px; font-weight: 600; padding: .6rem 1rem; transition: background-color .18s ease, box-shadow .18s ease; }
.btn-sm { padding: .35rem .7rem; border-radius: 7px; }
.btn-lg { padding: .8rem 1.4rem; border-radius: 12px; }
.btn-primary { background: var(--md-primary); border-color: var(--md-primary); box-shadow: 0 3px 8px rgba(57, 87, 184, .2); }
.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active { background: var(--md-primary-hover) !important; border-color: var(--md-primary-hover) !important; }
.btn-outline-primary { color: var(--md-primary); border-color: #a9b8de; background: transparent; }
.btn-outline-primary:hover, .btn-outline-primary.active { background: var(--md-primary); border-color: var(--md-primary); color: white; }
.btn-light { background: #eff3fa; border-color: #e3e9f3; color: #445472; }
.btn:disabled, .btn.disabled { box-shadow: none; }
a:focus-visible, button:focus-visible, .btn:focus-visible, [tabindex]:focus-visible { outline: 3px solid #809ce7; outline-offset: 3px; }

/* Tables stay horizontally scrollable; menus and responsive details retain their behavior. */
table.dataTable { border-radius: 0; overflow: visible; box-shadow: none; background: white; }
.table { --bs-table-color: var(--md-text); --bs-table-hover-bg: #eef3fd; color: var(--md-text); }
.table > :not(caption) > * > * { color: var(--md-text); border-bottom-color: #e9edf5; padding: 13px 14px; vertical-align: middle; }
table.dataTable thead th, .table thead th {
    background: #f0f3fa;
    color: #52617c;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .035em;
    padding: 14px;
    border-bottom: 1px solid #dfe5f0 !important;
}
table.dataTable tbody tr.odd td, table.dataTable tbody tr.even td { background: white; color: var(--md-text); border-bottom: 1px solid #edf0f6 !important; }
table.dataTable tbody tr:hover td { background: #f3f6fd !important; }
table.dataTable tbody tr.selected td { background: #e7eeff !important; }
.dataTables_wrapper .dataTables_filter input, .dataTables_wrapper .dataTables_length select { border: 1px solid #cdd5e4; border-radius: 8px; background: white; padding: 7px 10px; color: var(--md-text); }
.dataTables_wrapper .dataTables_info { color: var(--md-muted); font-size: 12px; }
.page-link { color: var(--md-primary); border-color: #e0e6f0; padding: .5rem .8rem; }
.page-item.active .page-link { background: var(--md-primary); border-color: var(--md-primary); box-shadow: 0 2px 6px rgba(57, 87, 184, .2); }
.page-link:hover { background: var(--md-primary-soft); color: var(--md-primary); }
.nav-tabs { border-color: var(--md-outline); gap: 4px; }
.nav-tabs .nav-link { color: var(--md-muted); border-radius: 9px 9px 0 0; }
.nav-tabs .nav-link.active { color: var(--md-primary); background: var(--md-primary-soft); border-color: transparent transparent var(--md-primary); }
.nav-pills .nav-link.active { background: var(--md-primary); }
.p-link.p-link-active { color: var(--md-primary) !important; border-top-color: var(--md-primary); }
.alert { border-radius: 12px; }
.badge { font-weight: 600; letter-spacing: .01em; padding: .4em .65em; }
.progress { border-radius: 20px; background: #e9eef8; }
.account-pages .card { border-radius: 22px !important; box-shadow: 0 16px 56px rgba(29, 43, 76, .1); }
.account-pages .logo { max-width: 100%; height: auto; }

@media (max-width: 767.98px) {
    .card-body, .modal-body { padding: 16px; }
    .modal-header, .modal-footer, .card-header { padding: 14px 16px; }
    .page-fragment-bar { padding: 12px 16px; }
    .dataTables_wrapper .dataTables_filter { text-align: start; }
    .dataTables_wrapper .dataTables_filter input { max-width: 100%; }
    .bread-cum { margin-bottom: 16px; }
}
@media (prefers-reduced-motion: reduce) {
    .btn, .form-control, .form-select, #sidebar-menu ul li a { transition: none; }
}
@media print {
    body { background: white !important; color: black !important; }
    .card, .shadow-card, .modal-content { box-shadow: none !important; }
    .table > :not(caption) > * > * { background: white !important; color: black !important; }
}

/* Indigo, violet and teal accents shared by the admin workspace. */
:root { --md-gradient: linear-gradient(125deg, #3459bc 0%, #6950bd 100%); }
body:not(.authentication-bg) { background: radial-gradient(ellipse at 95% 0%, #e9e6fb 0%, transparent 40%), var(--md-canvas); }
#page-topbar::after { content: ""; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #3459bc, #805acb, #30a6ab); pointer-events: none; }
.btn-primary { background: var(--md-gradient); border-color: transparent; }
.btn-primary:hover { box-shadow: 0 6px 16px rgba(74, 70, 161, .27); }
.page-fragment-bar { background: linear-gradient(110deg, #e8efff, #f0eafa); }
#sidebar-menu ul li.mm-active > a, #sidebar-menu ul li a.active { background: linear-gradient(100deg, #e5edff, #f0eafa); }
#sidebar-menu ul li a i { transition: transform .2s ease, color .2s ease; }
#sidebar-menu ul li a:hover i { transform: translateY(-2px) rotate(-5deg); color: var(--md-primary); }
.card-header { background: linear-gradient(110deg, #f9fbff, #faf8ff); }
.auth-split { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); min-height: 100vh; min-height: 100dvh; background: #fff; }
.auth-brand-panel { position: relative; isolation: isolate; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: clamp(32px, 5vw, 80px); background: linear-gradient(145deg, #233d89 0%, #5141a0 58%, #8460be 100%); color: white; }
.auth-recovery .auth-brand-panel { background: linear-gradient(145deg, #174966 0%, #296e86 55%, #4e589f 100%); }
.auth-brand-panel::before, .auth-brand-panel::after { content: ""; position: absolute; z-index: -1; width: 460px; height: 460px; border: 1px solid rgba(255,255,255,.15); border-radius: 50%; pointer-events: none; }
.auth-brand-panel::before { top: -240px; right: -130px; box-shadow: 0 0 0 65px rgba(255,255,255,.035), 0 0 0 130px rgba(255,255,255,.025); }
.auth-brand-panel::after { bottom: -320px; left: -110px; box-shadow: 0 0 0 80px rgba(255,255,255,.035); }
.auth-brand-logo { align-self: flex-start; padding: 14px 20px; background: white; border-radius: 14px; }
.auth-brand-logo img { width: auto; max-width: min(230px, 60vw); max-height: 44px; object-fit: contain; }
.auth-brand-content { max-width: 510px; margin: 64px 0; }
.auth-feature-icon { display: inline-grid; place-items: center; width: 76px; height: 76px; margin-bottom: 28px; border: 1px solid rgba(255,255,255,.25); border-radius: 24px; background: rgba(255,255,255,.1); font-size: 38px; box-shadow: 0 12px 30px rgba(20,20,60,.12); }
.auth-brand-content .auth-eyebrow { font-size: 12px; letter-spacing: .14em; text-transform: uppercase; font-weight: 600; color: #e4e9ff; }
.auth-brand-content h1 { color: white; font-size: clamp(32px, 4vw, 58px); line-height: 1.14; letter-spacing: -.035em; font-weight: 700; margin-bottom: 24px; }
.auth-brand-content p { font-size: 17px; line-height: 1.8; color: #e5e9ff; }
.auth-brand-footer { display: flex; align-items: center; gap: 10px; color: #e5e9ff; font-size: 13px; }
.auth-brand-footer i { font-size: 20px; }
.auth-form-panel { display: flex; justify-content: center; align-items: center; padding: 48px 32px; background: radial-gradient(ellipse at right top, #f0f2ff, transparent 60%), #fff; }
.auth-form-content { width: 100%; max-width: 420px; }
.auth-form-icon { display: grid; place-items: center; height: 54px; width: 54px; border-radius: 17px; color: #5b4caa; background: #efecfb; font-size: 28px; margin-bottom: 24px; }
.auth-form-title { font-size: 28px; font-weight: 700; letter-spacing: -.025em; margin-bottom: 12px; }
.auth-form-panel .form-control { min-height: 48px; }
.auth-form-panel .btn[type="submit"] { width: 100%; min-height: 48px; margin-top: 12px; }
.auth-form-panel label { font-size: 13px; }
.auth-back-link { display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; font-weight: 600; }
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
    .auth-form-panel { padding: 36px 24px; }
}
@media (prefers-reduced-motion: reduce) {
    #sidebar-menu ul li a i { transition: none; }
    #sidebar-menu ul li a:hover i { transform: none; }
}
@media print { body:not(.authentication-bg) { background: white !important; } }
/* Professional top navigation: deep indigo with a soft violet highlight. */
#page-topbar {
    background: linear-gradient(115deg, #203568 0%, #354887 52%, #59458b 100%);
    border-bottom: 1px solid rgba(255, 255, 255, .12);
    box-shadow: 0 4px 20px rgba(32, 53, 104, .18);
}
#page-topbar::after {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(204, 219, 255, .55), transparent);
}
#page-topbar .header-item,
#page-topbar .header-item > i { color: #f2f5ff; }
#page-topbar .header-item:hover,
#page-topbar .header-item:focus-visible,
#page-topbar .header-item[aria-expanded="true"] {
    color: #fff;
    background: rgba(255, 255, 255, .12);
}
#page-topbar .header-item:focus-visible { outline-color: #c9dbff; outline-offset: -4px; }
#page-topbar .header-profile-user {
    border: 2px solid rgba(255, 255, 255, .65);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, .08);
}
#page-topbar .dropdown-menu { background: var(--md-surface); color: var(--md-text); }
/* Sidebar complements the topbar, including collapsed fly-out menus. */
.vertical-menu {
    background: linear-gradient(165deg, #203568 0%, #354478 55%, #504078 100%);
    border-right: 1px solid rgba(255, 255, 255, .1);
    box-shadow: 4px 0 20px rgba(32, 53, 104, .1);
}
.vertical-menu .navbar-brand-box {
    background: #203568;
    border-bottom: 1px solid rgba(255, 255, 255, .12);
}
.vertical-menu .navbar-brand-box img {
    background: #fff;
    border-radius: 6px;
    padding: 4px;
    object-fit: contain;
}
.vertical-menu .vertical-menu-btn { color: #edf2ff; }
.vertical-menu #sidebar-menu ul li a { color: #e0e7fa; }
.vertical-menu #sidebar-menu ul li a i { color: #c2cff2; }
.vertical-menu #sidebar-menu .menu-title { color: #bdcbed; }
.vertical-menu #sidebar-menu ul li a:hover,
.vertical-menu #sidebar-menu ul li a:focus-visible {
    background: rgba(255, 255, 255, .1);
    color: #fff;
}
.vertical-menu #sidebar-menu ul li.mm-active > a,
.vertical-menu #sidebar-menu ul li a.active {
    background: linear-gradient(110deg, rgba(134, 169, 255, .25), rgba(186, 151, 241, .22));
    color: #fff !important;
    box-shadow: inset 3px 0 0 #b6cbff;
}
.vertical-menu #sidebar-menu ul li a:hover i,
.vertical-menu #sidebar-menu ul li.mm-active > a i,
.vertical-menu #sidebar-menu ul li a.active i { color: #fff !important; }
.vertical-menu #sidebar-menu a:focus-visible { outline-color: #c9dbff; outline-offset: -3px; }
.vertical-menu .simplebar-scrollbar::before { background: #c2cff2; }
body[data-sidebar-size="sm"] .vertical-menu #sidebar-menu > ul > li:hover > a,
body[data-sidebar-size="sm"] .vertical-menu #sidebar-menu > ul > li:hover > ul {
    background: #304375;
    color: #fff;
}
/* Keep nested menu labels legible against the dark sidebar. */
.vertical-menu #sidebar-menu ul li ul.sub-menu li > a {
    color: #f5f7ff !important;
    opacity: 1;
}
.vertical-menu #sidebar-menu ul li ul.sub-menu li > a:hover,
.vertical-menu #sidebar-menu ul li ul.sub-menu li > a:focus-visible,
.vertical-menu #sidebar-menu ul li ul.sub-menu li.mm-active > a,
.vertical-menu #sidebar-menu ul li ul.sub-menu li > a.active {
    color: #fff !important;
}
.vertical-menu #sidebar-menu ul.sub-menu a i,
.vertical-menu #sidebar-menu ul.sub-menu .has-arrow::after {
    color: #f5f7ff !important;
    opacity: 1;
}
</style>
