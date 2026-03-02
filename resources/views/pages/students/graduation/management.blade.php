@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('main_trans.list_Graduate') }}
@endsection

@section('PageTitle')
    {{ trans('main_trans.list_Graduate') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    @error('graduation')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @enderror

                    @error('graduation_batch')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @enderror

                    @if ($errors->any() && ! $errors->has('graduation') && ! $errors->has('graduation_batch'))
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

                    <form action="{{ route('graduates.store') }}" method="post" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="student_id">{{ trans('Students_trans.name') }}</label>
                                <select class="custom-select" id="student_id" name="student_id" required>
                                    <option value="" disabled selected>{{ trans('Students_trans.select_student') }}</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->name }}
                                            - {{ $student->grade?->Name ?? '-' }}
                                            - {{ $student->classroom?->name ?? '-' }}
                                            - {{ $student->section?->name ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="academic_year">{{ trans('Students_trans.academic_year') }}</label>
                                <input type="text" name="academic_year" id="academic_year" class="form-control"
                                    value="{{ old('academic_year', date('Y')) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="graduated_at">{{ trans('Students_trans.graduated_at') }}</label>
                                <input type="date" name="graduated_at" id="graduated_at" class="form-control"
                                    value="{{ old('graduated_at', now()->toDateString()) }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-success btn-block">{{ trans('main_trans.add_Graduate') }}</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <form action="{{ route('graduates.bulk') }}" method="post" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="bulk_grade_id">{{ trans('Students_trans.Grade') }}</label>
                                <select id="bulk_grade_id" class="custom-select" name="grade_id" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}">{{ $grade->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="bulk_classroom_id">{{ trans('Students_trans.classrooms') }}</label>
                                <select id="bulk_classroom_id" class="custom-select" name="classroom_id" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="bulk_section_id">{{ trans('Students_trans.section') }}</label>
                                <select id="bulk_section_id" class="custom-select" name="section_id" required>
                                    <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="bulk_academic_year">{{ trans('Students_trans.academic_year') }}</label>
                                <input type="text" name="academic_year" id="bulk_academic_year" class="form-control"
                                    value="{{ old('academic_year', date('Y')) }}" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="bulk_graduated_at">{{ trans('Students_trans.graduated_at') }}</label>
                                <input type="date" name="graduated_at" id="bulk_graduated_at" class="form-control"
                                    value="{{ old('graduated_at', now()->toDateString()) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="bulk_notes">{{ trans('Students_trans.notes') }}</label>
                                <input type="text" name="notes" id="bulk_notes" class="form-control"
                                    value="{{ old('notes') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-primary btn-block">{{ trans('Students_trans.graduate_section') }}</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('Students_trans.name') }}</th>
                                    <th>{{ trans('Students_trans.Grade') }}</th>
                                    <th>{{ trans('Students_trans.classrooms') }}</th>
                                    <th>{{ trans('Students_trans.section') }}</th>
                                    <th>{{ trans('Students_trans.academic_year') }}</th>
                                    <th>{{ trans('Students_trans.graduated_at') }}</th>
                                    <th>{{ trans('Students_trans.notes') }}</th>
                                    <th>{{ trans('Students_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($graduations as $graduation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $graduation->student?->name ?? '-' }}</td>
                                        <td>{{ $graduation->student?->grade?->Name ?? '-' }}</td>
                                        <td>{{ $graduation->student?->classroom?->name ?? '-' }}</td>
                                        <td>{{ $graduation->student?->section?->name ?? '-' }}</td>
                                        <td>{{ $graduation->academic_year }}</td>
                                        <td>{{ optional($graduation->graduated_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                        <td>{{ $graduation->notes ?? '-' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                data-toggle="modal"
                                                data-target="#RollbackOneGraduationModal{{ $graduation->id }}">
                                                {{ trans('Students_trans.graduation_rollback_one') }}
                                            </button>
                                        </td>
                                    </tr>
                                    @include('pages.students.graduation.delete-one', ['graduation' => $graduation])
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-muted py-3">
                                            {{ trans('Students_trans.no_graduations') }}
                                        </td>
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

@section('js')
    @toastr_js
    @toastr_render
    <script>
        $(document).ready(function() {
            const classroomUrlTemplate = @json(route('students.getClassrooms', ['id' => '__id__']));
            const sectionUrlTemplate = @json(route('students.getSections', ['id' => '__id__']));
            const chooseLabel = @json(trans('Parent_trans.Choose') . ' ...');

            const gradeSelect = $('#bulk_grade_id');
            const classroomSelect = $('#bulk_classroom_id');
            const sectionSelect = $('#bulk_section_id');

            function buildUrl(template, id) {
                return template.replace('__id__', id);
            }

            function resetClassroomsAndSections() {
                classroomSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');
                sectionSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');
            }

            gradeSelect.on('change', function() {
                const gradeId = $(this).val();
                resetClassroomsAndSections();

                if (!gradeId) {
                    return;
                }

                $.getJSON(buildUrl(classroomUrlTemplate, gradeId), function(data) {
                    $.each(data, function(key, value) {
                        classroomSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            });

            classroomSelect.on('change', function() {
                const classroomId = $(this).val();
                sectionSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');

                if (!classroomId) {
                    return;
                }

                $.getJSON(buildUrl(sectionUrlTemplate, classroomId), function(data) {
                    $.each(data, function(key, value) {
                        sectionSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
