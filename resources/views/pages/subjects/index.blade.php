@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('Subjects_trans.list') }}
@endsection

@section('PageTitle')
    {{ trans('Subjects_trans.list') }}
@endsection

@section('content')
    {{-- Subjects page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Top action --}}
                    <div class="mb-3">
                        <a href="{{ route('subjects.create') }}" class="btn btn-success btn-sm" role="button"
                            aria-pressed="true">
                            {{ trans('Subjects_trans.add_new') }}
                        </a>
                    </div>

                    {{-- Subjects listing table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('Subjects_trans.name') }}</th>
                                    <th>{{ trans('Subjects_trans.grade') }}</th>
                                    <th>{{ trans('Subjects_trans.classroom') }}</th>
                                    <th>{{ trans('Subjects_trans.teacher') }}</th>
                                    <th>{{ trans('Subjects_trans.processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->grade?->Name }}</td>
                                        <td>{{ $subject->classroom?->name }}</td>
                                        <td>{{ $subject->teacher?->name }}</td>
                                        <td>
                                            <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-info btn-sm"
                                                role="button" aria-pressed="true">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_subject{{ $subject->id }}" title="{{ trans('Subjects_trans.delete_title') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Delete subject modal --}}
                                    <div class="modal fade" id="delete_subject{{ $subject->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="deleteSubjectLabel{{ $subject->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteSubjectLabel{{ $subject->id }}">
                                                            {{ trans('Subjects_trans.delete_title') }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ trans('Subjects_trans.delete_warning') }} {{ $subject->name }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ trans('Students_trans.Close') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ trans('Students_trans.submit') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
