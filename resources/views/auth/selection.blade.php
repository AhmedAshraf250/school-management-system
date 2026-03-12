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
        {{-- Authentication selection section --}}
        <section class="height-100vh d-flex align-items-center page-section-ptb login"
            style="background-image: url('{{ asset('assets/images/sativa.png') }}');">
            <div class="container">
                <div class="row justify-content-center no-gutters vertical-align">
                    <div class="col-lg-10 col-md-11 bg-white" style="border-radius: 15px;">
                        <div class="login-fancy pb-40 clearfix text-center">
                            <div class="d-flex justify-content-between align-items-center mb-20">
                                <a href="{{ route('home') }}" class="btn btn-link p-0">
                                    {{ trans('main_trans.back_to_home') }}
                                </a>
                                <x-ui.language-switcher />
                            </div>

                            <h3 class="mb-10" style="font-family: 'Cairo', sans-serif;">
                                {{ trans('main_trans.auth_selection_title') }}
                            </h3>
                            <p class="text-muted mb-30">
                                {{ trans('main_trans.auth_selection_subtitle') }}
                            </p>

                            {{-- Guard cards --}}
                            <div class="row justify-content-center">
                                <div class="col-6 col-md-3 mb-20">
                                    <a class="btn btn-outline-primary w-100 py-20 d-flex flex-column align-items-center"
                                        href="{{ route('login.guard', ['guard' => 'student']) }}">
                                        <img alt="student" width="80" class="mb-10"
                                            src="{{ asset('assets/images/student.png') }}">
                                        <span>{{ trans('main_trans.students') }}</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3 mb-20">
                                    <a class="btn btn-outline-primary w-100 py-20 d-flex flex-column align-items-center"
                                        href="{{ route('login.guard', ['guard' => 'guardian']) }}">
                                        <img alt="guardian" width="80" class="mb-10"
                                            src="{{ asset('assets/images/parent.png') }}">
                                        <span>{{ trans('main_trans.Parents') }}</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3 mb-20">
                                    <a class="btn btn-outline-primary w-100 py-20 d-flex flex-column align-items-center"
                                        href="{{ route('login.guard', ['guard' => 'teacher']) }}">
                                        <img alt="teacher" width="80" class="mb-10"
                                            src="{{ asset('assets/images/teacher.png') }}">
                                        <span>{{ trans('main_trans.Teachers') }}</span>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3 mb-20">
                                    <a class="btn btn-outline-primary w-100 py-20 d-flex flex-column align-items-center"
                                        href="{{ route('login.guard', ['guard' => 'admin']) }}">
                                        <img alt="admin" width="80" class="mb-10"
                                            src="{{ asset('assets/images/admin.png') }}">
                                        <span>{{ trans('main_trans.admin') }}</span>
                                    </a>
                                </div>
                            </div>
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
