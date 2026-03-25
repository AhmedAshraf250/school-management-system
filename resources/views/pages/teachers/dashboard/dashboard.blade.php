@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.Main_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? $teacher->email ?? '-',
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', ['title' => trans('main_trans.teacher_dashboard_title')])

    <div class="teacher-view-scope">
        {{-- Welcome hero --}}
        <div class="row">
        <div class="col-12 mb-30">
            <div class="card bg-primary text-white border-0">
                <div class="card-body py-4 text-center text-md-left">
                    <h4 class="mb-2">{{ trans('main_trans.welcome_user', ['name' => $teacher->name]) }}</h4>
                    <p class="mb-0">{{ trans('main_trans.teacher_dashboard_latest_students') }}</p>
                </div>
            </div>
        </div>
    </div>

        {{-- Top quick stats --}}
        <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                    <h3 class="mb-1">{{ $teacherStudentsCount }}</h3>
                    <p class="mb-0">{{ trans('main_trans.teacher_dashboard_students_count') }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard fa-2x text-success mb-2"></i>
                    <h3 class="mb-1">{{ $teacherSectionsCount }}</h3>
                    <p class="mb-0">{{ trans('main_trans.teacher_dashboard_sections_count') }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <i class="fas fa-list fa-2x text-info mb-2"></i>
                    <p class="mb-3">{{ trans('main_trans.teacher_dashboard_students_entry') }}</p>
                    <a href="{{ route('teacher.students.index') }}" class="btn btn-sm btn-outline-info">
                        {{ trans('main_trans.teacher_dashboard_open_students') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                    <p class="mb-3">{{ trans('main_trans.dashboard_calendar_title') }}</p>
                    <a href="{{ route('teacher.calendar') }}" class="btn btn-sm btn-outline-warning">
                        {{ trans('main_trans.teacher_open_calendar') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

        {{-- Sections cards --}}
        <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.teacher_dashboard_sections_entry') }}</h5>

                    <div class="row">
                        @forelse($teacherSections as $section)
                            <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="mb-2 text-primary">{{ $section->name }}</h6>
                                    <p class="mb-1"><strong>{{ trans('Students_trans.Grade') }}:</strong>
                                        {{ $section->grade?->Name }}</p>
                                    <p class="mb-1"><strong>{{ trans('Students_trans.classrooms') }}:</strong>
                                        {{ $section->classroom?->name }}</p>
                                    <p class="mb-3"><strong>{{ trans('main_trans.teacher_dashboard_students_in_section') }}:</strong>
                                        {{ $section->students_count }}</p>
                                    <a href="{{ route('teacher.students.index', ['section_id' => $section->id]) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        {{ trans('main_trans.teacher_dashboard_students_entry') }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted mb-0">{{ trans('main_trans.teacher_dashboard_no_sections') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- Latest students compact list --}}
        <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.teacher_dashboard_latest_students') }}</h5>

                    <div class="list-group">
                        @forelse($latestStudents as $student)
                            <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h6 class="mb-1">{{ $student->name }}</h6>
                                    <small class="text-muted">
                                        {{ $student->grade?->Name }} / {{ $student->classroom?->name }} /
                                        {{ $student->section?->name }}
                                    </small>
                                </div>
                                <a href="{{ route('teacher.students.show', $student->id) }}"
                                    class="btn btn-sm btn-outline-info mt-2 mt-md-0">
                                    {{ trans('main_trans.teacher_students_show_profile') }}
                                </a>
                            </div>
                        @empty
                            <p class="text-muted mb-0">{{ trans('main_trans.teacher_dashboard_no_students') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
