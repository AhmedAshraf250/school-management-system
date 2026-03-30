@extends('layouts.user-portal')

@section('title')
    {{ trans('Quizzes_trans.teacher_results_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', ['title' => trans('Quizzes_trans.teacher_results_title')])

    {{-- Teacher results listing with filters --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        <form method="GET" action="{{ route('teacher.quizzes.results.index') }}" class="mb-3">
                            <div class="form-row">
                                <div class="col-md-4 mb-2">
                                    <label for="academic_year">{{ trans('Quizzes_trans.academic_year') }}</label>
                                    <select id="academic_year" name="academic_year" class="custom-select"
                                        onchange="this.form.submit()">
                                        <option value="">{{ trans('main_trans.filter_all') }}</option>
                                        @foreach ($academicYearOptions as $academicYear)
                                            <option value="{{ $academicYear }}" @selected($selectedAcademicYear === $academicYear)>
                                                {{ $academicYear }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="quiz_id">{{ trans('Quizzes_trans.name') }}</label>
                                    <select id="quiz_id" name="quiz_id" class="custom-select" onchange="this.form.submit()">
                                        <option value="0">{{ trans('main_trans.filter_all') }}</option>
                                        @foreach ($teacherQuizzes as $quiz)
                                            <option value="{{ $quiz->id }}" @selected($selectedQuizId === (int) $quiz->id)>
                                                {{ $quiz->name }} ({{ $quiz->subject?->name ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2 d-flex align-items-end">
                                    <a href="{{ route('teacher.quizzes.results.index') }}"
                                        class="btn btn-outline-secondary btn-block">
                                        {{ trans('Quizzes_trans.teacher_reset_filters') }}
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="text-align: center;">
                                <thead>
                                    <tr class="table-info text-danger">
                                        <th>#</th>
                                        <th>{{ trans('Students_trans.name') }}</th>
                                        <th>{{ trans('Quizzes_trans.name') }}</th>
                                        <th>{{ trans('Quizzes_trans.subject') }}</th>
                                        <th>{{ trans('Quizzes_trans.academic_year') }}</th>
                                        <th>{{ trans('Quizzes_trans.status') }}</th>
                                        <th>{{ trans('Quizzes_trans.student_score') }}</th>
                                        <th>{{ trans('Quizzes_trans.processes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attempts as $attempt)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attempt->student?->name ?? '-' }}</td>
                                            <td>{{ $attempt->quiz?->name ?? '-' }}</td>
                                            <td>{{ $attempt->quiz?->subject?->name ?? '-' }}</td>
                                            <td>{{ $attempt->quiz?->academic_year ?? '-' }}</td>
                                            <td>
                                                @if ($attempt->status === \App\Models\QuizAttempt::STATUS_BLOCKED)
                                                    <span class="badge badge-danger">{{ trans('Quizzes_trans.student_blocked') }}</span>
                                                @elseif ($attempt->status === \App\Models\QuizAttempt::STATUS_SUBMITTED)
                                                    <span
                                                        class="badge badge-success">{{ trans('Quizzes_trans.student_submitted') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-warning">{{ trans('Quizzes_trans.student_in_progress') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $attempt->total_score }} / {{ $attempt->max_score }}</td>
                                            <td>
                                                @if ($attempt->status === \App\Models\QuizAttempt::STATUS_BLOCKED)
                                                    <form method="POST"
                                                        action="{{ route('teacher.quizzes.results.unlock', $attempt->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning">
                                                            {{ trans('Quizzes_trans.teacher_unlock_attempt') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-muted">{{ trans('main_trans.teacher_reports_no_data') }}</td>
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
