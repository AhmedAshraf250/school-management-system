@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.tuition_fees') }}
@endsection

@section('PageTitle')
    {{ trans('fees_trans.tuition_fees') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <a href="{{ route('fees.create') }}" class="btn btn-success btn-sm" role="button" aria-pressed="true">
                        {{ trans('fees_trans.add_new_fees') }}
                    </a>
                    <br><br>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center;">
                            <thead>
                                <tr class="alert-success">
                                    <th>#</th>
                                    <th>{{ trans('fees_trans.name') }}</th>
                                    <th>{{ trans('fees_trans.amount') }}</th>
                                    <th>{{ trans('fees_trans.grade') }}</th>
                                    <th>{{ trans('fees_trans.classroom') }}</th>
                                    <th>{{ trans('fees_trans.academic_year') }}</th>
                                    <th>{{ trans('fees_trans.notes') }}</th>
                                    <th>{{ trans('fees_trans.processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fees as $fee)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $fee->title }}</td>
                                        <td>{{ number_format((float) $fee->amount, 2) }}</td>
                                        <td>{{ $fee->grade?->Name ?? '-' }}</td>
                                        <td>{{ $fee->classroom?->name ?? '-' }}</td>
                                        <td>{{ $fee->year }}</td>
                                        <td>{{ $fee->description ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('fees.edit', $fee->id) }}" class="btn btn-info btn-sm"
                                                role="button" aria-pressed="true" title="{{ trans('fees_trans.edit_fee') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#Delete_Fee{{ $fee->id }}"
                                                title="{{ trans('Grades_trans.Delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @include('pages.students.fees.delete', ['fee' => $fee])
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-muted">{{ trans('fees_trans.no_fees') }}</td>
                                    </tr>
                                @endforelse
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
