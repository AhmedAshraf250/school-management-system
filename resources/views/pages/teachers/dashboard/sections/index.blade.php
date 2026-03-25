@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.teacher_sections_page_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', [
        'title' => trans('main_trans.teacher_sections_page_title'),
    ])

    {{-- Teacher sections listing page --}}
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

                        <div class="row">
                            @forelse ($teacherSections as $section)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="text-primary mb-2">{{ $section->name }}</h6>
                                        <p class="mb-1"><strong>{{ trans('Students_trans.Grade') }}:</strong>
                                            {{ $section->grade?->Name ?? '-' }}</p>
                                        <p class="mb-1"><strong>{{ trans('Students_trans.classrooms') }}:</strong>
                                            {{ $section->classroom?->name ?? '-' }}</p>
                                        <p class="mb-3">
                                            <strong>{{ trans('main_trans.teacher_dashboard_students_in_section') }}:</strong>
                                            {{ $section->students_count }}
                                        </p>
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
    </div>
@endsection
