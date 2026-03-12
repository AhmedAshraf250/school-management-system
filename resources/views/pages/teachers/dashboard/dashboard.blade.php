<!DOCTYPE html>
<html lang="en">
@section('title')
    {{ trans('main_trans.Main_title') }}
@stop

<head>
    @include('layouts.partials.head')
    @stack('css')
</head>

<body style="font-family: 'Cairo', sans-serif">
    <div class="wrapper" style="font-family: 'Cairo', sans-serif">
        {{-- Global preloader --}}
        <x-preloader />

        {{-- Main header --}}
        @include('layouts.partials.main-header')

        <div class="content-wrapper">
            {{-- Teacher dashboard title --}}
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="mb-0">{{ trans('main_trans.teacher_dashboard_title') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>

    @include('layouts.partials.footer-scripts')
    @stack('scripts')
</body>

</html>
