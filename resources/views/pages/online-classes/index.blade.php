@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('OnlineClasses_trans.title') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('OnlineClasses_trans.title') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
{{-- Classes table (integrated + manual) --}}
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <a href="{{ route('online-classes.create') }}" class="btn btn-success" role="button"
                                aria-pressed="true">{{ trans('OnlineClasses_trans.add_integrated_button') }}</a>
                            <a class="btn btn-warning"
                                href="{{ route('online-classes.indirectCreate') }}">{{ trans('OnlineClasses_trans.add_manual_button') }}</a>
                            <div class="table-responsive">
                                <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                    data-page-length="50" style="text-align: center">
                                    <thead>
                                        <tr class="alert-success">
                                            <th>#</th>
                                            <th>{{ trans('OnlineClasses_trans.grade') }}</th>
                                            <th>{{ trans('OnlineClasses_trans.classroom') }}</th>
                                            <th>{{ trans('OnlineClasses_trans.section') }}</th>
                                            <th>{{ trans('OnlineClasses_trans.teacher') }}</th>
                                            <th>{{ trans('OnlineClasses_trans.topic') }}</th>
                                            <th>{{ trans('OnlineClasses_trans.start_at') }}</th>
                                            <th>{{ trans('OnlineClasses_trans.duration') }}</th>
                                            <th>{{ trans('OnlineClasses_trans.join_link') }}</th>
                                            <th>{{ trans('OnlineClasses_trans.operations') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($online_classes as $online_classe)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $online_classe->grade->Name }}</td>
                                                <td>{{ $online_classe->classroom->name }}</td>
                                                <td>{{ $online_classe->section->name }}</td>
                                                <td>{{ $online_classe->user->name }}</td>
                                                <td>{{ $online_classe->topic }}</td>
                                                <td>{{ $online_classe->start_at }}</td>
                                                <td>{{ $online_classe->duration }}</td>
                                                <td class="text-danger"><a href="{{ $online_classe->join_url }}"
                                                        target="_blank">{{ trans('OnlineClasses_trans.join_now') }}</a></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#Delete_receipt{{ $online_classe->id }}"><i
                                                            class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            @include('pages.online-classes.delete')
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')
@toastr_js
@toastr_render
@endsection
