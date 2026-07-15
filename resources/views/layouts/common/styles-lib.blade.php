@inject('settings', 'App\Services\SettingService')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="shortcut icon" href="{{ $settings->getImageUrl('favicon') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />

<script src="{{ asset('assets/js/head.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<style>
    /* Force Toastr background colors */
    #toast-container>.toast-success {
        background-color: #28a745 !important;
        /* Green */
        color: white !important;
    }

    #toast-container>.toast-error {
        background-color: #dc3545 !important;
        /* Red */
        color: white !important;
    }

    #toast-container>.toast-warning {
        background-color: #ffc107 !important;
        /* Yellow */
        color: black !important;
    }

    #toast-container>.toast-info {
        background-color: #17a2b8 !important;
        /* Blue */
        color: white !important;
    }

    /* Optional: Fix opacity if it looks faded */
    #toast-container>.toast {
        opacity: 1 !important;
    }

    body {
        border-top: none !important;
    }

    .topbar-custom {
        background: #1f0f0a !important; /* Rich obsidian dark */
        border-top: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .logo-box {
        background: #1f0f0a !important; /* Seamless obsidian integration */
        border-top: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sidebar-menu {
        background: #ffffff !important;
        border-right: 1px solid #f0f0f2;
    }

    /* Force light colors on icons and text for dark header */
    .topbar-custom .topbar-button,
    .topbar-custom iconify-icon,
    .topbar-custom .text-dark {
        color: #e9ecef !important;
        transition: color 0.15s ease-in-out;
    }

    .topbar-custom .topbar-button:hover,
    .topbar-custom iconify-icon:hover {
        color: #ffffff !important;
    }

    /* Clean outlines and align elements vertically to center */
    .topbar-custom .button-toggle-menu,
    .topbar-custom .topbar-dropdown .nav-link {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0px !important;
        margin-top: 24px !important;
        height: auto !important;
        line-height: normal !important;
        padding: 0 !important;
    }

    .topbar-custom .nav-user {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0px !important;
        margin-top: 18px !important;
        height: auto !important;
        line-height: normal !important;
        padding: 0 !important;
    }

    .dropdown-toggle::after {
        display: none !important;
    }
    .btn-primary {
        background: #4b2c20 !important;
        border: #4b2c20;
    }
    .table-card{
        padding: 0px !important;
        margin: 0px !important;
    }
</style>







