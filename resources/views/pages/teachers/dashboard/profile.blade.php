@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.teacher_profile_title') }}
@stop

@section('content')
    {{-- Unified page heading for teacher profile tab --}}
    @include('pages.teachers.partials.page-heading', ['title' => trans('main_trans.teacher_profile_title')])

    {{-- Top action bar for quick return to dashboard --}}
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti-arrow-right"></i>
                {{ trans('main_trans.back_action') }}
            </a>
        </div>
    </div>

    {{-- Identity and account summary card --}}
    <div class="row">
        <div class="col-lg-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.profile_tab_title') }}</h5>

                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th>{{ trans('main_trans.profile_name_label') }}</th>
                                    <td>{{ $teacher->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_email_label') }}</th>
                                    <td>{{ $teacher->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_specialization_label') }}</th>
                                    <td>{{ $teacher->specialization?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_gender_label') }}</th>
                                    <td>{{ $teacher->gender?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_joining_date_label') }}</th>
                                    <td>{{ optional($teacher->joining_date)->format('Y-m-d') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_address_label') }}</th>
                                    <td>{{ $teacher->address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sections list card to clarify current teaching scope --}}
        <div class="col-lg-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.teacher_dashboard_sections_entry') }}</h5>

                    <div class="list-group">
                        @forelse($teacherSections as $section)
                            <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h6 class="mb-1">{{ $section->name }}</h6>
                                    <small class="text-muted">
                                        {{ $section->grade?->Name }} / {{ $section->classroom?->name }}
                                    </small>
                                </div>
                                <a href="{{ route('teacher.students.index', ['section_id' => $section->id]) }}"
                                    class="btn btn-sm btn-outline-primary mt-2 mt-md-0">
                                    {{ trans('main_trans.teacher_dashboard_students_entry') }}
                                </a>
                            </div>
                        @empty
                            <p class="text-muted mb-0">{{ trans('main_trans.teacher_dashboard_no_sections') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Password update card for authenticated teacher account --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.profile_change_password_title') }}</h5>

                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success">
                            {{ trans('main_trans.profile_password_updated_success') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('teacher.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="teacher_current_password">{{ trans('main_trans.profile_current_password_label') }}</label>
                                    <input id="teacher_current_password" type="password" name="current_password"
                                        class="form-control" required>
                                    @error('current_password', 'updatePassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="teacher_new_password">{{ trans('main_trans.profile_new_password_label') }}</label>
                                    <input id="teacher_new_password" type="password" name="password"
                                        class="form-control" required>
                                    @error('password', 'updatePassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="teacher_password_confirmation">{{ trans('main_trans.profile_confirm_password_label') }}</label>
                                    <input id="teacher_password_confirmation" type="password"
                                        name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-success btn-sm" type="submit">
                            {{ trans('main_trans.profile_change_password_action') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
