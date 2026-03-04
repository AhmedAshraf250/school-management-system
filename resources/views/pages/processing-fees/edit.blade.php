@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.edit_processing_fee') }}
@stop

@section('page-header')
    {{-- Page Header --}}
@section('PageTitle')
    {{ trans('fees_trans.edit_processing_fee') }} : <label style="color: red">{{ $processingFee->student->name }}</label>
@stop
@endsection

@section('content')

@php
    $outstandingBalance =
        $processingFee->student->student_account->sum('debit') -
        $processingFee->student->student_account->sum('credit');
@endphp

{{-- Main Content --}}
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form: Update Processing Fee --}}
                <form action="{{ route('processing-fees.update', $processingFee->id) }}" method="post" autocomplete="off">
                    @method('PUT')
                    @csrf

                    {{-- Amount + Hidden Data --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('fees_trans.amount') }} : <span class="text-danger">*</span></label>
                                <input class="form-control" name="debit" value="{{ $processingFee->amount }}"
                                    type="number">
                                <input type="hidden" name="student_id" value="{{ $processingFee->student->id }}"
                                    class="form-control">
                                <input type="hidden" name="id" value="{{ $processingFee->id }}"
                                    class="form-control">
                            </div>
                        </div>

                        {{-- Outstanding Balance --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('fees_trans.final_balance') }} : </label>
                                <input class="form-control" name="final_balance"
                                    value="{{ number_format($outstandingBalance, 2) }}" type="text" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('fees_trans.description') }} : <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3">{{ $processingFee->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="submit">
                        {{ trans('Students_trans.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@toastr_js
@toastr_render
@endsection
