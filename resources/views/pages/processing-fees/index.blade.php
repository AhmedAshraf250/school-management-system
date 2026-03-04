@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.processing_fees') }}
@stop

@section('page-header')
    {{-- Page Header --}}
@section('PageTitle')
    {{ trans('fees_trans.processing_fees') }}
@stop
@endsection

@section('content')
    {{-- Main Content --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xl-12 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                {{-- Processing Fees Table --}}
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                                        style="text-align: center">
                                        <thead>
                                            <tr class="alert-success">
                                                <th>#</th>
                                                <th>{{ trans('Students_trans.name') }}</th>
                                                <th>{{ trans('fees_trans.amount') }}</th>
                                                <th>{{ trans('fees_trans.description') }}</th>
                                                <th>{{ trans('fees_trans.processes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($processingFees as $processingFee)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $processingFee->student->name }}</td>
                                                    <td>{{ number_format($processingFee->amount, 2) }}</td>
                                                    <td>{{ $processingFee->description }}</td>
                                                    <td>
                                                        {{-- Row Actions --}}
                                                        <a href="{{ route('processing-fees.edit', $processingFee->id) }}"
                                                            class="btn btn-info btn-sm" role="button" aria-pressed="true">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                            data-target="#Delete_processing_fee{{ $processingFee->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                {{-- Delete Modal --}}
                                                @include('pages.processing-fees.partials.delete', ['processingFee' => $processingFee])
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
    @toastr_js
    @toastr_render
@endsection
