<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="HTML5 Template" />
<meta name="description" content="School Management System" />
<meta name="author" content="Ahmed Ashraf" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Title -->
<title>@yield('title')</title>

{{-- <title>{{ $title }}</title> --}}

<!-- Favicon -->
<link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}" type="image/x-icon" />

<!-- Font -->
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">

<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

@yield('css')

{{-- @vite(['resources/css/wizard.css']) --}}
<link href="{{ URL::asset('css/wizard.css') }}" rel="stylesheet" id="bootstrap-css">

<!--- Style css -->
@if (App::getLocale() == 'en')
    <link href="{{ URL::asset('assets/css/ltr.css') }}" rel="stylesheet">
@else
    <link href="{{ URL::asset('assets/css/rtl.css') }}" rel="stylesheet">
@endif

<style>
    /* Keep the global shell inside the viewport and prevent horizontal drifting. */
    html,
    body {
        overflow-x: hidden;
    }

    .admin-header.navbar {
        width: 100%;
        max-width: 100%;
    }

    .content-wrapper {
        width: auto !important;
        max-width: 100%;
        overflow-x: hidden;
        padding-top: 88px !important;
    }

    .side-menu-fixed .side-menu-bg {
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }

    .dashboard-title-shell {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .dashboard-title-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        padding: 0.9rem 1.9rem;
        border-radius: 999px;
        border: 1px solid #d5a93b;
        background: linear-gradient(135deg, #fff7d4 0%, #f2c14d 45%, #d4a017 100%);
        color: #4a3511;
        font-weight: 700;
        font-size: 1rem;
        text-align: center;
        box-shadow: 0 10px 22px rgba(183, 134, 11, 0.28);
    }

    .dashboard-title-pill i {
        color: #7a5600;
        font-size: 0.95rem;
    }

</style>
