@extends('layouts.user-portal')

@section('title')
    {{ trans('Quizzes_trans.title_page') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_student'),
        'identity' => $student->name ?? $student->email ?? '-',
    ])

    {{-- Student quizzes page --}}
    <div class="row">
        <div class="col-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="mb-3">{{ trans('Quizzes_trans.list') }}</h5>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="text-align: center;">
                            <thead>
                                <tr class="table-info text-danger">
                                    <th>#</th>
                                    <th>{{ trans('Quizzes_trans.name') }}</th>
                                    <th>{{ trans('Quizzes_trans.subject') }}</th>
                                    <th>{{ trans('Quizzes_trans.teacher') }}</th>
                                    <th>{{ trans('Questions_trans.list') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $quiz->name }}</td>
                                        <td>{{ $quiz->subject?->name ?? '-' }}</td>
                                        <td>{{ $quiz->teacher?->name ?? '-' }}</td>
                                        <td>{{ $quiz->questions->count() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted">{{ trans('main_trans.teacher_reports_no_data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
