@php
    $isDashboardActive = request()->routeIs('dashboard');
    $isGradesActive = request()->routeIs('grades.*');
    $isClassroomsActive = request()->routeIs('classrooms.*');
    $isSectionsActive = request()->routeIs('sections.*');
    $isStudentsInfoActive = request()->routeIs('students.*');
    $isStudentsPromotionsActive = request()->routeIs('promotions.*');
    $isStudentsGraduatesActive = request()->routeIs('graduates.*');
    $isStudentsActive = $isStudentsInfoActive || $isStudentsPromotionsActive || $isStudentsGraduatesActive;
    $isTeachersActive = request()->routeIs('teachers.*');
    $isGuardiansActive = request()->routeIs('guardians');
    $isAttendanceActive = request()->routeIs('attendances.*');
    $isSubjectsActive = request()->routeIs('subjects.*');
    $isQuizzesActive = request()->routeIs('quizzes.*');
    $isQuestionsActive = request()->routeIs('questions.*');
    $isExamsActive = $isSubjectsActive || $isQuizzesActive || $isQuestionsActive;
    $isAccountsActive = request()->routeIs([
        'fees.*',
        'fee-invoices.*',
        'receipts.*',
        'processing-fees.*',
        'student-payments.*',
    ]);
@endphp

<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar start-->
        <div class="side-menu-fixed">
            <div class="scrollbar side-menu-bg" style="overflow: scroll">
                <ul class="nav navbar-nav side-menu" id="sidebarnav">
                    <!-- menu item Dashboard-->
                    <li class="{{ $isDashboardActive ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <div class="pull-left"><i class="ti-home"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Dashboard') }}</span>
                            </div>
                            <div class="clearfix"></div>
                        </a>
                    </li>
                    <!-- menu title -->
                    <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title">{{ trans('main_trans.Programname') }}
                    </li>

                    <!-- Grades-->
                    <li class="{{ $isGradesActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Grades-menu">
                            <div class="pull-left"><i class="fas fa-school"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Grades') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Grades-menu" class="collapse {{ $isGradesActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ request()->routeIs('grades.index') ? 'active' : '' }}">
                                <a href="{{ route('grades.index') }}">{{ trans('main_trans.Grades_list') }}</a>
                            </li>

                        </ul>
                    </li>
                    <!-- classes-->
                    <li class="{{ $isClassroomsActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#classes-menu">
                            <div class="pull-left"><i class="fa fa-building"></i><span
                                    class="right-nav-text">{{ trans('main_trans.classes') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="classes-menu" class="collapse {{ $isClassroomsActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ request()->routeIs('classrooms.index') ? 'active' : '' }}">
                                <a href="{{ route('classrooms.index') }}">{{ trans('main_trans.List_classes') }}</a>
                            </li>
                        </ul>
                    </li>


                    <!-- sections-->
                    <li class="{{ $isSectionsActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#sections-menu">
                            <div class="pull-left"><i class="fas fa-chalkboard"></i><span
                                    class="right-nav-text">{{ trans('main_trans.sections') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="sections-menu" class="collapse {{ $isSectionsActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ request()->routeIs('sections.index') ? 'active' : '' }}">
                                <a href="{{ route('sections.index') }}">{{ trans('main_trans.List_sections') }}</a>
                            </li>
                        </ul>
                    </li>


                    <!-- students-->
                    <li class="{{ $isStudentsActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#students-menu"><i
                                class="fas fa-user-graduate"></i>{{ trans('main_trans.students') }}<div
                                class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="students-menu" class="collapse {{ $isStudentsActive ? 'show' : '' }}">
                            <li class="{{ $isStudentsInfoActive ? 'active' : '' }}">
                                <a href="javascript:void(0);" data-toggle="collapse"
                                    data-target="#Student_information">{{ trans('main_trans.Student_information') }}
                                    <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                                    <div class="clearfix"></div>
                                </a>
                                <ul id="Student_information"
                                    class="collapse {{ $isStudentsInfoActive ? 'show' : '' }}">
                                    <li class="{{ request()->routeIs('students.create') ? 'active' : '' }}"> <a
                                            href="{{ route('students.create') }}">{{ trans('main_trans.add_student') }}</a>
                                    </li>
                                    <li
                                        class="{{ request()->routeIs('students.index', 'students.show', 'students.edit') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('students.index') }}">{{ trans('main_trans.list_students') }}</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ $isStudentsPromotionsActive ? 'active' : '' }}">
                                <a href="javascript:void(0);" data-toggle="collapse"
                                    data-target="#Students_upgrade">{{ trans('main_trans.Students_Promotions') }}<div
                                        class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                                    <div class="clearfix"></div>
                                </a>
                                <ul id="Students_upgrade"
                                    class="collapse {{ $isStudentsPromotionsActive ? 'show' : '' }}">
                                    <li class="{{ request()->routeIs('promotions.create') ? 'active' : '' }}"> <a
                                            href="{{ route('promotions.create') }}">{{ trans('main_trans.add_Promotion') }}</a>
                                    </li>
                                    <li class="{{ request()->routeIs('promotions.index') ? 'active' : '' }}"> <a
                                            href="{{ route('promotions.index') }}">{{ trans('main_trans.list_Promotions') }}</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ $isStudentsGraduatesActive ? 'active' : '' }}">
                                <a href="javascript:void(0);" data-toggle="collapse"
                                    data-target="#Graduate-students">{{ trans('main_trans.Graduate_students') }}<div
                                        class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                                    <div class="clearfix"></div>
                                </a>
                                <ul id="Graduate-students"
                                    class="collapse {{ $isStudentsGraduatesActive ? 'show' : '' }}">
                                    <li class="{{ request()->routeIs('graduates.index') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('graduates.index') }}">{{ trans('main_trans.add_Graduate') }}</a>
                                    </li>
                                    <li class="{{ request()->routeIs('graduates.index') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('graduates.index') }}">{{ trans('main_trans.list_Graduate') }}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>


                    <!-- Teachers-->
                    <li class="{{ $isTeachersActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Teachers-menu">
                            <div class="pull-left"><i class="fas fa-chalkboard-teacher"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Teachers') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Teachers-menu" class="collapse {{ $isTeachersActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ $isTeachersActive ? 'active' : '' }}"> <a
                                    href="{{ route('teachers.index') }}">{{ trans('main_trans.List_Teachers') }}</a>
                            </li>
                        </ul>
                    </li>


                    <!-- Parents-->
                    <li class="{{ $isGuardiansActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Parents-menu">
                            <div class="pull-left"><i class="fas fa-user-tie"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Parents') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Parents-menu" class="collapse {{ $isGuardiansActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ $isGuardiansActive ? 'active' : '' }}">
                                <a href="{{ route('guardians') }}">{{ trans('main_trans.List_Parents') }}</a>
                            </li>
                        </ul>
                    </li>

                    <!-- Fees-->
                    <li class="{{ $isAccountsActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Accounts-menu">
                            <div class="pull-left"><i class="fas fa-money-bill-wave-alt"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Accounts') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Accounts-menu" class="collapse {{ $isAccountsActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ request()->routeIs('fees.index') ? 'active' : '' }}"> <a
                                    href="{{ route('fees.index') }}">{{ trans('fees_trans.tuition_fees') }}</a>
                            </li>
                            <li class="{{ request()->routeIs('fee-invoices.index') ? 'active' : '' }}"> <a
                                    href="{{ route('fee-invoices.index') }}">{{ trans('fees_trans.invoices') }}</a>
                            </li>
                            <li class="{{ request()->routeIs('receipts.index') ? 'active' : '' }}"> <a
                                    href="{{ route('receipts.index') }}">{{ trans('fees_trans.receipts') }}</a>
                            </li>
                            <li class="{{ request()->routeIs('processing-fees.index') ? 'active' : '' }}"> <a
                                    href="{{ route('processing-fees.index') }}">{{ trans('fees_trans.fee_exclusion') }}</a>
                            </li>
                            <li class="{{ request()->routeIs('student-payments.index') ? 'active' : '' }}"> <a
                                    href="{{ route('student-payments.index') }}">{{ trans('fees_trans.payment_voucher') }}</a>
                            </li>

                        </ul>
                    </li>

                    <!-- Attendance-->
                    <li class="{{ $isAttendanceActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Attendance-icon">
                            <div class="pull-left"><i class="fas fa-calendar-alt"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Attendance') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Attendance-icon" class="collapse {{ $isAttendanceActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ $isAttendanceActive ? 'active' : '' }}"> <a
                                    href="{{ route('attendances.index') }}">{{ trans('Attendance_trans.students_list') }}</a>
                            </li>
                        </ul>
                    </li>

                    <!-- Subjects-->
                    <li class="{{ $isSubjectsActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Subject-icon">
                            <div class="pull-left"><i class="fas fa-book-open"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Subjects') }}</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Subject-icon" class="collapse {{ $isSubjectsActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ $isSubjectsActive ? 'active' : '' }}"> <a
                                    href="{{ route('subjects.index') }}">{{ trans('Subjects_trans.list') }}</a> </li>
                        </ul>
                    </li>

                    <!-- Quizzes-->
                    <li class="{{ $isExamsActive ? 'active' : '' }}">
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Quizzes-icon">
                            <div class="pull-left"><i class="fas fa-book-open"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Exams') }}</span></div>
                            <div class="pull-right"><i class="ti-plus"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Quizzes-icon" class="collapse {{ $isExamsActive ? 'show' : '' }}"
                            data-parent="#sidebarnav">
                            <li class="{{ $isQuizzesActive ? 'active' : '' }}"> <a
                                    href="{{ route('quizzes.index') }}">{{ trans('Quizzes_trans.list') }}</a> </li>
                            <li class="{{ $isQuestionsActive ? 'active' : '' }}"> <a
                                    href="{{ route('questions.index') }}">{{ trans('Questions_trans.list') }}</a> </li>
                        </ul>
                    </li>


                    <!-- library-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#library-icon">
                            <div class="pull-left"><i class="fas fa-book"></i><span
                                    class="right-nav-text">{{ trans('main_trans.library') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="library-icon" class="collapse" data-parent="#sidebarnav">
                            <li> <a href="fontawesome-icon.html">font Awesome</a> </li>
                            <li> <a href="themify-icons.html">Themify icons</a> </li>
                            <li> <a href="weather-icon.html">Weather icons</a> </li>
                        </ul>
                    </li>


                    <!-- Onlinec lasses-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Onlineclasses-icon">
                            <div class="pull-left"><i class="fas fa-video"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Onlineclasses') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Onlineclasses-icon" class="collapse" data-parent="#sidebarnav">
                            <li> <a href="fontawesome-icon.html">font Awesome</a> </li>
                            <li> <a href="themify-icons.html">Themify icons</a> </li>
                            <li> <a href="weather-icon.html">Weather icons</a> </li>
                        </ul>
                    </li>


                    <!-- Settings-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Settings-icon">
                            <div class="pull-left"><i class="fas fa-cogs"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Settings') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Settings-icon" class="collapse" data-parent="#sidebarnav">
                            <li> <a href="fontawesome-icon.html">font Awesome</a> </li>
                            <li> <a href="themify-icons.html">Themify icons</a> </li>
                            <li> <a href="weather-icon.html">Weather icons</a> </li>
                        </ul>
                    </li>


                    <!-- Users-->
                    <li>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#Users-icon">
                            <div class="pull-left"><i class="fas fa-users"></i><span
                                    class="right-nav-text">{{ trans('main_trans.Users') }}</span></div>
                            <div class="pull-right"><i class="ti-plus toggle-icon"></i></div>
                            <div class="clearfix"></div>
                        </a>
                        <ul id="Users-icon" class="collapse" data-parent="#sidebarnav">
                            <li> <a href="fontawesome-icon.html">font Awesome</a> </li>
                            <li> <a href="themify-icons.html">Themify icons</a> </li>
                            <li> <a href="weather-icon.html">Weather icons</a> </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>

        <!-- Left Sidebar End-->

        <script>
            $(function() {
                function syncSidebarIcon(collapseId, isOpen) {
                    const trigger = $('[data-target="#' + collapseId + '"]');
                    const icon = trigger.find('.toggle-icon').first();

                    if (!icon.length) {
                        return;
                    }

                    icon.toggleClass('ti-plus', !isOpen).toggleClass('ti-minus', isOpen);
                }

                $('.side-menu .collapse[id]').each(function() {
                    syncSidebarIcon(this.id, $(this).hasClass('show'));
                });

                $('.side-menu .collapse[id]').on('show.bs.collapse', function() {
                    syncSidebarIcon(this.id, true);
                });

                $('.side-menu .collapse[id]').on('hide.bs.collapse', function() {
                    syncSidebarIcon(this.id, false);
                });
            });
        </script>
