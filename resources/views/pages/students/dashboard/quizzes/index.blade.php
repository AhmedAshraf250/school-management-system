@extends('layouts.user-portal')

@section('title')
    {{ trans('Quizzes_trans.title_page') }}
@stop

@section('content')
    {{-- Dashboard identity header for student portal --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_student'),
        'identity' => $student->name ?? $student->email ?? '-',
    ])

    {{-- Student quizzes listing with academic year filter --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <h5 class="mb-0">{{ trans('Quizzes_trans.list') }}</h5>

                        <form method="GET" action="{{ route('student.quizzes') }}" class="d-flex flex-wrap align-items-center gap-2">
                            <label for="academic_year" class="mb-0 text-muted">{{ trans('Quizzes_trans.academic_year') }}</label>
                            <select id="academic_year" name="academic_year" class="custom-select custom-select-sm" onchange="this.form.submit()">
                                <option value="">{{ trans('main_trans.filter_all') }}</option>
                                @foreach ($academicYearOptions as $academicYear)
                                    <option value="{{ $academicYear }}" @selected($selectedAcademicYear === $academicYear)>
                                        {{ $academicYear }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="text-align: center;">
                            <thead>
                                <tr class="table-info text-danger">
                                    <th>#</th>
                                    <th>{{ trans('Quizzes_trans.name') }}</th>
                                    <th>{{ trans('Quizzes_trans.subject') }}</th>
                                    <th>{{ trans('Quizzes_trans.teacher') }}</th>
                                    <th>{{ trans('Questions_trans.list') }}</th>
                                    <th>{{ trans('Quizzes_trans.status') }}</th>
                                    <th>{{ trans('Quizzes_trans.processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($quizzes as $quiz)
                                    @php
                                        $attempt = $attemptsByQuizId->get($quiz->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $quiz->name }}</td>
                                        <td>{{ $quiz->subject?->name ?? '-' }}</td>
                                        <td>{{ $quiz->teacher?->name ?? '-' }}</td>
                                        <td>{{ $quiz->questions_count }}</td>
                                        <td>
                                            @if (! $attempt)
                                                <span class="badge badge-secondary">{{ trans('Quizzes_trans.student_not_started') }}</span>
                                            @elseif ($attempt->status === \App\Models\QuizAttempt::STATUS_IN_PROGRESS)
                                                <span class="badge badge-warning">{{ trans('Quizzes_trans.student_in_progress') }}</span>
                                            @elseif ($attempt->status === \App\Models\QuizAttempt::STATUS_BLOCKED)
                                                <span class="badge badge-danger">{{ trans('Quizzes_trans.student_blocked') }}</span>
                                            @else
                                                <span class="badge badge-success">{{ trans('Quizzes_trans.student_submitted') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn btn-sm btn-primary">
                                                @if ($attempt && in_array($attempt->status, [\App\Models\QuizAttempt::STATUS_SUBMITTED, \App\Models\QuizAttempt::STATUS_BLOCKED], true))
                                                    {{ trans('Quizzes_trans.student_view_result') }}
                                                @else
                                                    {{ trans('Quizzes_trans.student_start_quiz') }}
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-muted">{{ trans('main_trans.teacher_reports_no_data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
