@extends('layouts.master')

@section('css')
    {{-- @toastr_css --}}
@endsection

@section('title')
    {{ trans('main_trans.List_Teachers') }}
@endsection

@section('PageTitle')
    {{ trans('main_trans.List_Teachers') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xl-12 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                <a href="{{ route('teachers.create') }}" class="btn btn-success btn-sm" role="button"
                                    aria-pressed="true">{{ trans('Teacher_trans.Add_Teacher') }}</a><br><br>

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

                                <div class="table-responsive">
                                    <table id="datatable" class="table table-hover table-sm table-bordered p-0"
                                        data-page-length="50" style="text-align: center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ trans('Teacher_trans.Name_Teacher') }}</th>
                                                <th>{{ trans('Teacher_trans.Gender') }}</th>
                                                <th>{{ trans('Teacher_trans.Joining_Date') }}</th>
                                                <th>{{ trans('Teacher_trans.specialization') }}</th>
                                                <th>{{ trans('Teacher_trans.processes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($teachers as $teacher)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $teacher->name }}</td>
                                                    <td>{{ $teacher->gender?->name }}</td>
                                                    <td>{{ $teacher->joining_date?->format('Y-m-d') }}</td>
                                                    <td>{{ $teacher->specialization?->name }}</td>
                                                    <td>
                                                        <a href="{{ route('teachers.edit', $teacher->id) }}"
                                                            class="btn btn-info btn-sm" role="button"
                                                            aria-pressed="true"><i class="fa fa-edit"></i></a>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#delete_Teacher{{ $teacher->id }}"
                                                            title="{{ trans('Teacher_trans.Delete_Teacher') }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="delete_Teacher{{ $teacher->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="deleteTeacherLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form action="{{ route('teachers.destroy', $teacher->id) }}"
                                                            method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 style="font-family: 'Cairo', sans-serif;"
                                                                        class="modal-title" id="deleteTeacherLabel">
                                                                        {{ trans('Teacher_trans.Delete_Teacher') }}</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>{{ trans('Teacher_trans.Warning_Teacher') }}</p>
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $teacher->id }}">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">{{ trans('Teacher_trans.Close') }}</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger">{{ trans('Teacher_trans.delete') }}</button>
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
            </div>
        </div>
    </div>
@endsection

@section('js')
    @flasher_render()
@endsection
