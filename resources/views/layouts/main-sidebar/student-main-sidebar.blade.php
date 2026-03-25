@php
    // Active-state map for student sidebar links.
    $isStudentDashboardActive = request()->routeIs('student.dashboard');
    $isStudentCalendarActive = request()->routeIs('student.calendar');
    $isStudentQuizzesActive = request()->routeIs('student.quizzes');
@endphp

{{-- Student sidebar shell --}}
<div class="side-menu-fixed">
    <div class="scrollbar side-menu-bg" style="overflow-y: auto; overflow-x: hidden;">
        {{-- Main student navigation list --}}
        <ul class="nav navbar-nav side-menu" id="sidebarnav">
            {{-- Dashboard link --}}
            <li class="{{ $isStudentDashboardActive ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}">
                    <div class="pull-left"><i class="ti-home"></i><span class="right-nav-text">{{ trans('main_trans.Dashboard') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>

            {{-- Calendar link --}}
            <li class="{{ $isStudentCalendarActive ? 'active' : '' }}">
                <a href="{{ route('student.calendar') }}">
                    <div class="pull-left"><i class="fas fa-calendar-alt"></i><span
                            class="right-nav-text">{{ trans('main_trans.dashboard_calendar_title') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>

            {{-- Quizzes link --}}
            <li class="{{ $isStudentQuizzesActive ? 'active' : '' }}">
                <a href="{{ route('student.quizzes') }}">
                    <div class="pull-left"><i class="fas fa-file-alt"></i><span
                            class="right-nav-text">{{ trans('Quizzes_trans.title_page') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>
        </ul>
    </div>
</div>
