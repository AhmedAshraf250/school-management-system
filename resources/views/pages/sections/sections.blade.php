@extends('layouts.master')

@section('css')
    {{-- @toastr_css  // IGNORE (using Flasher instead of Toastr) --}}
@endsection

@section('title')
    {{ trans('Sections_trans.title_page') }}
@stop

@section('PageTitle')
    {{ trans('Sections_trans.title_page') }}
@stop

@section('content')
    {{-- Page Layout: sections management screen --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                {{-- Top Actions: open create modal --}}
                <div class="card-body">
                    <a class="button x-small" href="#" data-toggle="modal" data-target="#createSectionModal">
                        {{ trans('Sections_trans.add_section') }}
                    </a>
                </div>

                {{-- Global Validation Errors --}}
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

                {{-- Data Region: each grade contains its sections table --}}
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        <div class="accordion gray plus-icon round">
                            @foreach ($grades as $grade)
                                <div class="acd-group">
                                    <a href="#" class="acd-heading">{{ $grade->Name }}</a>

                                    <div class="acd-des">
                                        <div class="table-responsive mt-15">
                                            <table class="table center-aligned-table mb-0">
                                                <thead>
                                                    <tr class="text-dark">
                                                        <th>#</th>
                                                        <th>{{ trans('Sections_trans.Name_Section') }}</th>
                                                        <th>{{ trans('Sections_trans.Name_Class') }}</th>
                                                        <th>{{ trans('Sections_trans.Name_Teacher') }}</th>
                                                        <th>{{ trans('Sections_trans.Status') }}</th>
                                                        <th>{{ trans('Sections_trans.Processes') }}</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($grade->sections as $section)
                                                        {{-- Section Row + row-level actions --}}
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $section->name }}</td>
                                                            <td>{{ $section->classroom?->name }}</td>
                                                            <td>
                                                                @forelse ($section->teachers as $teacher)
                                                                    <span
                                                                        class="badge badge-info mr-1">{{ $teacher->name }}</span>
                                                                @empty
                                                                    <span class="text-muted">--</span>
                                                                @endforelse
                                                            </td>
                                                            <td>
                                                                @if ((int) $section->status === 1)
                                                                    <label class="badge badge-success">
                                                                        {{ trans('Sections_trans.Status_Section_AC') }}
                                                                    </label>
                                                                @else
                                                                    <label class="badge badge-danger">
                                                                        {{ trans('Sections_trans.Status_Section_No') }}
                                                                    </label>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="#" class="btn btn-outline-info btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#edit{{ $section->id }}">
                                                                    {{ trans('Sections_trans.Edit') }}
                                                                </a>
                                                                <a href="#" class="btn btn-outline-danger btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#delete{{ $section->id }}">
                                                                    {{ trans('Sections_trans.Delete') }}
                                                                </a>
                                                            </td>
                                                        </tr>

                                                        {{-- Edit Modal: updates name/grade/classroom/status --}}
                                                        <div class="modal fade" id="edit{{ $section->id }}" tabindex="-1"
                                                            role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            style="font-family: 'Cairo', sans-serif;">
                                                                            {{ trans('Sections_trans.edit_Section') }}
                                                                        </h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <form
                                                                        action="{{ route('sections.update', $section->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PATCH')

                                                                        <div class="modal-body">
                                                                            {{-- Name Inputs (AR/EN) --}}
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <input type="text" name="name_ar"
                                                                                        class="form-control"
                                                                                        value="{{ $section->getTranslation('name', 'ar') }}">
                                                                                </div>
                                                                                <div class="col">
                                                                                    <input type="text" name="name_en"
                                                                                        class="form-control"
                                                                                        value="{{ $section->getTranslation('name', 'en') }}">
                                                                                </div>
                                                                            </div>

                                                                            <br>

                                                                            {{-- Grade Selector --}}
                                                                            <div class="col p-0">
                                                                                <label class="control-label">
                                                                                    {{ trans('Sections_trans.Name_Grade') }}
                                                                                </label>
                                                                                <select name="grade_id"
                                                                                    class="custom-select grade-selector"
                                                                                    data-target="edit-classroom-{{ $section->id }}">
                                                                                    @foreach ($grades as $listGrade)
                                                                                        <option
                                                                                            value="{{ $listGrade->id }}"
                                                                                            @selected($listGrade->id === $section->grade_id)>
                                                                                            {{ $listGrade->Name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <br>

                                                                            {{-- Classroom Selector (depends on selected grade) --}}
                                                                            <div class="col p-0">
                                                                                <label class="control-label">
                                                                                    {{ trans('Sections_trans.Name_Class') }}
                                                                                </label>
                                                                                <select name="classroom_id"
                                                                                    id="edit-classroom-{{ $section->id }}"
                                                                                    class="custom-select classroom-selector">
                                                                                    <option
                                                                                        value="{{ $section->classroom_id }}">
                                                                                        {{ $section->classroom?->name }}
                                                                                    </option>
                                                                                </select>
                                                                            </div>

                                                                            <br>

                                                                            {{-- Status Toggle --}}
                                                                            <div class="col p-0">
                                                                                <div class="form-check">
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input"
                                                                                        name="status" value="1"
                                                                                        id="status{{ $section->id }}"
                                                                                        @checked((int) $section->status === 1)>
                                                                                    <label class="form-check-label"
                                                                                        for="status{{ $section->id }}">
                                                                                        {{ trans('Sections_trans.Status') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>

                                                                            <br>

                                                                            <div class="col">
                                                                                <label for="inputName"
                                                                                    class="control-label">{{ trans('Sections_trans.Name_Teacher') }}</label>
                                                                                <select multiple name="teacher_id[]"
                                                                                    class="form-control"
                                                                                    id="edit-teachers-{{ $section->id }}">
                                                                                    @foreach ($teachers as $teacherOption)
                                                                                        <option
                                                                                            value="{{ $teacherOption->id }}"
                                                                                            @selected($section->teachers->contains('id', $teacherOption->id))>
                                                                                            {{ $teacherOption->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">
                                                                                {{ trans('Sections_trans.Close') }}
                                                                            </button>
                                                                            <button type="submit" class="btn btn-danger">
                                                                                {{ trans('Sections_trans.submit') }}
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Delete Modal: confirms permanent deletion --}}
                                                        <div class="modal fade" id="delete{{ $section->id }}"
                                                            tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            style="font-family: 'Cairo', sans-serif;">
                                                                            {{ trans('Sections_trans.delete_Section') }}
                                                                        </h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <form
                                                                            action="{{ route('sections.destroy', $section->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            {{ trans('Sections_trans.Warning_Section') }}

                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-dismiss="modal">
                                                                                    {{ trans('Sections_trans.Close') }}
                                                                                </button>
                                                                                <button type="submit"
                                                                                    class="btn btn-danger">
                                                                                    {{ trans('Sections_trans.submit') }}
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Create Modal: inserts a new section --}}
                <div class="modal fade" id="createSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" style="font-family: 'Cairo', sans-serif;">
                                    {{ trans('Sections_trans.add_section') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form action="{{ route('sections.store') }}" method="POST">
                                @csrf

                                <div class="modal-body">
                                    @php
                                        $selectedTeacherIds = array_map('strval', old('teacher_id', []));
                                    @endphp

                                    {{-- Name Inputs (AR/EN) --}}
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" name="name_ar" class="form-control"
                                                placeholder="{{ trans('Sections_trans.Section_name_ar') }}"
                                                value="{{ old('name_ar') }}">
                                        </div>
                                        <div class="col">
                                            <input type="text" name="name_en" class="form-control"
                                                placeholder="{{ trans('Sections_trans.Section_name_en') }}"
                                                value="{{ old('name_en') }}">
                                        </div>
                                    </div>

                                    <br>

                                    {{-- Grade Selector --}}
                                    <div class="col p-0">
                                        <label class="control-label">{{ trans('Sections_trans.Name_Grade') }}</label>
                                        <select name="grade_id" class="custom-select grade-selector"
                                            data-target="create-classroom">
                                            <option value="" selected disabled>
                                                {{ trans('Sections_trans.Select_Grade') }}
                                            </option>
                                            @foreach ($grades as $listGrade)
                                                <option value="{{ $listGrade->id }}" @selected((string) old('grade_id') === (string) $listGrade->id)>
                                                    {{ $listGrade->Name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <br>

                                    {{-- Classroom Selector (loaded via AJAX) --}}
                                    <div class="col p-0">
                                        <label class="control-label">{{ trans('Sections_trans.Name_Class') }}</label>
                                        <select name="classroom_id" id="create-classroom"
                                            class="custom-select classroom-selector"></select>
                                    </div>

                                    <br>

                                    <div class="col p-0">
                                        <label for="create-teachers"
                                            class="control-label">{{ trans('Sections_trans.Name_Teacher') }}</label>
                                        <select multiple name="teacher_id[]" class="form-control" id="create-teachers">
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" @selected(in_array((string) $teacher->id, $selectedTeacherIds, true))>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        {{ trans('Sections_trans.Close') }}
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        {{ trans('Sections_trans.submit') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @flasher_render

    <script>
        // Client behavior: refresh classroom options when grade changes in create/edit forms.
        $(document).ready(function() {
            const endpointTemplate = @json(route('sections.getclasses', ['id' => '__grade__']));

            $('.grade-selector').on('change', function() {
                const gradeId = $(this).val();
                const targetId = $(this).data('target');
                const classroomSelect = $('#' + targetId);

                if (!gradeId || classroomSelect.length === 0) {
                    return;
                }

                $.ajax({
                    url: endpointTemplate.replace('__grade__', gradeId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        classroomSelect.empty();

                        $.each(data, function(key, value) {
                            classroomSelect.append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                    },
                });
            });
        });
    </script>
@endsection
