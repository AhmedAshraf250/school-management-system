@extends('layouts.user-portal')

@section('title')
    {{ trans('Questions_trans.list') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', [
        'title' => trans('Questions_trans.list'),
        'subtitle' => $quiz->name,
    ])

    {{-- Teacher quiz questions listing page --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('teacher.quizzes.questions.create', $quiz->id) }}"
                                    class="btn btn-success btn-sm">
                                    {{ trans('Questions_trans.add_new') }}
                                </a>
                                <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-outline-secondary btn-sm">
                                    {{ trans('main_trans.back_action') }}
                                </a>
                            </div>
                        </div>

                        {{-- Quiz metadata summary --}}
                        <div class="row mb-3">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <span class="badge badge-pill badge-primary p-2">
                                    {{ trans('Quizzes_trans.grade') }}: {{ $quiz->grade?->Name ?? '-' }}
                                </span>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <span class="badge badge-pill badge-info p-2">
                                    {{ trans('Quizzes_trans.classroom') }}: {{ $quiz->classroom?->name ?? '-' }}
                                </span>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <span class="badge badge-pill badge-warning p-2">
                                    {{ trans('Quizzes_trans.section') }}: {{ $quiz->section?->name ?? '-' }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span class="badge badge-pill badge-dark p-2">
                                    {{ trans('Quizzes_trans.academic_year') }}: {{ $quiz->academic_year ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Questions table --}}
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="text-align: center;">
                                <thead>
                                    <tr class="table-info text-danger">
                                        <th>#</th>
                                        <th>{{ trans('Questions_trans.title') }}</th>
                                        <th>{{ trans('Questions_trans.answers') }}</th>
                                        <th>{{ trans('Questions_trans.right_answer') }}</th>
                                        <th>{{ trans('Questions_trans.score') }}</th>
                                        <th>{{ trans('Questions_trans.processes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($questions as $question)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $question->title }}</td>
                                            <td>{{ $question->answers }}</td>
                                            <td>{{ $question->right_answer }}</td>
                                            <td>{{ $question->score }}</td>
                                            <td>
                                                <div class="teacher-action-group d-flex justify-content-center flex-wrap">
                                                    <a href="{{ route('teacher.quizzes.questions.edit', [$quiz->id, $question->id]) }}"
                                                        class="btn btn-info btn-sm"
                                                        title="{{ trans('Questions_trans.edit_title') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @include('pages.teachers.dashboard.quizzes.questions.partials.destroy')
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-muted">
                                                {{ trans('main_trans.teacher_reports_no_data') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .teacher-action-group>* {
            margin: 0.2rem;
        }
    </style>
@endpush
