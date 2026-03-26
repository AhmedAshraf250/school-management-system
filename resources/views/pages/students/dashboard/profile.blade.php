@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.student_profile_title') }}
@stop

@section('content')
    {{-- Student profile heading for dashboard tab --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="mb-0">{{ trans('main_trans.student_profile_title') }}</h4>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
                <i class="ti-arrow-right"></i>
                {{ trans('main_trans.back_action') }}
            </a>
        </div>
    </div>

    {{-- Main student identity card --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.profile_tab_title') }}</h5>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <tbody>
                                <tr>
                                    <th>{{ trans('main_trans.profile_name_label') }}</th>
                                    <td>{{ $student->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_email_label') }}</th>
                                    <td>{{ $student->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_gender_label') }}</th>
                                    <td>{{ $student->gender?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_nationality_label') }}</th>
                                    <td>{{ $student->nationality?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_blood_type_label') }}</th>
                                    <td>{{ $student->bloodType?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_birth_date_label') }}</th>
                                    <td>{{ optional($student->date_birth)->format('Y-m-d') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Students_trans.Grade') }}</th>
                                    <td>{{ $student->grade?->Name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Students_trans.classrooms') }}</th>
                                    <td>{{ $student->classroom?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Students_trans.section') }}</th>
                                    <td>{{ $student->section?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Students_trans.academic_year') }}</th>
                                    <td>{{ $student->academic_year ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_guardian_label') }}</th>
                                    <td>{{ $student->guardian?->father_name ?? $student->guardian?->mother_name ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Password update card for authenticated student account --}}
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

                    <form method="post" action="{{ route('student.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student_current_password">{{ trans('main_trans.profile_current_password_label') }}</label>
                                    <input id="student_current_password" type="password" name="current_password"
                                        class="form-control" required>
                                    @error('current_password', 'updatePassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student_new_password">{{ trans('main_trans.profile_new_password_label') }}</label>
                                    <input id="student_new_password" type="password" name="password"
                                        class="form-control" required>
                                    @error('password', 'updatePassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student_password_confirmation">{{ trans('main_trans.profile_confirm_password_label') }}</label>
                                    <input id="student_password_confirmation" type="password"
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
