@extends('layouts.master')

@section('css')
@toastr_css
@endsection

@section('title')
{{ trans('Grades_trans.title_page') }}
@stop

{{-- @section('page-header')
{{ trans('main_trans.Grades') }}
@stop --}}

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
                    <strong>{{ trans('main_trans.Error') }}:</strong>
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
                            @foreach ($Grades as $Grade)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $Grade->Name }}</td>
                                <td>{{ $Grade->Notes }}</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#edit{{ $Grade->id }}" title="{{ trans('Grades_trans.Edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#delete{{ $Grade->id }}"
                                        title="{{ trans('Grades_trans.Delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            {{-- Edit & Delete Modals --}}
                            <x-modals.grade-edit :grade="$Grade" />
                            <x-modals.grade-delete :grade="$Grade" />
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <x-modals.grade-add />
</div>
@endsection

@section('js')
@toastr_js
@toastr_render
@endsection