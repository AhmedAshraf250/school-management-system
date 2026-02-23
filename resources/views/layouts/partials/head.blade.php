<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="HTML5 Template" />
<meta name="description" content="School Management System" />
<meta name="author" content="Your Name" />
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
<link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet">


<!--- Style css -->
@if (App::getLocale() == 'en')
    <link href="{{ URL::asset('assets/css/ltr.css') }}" rel="stylesheet">
@else
    <link href="{{ URL::asset('assets/css/rtl.css') }}" rel="stylesheet">
@endif
