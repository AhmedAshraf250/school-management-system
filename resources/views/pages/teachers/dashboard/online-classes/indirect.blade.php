@extends('layouts.user-portal')
@push('css')
    @toastr_css
@endpush

@section('title')
    {{ trans('OnlineClasses_trans.add_manual_title') }}
@endsection

@section('content')

    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', [
        'title' => trans('OnlineClasses_trans.add_manual_title'),
    ])

    {{-- Manual class creation form without Zoom API --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="alert alert-warning">
                        {{ trans('OnlineClasses_trans.add_manual_hint') }}
                    </div>

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Manual meeting details --}}
                    <form method="post" action="{{ route('teacher.online-classes.indirectStore') }}" autocomplete="off">
                        @csrf

                        {{-- Teacher section selection --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="section_id">{{ trans('Students_trans.section') }} : <span
                                            class="text-danger">*</span></label>
                                    <select class="custom-select mr-sm-2" name="section_id" id="section_id" required>
                                        <option value="" selected disabled>{{ trans('Parent_trans.Choose') }}...
                                        </option>
                                        @foreach ($teacherSections as $section)
                                            <option value="{{ $section->id }}" @selected((int) old('section_id') === $section->id)>
                                                {{ $section->name }} - {{ $section->grade?->Name }} /
                                                {{ $section->classroom?->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>

                        {{-- Meeting identity and schedule --}}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="meeting_id">{{ trans('OnlineClasses_trans.meeting_id_label') }} : <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="meeting_id" name="meeting_id" type="text"
                                        value="{{ old('meeting_id') }}" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="topic">{{ trans('OnlineClasses_trans.topic_label') }} : <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="topic" name="topic" type="text"
                                        value="{{ old('topic') }}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_time">{{ trans('OnlineClasses_trans.start_time_label') }} : <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="start_time" type="datetime-local" name="start_time"
                                        value="{{ old('start_time') }}" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="duration">{{ trans('OnlineClasses_trans.duration_label') }} : <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="duration" name="duration" type="number" min="1"
                                        value="{{ old('duration') }}" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="password">{{ trans('OnlineClasses_trans.password_label') }} : <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="password" name="password" type="text"
                                        value="{{ old('password') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Host and participant links --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_url">{{ trans('OnlineClasses_trans.start_url_label') }} : <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="start_url" name="start_url" type="url"
                                        value="{{ old('start_url') }}" required>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="join_url">{{ trans('OnlineClasses_trans.join_url_label') }} : <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="join_url" name="join_url" type="url"
                                        value="{{ old('join_url') }}" required>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="submit">
                            {{ trans('Students_trans.submit') }}
                        </button>
                        <a href="{{ route('teacher.online-classes.index') }}" class="btn btn-outline-secondary btn-sm">
                            {{ trans('main_trans.back_action') }}
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @toastr_js
    @toastr_render
@endpush
