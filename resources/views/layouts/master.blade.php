<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>

<body style="font-family: 'Cairo', sans-serif">
    <div class="wrapper">

        {{-- Preloader Component --}}
        <x-preloader />

        {{-- Header --}}
        @include('layouts.partials.main-header')

        {{-- Sidebar --}}
        @include('layouts.partials.main-sidebar')

        {{-- Main Content --}}
        <div class="content-wrapper">
            @yield('page-header')

            {{-- {{ $breadcrumbs ?? '' }} --}}
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="mb-0">@yield('PageTitle')</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/dashboard') }}" class="default-color">
                                    {{ trans('main_trans.Dashboard') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">@yield('PageTitle')</li>
                        </ol>
                    </div>
                </div>
            </div>

            @include('layouts.partials.flash-alerts')

            @yield('content')
            {{-- {{ $slot }} --}}

            {{-- Footer --}}
            @include('layouts.partials.footer')
        </div>
    </div>

    {{-- Footer Scripts --}}
    @include('layouts.partials.footer-scripts')
    @stack('scripts')
</body>

</html>
