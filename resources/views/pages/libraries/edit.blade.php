@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('libraries_trans.edit_title', ['title' => $book->title]) }}
@endsection

@section('PageTitle')
    {{ trans('libraries_trans.edit_title', ['title' => $book->title]) }}
@endsection

@section('content')
    {{-- Library edit page wrapper --}}
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

                    {{-- Library edit form --}}
                    <form action="{{ route('libraries.update', $book->id) }}" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        @method('PATCH')
                        @csrf

                        {{-- Book title field --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="title">{{ trans('libraries_trans.book_title') }}</label>
                                <input id="title" type="text" name="title" class="form-control"
                                    value="{{ old('title', $book->title) }}" required>
                            </div>
                        </div>

                        {{-- Relationships fields --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="grade_id">{{ trans('Students_trans.Grade') }} <span class="text-danger">*</span></label>
                                <select class="custom-select" name="grade_id" id="grade_id" required>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            @selected(old('grade_id', $book->grade_id) == $grade->id)>
                                            {{ $grade->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col">
                                <label for="classroom_id">{{ trans('Students_trans.classrooms') }} <span class="text-danger">*</span></label>
                                <select class="custom-select" name="classroom_id" id="classroom_id" required>
                                    @foreach ($classrooms as $classroomId => $classroomName)
                                        <option value="{{ $classroomId }}"
                                            @selected(old('classroom_id', $book->classroom_id) == $classroomId)>
                                            {{ $classroomName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col">
                                <label for="section_id">{{ trans('Students_trans.section') }} <span class="text-danger">*</span></label>
                                <select class="custom-select" name="section_id" id="section_id" required>
                                    @foreach ($sections as $sectionId => $sectionName)
                                        <option value="{{ $sectionId }}"
                                            @selected(old('section_id', $book->section_id) == $sectionId)>
                                            {{ $sectionName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col">
                                <label for="teacher_id">{{ trans('libraries_trans.teacher_name') }} <span class="text-danger">*</span></label>
                                <select class="custom-select" name="teacher_id" id="teacher_id" required>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}"
                                            @selected(old('teacher_id', $book->teacher_id) == $teacher->id)>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Current attachment preview and replacement --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <a href="{{ route('libraries.download', $book->id) }}" class="btn btn-outline-secondary btn-sm mb-2"
                                    role="button">
                                    {{ trans('libraries_trans.download_current') }}
                                </a>

                                <label for="file">{{ trans('libraries_trans.attachment_optional') }}</label>
                                <input id="file" type="file" accept="application/pdf" name="file"
                                    class="form-control-file">
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
            function loadClassrooms(gradeId, selectedClassroom = null, selectedSection = null) {
                const classroomSelect = $('#classroom_id');

                if (!gradeId) {
                    classroomSelect.empty();
                    $('#section_id').empty();
                    return;
                }

                $.get("{{ URL::to('students/classrooms') }}/" + gradeId, function(data) {
                    classroomSelect.empty();

                    $.each(data, function(key, value) {
                        const option = $('<option>', {
                            value: key,
                            text: value,
                        });

                        if (String(selectedClassroom) === String(key)) {
                            option.prop('selected', true);
                        }

                        classroomSelect.append(option);
                    });

                    if (selectedClassroom) {
                        loadSections(selectedClassroom, selectedSection);
                    }
                });
            }

            function loadSections(classroomId, selectedSection = null) {
                const sectionSelect = $('#section_id');

                if (!classroomId) {
                    sectionSelect.empty();
                    return;
                }

                $.get("{{ URL::to('students/sections') }}/" + classroomId, function(data) {
                    sectionSelect.empty();

                    $.each(data, function(key, value) {
                        const option = $('<option>', {
                            value: key,
                            text: value,
                        });

                        if (String(selectedSection) === String(key)) {
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
                loadClassrooms(
                    $('#grade_id').val(),
                    @json(old('classroom_id', $book->classroom_id)),
                    @json(old('section_id', $book->section_id)),
                );
            }
        });
    </script>
@endsection
