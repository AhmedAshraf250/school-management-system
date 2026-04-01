@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.Main_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_guardian'),
        'identity' => $guardian?->father_name ?? ($guardian?->email ?? '-'),
    ])

    {{-- Guardian dashboard tabs --}}
    @include('pages.guardians.dashboard.partials.tabs')

    {{-- Welcome message --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="alert alert-primary mb-0" role="alert">
                <strong>{{ trans('main_trans.welcome_user', ['name' => $guardian?->father_name]) }}</strong>
            </div>
        </div>
    </div>

    {{-- Guardian quick metrics --}}
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h6 class="mb-2">{{ trans('main_trans.guardian_children_count') }}</h6>
                    <h3 class="mb-0">{{ $students->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h6 class="mb-2">{{ trans('main_trans.guardian_present_today') }}</h6>
                    <h3 class="mb-0 text-success">{{ $presentTodayCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h6 class="mb-2">{{ trans('main_trans.guardian_absent_today') }}</h6>
                    <h3 class="mb-0 text-danger">{{ $absentTodayCount }}</h3>
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

    {{-- Children quick cards --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.guardian_children_overview') }}</h5>

                    <div class="row">
                        @forelse($students as $student)
                            @php
                                $studentImagePath = $student->images->first()?->path;
                            @endphp
                            <div class="col-xl-4 col-md-6 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $studentImagePath ? asset('storage/' . $studentImagePath) : asset('assets/images/user_icon.png') }}"
                                            alt="{{ $student->name }}" class="rounded-circle mr-3"
                                            style="width: 64px; height: 64px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">{{ $student->name }}</h6>
                                            <p class="mb-0 text-muted">{{ $student->grade?->Name ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <hr class="my-3">

                                    <p class="mb-1">
                                        <strong>{{ trans('Students_trans.classrooms') }}:</strong>
                                        {{ $student->classroom?->name ?? '-' }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>{{ trans('Students_trans.section') }}:</strong>
                                        {{ $student->section?->name ?? '-' }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>{{ trans('Students_trans.academic_year') }}:</strong>
                                        {{ $student->academic_year ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning mb-0">
                                    {{ trans('main_trans.guardian_no_children') }}
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Unrecorded attendance hint --}}
    @if ($unrecordedTodayCount > 0)
        <div class="row">
            <div class="col-12 mb-30">
                <div class="alert alert-info mb-0" role="alert">
                    {{ trans('main_trans.guardian_unrecorded_attendance_notice', ['count' => $unrecordedTodayCount]) }}
                </div>
            </div>
        </div>
    @endif
@endsection
