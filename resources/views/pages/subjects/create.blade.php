@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('Subjects_trans.add_title') }}
@endsection

@section('PageTitle')
    {{ trans('Subjects_trans.add_title') }}
@endsection

@section('content')
    {{-- Subject create page wrapper --}}
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

                    {{-- Subject create form --}}
                    <form action="{{ route('subjects.store') }}" method="post" autocomplete="off">
                        @csrf

                        {{-- Translated subject name fields --}}
                        <div class="form-row">
                            <div class="col">
                                <label for="name_ar">{{ trans('Subjects_trans.name_ar') }}</label>
                                <input id="name_ar" type="text" name="name_ar" class="form-control"
                                    value="{{ old('name_ar') }}" required>
                            </div>
                            <div class="col">
                                <label for="name_en">{{ trans('Subjects_trans.name_en') }}</label>
                                <input id="name_en" type="text" name="name_en" class="form-control"
                                    value="{{ old('name_en') }}" required>
                            </div>
                        </div>

                        <br>

                        {{-- Subject relationships fields --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="grade_id">{{ trans('Subjects_trans.grade') }}</label>
                                <select class="custom-select" name="grade_id" id="grade_id" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" @selected(old('grade_id') == $grade->id)>
                                            {{ $grade->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col">
                                <label for="classroom_id">{{ trans('Subjects_trans.classroom') }}</label>
                                <select name="classroom_id" id="classroom_id" class="custom-select" required></select>
                            </div>

                            <div class="form-group col">
                                <label for="teacher_id">{{ trans('Subjects_trans.teacher') }}</label>
                                <select class="custom-select" name="teacher_id" id="teacher_id" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
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

    {{-- Load classrooms by selected grade --}}
    <script>
        $(document).ready(function() {
            $('#grade_id').on('change', function() {
                const gradeId = $(this).val();

                if (!gradeId) {
                    return;
                }

                $.get("{{ URL::to('students/classrooms') }}/" + gradeId, function(data) {
                    const classroomSelect = $('#classroom_id');
                    classroomSelect.empty();

                    $.each(data, function(key, value) {
                        classroomSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
