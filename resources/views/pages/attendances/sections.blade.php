@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('Attendance_trans.title') }}
@stop
@endsection
@section('page-header')
    {{-- Page title (breadcrumb area) --}}
@section('PageTitle')
    {{ trans('Attendance_trans.title') }}
@stop
@endsection
@section('content')
    {{-- Section groups wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                {{-- Validation errors block --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
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

                <div class="card-body">
                    {{-- Grade accordion --}}
                    <div class="accordion gray plus-icon round">
                        @foreach ($grades as $grade)
                            <div class="acd-group">
                                <a href="#" class="acd-heading">{{ $grade->Name }}</a>
                                <div class="acd-des">
                                    {{-- Sections table inside each grade --}}
                                    <div class="table-responsive mt-15">
                                        <table class="table center-aligned-table mb-0">
                                            <thead>
                                                <tr class="text-dark">
                                                    <th>#</th>
                                                    <th>{{ trans('Sections_trans.Name_Section') }}</th>
                                                    <th>{{ trans('Sections_trans.Name_Class') }}</th>
                                                    <th>{{ trans('Sections_trans.Status') }}</th>
                                                    <th>{{ trans('Sections_trans.Processes') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($grade->sections as $section)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $section->name }}</td>
                                                        <td>{{ $section->classroom?->name }}</td>
                                                        <td>
                                                            <label
                                                                class="badge badge-{{ $section->status ? 'success' : 'danger' }}">
                                                                {{ $section->status ? trans('Attendance_trans.status_active') : trans('Attendance_trans.status_inactive') }}
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('attendances.show', $section->id) }}"
                                                                class="btn btn-warning btn-sm" role="button"
                                                                aria-pressed="true">
                                                                {{ trans('Attendance_trans.students_list') }}
                                                            </a>
                                                        </td>
                                                    </tr>
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
        </div>
    </div>
@endsection
@section('js')
    @toastr_js
    @toastr_render
@endsection
