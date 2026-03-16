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
        'identity' => $guardian?->father_name ?? $guardian?->email ?? '-',
    ])

    {{-- Guardian read-only calendar --}}
    <div class="row">
        <div class="col-12 mb-30">
            <livewire:calendar.calendar-widget :editable="false" :compact="false" />
        </div>
    </div>
@endsection
