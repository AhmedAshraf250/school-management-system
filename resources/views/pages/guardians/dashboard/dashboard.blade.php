@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.Main_title') }}
@stop

@section('content')
    @php
        $guardian = auth()->guard('guardian')->user();
    @endphp

    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_guardian'),
        'identity' => $guardian?->father_name ?? ($guardian?->email ?? '-'),
    ])

    {{-- Welcome message --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="alert alert-primary mb-0" role="alert">
                <strong>{{ trans('main_trans.welcome_user', ['name' => $guardian?->father_name]) }}</strong>
            </div>
        </div>
    </div>

    {{-- Current guardian quick info --}}
    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">{{ trans('main_trans.current_user_label') }}</h5>
                    <p class="mb-0">
                        <strong>{{ trans('Parent_trans.Name_Father') }}:</strong>
                        {{ $guardian?->father_name }}
                    </p>
                    <p class="mb-0">
                        <strong>{{ trans('Parent_trans.Email') }}:</strong> {{ $guardian?->email }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
