@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('libraries_trans.add_title') }}
@endsection

@section('PageTitle')
    {{ trans('libraries_trans.add_title') }}
@endsection

@section('content')
    {{-- Library create page wrapper --}}
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

                    {{-- Library create form --}}
                    <form action="{{ route('libraries.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        {{-- Book title field --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="title">{{ trans('libraries_trans.book_title') }}</label>
                                <input id="title" type="text" name="title" class="form-control"
                                    value="{{ old('title') }}" required>
                            </div>
                        </div>

                        {{-- Relationships fields --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="grade_id">{{ trans('Students_trans.Grade') }} <span class="text-danger">*</span></label>
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
                                <label for="classroom_id">{{ trans('Students_trans.classrooms') }} <span class="text-danger">*</span></label>
                                <select class="custom-select" name="classroom_id" id="classroom_id" required></select>
                            </div>

                            <div class="form-group col">
                                <label for="section_id">{{ trans('Students_trans.section') }} <span class="text-danger">*</span></label>
                                <select class="custom-select" name="section_id" id="section_id" required></select>
                            </div>

                            <div class="form-group col">
                                <label for="teacher_id">{{ trans('libraries_trans.teacher_name') }} <span class="text-danger">*</span></label>
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

                        {{-- PDF attachment field --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="file">{{ trans('libraries_trans.attachment') }} <span class="text-danger">*</span></label>
                                <input id="file" type="file" accept="application/pdf" name="file" class="form-control-file" required>
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

    {{-- Load classrooms and sections by selected parent values --}}
    <script>
        $(document).ready(function() {
            const selectedClassroom = @json(old('classroom_id'));
            const selectedSection = @json(old('section_id'));

            function loadClassrooms(gradeId, selectedId = null) {
                const classroomSelect = $('#classroom_id');
                const sectionSelect = $('#section_id');

                classroomSelect.empty();
                sectionSelect.empty();

                if (!gradeId) {
                    return;
                }

                $.get("{{ URL::to('students/classrooms') }}/" + gradeId, function(data) {
                    classroomSelect.append('<option value="" disabled selected>{{ trans('Parent_trans.Choose') }}...</option>');

                    $.each(data, function(key, value) {
                        const option = $('<option>', {
                            value: key,
                            text: value,
                        });

                        if (String(selectedId) === String(key)) {
                            option.prop('selected', true);
                        }

                        classroomSelect.append(option);
                    });

                    if (selectedId) {
                        loadSections(selectedId, selectedSection);
                    }
                });
            }

            function loadSections(classroomId, selectedId = null) {
                const sectionSelect = $('#section_id');
                sectionSelect.empty();

                if (!classroomId) {
                    return;
                }

                $.get("{{ URL::to('students/sections') }}/" + classroomId, function(data) {
                    sectionSelect.append('<option value="" disabled selected>{{ trans('Parent_trans.Choose') }}...</option>');

                    $.each(data, function(key, value) {
                        const option = $('<option>', {
                            value: key,
                            text: value,
                        });

                        if (String(selectedId) === String(key)) {
                            option.prop('selected', true);
                        }

                        sectionSelect.append(option);
                    });
                });
            }

            $('#grade_id').on('change', function() {
                loadClassrooms($(this).val());
            });

            $('#classroom_id').on('change', function() {
                loadSections($(this).val());
            });

            if ($('#grade_id').val()) {
                loadClassrooms($('#grade_id').val(), selectedClassroom);
            }
        });
    </script>
@endsection
