@php
    // Active-state map for student sidebar links.
    $isStudentDashboardActive = request()->routeIs('student.dashboard');
    $isStudentCalendarActive = request()->routeIs('student.calendar');
    $isStudentQuizzesActive = request()->routeIs('student.quizzes*');
    $isStudentProfileActive = request()->routeIs('student.profile');
@endphp

{{-- Student sidebar shell --}}
<div class="side-menu-fixed">
    <div class="scrollbar side-menu-bg" style="overflow-y: auto; overflow-x: hidden;">
        {{-- Main student navigation list --}}
        <ul class="nav navbar-nav side-menu" id="sidebarnav">
            {{-- Dashboard link --}}
            <li class="{{ $isStudentDashboardActive ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}">
                    <div class="pull-left"><i class="ti-home"></i><span
                            class="right-nav-text">{{ trans('main_trans.Dashboard') }}</span></div>
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

            {{-- Quizzes module --}}
            <li class="{{ $isStudentQuizzesActive ? 'active' : '' }}">
                <a href="javascript:void(0);" data-toggle="collapse" data-target="#student-quizzes-menu">
                    <div class="pull-left"><i class="fas fa-file-alt"></i><span class="right-nav-text">
                            {{ trans('Quizzes_trans.title_page') }}</span></div>
                    <div class="pull-right"><i class="ti-plus"></i></div>
                    <div class="clearfix"></div>
                </a>
                <ul id="student-quizzes-menu" class="collapse {{ $isStudentQuizzesActive ? 'show' : '' }}"
                    data-parent="#sidebarnav">
                    <li>
                        <a href="{{ route('student.quizzes') }}">
                            {{ trans('Quizzes_trans.student_sidebar_available_quizzes') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.quizzes.results') }}">
                            {{ trans('Quizzes_trans.student_sidebar_results') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Profile link --}}
            <li class="{{ $isStudentProfileActive ? 'active' : '' }}">
                <a href="{{ route('student.profile') }}">
                    <div class="pull-left"><i class="fas fa-id-card"></i><span
                            class="right-nav-text">{{ trans('main_trans.profile_tab_title') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>
        </ul>
    </div>
</div>
