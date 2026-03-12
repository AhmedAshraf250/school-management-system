<!DOCTYPE html>
<html lang="en">
@section('title')
    {{ trans('main_trans.Main_title') }}
@stop

<head>
    @include('layouts.partials.head')
    @stack('css')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
</head>

<body style="font-family: 'Cairo', sans-serif">

    <div class="wrapper" style="font-family: 'Cairo', sans-serif">

        {{-- Global preloader --}}
        <x-preloader />

        {{-- Main header --}}
        @include('layouts.partials.main-header')

        {{-- Main sidebar --}}
        @include('layouts.partials.main-sidebar')


        <div class="content-wrapper">
            {{-- Student dashboard title --}}
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="mb-0" style="font-family: 'Cairo', sans-serif">
                            {{ trans('main_trans.student_dashboard_title') }}</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                        </ol>
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
