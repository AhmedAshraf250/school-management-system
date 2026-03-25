@extends('layouts.user-portal')

@section('title')
    {{ trans('Questions_trans.list') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', [
        'title' => trans('main_trans.teacher_questions_list_entry'),
    ])

    {{-- Teacher questions listing page --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-outline-primary btn-sm">
                                    {{ trans('main_trans.teacher_quizzes_list_entry') }}
                                </a>
                                <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                                    {{ trans('main_trans.teacher_students_back_dashboard') }}
                                </a>
                            </div>
                        </div>

                        {{-- Questions filter form --}}
                        <form method="GET" action="{{ route('teacher.questions.index') }}" class="mb-4">
                            <div class="form-row align-items-end">
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label for="grade_id">{{ trans('Quizzes_trans.grade') }}</label>
                                    <select id="grade_id" name="grade_id" class="custom-select">
                                        <option value="">{{ trans('main_trans.filter_all') }}</option>
                                        @foreach ($gradeOptions as $gradeOption)
                                            <option value="{{ $gradeOption['id'] }}"
                                                {{ (int) $selectedGradeId === (int) $gradeOption['id'] ? 'selected' : '' }}>
                                                {{ $gradeOption['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label for="classroom_id">{{ trans('Quizzes_trans.classroom') }}</label>
                                    <select id="classroom_id" name="classroom_id" class="custom-select">
                                        <option value="">{{ trans('main_trans.filter_all') }}</option>
                                        @foreach ($classroomOptions as $classroomOption)
                                            <option value="{{ $classroomOption['id'] }}"
                                                {{ (int) $selectedClassroomId === (int) $classroomOption['id'] ? 'selected' : '' }}>
                                                {{ $classroomOption['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label for="section_id">{{ trans('Quizzes_trans.section') }}</label>
                                    <select id="section_id" name="section_id" class="custom-select">
                                        <option value="">{{ trans('main_trans.teacher_students_all_sections') }}
                                        </option>
                                        @foreach ($sectionOptions as $sectionOption)
                                            <option value="{{ $sectionOption->id }}"
                                                {{ (int) $selectedSectionId === (int) $sectionOption->id ? 'selected' : '' }}>
                                                {{ $sectionOption->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label for="academic_year">{{ trans('Quizzes_trans.academic_year') }}</label>
                                    <select id="academic_year" name="academic_year" class="custom-select">
                                        <option value="">{{ trans('main_trans.filter_all') }}</option>
                                        @foreach ($academicYearOptions as $academicYearOption)
                                            <option value="{{ $academicYearOption }}"
                                                {{ $selectedAcademicYear === $academicYearOption ? 'selected' : '' }}>
                                                {{ $academicYearOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-primary">
                                        {{ trans('Students_trans.submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="text-align: center;">
                                <thead>
                                    <tr class="table-info text-danger">
                                        <th>#</th>
                                        <th>{{ trans('Questions_trans.title') }}</th>
                                        <th>{{ trans('Questions_trans.right_answer') }}</th>
                                        <th>{{ trans('Questions_trans.score') }}</th>
                                        <th>{{ trans('Questions_trans.quiz_name') }}</th>
                                        <th>{{ trans('Quizzes_trans.grade') }}</th>
                                        <th>{{ trans('Quizzes_trans.classroom') }}</th>
                                        <th>{{ trans('Quizzes_trans.section') }}</th>
                                        <th>{{ trans('Quizzes_trans.academic_year') }}</th>
                                        <th>{{ trans('Questions_trans.processes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($questions as $question)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $question->title }}</td>
                                            <td>{{ $question->right_answer }}</td>
                                            <td>{{ $question->score }}</td>
                                            <td>{{ $question->quiz?->name ?? '-' }}</td>
                                            <td>{{ $question->quiz?->grade?->Name ?? '-' }}</td>
                                            <td>{{ $question->quiz?->classroom?->name ?? '-' }}</td>
                                            <td>{{ $question->quiz?->section?->name ?? '-' }}</td>
                                            <td>{{ $question->quiz?->academic_year ?? '-' }}</td>
                                            <td>
                                                @if ($question->quiz)
                                                    <a href="{{ route('teacher.quizzes.questions.edit', [$question->quiz->id, $question->id]) }}"
                                                        class="btn btn-info btn-sm"
                                                        title="{{ trans('Quizzes_trans.edit_title') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-muted">
                                                {{ trans('main_trans.teacher_reports_no_data') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
