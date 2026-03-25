@extends('layouts.user-portal')

@section('title')
    {{ trans('Questions_trans.add_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', [
        'title' => trans('Questions_trans.add_title'),
        'subtitle' => $quiz->name,
    ])

    {{-- Teacher create question page --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        <form action="{{ route('teacher.quizzes.questions.store', $quiz->id) }}" method="POST"
                            autocomplete="off">
                            @csrf

                            {{-- Question title --}}
                            <div class="form-group">
                                <label for="title">{{ trans('Questions_trans.title') }}</label>
                                <input id="title" type="text" name="title" class="form-control"
                                    value="{{ old('title') }}" required>
                            </div>

                            {{-- Question answers --}}
                            <div class="form-group">
                                <label for="answers">{{ trans('Questions_trans.answers') }}</label>
                                <textarea id="answers" name="answers" rows="4" class="form-control" required>{{ old('answers') }}</textarea>
                                <small class="text-muted">{{ trans('Questions_trans.answers_hint') }}</small>
                            </div>

                            {{-- Correct answer and score --}}
                            <div class="form-row">
                                <div class="col-md-8 mb-3">
                                    <label for="right_answer">{{ trans('Questions_trans.right_answer') }}</label>
                                    <input id="right_answer" type="text" name="right_answer" class="form-control"
                                        value="{{ old('right_answer') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="score">{{ trans('Questions_trans.score') }}</label>
                                    <select id="score" name="score" class="custom-select" required>
                                        <option value="">{{ trans('Questions_trans.choose_score') }}</option>
                                        @foreach ([5, 10, 15, 20] as $score)
                                            <option value="{{ $score }}"
                                                {{ (int) old('score') === $score ? 'selected' : '' }}>
                                                {{ $score }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Submit action --}}
                            <button type="submit" class="btn btn-success">{{ trans('Students_trans.submit') }}</button>
                            <a href="{{ route('teacher.quizzes.questions.index', $quiz->id) }}" class="btn btn-secondary">
                                {{ trans('main_trans.back_action') }}
                            </a>
                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3 mb-0">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
