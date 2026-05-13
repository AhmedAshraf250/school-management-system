@extends('layouts.user-portal')

@section('title')
    {{ trans('OnlineClasses_trans.student_title') }}
@stop

@push('css')
    @toastr_css
@endpush

@section('content')
    {{-- Student dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_student'),
        'identity' => $student->name ?? ($student->email ?? '-'),
    ])

    {{-- Student online classes listing --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <h5 class="mb-0">{{ trans('OnlineClasses_trans.student_title') }}</h5>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            {{ trans('main_trans.back_action') }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="text-align: center;">
                            <thead>
                                <tr class="table-info text-danger">
                                    <th>#</th>
                                    <th>{{ trans('OnlineClasses_trans.topic') }}</th>
                                    <th>{{ trans('OnlineClasses_trans.teacher') }}</th>
                                    <th>{{ trans('OnlineClasses_trans.start_at') }}</th>
                                    <th>{{ trans('OnlineClasses_trans.duration') }}</th>
                                    <th>{{ trans('OnlineClasses_trans.passcode') }}</th>
                                    <th>{{ trans('OnlineClasses_trans.join_link') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($onlineClasses as $onlineClass)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $onlineClass->topic }}</td>
                                        <td>{{ $onlineClass->creator_display_name }}</td>
                                        <td>{{ $onlineClass->start_at }}</td>
                                        <td>{{ $onlineClass->duration }}</td>
                                        <td>{{ $onlineClass->password }}</td>
                                        <td>
                                            <a href="{{ $onlineClass->join_url }}" target="_blank" rel="noopener noreferrer">
                                                {{ trans('OnlineClasses_trans.join_now') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-muted">{{ trans('main_trans.teacher_reports_no_data') }}</td>
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

@push('scripts')
    @toastr_js
    @toastr_render
@endpush
