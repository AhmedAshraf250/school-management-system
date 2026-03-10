@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('settings_trans.page_title') }}
@endsection

@section('PageTitle')
    {{ trans('settings_trans.page_title') }}
@endsection

@section('content')
    {{-- Settings page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Validation summary --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Settings form --}}
                    <form enctype="multipart/form-data" method="post" action="{{ route('settings.update', $settingId) }}"
                        autocomplete="off">
                        @csrf
                        @method('PUT')

                        {{-- General school information --}}
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">
                                        {{ trans('settings_trans.school_name') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input name="school_name" value="{{ old('school_name', $setting['school_name']) }}"
                                            required type="text" class="form-control"
                                            placeholder="{{ trans('settings_trans.school_name') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="current_session" class="col-lg-3 col-form-label font-weight-semibold">
                                        {{ trans('settings_trans.current_session') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <select required name="current_session" id="current_session" class="form-control">
                                            <option value="" disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                            @for ($year = now()->year - 2; $year <= now()->year + 2; $year++)
                                                @php $sessionValue = $year . '-' . ($year + 1); @endphp
                                                <option value="{{ $sessionValue }}" @selected(old('current_session', $setting['current_session']) === $sessionValue)>
                                                    {{ $sessionValue }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">
                                        {{ trans('settings_trans.school_title') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <input name="school_title"
                                            value="{{ old('school_title', $setting['school_title']) }}" type="text"
                                            class="form-control" placeholder="{{ trans('settings_trans.school_title') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-lg-3 col-form-label font-weight-semibold">{{ trans('settings_trans.phone') }}</label>
                                    <div class="col-lg-9">
                                        <input name="phone" value="{{ old('phone', $setting['phone']) }}" type="text"
                                            class="form-control" placeholder="{{ trans('settings_trans.phone') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">
                                        {{ trans('settings_trans.school_email') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <input name="school_email"
                                            value="{{ old('school_email', $setting['school_email']) }}" type="email"
                                            class="form-control" placeholder="{{ trans('settings_trans.school_email') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">
                                        {{ trans('settings_trans.address') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input required name="address" value="{{ old('address', $setting['address']) }}"
                                            type="text" class="form-control"
                                            placeholder="{{ trans('settings_trans.address') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Academic dates and logo --}}
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label font-weight-semibold">
                                        {{ trans('settings_trans.end_first_term') }}
                                    </label>
                                    <div class="col-lg-8">
                                        <input name="end_first_term"
                                            value="{{ old('end_first_term', $setting['end_first_term']) }}" type="date"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label font-weight-semibold">
                                        {{ trans('settings_trans.end_second_term') }}
                                    </label>
                                    <div class="col-lg-8">
                                        <input name="end_second_term"
                                            value="{{ old('end_second_term', $setting['end_second_term']) }}"
                                            type="date" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-lg-4 col-form-label font-weight-semibold">{{ trans('settings_trans.logo') }}</label>
                                    <div class="col-lg-8">
                                        @if ($logoUrl)
                                            <div class="mb-3 text-center p-2 border rounded bg-light">
                                                <img style="width: 140px; max-height: 140px; object-fit: contain;"
                                                    src="{{ $logoUrl }}" alt="{{ trans('settings_trans.logo') }}">
                                            </div>
                                        @endif

                                        <input name="logo" accept="image/*" type="file" class="form-control-file">
                                        <small class="form-text text-muted">{{ trans('settings_trans.logo_hint') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit action --}}
                        <hr>
                        <button class="btn btn-success btn-sm pull-right" type="submit">
                            {{ trans('Students_trans.submit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @toastr_js
    @toastr_render
@endsection
