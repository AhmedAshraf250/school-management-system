@extends('layouts.master')

@section('title')
    {{ trans('main_trans.Add_Parent') }}
@stop

@section('PageTitle')
    {{ trans('main_trans.Add_Parent') }}
@stop

@section('content')
    {{-- Guardian wizard page container --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <livewire:add-guardian />
                </div>
            </div>
        </div>
    </div>
@endsection
