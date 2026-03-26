@extends('layouts.user-portal')
@push('css')
    @toastr_css
@endpush

@section('content')
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])

    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', ['title' => trans('OnlineClasses_trans.title')])

    {{-- Classes table (integrated + manual) --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xl-12 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                <a href="{{ route('teacher.online-classes.create') }}" class="btn btn-success" role="button"
                                    aria-pressed="true">{{ trans('OnlineClasses_trans.add_integrated_button') }}</a>
                                <a class="btn btn-warning"
                                    href="{{ route('teacher.online-classes.indirectCreate') }}">{{ trans('OnlineClasses_trans.add_manual_button') }}</a>
                                <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">
                                    {{ trans('main_trans.back_action') }}
                                </a>
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
                                                <th>{{ trans('OnlineClasses_trans.passcode') }}</th>
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
                                                    <td>{{ $online_classe->creator_display_name }}</td>
                                                    <td>{{ $online_classe->topic }}</td>
                                                    <td>{{ $online_classe->start_at }}</td>
                                                    <td>{{ $online_classe->duration }}</td>
                                                    <td>
                                                        <span class="meeting-password-mask"
                                                            data-password="{{ $online_classe->password }}">••••••••</span>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-info toggle-password-visibility ml-1"
                                                            data-show-text="{{ trans('OnlineClasses_trans.show_passcode') }}"
                                                            data-hide-text="{{ trans('OnlineClasses_trans.hide_passcode') }}">
                                                            {{ trans('OnlineClasses_trans.show_passcode') }}
                                                        </button>
                                                    </td>
                                                    <td class="text-danger"><a href="{{ $online_classe->join_url }}"
                                                            target="_blank">{{ trans('OnlineClasses_trans.join_now') }}</a>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#Delete_receipt{{ $online_classe->id }}"><i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                                @include('pages.teachers.dashboard.online-classes.delete')
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
@push('scripts')
    @toastr_js
    @toastr_render
    <script>
        document.addEventListener('click', function(event) {
            const button = event.target.closest('.toggle-password-visibility');

            if (!button) {
                return;
            }

            const container = button.closest('td');
            const passwordSpan = container?.querySelector('.meeting-password-mask');

            if (!passwordSpan) {
                return;
            }

            const isMasked = passwordSpan.textContent.trim() === '••••••••';
            const passwordValue = passwordSpan.dataset.password ?? '';

            if (isMasked) {
                passwordSpan.textContent = passwordValue;
                button.textContent = button.dataset.hideText ?? '';
            } else {
                passwordSpan.textContent = '••••••••';
                button.textContent = button.dataset.showText ?? '';
            }
        });
    </script>
@endpush
