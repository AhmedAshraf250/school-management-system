@php
    // Active-state map for the teacher sidebar links and dropdowns.
    $isTeacherDashboardActive = request()->routeIs('teacher.dashboard');
    $isTeacherStudentsActive = request()->routeIs('teacher.students.*');
    $isTeacherSectionsActive = request()->routeIs('teacher.sections.*');
    $isTeacherCalendarActive = request()->routeIs('teacher.calendar');
    $isTeacherReportsActive = request()->routeIs('teacher.reports.*');
    $isTeacherQuizzesActive = request()->routeIs('teacher.quizzes.*');
    $isTeacherQuestionsActive = request()->routeIs('teacher.questions.*');
    $isTeacherOnlineClassesActive = request()->routeIs('teacher.online-classes.*');
    $isTeacherProfileActive = request()->routeIs('teacher.profile');
    $isTeacherQuizMenuActive = $isTeacherQuizzesActive || $isTeacherQuestionsActive;
@endphp

{{-- Teacher sidebar shell --}}
<div class="side-menu-fixed">
    <div class="scrollbar side-menu-bg" style="overflow-y: auto; overflow-x: hidden;">
        {{-- Main teacher navigation list --}}
        <ul class="nav navbar-nav side-menu" id="sidebarnav">
            {{-- Dashboard link --}}
            <li class="{{ $isTeacherDashboardActive ? 'active' : '' }}">
                <a href="{{ route('teacher.dashboard') }}">
                    <div class="pull-left"><i class="ti-home"></i><span
                            class="right-nav-text">{{ trans('main_trans.Dashboard') }}</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>

            {{-- Students management link --}}
            <li class="{{ $isTeacherStudentsActive ? 'active' : '' }}">
                <a href="{{ route('teacher.students.index') }}">
                    <div class="pull-left"><i class="fas fa-user-graduate"></i><span
                            class="right-nav-text">{{ trans('main_trans.teacher_dashboard_students_entry') }}</span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>

            {{-- Sections link --}}
            <li class="{{ $isTeacherSectionsActive ? 'active' : '' }}">
                <a href="{{ route('teacher.sections.index') }}">
                    <div class="pull-left"><i class="fas fa-chalkboard"></i><span
                            class="right-nav-text">{{ trans('main_trans.teacher_dashboard_sections_entry') }}</span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>

            {{-- Calendar link --}}
            <li class="{{ $isTeacherCalendarActive ? 'active' : '' }}">
                <a href="{{ route('teacher.calendar') }}">
                    <div class="pull-left"><i class="fas fa-calendar-alt"></i><span
                            class="right-nav-text">{{ trans('main_trans.dashboard_calendar_title') }}</span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>

            {{-- Online classes link --}}
            <li class="{{ $isTeacherOnlineClassesActive ? 'active' : '' }}">
                <a href="{{ route('teacher.online-classes.index') }}">
                    <div class="pull-left"><i class="fas fa-video"></i><span
                            class="right-nav-text">{{ trans('main_trans.teacher_online_classes_entry') }}</span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>

            {{-- Quizzes module with nested links (quizzes + all questions) --}}
            <li class="{{ $isTeacherQuizMenuActive ? 'active' : '' }}">
                <a href="javascript:void(0);" data-toggle="collapse" data-target="#teacher-quizzes-menu">
                    <div class="pull-left"><i class="fas fa-file-alt"></i><span
                            class="right-nav-text">{{ trans('Quizzes_trans.title_page') }}</span></div>
                    <div class="pull-right"><i class="ti-plus"></i></div>
                    <div class="clearfix"></div>
                </a>
                <ul id="teacher-quizzes-menu" class="collapse {{ $isTeacherQuizMenuActive ? 'show' : '' }}"
                    data-parent="#sidebarnav">
                    <li>
                        {{-- Quizzes list --}}
                        <a href="{{ route('teacher.quizzes.index') }}">
                            {{ trans('main_trans.teacher_quizzes_list_entry') }}
                        </a>
                    </li>
                    <li>
                        {{-- Questions list across teacher quizzes --}}
                        <a href="{{ route('teacher.questions.index') }}">
                            {{ trans('main_trans.teacher_questions_list_entry') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Reports module --}}
            <li class="{{ $isTeacherReportsActive ? 'active' : '' }}">
                <a href="javascript:void(0);" data-toggle="collapse" data-target="#reports-menu">
                    <div class="pull-left"><i class="fas fa-chart-line"></i><span
                            class="right-nav-text">{{ trans('main_trans.teacher_reports_tab') }}</span></div>
                    <div class="pull-right"><i class="ti-plus"></i></div>
                    <div class="clearfix"></div>
                </a>
                <ul id="reports-menu" class="collapse {{ $isTeacherReportsActive ? 'show' : '' }}"
                    data-parent="#sidebarnav">
                    <li>
                        {{-- Attendance reports --}}
                        <a href="{{ route('teacher.reports.attendances') }}">
                            {{ trans('main_trans.teacher_reports_attendance_title') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Profile link --}}
            <li class="{{ $isTeacherProfileActive ? 'active' : '' }}">
                <a href="{{ route('teacher.profile') }}">
                    <div class="pull-left"><i class="fas fa-id-card"></i><span
                            class="right-nav-text">{{ trans('main_trans.profile_tab_title') }}</span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>

        </ul>
    </div>
</div>
