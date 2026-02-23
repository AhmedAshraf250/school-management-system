@extends('layouts.master')

@section('css')
    {{-- @toastr_css  // IGNORE (using Flasher instead of Toastr) --}}
@endsection

@section('title')
    {{ trans('Grades_trans.title_page') }}
@stop

@section('PageTitle')
    {{ trans('main_trans.Grades') }}
@stop

@section('content')
    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <button type="button" class="button x-small" data-toggle="modal" data-target="#add-grade-modal">
                        {{ trans('Grades_trans.add_Grade') }}
                    </button>

                    <br><br>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('Grades_trans.Name') }}</th>
                                    <th>{{ trans('Grades_trans.Notes') }}</th>
                                    <th>{{ trans('Grades_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grades as $grade)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $grade->Name }}</td>
                                        <td>{{ $grade->Notes }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm edit-btn"
                                                data-id="{{ $grade->id }}"
                                                data-name-ar="{{ $grade->getTranslation('Name', 'ar') }}"
                                                data-name-en="{{ $grade->getTranslation('Name', 'en') }}"
                                                data-notes="{{ $grade->Notes }}" title="{{ trans('Grades_trans.Edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $grade->id }}" data-name="{{ $grade->Name }}"
                                                title="{{ trans('Grades_trans.Delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <x-modals.grade-add />

        <x-modals.grade-edit />

        <x-modals.grade-delete />
    </div>
@endsection

@section('js')
    @flasher_render

    <script>
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            let nameAr = $(this).data('name-ar');
            let nameEn = $(this).data('name-en');
            let notes = $(this).data('notes');

            $('#edit-modal-id').val(id);
            $('#edit-modal-name-ar').val(nameAr);
            $('#edit-modal-name-en').val(nameEn);
            $('#edit-modal-notes').val(notes);
            $('#edit-modal-form').attr('action', '/grades/' + id);

            $('#edit-grade-modal').modal('show');
        });

        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');

            $('#delete-modal-id').val(id);
            $('#delete-modal-name').text(name);
            $('#delete-modal-form').attr('action', '/grades/' + id);

            $('#delete-grade-modal').modal('show');
        });
    </script>
@endsection
