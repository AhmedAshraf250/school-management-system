@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('Quizzes_trans.list') }}
@endsection

@section('PageTitle')
    {{ trans('Quizzes_trans.list') }}
@endsection

@section('content')
    {{-- Quizzes page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Top action --}}
                    <div class="mb-3">
                        <a href="{{ route('quizzes.create') }}" class="btn btn-success btn-sm" role="button"
                            aria-pressed="true">
                            {{ trans('Quizzes_trans.add_new') }}
                        </a>
                    </div>

                    {{-- Quizzes listing table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('Quizzes_trans.name') }}</th>
                                    <th>{{ trans('Quizzes_trans.subject') }}</th>
                                    <th>{{ trans('Quizzes_trans.teacher') }}</th>
                                    <th>{{ trans('Quizzes_trans.grade') }}</th>
                                    <th>{{ trans('Quizzes_trans.classroom') }}</th>
                                    <th>{{ trans('Quizzes_trans.section') }}</th>
                                    <th>{{ trans('Quizzes_trans.processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $quiz->name }}</td>
                                        <td>{{ $quiz->subject?->name }}</td>
                                        <td>{{ $quiz->teacher?->name }}</td>
                                        <td>{{ $quiz->grade?->Name }}</td>
                                        <td>{{ $quiz->classroom?->name }}</td>
                                        <td>{{ $quiz->section?->name }}</td>
                                        <td>
                                            <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-info btn-sm"
                                                role="button" aria-pressed="true">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#delete_quiz{{ $quiz->id }}" title="{{ trans('Quizzes_trans.delete_title') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Delete quiz modal --}}
                                    <div class="modal fade" id="delete_quiz{{ $quiz->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="deleteQuizLabel{{ $quiz->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteQuizLabel{{ $quiz->id }}">
                                                            {{ trans('Quizzes_trans.delete_title') }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ trans('Quizzes_trans.delete_warning') }} {{ $quiz->name }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ trans('Students_trans.Close') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ trans('Students_trans.submit') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
