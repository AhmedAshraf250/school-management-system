@extends('layouts.master')

@section('css')
    {{-- @toastr_css --}}
@endsection

@section('title')
    {{ trans('Teacher_trans.Edit_Teacher') }}
@endsection

@section('PageTitle')
    {{ trans('Teacher_trans.Edit_Teacher') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="col-xs-12">
                        <div class="col-md-12">
                            <br>
                            <form action="{{ route('teachers.update', $teacher->id) }}" method="post">
                                @method('PATCH')
                                @csrf
                                <div class="form-row">
                                    <div class="col">
                                        <label for="email">{{ trans('Teacher_trans.Email') }}</label>
                                        <input id="email" type="email" name="email"
                                            value="{{ old('email', $teacher->email) }}" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="password">{{ trans('Teacher_trans.Password') }}</label>
                                        <input id="password" type="password" name="password" class="form-control">
                                    </div>
                                </div>
                                <br>

                                <div class="form-row">
                                    <div class="col">
                                        <label for="name_ar">{{ trans('Teacher_trans.Name_ar') }}</label>
                                        <input id="name_ar" type="text" name="name_ar"
                                            value="{{ old('name_ar', $teacher->getTranslation('name', 'ar')) }}"
                                            class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="name_en">{{ trans('Teacher_trans.Name_en') }}</label>
                                        <input id="name_en" type="text" name="name_en"
                                            value="{{ old('name_en', $teacher->getTranslation('name', 'en')) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <br>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="specialization_id">{{ trans('Teacher_trans.specialization') }}</label>
                                        <select id="specialization_id" class="custom-select my-1 mr-sm-2"
                                            name="specialization_id">
                                            @foreach ($specializations as $specialization)
                                                <option value="{{ $specialization->id }}" @selected((int) old('specialization_id', $teacher->specialization_id) === $specialization->id)>
                                                    {{ $specialization->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col">
                                        <label for="gender_id">{{ trans('Teacher_trans.Gender') }}</label>
                                        <select id="gender_id" class="custom-select my-1 mr-sm-2" name="gender_id">
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->id }}" @selected((int) old('gender_id', $teacher->gender_id) === $gender->id)>
                                                    {{ $gender->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>

                                <div class="form-row">
                                    <div class="col">
                                        <label for="joining_date">{{ trans('Teacher_trans.Joining_Date') }}</label>
                                        <div class="input-group date">
                                            <input class="form-control" type="date" id="joining_date"
                                                value="{{ old('joining_date', $teacher->joining_date?->format('Y-m-d')) }}"
                                                name="joining_date" required>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="form-group">
                                    <label for="address">{{ trans('Teacher_trans.Address') }}</label>
                                    <textarea class="form-control" name="address" id="address" rows="4">{{ old('address', $teacher->address) }}</textarea>
                                </div>

                                <button class="btn btn-success btn-sm nextBtn btn-lg pull-right"
                                    type="submit">{{ trans('Teacher_trans.save') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @flasher_render
@endsection
