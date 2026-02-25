@extends('layouts.master')

@section('title')
    {{ trans('main_trans.list_students') }}
@endsection

@section('PageTitle')
    {{ trans('main_trans.list_students') }}
@endsection

@section('content')
    {{-- Students page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Top actions --}}
                    <div class="mb-3">
                        <a href="{{ route('students.create') }}"
                            class="btn btn-success btn-sm d-inline-flex align-items-center px-3 py-2 rounded-pill shadow-sm"
                            role="button">
                            <i class="fa fa-plus mr-2"></i>
                            {{ trans('main_trans.add_student') }}
                        </a>
                    </div>

                    {{-- Students listing table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0"
                            data-page-length="50" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('Students_trans.name') }}</th>
                                    <th>{{ trans('Students_trans.email') }}</th>
                                    <th>{{ trans('Students_trans.gender') }}</th>
                                    <th>{{ trans('Students_trans.Grade') }}</th>
                                    <th>{{ trans('Students_trans.classrooms') }}</th>
                                    <th>{{ trans('Students_trans.section') }}</th>
                                    <th>{{ trans('Students_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    {{-- Student row --}}
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->gender?->name }}</td>
                                        <td>{{ $student->grade?->Name }}</td>
                                        <td>{{ $student->classroom?->name }}</td>
                                        <td>{{ $student->section?->name }}</td>
                                        <td>
                                            {{-- Row actions --}}
                                            <a href="{{ route('students.edit', $student->id) }}"
                                                class="btn btn-info btn-sm d-inline-flex align-items-center px-3 rounded-pill"
                                                role="button" title="{{ trans('messages.Update') }}">
                                                <i class="fa fa-edit mr-1"></i>
                                                {{ trans('messages.Update') }}
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm d-inline-flex align-items-center px-3 rounded-pill"
                                                data-toggle="modal"
                                                data-target="#Delete_Student{{ $student->id }}"
                                                title="{{ trans('messages.Delete') }}">
                                                <i class="fa fa-trash mr-1"></i>
                                                {{ trans('messages.Delete') }}
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Delete modal --}}
                                    @include('pages.students.delete', ['student' => $student])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
