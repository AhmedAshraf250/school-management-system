@extends('layouts.user-portal')

@section('title')
    {{ trans('Quizzes_trans.student_results_page_title') }}
@stop

@section('content')
    {{-- Dashboard identity header for student portal --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_student'),
        'identity' => $student->name ?? $student->email ?? '-',
    ])

    {{-- Student submitted quizzes and scores --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <h5 class="mb-0">{{ trans('Quizzes_trans.student_results_page_title') }}</h5>

                        <form method="GET" action="{{ route('student.quizzes.results') }}"
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

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="text-align: center;">
                            <thead>
                                <tr class="table-info text-danger">
                                    <th>#</th>
                                    <th>{{ trans('Quizzes_trans.name') }}</th>
                                    <th>{{ trans('Quizzes_trans.subject') }}</th>
                                    <th>{{ trans('Quizzes_trans.teacher') }}</th>
                                    <th>{{ trans('Quizzes_trans.status') }}</th>
                                    <th>{{ trans('Quizzes_trans.student_score') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attempts as $attempt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attempt->quiz?->name ?? '-' }}</td>
                                        <td>{{ $attempt->quiz?->subject?->name ?? '-' }}</td>
                                        <td>{{ $attempt->quiz?->teacher?->name ?? '-' }}</td>
                                        <td>
                                            @if ($attempt->status === \App\Models\QuizAttempt::STATUS_BLOCKED)
                                                <span class="badge badge-danger">{{ trans('Quizzes_trans.student_blocked') }}</span>
                                            @else
                                                <span
                                                    class="badge badge-success">{{ trans('Quizzes_trans.student_submitted') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $attempt->total_score }} / {{ $attempt->max_score }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted">{{ trans('main_trans.teacher_reports_no_data') }}</td>
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
