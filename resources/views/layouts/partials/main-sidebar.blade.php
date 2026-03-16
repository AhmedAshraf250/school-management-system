@php
    $currentGuard = \App\Support\Auth\GuardResolver::currentGuard();
@endphp

@if ($currentGuard === 'student')
    @include('layouts.main-sidebar.student-main-sidebar')
@elseif ($currentGuard === 'teacher')
    @include('layouts.main-sidebar.teacher-main-sidebar')
@elseif ($currentGuard === 'guardian')
    @include('layouts.main-sidebar.guardian-main-sidebar')
@else
    @include('layouts.main-sidebar.admin-main-sidebar')
@endif
