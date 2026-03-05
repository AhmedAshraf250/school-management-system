@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('Attendance_trans.title') }}
@stop
@endsection
@section('page-header')
    {{-- Page title (breadcrumb area) --}}
@section('PageTitle')
    {{ trans('Attendance_trans.title') }}
@stop
@endsection
@section('content')
    {{-- Validation errors block --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Session status block --}}
    @if (session('status'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                <li>{{ session('status') }}</li>
            </ul>
        </div>
    @endif

    {{-- Current date indicator --}}
    <h5 style="font-family: 'Cairo', sans-serif;color: red">
        {{ trans('Attendance_trans.today_date') }} : {{ now()->toDateString() }}
    </h5>

    {{-- Warn if section has no linked teacher --}}
    @if (blank($teacherId))
        <div class="alert alert-warning">{{ trans('Attendance_trans.section_without_teacher') }}</div>
    @endif

    {{-- Attendance form --}}
    <form method="post" action="{{ route('attendances.store') }}">
        @csrf

        {{-- Context payload for saving attendance --}}
        <input type="hidden" name="section_id" value="{{ $section->id }}">
        <input type="hidden" name="teacher_id" value="{{ $teacherId }}">
        <input type="hidden" name="attendence_date" value="{{ now()->toDateString() }}">

        {{-- Students attendance table --}}
        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
            style="text-align: center">
            <thead>
                <tr>
                    <th class="alert-success">#</th>
                    <th class="alert-success">{{ trans('Students_trans.name') }}</th>
                    <th class="alert-success">{{ trans('Students_trans.email') }}</th>
                    <th class="alert-success">{{ trans('Students_trans.gender') }}</th>
                    <th class="alert-success">{{ trans('Students_trans.Grade') }}</th>
                    <th class="alert-success">{{ trans('Students_trans.classrooms') }}</th>
                    <th class="alert-success">{{ trans('Students_trans.section') }}</th>
                    <th class="alert-success">{{ trans('Attendance_trans.student_reference') }}</th>
                    <th class="alert-success">{{ trans('Students_trans.Processes') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    {{-- Resolve current-day attendance status for each student --}}
                    @php
                        $todayAttendance = $student->todayAttendance;
                        $isAttendanceAlreadySaved = filled($todayAttendance);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->gender?->name }}</td>
                        <td>{{ $student->grade?->Name }}</td>
                        <td>{{ $student->classroom?->name }}</td>
                        <td>{{ $student->section?->name }}</td>
                        <td>
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm" target="_blank">
                                {{ trans('Attendance_trans.show_student') }}
                            </a>
                        </td>
                        <td>
                            {{-- Presence option --}}
                            <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                <input name="attendences[{{ $student->id }}]"
                                    {{ $isAttendanceAlreadySaved ? 'disabled' : '' }}
                                    {{ $todayAttendance?->attendence_status ? 'checked' : '' }} class="leading-tight"
                                    type="radio" value="presence">
                                <span class="text-success">{{ trans('Attendance_trans.presence') }}</span>
                            </label>

                            {{-- Absence option --}}
                            <label class="ml-4 block text-gray-500 font-semibold">
                                <input name="attendences[{{ $student->id }}]"
                                    {{ $isAttendanceAlreadySaved ? 'disabled' : '' }}
                                    {{ filled($todayAttendance) && ! $todayAttendance->attendence_status ? 'checked' : '' }}
                                    class="leading-tight" type="radio" value="absent">
                                <span class="text-danger">{{ trans('Attendance_trans.absence') }}</span>
                            </label>

                            {{-- Keep student ids for bulk save --}}
                            <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Submit attendance --}}
        <p>
            <button class="btn btn-success" type="submit" {{ blank($teacherId) ? 'disabled' : '' }}>
                {{ trans('Students_trans.submit') }}
            </button>
        </p>
    </form>
    <br>
@endsection
@section('js')
    @toastr_js
    @toastr_render
@endsection
