@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('Questions_trans.edit_title') }}: {{ $question->title }}
@endsection

@section('PageTitle')
    {{ trans('Questions_trans.edit_title') }}: <span class="text-danger">{{ $question->title }}</span>
@endsection

@section('content')
    {{-- Question edit page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Validation summary --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Question edit form --}}
                    <form action="{{ route('questions.update', $question->id) }}" method="post" autocomplete="off">
                        @method('PUT')
                        @csrf

                        {{-- Question title field --}}
                        <div class="form-row">
                            <div class="col">
                                <label for="title">{{ trans('Questions_trans.title') }}</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ old('title', $question->title) }}" required>
                            </div>
                        </div>

                        <br>

                        {{-- Answers field --}}
                        <div class="form-row">
                            <div class="col">
                                <label for="answers">{{ trans('Questions_trans.answers') }}</label>
                                <textarea name="answers" id="answers" class="form-control" rows="4" required>{{ old('answers', $question->answers) }}</textarea>
                                <small class="text-muted">{{ trans('Questions_trans.answers_hint') }}</small>
                            </div>
                        </div>

                        <br>

                        {{-- Correct answer field --}}
                        <div class="form-row">
                            <div class="col">
                                <label for="right_answer">{{ trans('Questions_trans.right_answer') }}</label>
                                <input type="text" name="right_answer" id="right_answer" class="form-control"
                                    value="{{ old('right_answer', $question->right_answer) }}" required>
                            </div>
                        </div>

                        <br>

                        {{-- Quiz and score fields --}}
                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="quiz_id">{{ trans('Questions_trans.quiz_name') }} <span class="text-danger">*</span></label>
                                    <select class="custom-select" name="quiz_id" id="quiz_id" required>
                                        @foreach ($quizzes as $quiz)
                                            <option value="{{ $quiz->id }}" @selected(old('quiz_id', $question->quiz_id) == $quiz->id)>
                                                {{ $quiz->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="score">{{ trans('Questions_trans.score') }} <span class="text-danger">*</span></label>
                                    <select class="custom-select" name="score" id="score" required>
                                        <option value="5" @selected(old('score', $question->score) == 5)>5</option>
                                        <option value="10" @selected(old('score', $question->score) == 10)>10</option>
                                        <option value="15" @selected(old('score', $question->score) == 15)>15</option>
                                        <option value="20" @selected(old('score', $question->score) == 20)>20</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Submit action --}}
                        <button class="btn btn-success btn-sm" type="submit">{{ trans('Students_trans.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @toastr_js
    @toastr_render
@endsection
