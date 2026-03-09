@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('OnlineClasses_trans.add_integrated_title') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('OnlineClasses_trans.add_integrated_title') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
{{-- Integrated Zoom class creation form --}}
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Main form --}}
                <form method="post" action="{{ route('online-classes.store') }}" autocomplete="off">
                    @csrf

                    {{-- Academic hierarchy: grade, classroom, section --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="grade_id">{{ trans('Students_trans.Grade') }} : <span class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="grade_id" id="grade_id" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" @selected((int) old('grade_id') === $grade->id)>
                                            {{ $grade->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="classroom_id">{{ trans('Students_trans.classrooms') }} : <span class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="classroom_id" id="classroom_id" required></select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="section_id">{{ trans('Students_trans.section') }} : <span class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="section_id" id="section_id" required></select>
                            </div>
                        </div>
                    </div>

                    <br>

                    {{-- Meeting details --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="topic">{{ trans('OnlineClasses_trans.topic_label') }} : <span class="text-danger">*</span></label>
                                <input class="form-control" id="topic" name="topic" type="text" value="{{ old('topic') }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_time">{{ trans('OnlineClasses_trans.start_time_label') }} : <span class="text-danger">*</span></label>
                                <input class="form-control" id="start_time" type="datetime-local" name="start_time"
                                    value="{{ old('start_time') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="duration">{{ trans('OnlineClasses_trans.duration_label') }} : <span class="text-danger">*</span></label>
                                <input class="form-control" id="duration" name="duration" type="number" min="1" value="{{ old('duration') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="password">{{ trans('OnlineClasses_trans.password_optional_label') }}</label>
                                <input class="form-control" id="password" name="password" type="text" value="{{ old('password') }}">
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="submit">
                        {{ trans('Students_trans.submit') }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@toastr_js
@toastr_render

{{-- Load classrooms and sections based on selected grade --}}
<script>
    $(document).ready(function() {
        const classroomUrlTemplate = @json(route('students.getClassrooms', ['id' => '__id__']));
        const sectionUrlTemplate = @json(route('students.getSections', ['id' => '__id__']));

        const gradeSelect = $('#grade_id');
        const classroomSelect = $('#classroom_id');
        const sectionSelect = $('#section_id');

        const oldGradeId = @json(old('grade_id'));
        const oldClassroomId = @json(old('classroom_id'));
        const oldSectionId = @json(old('section_id'));

        function resetSelect(select, placeholder) {
            select.empty().append(new Option(placeholder, '', true, true));
        }

        function loadClassrooms(gradeId, selectedId = null) {
            resetSelect(classroomSelect, '{{ trans('Parent_trans.Choose') }}...');
            resetSelect(sectionSelect, '{{ trans('Parent_trans.Choose') }}...');

            if (!gradeId) {
                return;
            }

            $.getJSON(classroomUrlTemplate.replace('__id__', gradeId), function(data) {
                $.each(data, function(id, name) {
                    classroomSelect.append(new Option(name, id, false, String(selectedId) === String(id)));
                });

                if (selectedId) {
                    classroomSelect.trigger('change');
                }
            });
        }

        function loadSections(classroomId, selectedId = null) {
            resetSelect(sectionSelect, '{{ trans('Parent_trans.Choose') }}...');

            if (!classroomId) {
                return;
            }

            $.getJSON(sectionUrlTemplate.replace('__id__', classroomId), function(data) {
                $.each(data, function(id, name) {
                    sectionSelect.append(new Option(name, id, false, String(selectedId) === String(id)));
                });
            });
        }

        gradeSelect.on('change', function() {
            loadClassrooms($(this).val());
        });

        classroomSelect.on('change', function() {
            loadSections($(this).val());
        });

        if (oldGradeId) {
            loadClassrooms(oldGradeId, oldClassroomId);

            if (oldClassroomId) {
                loadSections(oldClassroomId, oldSectionId);
            }
        } else {
            resetSelect(classroomSelect, '{{ trans('Parent_trans.Choose') }}...');
            resetSelect(sectionSelect, '{{ trans('Parent_trans.Choose') }}...');
        }
    });
</script>
@endsection
