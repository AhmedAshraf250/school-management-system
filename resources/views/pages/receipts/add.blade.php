@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.add_receipt') }}
@stop

@section('page-header')
    {{-- Page Header --}}
@section('PageTitle')
    {{ trans('fees_trans.add_receipt') }} {{ $student->name }}
@stop
@endsection

@section('content')
    @php
        $outstandingBalance = $student->student_account->sum('debit') - $student->student_account->sum('credit');
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

                    @if ($outstandingBalance <= 0)
                        <div class="alert alert-warning">
                            {{ trans('fees_trans.no_outstanding_balance') }}
                        </div>
                    @endif

                    {{-- Form: Create Receipt --}}
                    <form method="post" action="{{ route('receipts.store') }}" autocomplete="off">
                        @csrf

                        {{-- Amount + Student Context --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('fees_trans.amount') }} : <span class="text-danger">*</span></label>
                                    <input class="form-control" name="debit" type="number" step="0.01" min="0.01"
                                        max="{{ max((float) $outstandingBalance, 0) }}"
                                        value="{{ old('debit') }}">
                                    <input type="hidden" name="student_id" value="{{ $student->id }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('fees_trans.final_balance') }} : </label>
                                    <input class="form-control" name="final_balance"
                                        value="{{ number_format($outstandingBalance, 2) }}"
                                        type="text" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ trans('fees_trans.description') }} : <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="submit"
                            @disabled($outstandingBalance <= 0)>
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
