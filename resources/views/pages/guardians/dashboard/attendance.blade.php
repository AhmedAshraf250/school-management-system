@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.guardian_attendance_tab') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_guardian'),
        'identity' => $guardian?->father_name ?? ($guardian?->email ?? '-'),
    ])

    {{-- Guardian dashboard tabs --}}
    @include('pages.guardians.dashboard.partials.tabs')

    {{-- Attendance filters --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.guardian_attendance_tab') }}</h5>

                    <form method="GET" action="{{ route('guardian.dashboard.attendance') }}">
                        <div class="row align-items-end">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label
                                        for="guardian_attendance_student_id">{{ trans('main_trans.guardian_select_child') }}</label>
                                    <select id="guardian_attendance_student_id" name="student_id" class="custom-select">
                                        @forelse($students as $student)
                                            <option value="{{ $student->id }}" @selected($selectedStudent?->id === $student->id)>
                                                {{ $student->name }}</option>
                                        @empty
                                            <option value="">{{ trans('main_trans.guardian_no_children') }}</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label
                                        for="guardian_attendance_date">{{ trans('main_trans.teacher_reports_date_from') }}</label>
                                    <input id="guardian_attendance_date" type="date" name="date" class="form-control"
                                        value="{{ $selectedDate }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-primary btn-block" type="submit">
                                    {{ trans('main_trans.dashboard_view_data') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance result card --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.guardian_daily_attendance_status') }}</h5>

                    @if ($selectedStudent)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <tbody>
                                    <tr>
                                        <th>{{ trans('main_trans.profile_name_label') }}</th>
                                        <td>{{ $selectedStudent->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('main_trans.teacher_reports_date_from') }}</th>
                                        <td>{{ $selectedDate }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('main_trans.teacher_students_attendance_title') }}</th>
                                        <td>
                                            @if ($attendanceStatus === 'unrecorded')
                                                <span
                                                    class="badge badge-warning">{{ trans('main_trans.guardian_not_recorded_yet') }}</span>
                                            @elseif($attendanceStatus === 'present')
                                                <span
                                                    class="badge badge-success">{{ trans('Attendance_trans.presence') }}</span>
                                            @else
                                                <span
                                                    class="badge badge-danger">{{ trans('Attendance_trans.absence') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            {{ trans('main_trans.guardian_no_children') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
