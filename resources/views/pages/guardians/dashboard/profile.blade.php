@extends('layouts.user-portal')

@section('title')
    {{ trans('main_trans.guardian_profile_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_guardian'),
        'identity' => $guardian?->father_name ?? ($guardian?->email ?? '-'),
    ])

    {{-- Guardian dashboard tabs --}}
    @include('pages.guardians.dashboard.partials.tabs')

    {{-- Guardian profile info --}}
    <div class="row">
        <div class="col-lg-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.guardian_father_info') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th>{{ trans('Parent_trans.Name_Father') }}</th>
                                    <td>{{ $guardian->father_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Parent_trans.Job_Father') }}</th>
                                    <td>{{ $guardian->father_job }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Parent_trans.Phone_Father') }}</th>
                                    <td>{{ $guardian->father_phone }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Parent_trans.Email') }}</th>
                                    <td>{{ $guardian->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_nationality_label') }}</th>
                                    <td>{{ $guardian->fatherNational?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_blood_type_label') }}</th>
                                    <td>{{ $guardian->fatherBloodType?->name ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mother and family summary --}}
        <div class="col-lg-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ trans('main_trans.guardian_mother_info') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th>{{ trans('Parent_trans.Name_Mother') }}</th>
                                    <td>{{ $guardian->mother_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Parent_trans.Job_Mother') }}</th>
                                    <td>{{ $guardian->mother_job }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('Parent_trans.Phone_Mother') }}</th>
                                    <td>{{ $guardian->mother_phone }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_nationality_label') }}</th>
                                    <td>{{ $guardian->motherNational?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.profile_blood_type_label') }}</th>
                                    <td>{{ $guardian->motherBloodType?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('main_trans.guardian_children_count') }}</th>
                                    <td>{{ $studentsCount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Password update card --}}
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

                    <form method="post" action="{{ route('guardian.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label
                                        for="guardian_current_password">{{ trans('main_trans.profile_current_password_label') }}</label>
                                    <input id="guardian_current_password" type="password" name="current_password"
                                        class="form-control" required>
                                    @error('current_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label
                                        for="guardian_new_password">{{ trans('main_trans.profile_new_password_label') }}</label>
                                    <input id="guardian_new_password" type="password" name="password" class="form-control"
                                        required>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label
                                        for="guardian_password_confirmation">{{ trans('main_trans.profile_confirm_password_label') }}</label>
                                    <input id="guardian_password_confirmation" type="password" name="password_confirmation"
                                        class="form-control" required>
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
