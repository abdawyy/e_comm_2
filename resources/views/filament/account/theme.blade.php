<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    html, body, .fi-body {
        background: #ffffff !important;
        color: #131316 !important;
        font-family: "IBM Plex Sans Arabic", "Segoe UI", sans-serif;
    }

    .fi-body .fi-layout,
    .fi-main-ctn,
    .fi-main,
    .fi-page,
    .fi-simple-layout {
        background: #ffffff !important;
    }

    .fi-topbar {
        background: #ffffff !important;
        border-bottom: 1px solid #e6e6e6 !important;
        box-shadow: none !important;
    }

    .fi-topbar-ctn,
    .fi-topbar nav {
        gap: 0.35rem;
    }

    .fi-sidebar-item-btn,
    .fi-sidebar-item-button,
    .fi-topbar-item-btn,
    .fi-topbar-item-button {
        border-radius: 999px !important;
        color: #131316 !important;
        background: transparent !important;
    }

    .fi-sidebar-item.fi-active .fi-sidebar-item-btn,
    .fi-sidebar-item.fi-active .fi-sidebar-item-button,
    .fi-topbar-item.fi-active .fi-topbar-item-btn,
    .fi-topbar-item.fi-active .fi-topbar-item-button,
    .fi-sidebar-item-active > a,
    .fi-sidebar-item-active > button {
        background: #eeddb0 !important;
        color: #131316 !important;
    }

    .fi-main {
        padding: 1.5rem 1rem 3rem !important;
    }

    .fi-header-heading,
    .fi-section-header-heading,
    .fi-simple-header-heading {
        color: #131316 !important;
        font-weight: 700 !important;
    }

    .fi-breadcrumbs,
    .fi-header-subheading {
        color: #5c6168 !important;
    }

    .fi-section,
    .fi-ta-ctn,
    .fi-wi-widget,
    .fi-sc-section,
    .fi-simple-main,
    .account-card {
        background: #ffffff !important;
        border: 1px solid #e6e6e6 !important;
        border-radius: 18px !important;
        box-shadow: none !important;
        color: #131316 !important;
    }

    .fi-ta-header-cell {
        background: #f7f4ee !important;
        color: #131316 !important;
        font-weight: 700 !important;
    }

    .fi-ta-row {
        background: #ffffff !important;
    }

    .fi-ta-row:hover {
        background: #faf8f4 !important;
    }

    .fi-ta-cell,
    .fi-ta-header-cell,
    .fi-section-content,
    .fi-fo-field-wrp-label {
        color: #131316 !important;
    }

    .fi-badge {
        border-radius: 999px !important;
        background: #eeddb0 !important;
        color: #131316 !important;
    }

    .fi-btn {
        border-radius: 999px !important;
        box-shadow: none !important;
    }

    .fi-btn.fi-color-primary,
    .fi-btn-color-primary,
    .fi-ac-btn-action.fi-color-primary {
        background: #131316 !important;
        color: #ffffff !important;
        border-color: #131316 !important;
    }

    .fi-btn.fi-color-gray,
    .fi-btn-color-gray {
        background: #ffffff !important;
        color: #131316 !important;
        border: 1px solid #e6e6e6 !important;
    }

    .fi-input-wrp,
    .fi-fo-text-input .fi-input-wrp,
    .fi-select-input,
    .fi-input,
    .fi-textarea {
        background: #ffffff !important;
        border-color: #e6e6e6 !important;
        color: #131316 !important;
        border-radius: 12px !important;
        box-shadow: none !important;
    }

    .fi-dropdown-panel,
    .fi-modal-window {
        background: #ffffff !important;
        color: #131316 !important;
        border: 1px solid #e6e6e6 !important;
    }

    .fi-simple-main {
        box-shadow: none !important;
    }

    .fi-main a:not(.fi-btn):not(.fi-badge) {
        color: #131316;
    }
</style>
@include('store.partials.whatsapp')
