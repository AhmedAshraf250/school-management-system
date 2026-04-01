@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('fees_trans.receipts') }}
@stop

@section('page-header')
    {{-- Page Header --}}
@section('PageTitle')
    {{ trans('fees_trans.receipts') }}
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
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                    <div class="btn-group btn-group-sm mb-2 mb-md-0" role="group">
                                        <a href="{{ route('receipts.index') }}"
                                            class="btn {{ $isTrashView ? 'btn-outline-primary' : 'btn-primary' }}">
                                            {{ trans('fees_trans.active_records') }}
                                        </a>
                                        <a href="{{ route('receipts.index', ['trash' => 1]) }}"
                                            class="btn {{ $isTrashView ? 'btn-primary' : 'btn-outline-primary' }}">
                                            {{ trans('fees_trans.trash_records') }}
                                        </a>
                                    </div>
                                    @if ($isTrashView)
                                        <span class="badge badge-warning p-2">{{ trans('fees_trans.archived_notice') }}</span>
                                    @endif
                                </div>

                                {{-- Receipts Table --}}
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
                                            @foreach ($receipts as $receipt)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $receipt->student->name }}</td>
                                                    <td>{{ number_format($receipt->debit, 2) }}</td>
                                                    <td>{{ $receipt->description }}</td>
                                                    <td>
                                                        {{-- Row Actions --}}
                                                        @if ($isTrashView)
                                                            <form action="{{ route('receipts.restore', $receipt->id) }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-success btn-sm">
                                                                    <i class="fa fa-undo"></i> {{ trans('fees_trans.restore') }}
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="{{ route('receipts.edit', $receipt->id) }}"
                                                                class="btn btn-info btn-sm" role="button"
                                                                aria-pressed="true">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#Delete_receipt{{ $receipt->id }}">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>

                                                @if (! $isTrashView)
                                                    {{-- Delete Modal --}}
                                                    @include('pages.receipts.partials.delete', ['receipt' => $receipt])
                                                @endif
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
