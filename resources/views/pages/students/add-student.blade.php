@extends('layouts.master')

@section('title')
    {{ trans('main_trans.add_student') }}
@endsection

@section('PageTitle')
    {{ trans('main_trans.add_student') }}
@endsection

@section('content')
    {{-- Add student page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Validation errors --}}
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

                    {{-- Add form --}}
                    <form method="post" action="{{ route('students.store') }}" autocomplete="off">
                        @csrf

                        @include('pages.students.partials.form-fields', ['student' => null])

                        {{-- Primary submit action --}}
                        <button class="btn btn-primary btn-sm d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm pull-right"
                            type="submit">
                            <i class="fa fa-check mr-2"></i>
                            {{ trans('Students_trans.submit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- Dependent selects: grade -> classroom -> section --}}
    @include('pages.students.partials.dependent-selects-script', ['student' => null])
@endsection
