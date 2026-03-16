@php
    $isStudentDashboardActive = request()->routeIs('student.dashboard');
    $isStudentCalendarActive = request()->routeIs('student.calendar');
@endphp

<div class="side-menu-fixed">
    <div class="scrollbar side-menu-bg" style="overflow-y: auto; overflow-x: hidden;">
        <ul class="nav navbar-nav side-menu" id="sidebarnav">
            <li class="{{ $isStudentDashboardActive ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}">
                    <div class="pull-left"><i class="ti-home"></i><span class="right-nav-text">{{ trans('main_trans.Dashboard') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="{{ $isStudentCalendarActive ? 'active' : '' }}">
                <a href="{{ route('student.calendar') }}">
                    <div class="pull-left"><i class="fas fa-calendar-alt"></i><span
                            class="right-nav-text">{{ trans('main_trans.dashboard_calendar_title') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>
        </ul>
    </div>
</div>
