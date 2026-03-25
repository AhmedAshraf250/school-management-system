@extends('layouts.user-portal')

@section('title')
    {{ trans('Students_trans.Student_details') }}
@stop

@section('content')
    {{-- Unified dashboard title --}}
    @include('layouts.partials.dashboard-title', [
        'roleLabel' => trans('main_trans.role_teacher'),
        'identity' => $teacher->name ?? ($teacher->email ?? '-'),
    ])
    @include('pages.teachers.partials.ui-typography')
    @include('pages.teachers.partials.page-heading', ['title' => trans('Students_trans.Student_details')])

    {{-- Teacher read-only student profile --}}
    <div class="teacher-view-scope">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="card card-statistics h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">{{ $student->name }}</h5>
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('teacher.students.index') }}">
                                {{ trans('main_trans.teacher_students_page_title') }}
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light text-primary w-25">{{ trans('Students_trans.name') }}</th>
                                        <td>{{ $student->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.email') }}</th>
                                        <td>{{ $student->email }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.gender') }}</th>
                                        <td>{{ $student->gender?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.Nationality') }}</th>
                                        <td>{{ $student->nationality?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.blood_type') }}</th>
                                        <td>{{ $student->bloodType?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.Date_of_Birth') }}</th>
                                        <td>{{ optional($student->date_birth)->format('d/m/Y') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.Grade') }}</th>
                                        <td>{{ $student->grade?->Name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.classrooms') }}</th>
                                        <td>{{ $student->classroom?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.section') }}</th>
                                        <td>{{ $student->section?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-primary">{{ trans('Students_trans.parent') }}</th>
                                        <td>{{ $student->guardian?->father_name ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
