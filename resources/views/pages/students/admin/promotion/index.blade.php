@extends('layouts.master')

@section('title')
    {{ trans('main_trans.add_Promotion') }}
@endsection

@section('PageTitle')
    {{ trans('main_trans.add_Promotion') }}
@endsection

@section('content')
    {{-- Promotion create page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- General promotion error --}}
                    @error('promotion')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @enderror

                    {{-- Validation errors --}}
                    @if ($errors->any() && ! $errors->has('promotion'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Promotion form --}}
                    <form method="post" action="{{ route('promotions.store') }}">
                        @csrf

                        {{-- Source stage heading --}}
                        <h6 style="font-family: 'Cairo', sans-serif; color: #dc3545;">
                            {{ trans('Students_trans.promotion_from') }}
                        </h6>
                        <br>

                        {{-- Source stage fields --}}
                        <div class="form-row">
                            {{-- Source grade --}}
                            <div class="form-group col-md-3">
                                <label for="from_grade_id">{{ trans('Students_trans.Grade') }} <span
                                        class="text-danger">*</span></label>
                                <select id="from_grade_id" class="custom-select" name="from_grade_id">
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" @selected((int) old('from_grade_id') === $grade->id)>
                                            {{ $grade->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Source classroom --}}
                            <div class="form-group col-md-3">
                                <label for="from_classroom_id">{{ trans('Students_trans.classrooms') }} <span
                                        class="text-danger">*</span></label>
                                <select id="from_classroom_id" class="custom-select" name="from_classroom_id">
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                </select>
                            </div>

                            {{-- Source section --}}
                            <div class="form-group col-md-3">
                                <label for="from_section_id">{{ trans('Students_trans.section') }} <span
                                        class="text-danger">*</span></label>
                                <select id="from_section_id" class="custom-select" name="from_section_id">
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                </select>
                            </div>

                            {{-- Source academic year --}}
                            <div class="form-group col-md-3">
                                <label for="academic_year_from">{{ trans('Students_trans.academic_year') }} <span
                                        class="text-danger">*</span></label>
                                <select id="academic_year_from" class="custom-select" name="academic_year_from">
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                    @php
                                        $currentYear = (int) date('Y');
                                    @endphp
                                    @for ($year = $currentYear - 1; $year <= $currentYear + 1; $year++)
                                        <option value="{{ $year }}" @selected((string) old('academic_year_from') === (string) $year)>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <br>

                        {{-- Target stage heading --}}
                        <h6 style="font-family: 'Cairo', sans-serif; color: #28a745;">
                            {{ trans('Students_trans.promotion_to') }}
                        </h6>
                        <br>

                        {{-- Target stage fields --}}
                        <div class="form-row">
                            {{-- Target grade --}}
                            <div class="form-group col-md-3">
                                <label for="to_grade_id">{{ trans('Students_trans.Grade') }} <span
                                        class="text-danger">*</span></label>
                                <select id="to_grade_id" class="custom-select" name="to_grade_id">
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" @selected((int) old('to_grade_id') === $grade->id)>
                                            {{ $grade->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Target classroom --}}
                            <div class="form-group col-md-3">
                                <label for="to_classroom_id">{{ trans('Students_trans.classrooms') }} <span
                                        class="text-danger">*</span></label>
                                <select id="to_classroom_id" class="custom-select" name="to_classroom_id">
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                </select>
                            </div>

                            {{-- Target section --}}
                            <div class="form-group col-md-3">
                                <label for="to_section_id">{{ trans('Students_trans.section') }} <span
                                        class="text-danger">*</span></label>
                                <select id="to_section_id" class="custom-select" name="to_section_id">
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                </select>
                            </div>

                            {{-- Target academic year --}}
                            <div class="form-group col-md-3">
                                <label for="academic_year_to">{{ trans('Students_trans.academic_year') }} <span
                                        class="text-danger">*</span></label>
                                <select id="academic_year_to" class="custom-select" name="academic_year_to">
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                    @php
                                        $currentYear = (int) date('Y');
                                    @endphp
                                    @for ($year = $currentYear; $year <= $currentYear + 2; $year++)
                                        <option value="{{ $year }}" @selected((string) old('academic_year_to') === (string) $year)>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Submit action --}}
                        <button type="submit" class="btn btn-primary">
                            {{ trans('Students_trans.submit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- Source grade -> classrooms --}}
    <script>
        $(document).ready(function() {
            $('#from_grade_id').on('change', function() {
                const gradeId = $(this).val();
                const classroomSelect = $('#from_classroom_id');
                const sectionSelect = $('#from_section_id');

                classroomSelect.empty().append(
                    '<option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>');
                sectionSelect.empty().append(
                    '<option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>');

                if (!gradeId) {
                    return;
                }

                $.getJSON("{{ route('students.getClassrooms', ':id') }}".replace(':id', gradeId), function(data) {
                    $.each(data, function(key, value) {
                        classroomSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            });
        });
    </script>

    {{-- Source classroom -> sections --}}
    <script>
        $(document).ready(function() {
            $('#from_classroom_id').on('change', function() {
                const classroomId = $(this).val();
                const sectionSelect = $('#from_section_id');

                sectionSelect.empty().append(
                    '<option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>');

                if (!classroomId) {
                    return;
                }

                $.getJSON("{{ route('students.getSections', ':id') }}".replace(':id', classroomId), function(data) {
                    $.each(data, function(key, value) {
                        sectionSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            });
        });
    </script>

    {{-- Target grade -> classrooms --}}
    <script>
        $(document).ready(function() {
            $('#to_grade_id').on('change', function() {
                const gradeId = $(this).val();
                const classroomSelect = $('#to_classroom_id');
                const sectionSelect = $('#to_section_id');

                classroomSelect.empty().append(
                    '<option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>');
                sectionSelect.empty().append(
                    '<option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>');

                if (!gradeId) {
                    return;
                }

                $.getJSON("{{ route('students.getClassrooms', ':id') }}".replace(':id', gradeId), function(data) {
                    $.each(data, function(key, value) {
                        classroomSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            });
        });
    </script>

    {{-- Target classroom -> sections --}}
    <script>
        $(document).ready(function() {
            $('#to_classroom_id').on('change', function() {
                const classroomId = $(this).val();
                const sectionSelect = $('#to_section_id');

                sectionSelect.empty().append(
                    '<option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>');

                if (!classroomId) {
                    return;
                }

                $.getJSON("{{ route('students.getSections', ':id') }}".replace(':id', classroomId), function(data) {
                    $.each(data, function(key, value) {
                        sectionSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
