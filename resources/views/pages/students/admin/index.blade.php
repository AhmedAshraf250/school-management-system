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
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center;">
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
                                            <div class="dropdown show">
                                                <a class="btn btn-success btn-sm dropdown-toggle" href="#"
                                                    role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    {{ trans('Students_trans.Processes') }}
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item"
                                                        href="{{ route('students.show', $student->id) }}"><i
                                                            style="color: #ffc107" class="far fa-eye "></i>&nbsp;
                                                        {{ trans('Students_trans.show_students') }}</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('students.edit', $student->id) }}"><i
                                                            style="color:green" class="fa fa-edit"></i>&nbsp;
                                                        {{ trans('Students_trans.edit_students') }}</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('fee-invoices.show', $student->id) }}"><i
                                                            style="color: #0000cc" class="fa fa-edit"></i>&nbsp;
                                                        {{ trans('Students_trans.add_fee_ivoice') }}</a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('receipts.show', $student->id) }}"><i
                                                            style="color: #9dc8e2" class="fas fa-money-bill-alt"></i>&nbsp;
                                                        &nbsp;{{ trans('Students_trans.receipt') }}</a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('processing-fees.show', $student->id) }}"><i
                                                            style="color: #9dc8e2" class="fas fa-money-bill-alt"></i>&nbsp;
                                                        &nbsp; {{ trans('fees_trans.fee_exclusion') }}</a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('student-payments.show', $student->id) }}"><i
                                                            style="color:goldenrod" class="fas fa-donate"></i>&nbsp;
                                                        &nbsp; {{ trans('fees_trans.payment_voucher') }}</a>

                                                    <a class="dropdown-item"
                                                        data-target="#Delete_Student{{ $student->id }}"
                                                        data-toggle="modal" href="##Delete_Student{{ $student->id }}"><i
                                                            style="color: red" class="fa fa-trash"></i>&nbsp;
                                                        {{ trans('Students_trans.Deleted_Student') }}</a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                    {{-- Delete modal --}}
                                    @include('pages.students.admin.delete', ['student' => $student])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
