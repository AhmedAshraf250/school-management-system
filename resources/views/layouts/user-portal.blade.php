<!DOCTYPE html>
<html lang="en">

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

        {{-- Main sidebar --}}
        @include('layouts.partials.main-sidebar')

        {{-- Main content container --}}
        <div class="content-wrapper">
            <div class="container-fluid">
                @yield('content')
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>

    @include('layouts.partials.footer-scripts')
    @stack('scripts')
</body>

</html>
