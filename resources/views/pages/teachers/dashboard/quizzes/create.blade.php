@extends('layouts.user-portal')

@section('title')
    {{ trans('Quizzes_trans.add_title') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', ['title' => trans('Quizzes_trans.add_title')])

    {{-- Teacher create quiz page --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        <form action="{{ route('teacher.quizzes.store') }}" method="POST" autocomplete="off">
                            @csrf

                            {{-- Quiz names --}}
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="name_ar">{{ trans('Quizzes_trans.name_ar') }}</label>
                                    <input id="name_ar" type="text" name="name_ar" class="form-control"
                                        value="{{ old('name_ar') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="name_en">{{ trans('Quizzes_trans.name_en') }}</label>
                                    <input id="name_en" type="text" name="name_en" class="form-control"
                                        value="{{ old('name_en') }}" required>
                                </div>
                            </div>

                            {{-- Quiz relations and status --}}
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="section_id">{{ trans('Quizzes_trans.section') }}</label>
                                    <select id="section_id" name="section_id" class="custom-select" required>
                                        <option value="">{{ trans('main_trans.teacher_students_all_sections') }}
                                        </option>
                                        @foreach ($teacherSections as $section)
                                            <option value="{{ $section->id }}" data-grade-id="{{ $section->grade_id }}"
                                                data-classroom-id="{{ $section->classroom_id }}"
                                                {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }} - {{ $section->grade?->Name ?? '-' }} -
                                                {{ $section->classroom?->name ?? '-' }} - {{ $section->students_count }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="subject_id">{{ trans('Quizzes_trans.subject') }}</label>
                                    <select id="subject_id" name="subject_id" class="custom-select" required>
                                        <option value="">{{ trans('Quizzes_trans.choose_subject') }}</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}" data-grade-id="{{ $subject->grade_id }}"
                                                data-classroom-id="{{ $subject->classroom_id }}"
                                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status">{{ trans('Quizzes_trans.status') }}</label>
                                    <select id="status" name="status" class="custom-select" required>
                                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                                            {{ trans('Quizzes_trans.draft') }}
                                        </option>
                                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>
                                            {{ trans('Quizzes_trans.published') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Quiz academic year --}}
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="academic_year">{{ trans('Quizzes_trans.academic_year') }}</label>
                                    <input id="academic_year" type="text" name="academic_year" class="form-control"
                                        value="{{ old('academic_year', '2025-2026') }}" required>
                                </div>
                            </div>

                            {{-- Submit action --}}
                            <button type="submit" class="btn btn-success">
                                {{ trans('Students_trans.submit') }}
                            </button>
                            <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary">
                                {{ trans('main_trans.back_action') }}
                            </a>
                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3 mb-0">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectionSelect = document.getElementById('section_id');
            const subjectSelect = document.getElementById('subject_id');

            if (!sectionSelect || !subjectSelect) {
                return;
            }

            const allSubjectOptions = Array.from(subjectSelect.options);
            const rebuildSubjectOptions = function() {
                const currentSelectedSubjectId = subjectSelect.value;
                const selectedSection = sectionSelect.options[sectionSelect.selectedIndex];
                const gradeId = selectedSection ? selectedSection.dataset.gradeId : null;
                const classroomId = selectedSection ? selectedSection.dataset.classroomId : null;

                subjectSelect.innerHTML = '';

                allSubjectOptions.forEach(function(option) {
                    if (option.value === '') {
                        subjectSelect.appendChild(option.cloneNode(true));
                        return;
                    }

                    if (!gradeId || !classroomId) {
                        subjectSelect.appendChild(option.cloneNode(true));
                        return;
                    }

                    if (option.dataset.gradeId === gradeId && option.dataset.classroomId ===
                        classroomId) {
                        subjectSelect.appendChild(option.cloneNode(true));
                    }
                });

                const hasCurrentSubject = Array.from(subjectSelect.options).some(function(option) {
                    return option.value === currentSelectedSubjectId;
                });

                subjectSelect.value = hasCurrentSubject ? currentSelectedSubjectId : '';
            };

            rebuildSubjectOptions();
            sectionSelect.addEventListener('change', rebuildSubjectOptions);
        });
    </script>
@endpush
