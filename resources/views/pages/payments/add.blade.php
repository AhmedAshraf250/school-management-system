@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.add_payment_voucher') }}
@stop

@section('page-header')
    {{-- Page Header --}}
@section('PageTitle')
    {{ trans('fees_trans.add_payment_voucher') }} {{ $student->name }}
@stop
@endsection

@section('content')
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

                    {{-- Form: Create Payment Voucher --}}
                    <form method="post" action="{{ route('student-payments.store') }}" autocomplete="off">
                        @csrf

                        {{-- Amount + Final Balance --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('fees_trans.amount') }} : <span class="text-danger">*</span></label>
                                    <input class="form-control" name="debit" type="number">
                                    <input type="hidden" name="student_id" value="{{ $student->id }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('fees_trans.final_balance') }} : </label>
                                    <input class="form-control" name="final_balance"
                                        value="{{ number_format($student->student_account->sum('debit') - $student->student_account->sum('credit'), 2) }}"
                                        type="text" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ trans('fees_trans.description') }} : <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
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
