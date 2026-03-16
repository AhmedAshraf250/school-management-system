@php
    $isTeacherDashboardActive = request()->routeIs('teacher.dashboard');
    $isTeacherStudentsActive = request()->routeIs('teacher.students.*');
    $isTeacherCalendarActive = request()->routeIs('teacher.calendar');
@endphp

<div class="side-menu-fixed">
    <div class="scrollbar side-menu-bg" style="overflow-y: auto; overflow-x: hidden;">
        <ul class="nav navbar-nav side-menu" id="sidebarnav">
            <li class="{{ $isTeacherDashboardActive ? 'active' : '' }}">
                <a href="{{ route('teacher.dashboard') }}">
                    <div class="pull-left"><i class="ti-home"></i><span
                            class="right-nav-text">{{ trans('main_trans.Dashboard') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="{{ $isTeacherStudentsActive ? 'active' : '' }}">
                <a href="{{ route('teacher.students.index') }}">
                    <div class="pull-left"><i class="fas fa-user-graduate"></i><span
                            class="right-nav-text">{{ trans('main_trans.teacher_dashboard_students_entry') }}</span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="{{ $isTeacherCalendarActive ? 'active' : '' }}">
                <a href="{{ route('teacher.calendar') }}">
                    <div class="pull-left"><i class="fas fa-calendar-alt"></i><span
                            class="right-nav-text">{{ trans('main_trans.dashboard_calendar_title') }}</span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
        </ul>
    </div>
</div>
