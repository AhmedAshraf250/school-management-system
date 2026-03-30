@extends('layouts.user-portal')

@section('title')
    {{ trans('Quizzes_trans.title_page') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', ['title' => trans('Quizzes_trans.list')])

    {{-- Teacher quizzes listing page --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-success btn-sm">
                                    {{ trans('Quizzes_trans.add_new') }}
                                </a>
                                <a href="{{ route('teacher.quizzes.results.index') }}" class="btn btn-outline-primary btn-sm">
                                    {{ trans('Quizzes_trans.teacher_results_title') }}
                                </a>
                                <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                                    {{ trans('main_trans.teacher_students_back_dashboard') }}
                                </a>
                            </div>
                            <form method="GET" action="{{ route('teacher.quizzes.index') }}"
                                class="d-flex flex-wrap align-items-center gap-2">
                                <label for="academic_year" class="mb-0 text-muted">{{ trans('Quizzes_trans.academic_year') }}</label>
                                <select id="academic_year" name="academic_year" class="custom-select custom-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="">{{ trans('main_trans.filter_all') }}</option>
                                    @foreach ($academicYearOptions as $academicYear)
                                        <option value="{{ $academicYear }}" @selected($selectedAcademicYear === $academicYear)>
                                            {{ $academicYear }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        {{-- Quizzes table --}}
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="text-align: center;">
                                <thead>
                                    <tr class="table-info text-danger">
                                        <th>#</th>
                                        <th>{{ trans('Quizzes_trans.name') }}</th>
                                        <th>{{ trans('Quizzes_trans.subject') }}</th>
                                        <th>{{ trans('Quizzes_trans.grade') }}</th>
                                        <th>{{ trans('Quizzes_trans.classroom') }}</th>
                                        <th>{{ trans('Quizzes_trans.section') }}</th>
                                        <th>{{ trans('Quizzes_trans.academic_year') }}</th>
                                        <th>{{ trans('Questions_trans.list') }}</th>
                                        <th>{{ trans('Quizzes_trans.status') }}</th>
                                        <th>{{ trans('Quizzes_trans.processes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($quizzes as $quiz)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $quiz->name }}</td>
                                            <td>{{ $quiz->subject?->name ?? '-' }}</td>
                                            <td>{{ $quiz->grade?->Name ?? '-' }}</td>
                                            <td>{{ $quiz->classroom?->name ?? '-' }}</td>
                                            <td>{{ $quiz->section?->name ?? '-' }}</td>
                                            <td>{{ $quiz->academic_year ?? '-' }}</td>
                                            <td>{{ $quiz->questions_count }}</td>
                                            <td>
                                                @if ($quiz->status === \App\Models\Quiz::STATUS_PUBLISHED)
                                                    <span
                                                        class="badge badge-success">{{ trans('Quizzes_trans.published') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-secondary">{{ trans('Quizzes_trans.draft') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="teacher-action-group d-flex justify-content-center flex-wrap">
                                                    <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}"
                                                        class="btn btn-info btn-sm"
                                                        title="{{ trans('Quizzes_trans.edit_title') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('teacher.quizzes.questions.index', $quiz->id) }}"
                                                        class="btn btn-warning btn-sm"
                                                        title="{{ trans('Questions_trans.list') }}">
                                                        <i class="fa fa-binoculars"></i>
                                                    </a>

                                                    @if ($quiz->status !== \App\Models\Quiz::STATUS_PUBLISHED)
                                                        <form method="POST"
                                                            action="{{ route('teacher.quizzes.publish', $quiz->id) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary btn-sm"
                                                                title="{{ trans('Quizzes_trans.publish') }}">
                                                                {{ trans('Quizzes_trans.publish') }}
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form method="POST"
                                                        action="{{ route('teacher.quizzes.destroy', $quiz->id) }}"
                                                        onsubmit="return confirm('{{ trans('Quizzes_trans.delete_warning') }} {{ $quiz->name }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            title="{{ trans('Quizzes_trans.delete_title') }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-muted">
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
