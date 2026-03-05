@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('Questions_trans.list') }}
@endsection

@section('PageTitle')
    {{ trans('Questions_trans.list') }}
@endsection

@section('content')
    {{-- Questions page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Top action --}}
                    <div class="mb-3">
                        <a href="{{ route('questions.create') }}" class="btn btn-success btn-sm" role="button"
                            aria-pressed="true">
                            {{ trans('Questions_trans.add_new') }}
                        </a>
                    </div>

                    {{-- Questions listing table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('Questions_trans.title') }}</th>
                                    <th>{{ trans('Questions_trans.answers') }}</th>
                                    <th>{{ trans('Questions_trans.right_answer') }}</th>
                                    <th>{{ trans('Questions_trans.score') }}</th>
                                    <th>{{ trans('Questions_trans.quiz_name') }}</th>
                                    <th>{{ trans('Questions_trans.processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $question)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $question->title }}</td>
                                        <td>{{ $question->answers }}</td>
                                        <td>{{ $question->right_answer }}</td>
                                        <td>{{ $question->score }}</td>
                                        <td>{{ $question->quiz?->name }}</td>
                                        <td>
                                            <a href="{{ route('questions.edit', $question->id) }}"
                                                class="btn btn-info btn-sm" role="button" aria-pressed="true">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_question{{ $question->id }}"
                                                title="{{ trans('Questions_trans.delete_title') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Reusable delete modal partial --}}
                                    @include('pages.questions.destroy')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @toastr_js
    @toastr_render
@endsection
