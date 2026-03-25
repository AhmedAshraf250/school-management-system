@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.Main_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? $teacher->email ?? '-',
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', ['title' => trans('main_trans.dashboard_calendar_title')])

    {{-- Teacher editable calendar --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <livewire:calendar.calendar-widget :editable="true" :compact="false" />
            </div>
        </div>
    </div>
@endsection
