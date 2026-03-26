@extends('layouts.user-portal')

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', [
        'title' => trans('main_trans.teacher_reports_attendance_title'),
    ])

    {{-- Teacher attendance report page --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('teacher.dashboard') }}">
                                {{ trans('main_trans.teacher_students_back_dashboard') }}
                            </a>
                        </div>

                        <form method="GET" action="{{ route('teacher.reports.attendances') }}" class="mb-4">
                            <div class="form-row align-items-end">
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label for="section_id">{{ trans('main_trans.teacher_reports_section_label') }}</label>
                                    <select name="section_id" id="section_id" class="custom-select"
                                        onchange="this.form.submit()">
                                        @foreach ($teacherSections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ $selectedSectionId === (int) $section->id ? 'selected' : '' }}>
                                                {{ $section->name }} - {{ $section->grade?->Name ?? '-' }} -
                                                {{ $section->classroom?->name ?? '-' }} - {{ $section->students_count }}
                                                {{ trans('main_trans.teacher_reports_students_count_suffix') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label for="student_id">{{ trans('main_trans.teacher_reports_student_label') }}</label>
                                    <select name="student_id" id="student_id" class="custom-select">
                                        <option value="">{{ trans('main_trans.teacher_reports_all_students') }}
                                        </option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}"
                                                {{ $selectedStudentId === (int) $student->id ? 'selected' : '' }}>
                                                {{ $student->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label for="search">{{ trans('main_trans.teacher_reports_search_label') }}</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $searchTerm }}"
                                        placeholder="{{ trans('main_trans.teacher_reports_search_placeholder') }}">
                                </div>

                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label for="date_from">{{ trans('main_trans.teacher_reports_date_from') }}</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control"
                                        value="{{ $dateFrom }}" required>
                                </div>

                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label for="date_to">{{ trans('main_trans.teacher_reports_date_to') }}</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control"
                                        value="{{ $dateTo }}" required>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{ trans('Students_trans.submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="alert alert-success mb-0">
                                    {{ trans('Attendance_trans.presence') }}: <strong>{{ $presentCount }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-danger mb-0">
                                    {{ trans('Attendance_trans.absence') }}: <strong>{{ $absentCount }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="text-align: center;">
                                <thead>
                                    <tr class="table-info text-danger">
                                        <th>#</th>
                                        <th>{{ trans('Students_trans.name') }}</th>
                                        <th>{{ trans('Students_trans.Grade') }}</th>
                                        <th>{{ trans('Students_trans.classrooms') }}</th>
                                        <th>{{ trans('Students_trans.section') }}</th>
                                        <th>{{ trans('Attendance_trans.today_date') }}</th>
                                        <th>{{ trans('main_trans.teacher_students_attendance_title') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendances->firstItem() + $loop->index }}</td>
                                            <td>{{ $attendance->student?->name ?? '-' }}</td>
                                            <td>{{ $attendance->student?->grade?->Name ?? '-' }}</td>
                                            <td>{{ $attendance->student?->classroom?->name ?? '-' }}</td>
                                            <td>{{ $attendance->section?->name ?? '-' }}</td>
                                            <td>{{ optional($attendance->attendence_date)->format('d/m/Y') }}</td>
                                            <td>
                                                @if ($attendance->attendence_status)
                                                    <span
                                                        class="badge badge-success">{{ trans('Attendance_trans.presence') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-danger">{{ trans('Attendance_trans.absence') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-muted">
                                                {{ trans('main_trans.teacher_reports_no_data') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $attendances->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
