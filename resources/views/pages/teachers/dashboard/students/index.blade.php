@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.teacher_students_page_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', [
        'title' => trans('main_trans.teacher_students_page_title'),
    ])

    {{-- Teacher students listing page --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        {{-- Header actions --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h5 class="mb-2 mb-md-0">{{ trans('main_trans.welcome_user', ['name' => $teacher->name]) }}</h5>
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('teacher.dashboard') }}">
                                {{ trans('main_trans.teacher_students_back_dashboard') }}
                            </a>
                        </div>

                        {{-- Section filter --}}
                        <form method="GET" action="{{ route('teacher.students.index') }}" class="mb-3">
                            <div class="form-row align-items-end teacher-section-filter">
                                <div class="col-md-6">
                                    <label
                                        for="section_id">{{ trans('main_trans.teacher_students_filter_section') }}</label>
                                    <select class="custom-select" id="section_id" name="section_id">
                                        <option value="">{{ trans('main_trans.teacher_students_all_sections') }}
                                        </option>
                                        @foreach ($teacherSections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ (int) $selectedSectionId === (int) $section->id ? 'selected' : '' }}>
                                                {{ $section->name }} - {{ $section->grade?->Name ?? '-' }} -
                                                {{ $section->classroom?->name ?? '-' }} - {{ $section->students_count }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mt-2 mt-md-0">
                                    <label for="attendence_date">{{ trans('Attendance_trans.today_date') }}</label>
                                    <input type="date" class="form-control" id="attendence_date" name="attendence_date"
                                        value="{{ $attendanceDate }}">
                                </div>
                                <div class="col-md-2 mt-2 mt-md-0">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{ trans('Students_trans.submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Teacher attendance batch form --}}
                        <form method="POST" action="{{ route('teacher.students.attendance.store') }}">
                            @csrf
                            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                            <input type="hidden" name="attendence_date" value="{{ $attendanceDate }}">

                            @if ($isFutureAttendanceDate)
                                <div class="alert alert-warning">
                                    {{ trans('main_trans.teacher_attendance_future_date_not_allowed') }}
                                </div>
                            @endif

                            @if ($isLockedPastAttendanceDate)
                                <div class="alert alert-warning">
                                    {{ trans('main_trans.teacher_attendance_old_date_not_editable') }}
                                </div>
                            @endif

                            {{-- Students table --}}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="text-align: center;">
                                    <thead>
                                        <tr class="table-info text-danger">
                                            <th>#</th>
                                            <th>{{ trans('Students_trans.name') }}</th>
                                            <th>{{ trans('Students_trans.email') }}</th>
                                            <th>{{ trans('Students_trans.Grade') }}</th>
                                            <th>{{ trans('Students_trans.classrooms') }}</th>
                                            <th>{{ trans('Students_trans.section') }}</th>
                                            <th>{{ trans('main_trans.teacher_students_attendance_title') }}</th>
                                            <th>{{ trans('Students_trans.Processes') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($students as $student)
                                            @php
                                                $todayAttendance = $student->attendances->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $students->firstItem() + $loop->index }}
                                                    <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                                                </td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->grade?->Name }}</td>
                                                <td>{{ $student->classroom?->name }}</td>
                                                <td>{{ $student->section?->name }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <label class="mb-0 mr-2">
                                                            <input type="radio" name="attendences[{{ $student->id }}]"
                                                                value="presence"
                                                                {{ !$canEditAttendance ? 'disabled' : '' }}
                                                                {{ $todayAttendance?->attendence_status ? 'checked' : '' }}>
                                                            <span class="text-success">
                                                                {{ trans('Attendance_trans.presence') }}
                                                            </span>
                                                        </label>
                                                        <label class="mb-0">
                                                            <input type="radio" name="attendences[{{ $student->id }}]"
                                                                value="absent" {{ !$canEditAttendance ? 'disabled' : '' }}
                                                                {{ $todayAttendance && !$todayAttendance->attendence_status ? 'checked' : '' }}>
                                                            <span class="text-danger">
                                                                {{ trans('Attendance_trans.absence') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-outline-info"
                                                        href="{{ route('teacher.students.show', $student->id) }}">
                                                        {{ trans('main_trans.teacher_students_show_profile') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-muted">
                                                    {{ trans('main_trans.teacher_dashboard_no_students') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($students->count() > 0)
                                <div class="mt-3 text-right">
                                    <button type="submit" class="btn btn-success teacher-confirm-btn"
                                        {{ !$canEditAttendance ? 'disabled' : '' }}>
                                        {{ trans('main_trans.teacher_students_save_attendance') }}
                                    </button>
                                </div>
                            @endif
                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3 mb-0">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-3">
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .teacher-section-filter .custom-select {
            min-height: 44px;
            text-align: right;
            direction: rtl;
            padding-right: 0.75rem;
            line-height: 1.4;
        }

        .teacher-confirm-btn {
            min-width: 220px;
            min-height: 50px;
            font-size: 1.05rem;
            font-weight: 700;
            padding-inline: 1.5rem;
        }
    </style>
@endpush
