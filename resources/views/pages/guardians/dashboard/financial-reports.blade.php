@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.guardian_financial_tab') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_guardian'),
        'identity' => $guardian?->father_name ?? ($guardian?->email ?? '-'),
    ])

    {{-- Guardian dashboard tabs --}}
    @include('pages.guardians.dashboard.partials.tabs')

    {{-- Financial filters --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.guardian_financial_tab') }}</h5>

                    <form method="GET" action="{{ route('guardian.dashboard.financial') }}">
                        <div class="row align-items-end">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label
                                        for="guardian_financial_student_id">{{ trans('main_trans.guardian_select_child') }}</label>
                                    <select id="guardian_financial_student_id" name="student_id" class="custom-select">
                                        @forelse($students as $student)
                                            <option value="{{ $student->id }}" @selected($selectedStudent?->id === $student->id)>
                                                {{ $student->name }}</option>
                                        @empty
                                            <option value="">{{ trans('main_trans.guardian_no_children') }}</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-primary btn-block" type="submit">
                                    {{ trans('main_trans.dashboard_view_data') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Financial totals --}}
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h6 class="mb-2">{{ trans('main_trans.guardian_total_invoices') }}</h6>
                    <h3 class="mb-0">{{ number_format($totalInvoicesAmount, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h6 class="mb-2">{{ trans('main_trans.guardian_total_debit') }}</h6>
                    <h3 class="mb-0">{{ number_format($totalDebit, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h6 class="mb-2">{{ trans('main_trans.guardian_total_credit') }}</h6>
                    <h3 class="mb-0">{{ number_format($totalCredit, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h6 class="mb-2">{{ trans('main_trans.guardian_current_balance') }}</h6>
                    <h3 class="mb-0">{{ number_format($outstandingAmount, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Invoices and account entries --}}
    <div class="row">
        <div class="col-lg-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.dashboard_latest_invoices') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ trans('main_trans.dashboard_invoice_date') }}</th>
                                    <th>{{ trans('fees_trans.amount') }}</th>
                                    <th>{{ trans('fees_trans.name') }}</th>
                                    <th>{{ trans('fees_trans.description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td>{{ optional($invoice->invoice_date)->format('Y-m-d') }}</td>
                                        <td>{{ number_format((float) $invoice->amount, 2) }}</td>
                                        <td>{{ $invoice->fee?->title ?? '-' }}</td>
                                        <td>{{ $invoice->description ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted">{{ trans('main_trans.dashboard_no_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.guardian_account_movements') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ trans('main_trans.teacher_reports_date_from') }}</th>
                                    <th>{{ trans('main_trans.guardian_total_debit') }}</th>
                                    <th>{{ trans('main_trans.guardian_total_credit') }}</th>
                                    <th>{{ trans('fees_trans.description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accountEntries as $entry)
                                    <tr>
                                        <td>{{ optional($entry->date)->format('Y-m-d') }}</td>
                                        <td>{{ number_format((float) $entry->debit, 2) }}</td>
                                        <td>{{ number_format((float) $entry->credit, 2) }}</td>
                                        <td>{{ $entry->description ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted">{{ trans('main_trans.dashboard_no_data') }}
                                        </td>
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
