@php
    $isGuardianDashboardActive = request()->routeIs('guardian.dashboard');
    $isGuardianCalendarActive = request()->routeIs('guardian.calendar');
@endphp

<div class="side-menu-fixed">
    <div class="scrollbar side-menu-bg" style="overflow-y: auto; overflow-x: hidden;">
        <ul class="nav navbar-nav side-menu" id="sidebarnav">
            <li class="{{ $isGuardianDashboardActive ? 'active' : '' }}">
                <a href="{{ route('guardian.dashboard') }}">
                    <div class="pull-left"><i class="ti-home"></i><span class="right-nav-text">{{ trans('main_trans.Dashboard') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="{{ $isGuardianCalendarActive ? 'active' : '' }}">
                <a href="{{ route('guardian.calendar') }}">
                    <div class="pull-left"><i class="fas fa-calendar-alt"></i><span
                            class="right-nav-text">{{ trans('main_trans.dashboard_calendar_title') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>
        </ul>
    </div>
</div>
