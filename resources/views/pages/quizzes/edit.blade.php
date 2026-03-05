@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('Quizzes_trans.edit_title') }}: {{ $quiz->name }}
@endsection

@section('PageTitle')
    {{ trans('Quizzes_trans.edit_title') }}: {{ $quiz->name }}
@endsection

@section('content')
    {{-- Quiz edit page wrapper --}}
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

                    {{-- Quiz edit form --}}
                    <form action="{{ route('quizzes.update', $quiz->id) }}" method="post" autocomplete="off">
                        @csrf
                        @method('PUT')

                        {{-- Translated quiz name fields --}}
                        <div class="form-row">
                            <div class="col">
                                <label for="name_ar">{{ trans('Quizzes_trans.name_ar') }}</label>
                                <input id="name_ar" type="text" name="name_ar" class="form-control"
                                    value="{{ old('name_ar', $quiz->getTranslation('name', 'ar')) }}" required>
                            </div>

                            <div class="col">
                                <label for="name_en">{{ trans('Quizzes_trans.name_en') }}</label>
                                <input id="name_en" type="text" name="name_en" class="form-control"
                                    value="{{ old('name_en', $quiz->getTranslation('name', 'en')) }}" required>
                            </div>
                        </div>

                        <br>

                        {{-- Subject and teacher fields --}}
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="subject_id">{{ trans('Quizzes_trans.subject') }} <span class="text-danger">*</span></label>
                                    <select class="custom-select" name="subject_id" id="subject_id" required>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}"
                                                @selected(old('subject_id', $quiz->subject_id) == $subject->id)>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="teacher_id">{{ trans('Quizzes_trans.teacher') }} <span class="text-danger">*</span></label>
                                    <select class="custom-select" name="teacher_id" id="teacher_id" required>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                @selected(old('teacher_id', $quiz->teacher_id) == $teacher->id)>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Grade/classroom/section fields --}}
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="grade_id">{{ trans('Quizzes_trans.grade') }} <span class="text-danger">*</span></label>
                                    <select class="custom-select" name="grade_id" id="grade_id" required>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}"
                                                @selected(old('grade_id', $quiz->grade_id) == $grade->id)>
                                                {{ $grade->Name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="classroom_id">{{ trans('Quizzes_trans.classroom') }} <span class="text-danger">*</span></label>
                                    <select class="custom-select" name="classroom_id" id="classroom_id" required>
                                        @foreach ($classrooms as $classroomId => $classroomName)
                                            <option value="{{ $classroomId }}"
                                                @selected(old('classroom_id', $quiz->classroom_id) == $classroomId)>
                                                {{ $classroomName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="section_id">{{ trans('Quizzes_trans.section') }} <span class="text-danger">*</span></label>
                                    <select class="custom-select" name="section_id" id="section_id" required>
                                        @foreach ($sections as $sectionId => $sectionName)
                                            <option value="{{ $sectionId }}"
                                                @selected(old('section_id', $quiz->section_id) == $sectionId)>
                                                {{ $sectionName }}
                                            </option>
                                        @endforeach
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

    {{-- Load classrooms and sections dynamically --}}
    <script>
        $(document).ready(function() {
            $('#grade_id').on('change', function() {
                const gradeId = $(this).val();

                if (!gradeId) {
                    return;
                }

                $.get("{{ URL::to('students/classrooms') }}/" + gradeId, function(data) {
                    const classroomSelect = $('#classroom_id');
                    const sectionSelect = $('#section_id');

                    classroomSelect.empty();
                    sectionSelect.empty();

                    $.each(data, function(key, value) {
                        classroomSelect.append('<option value="' + key + '">' + value + '</option>');
                    });

                    classroomSelect.trigger('change');
                });
            });

            $('#classroom_id').on('change', function() {
                const classroomId = $(this).val();

                if (!classroomId) {
                    return;
                }

                $.get("{{ URL::to('students/sections') }}/" + classroomId, function(data) {
                    const sectionSelect = $('#section_id');
                    sectionSelect.empty();

                    $.each(data, function(key, value) {
                        sectionSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
