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
    @php
        $guardLabelMap = [
            'admin' => trans('main_trans.admin'),
            'student' => trans('main_trans.students'),
            'teacher' => trans('main_trans.Teachers'),
            'guardian' => trans('main_trans.Parents'),
        ];
        $guardLabel = $guardLabelMap[$guard] ?? trans('main_trans.admin');
    @endphp

    <div class="wrapper">
        {{-- Login section --}}
        <section class="height-100vh d-flex align-items-center page-section-ptb login"
            style="background-image: url('{{ asset('assets/images/login-bg.jpg') }}');">
            <div class="container">
                <div class="row justify-content-center no-gutters vertical-align">
                    <div class="col-lg-4 col-md-6 login-fancy-bg bg"
                        style="background-image: url('{{ asset('images/login-inner-bg.jpg') }}');">
                        <div class="login-fancy">
                            <h2 class="text-white mb-20">{{ trans('main_trans.auth_welcome_title') }}</h2>
                            <p class="mb-20 text-white">{{ trans('main_trans.auth_welcome_text') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 bg-white">
                        <div class="login-fancy pb-40 clearfix">
                            <div class="d-flex justify-content-between align-items-center mb-20">
                                <a href="{{ route('auth.selection') }}" class="btn btn-link p-0">
                                    {{ trans('main_trans.back_to_account_selection') }}
                                </a>
                                <x-ui.language-switcher />
                            </div>

                            {{-- Login header --}}
                            <h3 class="mb-10">{{ trans('main_trans.login_title') }}</h3>
                            <p class="mb-20 text-muted">{{ trans('main_trans.login_as', ['role' => $guardLabel]) }}</p>

                            {{-- Login form --}}
                            <form method="POST" action="{{ route('login.guard.attempt', ['guard' => $guard]) }}">
                                @csrf

                                <div class="section-field mb-20">
                                    <label class="mb-10" for="email">{{ trans('main_trans.email') }}</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="section-field mb-20">
                                    <label class="mb-10" for="password">{{ trans('main_trans.password') }}</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="section-field">
                                    <div class="remember-checkbox mb-30">
                                        <input type="checkbox" class="form-control" name="remember" id="remember" />
                                        <label for="remember">{{ trans('main_trans.remember_me') }}</label>

                                        @if ($guard === 'admin' && Route::has('password.request'))
                                            <a href="{{ route('password.request') }}"
                                                class="float-right">{{ trans('main_trans.forgot_password') }}</a>
                                        @endif
                                    </div>
                                </div>

                                <button class="button" type="submit">
                                    <span>{{ trans('main_trans.sign_in') }}</span>
                                    <i class="fa fa-check"></i>
                                </button>

                            </form>
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
