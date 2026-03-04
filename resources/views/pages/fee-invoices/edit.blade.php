@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.edit_invoice') }}
@stop

@section('page-header')
    {{-- Page Header --}}
@section('PageTitle')
    {{ trans('fees_trans.edit_invoice') }}
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

                    {{-- Form: Update Invoice --}}
                    <form action="{{ route('fee-invoices.update', $feeInvoice->id) }}" method="post" autocomplete="off">
                        @method('PUT')
                        @csrf

                        {{-- Basic Fields --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="inputEmail4">{{ trans('Students_trans.name') }}</label>
                                <input type="text" value="{{ $feeInvoice->student->name }}" readonly class="form-control">
                                <input type="hidden" value="{{ $feeInvoice->id }}" name="id" class="form-control">
                                <input type="hidden" value="{{ $feeInvoice->student->id }}" name="student_id" class="form-control">
                            </div>

                            <div class="form-group col">
                                <label for="inputEmail4">{{ trans('fees_trans.amount') }}</label>
                                <input type="number" value="{{ $feeInvoice->amount }}" name="amount" class="form-control" step="0.01"
                                    readonly>
                            </div>
                        </div>

                        {{-- Fee Type Selector --}}
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="inputZip">{{ trans('fees_trans.fee_type') }}</label>
                                <select class="custom-select mr-sm-2" name="fee_id" id="fee_id">
                                    @foreach ($fees as $fee)
                                        <option value="{{ $fee->id }}" data-amount="{{ $fee->amount }}"
                                            {{ $fee->id == $feeInvoice->fee_id ? 'selected' : '' }}>
                                            {{ $fee->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Description Field --}}
                        <div class="form-group">
                            <label for="inputAddress">{{ trans('fees_trans.description') }}</label>
                            <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="4">{{ $feeInvoice->description }}</textarea>
                        </div>

                        <br>

                        {{-- Submit --}}
                        <button type="submit" class="btn btn-primary">{{ trans('fees_trans.confirm_data') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @toastr_js
    @toastr_render

    <script>
        $(document).ready(function() {
            const feeSelect = $('#fee_id');
            const amountInput = $('input[name="amount"]');

            function syncAmount() {
                const amount = feeSelect.find('option:selected').data('amount');
                amountInput.val(amount ?? '');
            }

            feeSelect.on('change', syncAmount);
            syncAmount();
        });
    </script>
@endsection
