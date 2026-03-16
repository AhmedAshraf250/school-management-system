@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.teacher_students_page_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? $teacher->email ?? '-',
    ])

    {{-- Teacher students listing page --}}
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
                                <label for="section_id">{{ trans('main_trans.teacher_students_filter_section') }}</label>
                                <select class="custom-select" id="section_id" name="section_id">
                                    <option value="">{{ trans('main_trans.teacher_students_all_sections') }}</option>
                                    @foreach ($teacherSections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ (int) $selectedSectionId === (int) $section->id ? 'selected' : '' }}>
                                            {{ $section->name }} ({{ $section->students_count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mt-2 mt-md-0">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ trans('Students_trans.submit') }}
                                </button>
                            </div>
                        </div>
                    </form>

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
                                    <th>{{ trans('Students_trans.created_at') }}</th>
                                    <th>{{ trans('Students_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr>
                                        <td>{{ $students->firstItem() + $loop->index }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->grade?->Name }}</td>
                                        <td>{{ $student->classroom?->name }}</td>
                                        <td>{{ $student->section?->name }}</td>
                                        <td>{{ $student->created_at }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-info"
                                                href="{{ route('teacher.students.show', $student->id) }}">
                                                {{ trans('main_trans.teacher_students_show_profile') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-muted">{{ trans('main_trans.teacher_dashboard_no_students') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $students->links() }}
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
    </style>
@endpush
