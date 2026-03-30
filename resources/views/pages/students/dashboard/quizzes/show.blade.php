@extends('layouts.user-portal')

@section('title')
    {{ $quiz->name }}
@stop

@section('content')
    {{-- Dashboard identity header for student portal --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_student'),
        'identity' => $student->name ?? $student->email ?? '-',
    ])

    {{-- Quiz attempt page shell --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <div>
                            <h5 class="mb-1">{{ $quiz->name }}</h5>
                            <div class="text-muted small">
                                {{ $quiz->subject?->name ?? '-' }}
                                <span class="mx-1">•</span>
                                {{ trans('Questions_trans.list') }}: {{ $questionCount }}
                            </div>
                        </div>
                        <a href="{{ route('student.quizzes') }}" class="btn btn-outline-secondary btn-sm">
                            {{ trans('main_trans.back_action') }}
                        </a>
                    </div>

                    {{-- Quiz warning notice --}}
                    @if ($attempt->status === \App\Models\QuizAttempt::STATUS_IN_PROGRESS)
                        <div class="alert alert-warning">
                            <h6 class="mb-2">{{ trans('Quizzes_trans.student_warning_title') }}</h6>
                            <ul class="mb-0 pl-3">
                                <li>{{ trans('Quizzes_trans.student_warning_rule_refresh') }}</li>
                                <li>{{ trans('Quizzes_trans.student_warning_rule_no_tamper') }}</li>
                                <li>{{ trans('Quizzes_trans.student_warning_rule_submit_once') }}</li>
                            </ul>
                        </div>
                    @endif

                    {{-- Submitted or blocked result panel --}}
                    @if ($attempt->status === \App\Models\QuizAttempt::STATUS_SUBMITTED)
                        <div class="alert alert-success mb-0">
                            <h6 class="mb-1">{{ trans('Quizzes_trans.student_result_title') }}</h6>
                            <p class="mb-0">
                                {{ trans('Quizzes_trans.student_result_score', ['score' => $attempt->total_score, 'max' => $attempt->max_score]) }}
                            </p>
                        </div>
                    @elseif ($attempt->status === \App\Models\QuizAttempt::STATUS_BLOCKED)
                        <div class="alert alert-danger mb-0">
                            <h6 class="mb-1">{{ trans('Quizzes_trans.student_blocked_title') }}</h6>
                            <p class="mb-1">{{ trans('Quizzes_trans.student_blocked_message') }}</p>
                            <p class="mb-0">
                                {{ trans('Quizzes_trans.student_result_score', ['score' => 0, 'max' => $attempt->max_score]) }}
                            </p>
                        </div>
                    @else
                        {{-- Livewire interactive component --}}
                        @livewire('students.quizzes.take-quiz', ['attempt' => $attempt], key('quiz-attempt-' . $attempt->id))
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
