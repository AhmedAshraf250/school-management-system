<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ trans('main_trans.Main_title') }}</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link href="{{ asset('assets/css/rtl.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        {{-- Public home hero section --}}
        <section class="height-100vh d-flex align-items-center page-section-ptb login"
            style="background-image: url('{{ asset('assets/images/sativa.png') }}');">
            <div class="container">
                <div class="row justify-content-center no-gutters vertical-align">
                    <div class="col-lg-10 col-md-11 bg-white p-30" style="border-radius: 15px;">
                        <div class="d-flex justify-content-between align-items-center mb-20">
                            <h2 class="mb-0" style="font-family: 'Cairo', sans-serif;">
                                {{ trans('main_trans.school_home_title') }}
                            </h2>
                            <x-ui.language-switcher />
                        </div>

                        <p class="text-muted mb-20">
                            {{ trans('main_trans.school_home_description') }}
                        </p>

                        {{-- Public about section --}}
                        <div class="mb-30">
                            <h4>{{ trans('main_trans.school_about_title') }}</h4>
                            <p class="mb-0">{{ trans('main_trans.school_about_description') }}</p>
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-flex flex-wrap">
                            <a href="{{ route('auth.selection') }}" class="button mr-10 mb-10">
                                <span>{{ trans('main_trans.go_to_login_selection') }}</span>
                                <i class="fa fa-sign-in"></i>
                            </a>
                            <a href="{{ route('login') }}" class="button black mb-10">
                                <span>{{ trans('main_trans.admin_login') }}</span>
                                <i class="fa fa-user"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins-jquery.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
