@extends('layouts.master')

@section('css')
    {{-- @toastr_css  // IGNORE (using Flasher instead of Toastr) --}}
@endsection

@section('title')
    {{ trans('classroom_trans.title_page') }}
@endsection

@section('PageTitle')
    {{ trans('classroom_trans.title_page') }}
@stop

@section('content')

    <!-- Main row -->
    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Button to open Add Classroom modal --}}
                    <button type="button" class="button x-small" data-toggle="modal" data-target="#addClassroomModal">
                        {{ trans('classroom_trans.add_class') }}
                    </button>

                    <button type="button" class="button x-small" id="btn_delete_all">
                        {{ trans('classroom_trans.delete_checkbox') }}
                    </button>

                    <br><br>

                    {{-- Search form --}}
                    <form action="{{ route('classrooms.filter_classes') }}" method="GET">
                        <select class="selectpicker" data-style="btn-info" name="grade_id" onchange="this.form.submit()">
                            <option value="" disabled {{ !request('grade_id') ? 'selected' : '' }}>
                                {{ trans('classroom_trans.Search_By_Grade') }}
                            </option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}"
                                    {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->Name }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    {{-- Classrooms table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th><input name="select_all" id="example-select-all" type="checkbox"
                                            onclick="CheckAll('box1', this)" /></th>
                                    <th>#</th>
                                    <th>{{ trans('classroom_trans.Name_class') }}</th>
                                    <th>{{ trans('classroom_trans.Name_Grade') }}</th>
                                    <th>{{ trans('classroom_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($classrooms as $index => $classroom)
                                    <tr>
                                        <td><input type="checkbox" value="{{ $classroom->id }}" class="box1"></td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $classroom->name }}</td>
                                        <td>{{ $classroom->grade->Name }}</td>
                                        <td>
                                            {{-- Edit button --}}
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#editClassroomModal{{ $classroom->id }}"
                                                title="{{ trans('classroom_trans.Edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            {{-- Delete button --}}
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteClassroomModal{{ $classroom->id }}"
                                                title="{{ trans('classroom_trans.Delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- End table --}}

                </div>
            </div>
        </div>
    </div>
    {{-- End main row --}}


    {{-- ============================================================ --}}
    {{-- Edit & Delete modals — placed outside the table (valid HTML) --}}
    {{-- ============================================================ --}}
    @foreach ($classrooms as $classroom)
        {{-- Edit Classroom Modal --}}
        <div class="modal fade" id="editClassroomModal{{ $classroom->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editClassroomLabel{{ $classroom->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editClassroomLabel{{ $classroom->id }}"
                            style="font-family: 'Cairo', sans-serif;">
                            {{ trans('classroom_trans.edit_class') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                {{-- Arabic name --}}
                                <div class="col">
                                    <label for="Name_{{ $classroom->id }}" class="mr-sm-2">
                                        {{ trans('classroom_trans.Name_class') }} :
                                    </label>
                                    <input id="Name_{{ $classroom->id }}" type="text" name="Name"
                                        class="form-control" value="{{ $classroom->getTranslation('name', 'ar') }}"
                                        required>
                                </div>

                                {{-- English name --}}
                                <div class="col">
                                    <label for="Name_en_{{ $classroom->id }}" class="mr-sm-2">
                                        {{ trans('classroom_trans.Name_class_en') }} :
                                    </label>
                                    <input id="Name_en_{{ $classroom->id }}" type="text" name="Name_en"
                                        class="form-control" value="{{ $classroom->getTranslation('name', 'en') }}"
                                        required>
                                </div>
                            </div>

                            <br>

                            {{-- Grade select --}}
                            <div class="form-group">
                                <label for="grade_id_{{ $classroom->id }}" class="mr-sm-2">
                                    {{ trans('classroom_trans.Name_Grade') }} :
                                </label>
                                <select id="grade_id_{{ $classroom->id }}" name="grade_id" class="form-control" required>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            {{ $classroom->grade_id == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ trans('classroom_trans.Close') }}</button>
                                <button type="submit"
                                    class="btn btn-success">{{ trans('classroom_trans.update') }}</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        {{-- End Edit Modal --}}


        {{-- Delete Classroom Modal --}}
        <div class="modal fade" id="deleteClassroomModal{{ $classroom->id }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteClassroomLabel{{ $classroom->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteClassroomLabel{{ $classroom->id }}"
                            style="font-family: 'Cairo', sans-serif;">
                            {{ trans('classroom_trans.Delete') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            {{-- Warning message before deleting --}}
                            <p>{{ trans('classroom_trans.warning_delete') }}</p>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ trans('classroom_trans.Close') }}</button>
                                <button type="submit"
                                    class="btn btn-danger">{{ trans('classroom_trans.delete') }}</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        {{-- End Delete Modal --}}
    @endforeach


    {{-- ========================== --}}
    {{-- Delete group of classrooms --}}
    {{-- ========================== --}}
    <div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        {{ trans('classroom_trans.delete_class') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('classrooms.delete_all') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        {{ trans('classroom_trans.warning_delete') }}
                        <input class="text" type="hidden" id="delete_all_id" name="delete_all_id" value=''>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ trans('classroom_trans.Close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ trans('classroom_trans.delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Delete Group --}}

    {{-- ============================================================ --}}
    {{-- Add Classroom Modal (with repeater for bulk adding)          --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="addClassroomModal" tabindex="-1" role="dialog" aria-labelledby="addClassroomLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="addClassroomLabel" style="font-family: 'Cairo', sans-serif;">
                        {{ trans('classroom_trans.add_class') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('classrooms.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            {{-- Repeater for adding multiple classrooms at once --}}
                            <div class="repeater">
                                <div data-repeater-list="List_Classes">
                                    <div data-repeater-item>
                                        <div class="row">

                                            {{-- Arabic class name --}}
                                            <div class="col">
                                                <label class="mr-sm-2">
                                                    {{ trans('classroom_trans.Name_class') }} :
                                                </label>
                                                <input class="form-control" type="text" name="Name" required />
                                            </div>

                                            {{-- English class name --}}
                                            <div class="col">
                                                <label class="mr-sm-2">
                                                    {{ trans('classroom_trans.Name_class_en') }} :
                                                </label>
                                                <input class="form-control" type="text" name="Name_class_en"
                                                    required />
                                            </div>

                                            {{-- Grade select --}}
                                            <div class="col">
                                                <label class="mr-sm-2">
                                                    {{ trans('classroom_trans.Name_Grade') }} :
                                                </label>
                                                <div class="box">
                                                    <select class="fancyselect form-control" name="grade_id" required>
                                                        @foreach ($grades as $grade)
                                                            <option value="{{ $grade->id }}">{{ $grade->Name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Delete row button (repeater) --}}
                                            <div class="col">
                                                <label class="mr-sm-2">
                                                    {{ trans('classroom_trans.Processes') }} :
                                                </label>
                                                <input class="btn btn-danger btn-block" data-repeater-delete
                                                    type="button" value="{{ trans('classroom_trans.delete_row') }}" />
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                {{-- Add new row button --}}
                                <div class="row mt-20">
                                    <div class="col-12">
                                        <input class="button" data-repeater-create type="button"
                                            value="{{ trans('classroom_trans.add_row') }}" />
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ trans('classroom_trans.Close') }}</button>
                            <button type="submit" class="btn btn-success">{{ trans('classroom_trans.submit') }}</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- End Add Modal --}}

@endsection

@section('js')
    @flasher_render
@endsection

@push('scripts')
    {{-- script for check all --}}
    <script>
        function CheckAll(className, elem) {
            var elements = document.getElementsByClassName(className);
            var l = elements.length;

            if (elem.checked) {
                for (var i = 0; i < l; i++) {
                    elements[i].checked = true;
                }
            } else {
                for (var i = 0; i < l; i++) {
                    elements[i].checked = false;
                }
            }
        }
    </script>

    {{-- doing the delete to the checked  --}}
    <script type="text/javascript">
        $(function() {
            $("#btn_delete_all").click(function() {
                var selected = new Array();
                $("#datatable input[type=checkbox]:checked").not('#example-select-all').each(function() {
                    selected.push(this.value);
                });

                if (selected.length > 0) {
                    $('#delete_all').modal('show')
                    $('input[id="delete_all_id"]').val(selected);
                } else {
                    toastr.warning('{{ trans('classroom_trans.select_at_least_one') }}');
                }
            });
        });
    </script>
@endpush
