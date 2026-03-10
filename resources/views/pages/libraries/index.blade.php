@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('libraries_trans.page_title') }}
@endsection

@section('PageTitle')
    {{ trans('libraries_trans.page_title') }}
@endsection

@section('content')
    {{-- Libraries list page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Header actions --}}
                    <a href="{{ route('libraries.create') }}" class="btn btn-success btn-sm" role="button" aria-pressed="true">
                        {{ trans('libraries_trans.add_new') }}
                    </a>

                    <br><br>

                    {{-- Books data table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('libraries_trans.book_title') }}</th>
                                    <th>{{ trans('libraries_trans.teacher_name') }}</th>
                                    <th>{{ trans('libraries_trans.grade_name') }}</th>
                                    <th>{{ trans('libraries_trans.classroom_name') }}</th>
                                    <th>{{ trans('libraries_trans.section_name') }}</th>
                                    <th>{{ trans('libraries_trans.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($books as $book)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->teacher?->name }}</td>
                                        <td>{{ $book->grade?->Name }}</td>
                                        <td>{{ $book->classroom?->name }}</td>
                                        <td>{{ $book->section?->name }}</td>
                                        <td>
                                            <a href="{{ route('libraries.download', $book->id) }}"
                                                title="{{ trans('libraries_trans.download') }}" class="btn btn-warning btn-sm"
                                                role="button" aria-pressed="true">
                                                <i class="fas fa-download"></i>
                                            </a>

                                            <a href="{{ route('libraries.edit', $book->id) }}" class="btn btn-info btn-sm"
                                                role="button" aria-pressed="true">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_book{{ $book->id }}"
                                                title="{{ trans('libraries_trans.delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    @include('pages.libraries.destroy')
                                @endforeach
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
@endsection
