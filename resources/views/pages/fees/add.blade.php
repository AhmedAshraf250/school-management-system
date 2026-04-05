@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.add_new_fees') }}
@endsection

@section('PageTitle')
    {{ trans('fees_trans.add_new_fees') }}
@endsection

@section('content')
    {{-- Main Content --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Validation Errors --}}
                    @if ($errors->any())
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

                    {{-- Form: Create Fee --}}
                    <form method="post" action="{{ route('fees.store') }}" autocomplete="off">
                        @csrf

                        {{-- Titles + Amount --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="title_ar">{{ trans('fees_trans.name_ar') }}</label>
                                <input id="title_ar" type="text" value="{{ old('title_ar') }}" name="title_ar"
                                    class="form-control" required>
                            </div>

                            <div class="form-group col">
                                <label for="title_en">{{ trans('fees_trans.name_en') }}</label>
                                <input id="title_en" type="text" value="{{ old('title_en') }}" name="title_en"
                                    class="form-control" required>
                            </div>

                            <div class="form-group col">
                                <label for="amount">{{ trans('fees_trans.amount') }}</label>
                                <input id="amount" type="number" step="0.01" value="{{ old('amount') }}"
                                    name="amount" class="form-control" required>
                            </div>
                        </div>

                        {{-- Grade + Classroom + Year + Fee Type --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="grade_id">{{ trans('fees_trans.grade') }}</label>
                                <select id="grade_id" class="custom-select mr-sm-2" name="grade_id" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            {{ (string) old('grade_id') === (string) $grade->id ? 'selected' : '' }}>
                                            {{ $grade->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col">
                                <label for="classroom_id">{{ trans('fees_trans.classroom') }}</label>
                                <select id="classroom_id" class="custom-select mr-sm-2" name="classroom_id" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                </select>
                            </div>

                            <div class="form-group col">
                                <label for="year">{{ trans('fees_trans.academic_year') }}</label>
                                <select id="year" class="custom-select mr-sm-2" name="year" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                    @php
                                        $selectedYear = (string) old('year');
                                    @endphp
                                    @foreach (range(now()->year - 2, now()->year + 2) as $year)
                                        <option value="{{ $year }}" @selected($selectedYear === (string) $year)>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col">
                                <label for="type">{{ trans('fees_trans.fee_type') }}</label>
                                <select id="type" class="custom-select mr-sm-2" name="type">
                                    <option value="1">{{ trans('fees_trans.tuition_fees') }}</option>
                                    <option value="2">{{ trans('fees_trans.transport_fees') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="form-group">
                            <label for="description">{{ trans('fees_trans.notes') }}</label>
                            <textarea class="form-control" name="description" id="description" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <br>

                        {{-- Submit --}}
                        <button type="submit" class="btn btn-primary">{{ trans('fees_trans.confirm') }}</button>
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
            const classroomUrlTemplate = @json(route('students.getClassrooms', ['id' => '__id__']));
            const chooseLabel = @json(trans('Parent_trans.Choose') . ' ...');
            const gradeSelect = $('#grade_id');
            const classroomSelect = $('#classroom_id');

            function buildUrl(template, id) {
                return template.replace('__id__', id);
            }

            function resetClassrooms() {
                classroomSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');
            }

            function loadClassrooms(gradeId, selectedClassroomId = null) {
                resetClassrooms();

                if (!gradeId) {
                    return;
                }

                $.getJSON(buildUrl(classroomUrlTemplate, gradeId), function(data) {
                    $.each(data, function(key, value) {
                        const selected = selectedClassroomId && String(selectedClassroomId) === String(key) ?
                            ' selected' : '';
                        classroomSelect.append('<option value="' + key + '"' + selected + '>' + value + '</option>');
                    });
                });
            }

            gradeSelect.on('change', function() {
                loadClassrooms($(this).val());
            });

            const oldGradeId = @json(old('grade_id'));
            const oldClassroomId = @json(old('classroom_id'));
            if (oldGradeId) {
                loadClassrooms(oldGradeId, oldClassroomId);
            }
        });
    </script>
@endsection
