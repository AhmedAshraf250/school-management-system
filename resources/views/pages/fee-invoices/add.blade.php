@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.add_invoice') }}
@stop

@section('page-header')
    {{-- Page Header --}}
@section('PageTitle')
    {{ trans('fees_trans.add_invoice') }} {{ $student->name }}
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

                {{-- Form: Create Invoice Rows --}}
                <form class="row mb-30" action="{{ route('fee-invoices.store') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="repeater">
                            <div data-repeater-list="list_fees">
                                <div data-repeater-item>
                                    <div class="row">
                                        {{-- Student Field --}}
                                        <div class="col">
                                            <label for="Name"
                                                class="mr-sm-2">{{ trans('Students_trans.name') }}</label>
                                            <select class="fancyselect" name="student_id" required>
                                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                                            </select>
                                        </div>

                                        {{-- Fee Type Field --}}
                                        <div class="col">
                                            <label for="Name_en"
                                                class="mr-sm-2">{{ trans('fees_trans.fee_type') }}</label>
                                            <div class="box">
                                                <select class="fancyselect" name="fee_id" required>
                                                    <option value="">{{ trans('classroom_trans.choose') }}
                                                    </option>
                                                    @foreach ($fees as $fee)
                                                        <option value="{{ $fee->id }}"
                                                            data-amount="{{ $fee->amount }}">{{ $fee->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Amount Field --}}
                                        <div class="col">
                                            <label class="mr-sm-2">{{ trans('fees_trans.amount') }}</label>
                                            <div class="box">
                                                <input type="number" class="form-control" name="amount" step="0.01"
                                                    readonly required>
                                            </div>
                                        </div>

                                        {{-- Description Field --}}
                                        <div class="col">
                                            <label for="description"
                                                class="mr-sm-2">{{ trans('fees_trans.description') }}</label>
                                            <div class="box">
                                                <input type="text" class="form-control" name="description" required>
                                            </div>
                                        </div>

                                        {{-- Delete Row Button --}}
                                        <div class="col">
                                            <label for="Name_en"
                                                class="mr-sm-2">{{ trans('fees_trans.processes') }}:</label>
                                            <input class="btn btn-danger btn-block" data-repeater-delete type="button"
                                                value="{{ trans('fees_trans.delete_row') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Add New Row --}}
                            <div class="row mt-20">
                                <div class="col-12">
                                    <input class="button" data-repeater-create type="button"
                                        value="{{ trans('fees_trans.add_row') }}">
                                </div>
                            </div>

                            <br>

                            {{-- Hidden Student Context --}}
                            <input type="hidden" name="grade_id" value="{{ $student->grade_id }}">
                            <input type="hidden" name="classroom_id" value="{{ $student->classroom_id }}">

                            {{-- Submit --}}
                            <button type="submit"
                                class="btn btn-primary">{{ trans('fees_trans.confirm_data') }}</button>
                        </div>
                    </div>
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
    function syncInvoiceRowAmount(feeSelectElement) {
        const feeSelect = $(feeSelectElement);
        const selectedOption = feeSelect.find('option:selected');
        const amount = selectedOption.data('amount');
        const row = feeSelect.closest('[data-repeater-item]');
        const amountInput = row.find('input[name="amount"], input[name$="[amount]"]').first();

        if (!amountInput.length) {
            return;
        }

        amountInput.val(typeof amount !== 'undefined' && amount !== null ? amount : '');
    }

    $(document).on('change', 'select[name="fee_id"], select[name$="[fee_id]"]', function() {
        syncInvoiceRowAmount(this);
    });

    $(document).ready(function() {
        $('select[name="fee_id"], select[name$="[fee_id]"]').each(function() {
            syncInvoiceRowAmount(this);
        });
    });
</script>
@endsection
