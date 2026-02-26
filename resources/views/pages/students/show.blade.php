@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('Students_trans.Student_details') }}
@endsection

@section('PageTitle')
    {{ trans('Students_trans.Student_details') }}
@endsection

@section('content')
    {{-- Student details page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Tabs: Details + Attachments --}}
                    <div class="tab nav-border">
                        <ul class="nav nav-tabs" role="tablist">
                            {{-- Tab trigger: Student details --}}
                            <li class="nav-item">
                                <a class="nav-link active show" id="student-details-tab" data-toggle="tab"
                                    href="#student-details-panel" role="tab" aria-controls="student-details-panel"
                                    aria-selected="true">
                                    {{ trans('Students_trans.Student_details') }}
                                </a>
                            </li>

                            {{-- Tab trigger: Attachments --}}
                            <li class="nav-item">
                                <a class="nav-link" id="student-attachments-tab" data-toggle="tab"
                                    href="#student-attachments-panel" role="tab" aria-controls="student-attachments-panel"
                                    aria-selected="false">
                                    {{ trans('Students_trans.Attachments') }}
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- Student details tab content --}}
                            <div class="tab-pane fade active show" id="student-details-panel" role="tabpanel"
                                aria-labelledby="student-details-tab">
                                {{-- Student identity header --}}
                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-3 mb-3">
                                    <h5 class="mb-2 mb-md-0 font-weight-bold text-primary">
                                        {{ trans('Students_trans.Student_details') }}:
                                        <span class="text-dark">{{ $student->name }}</span>
                                    </h5>
                                    <span class="badge badge-info px-3 py-2">
                                        {{ trans('Students_trans.academic_year') }}: {{ $student->academic_year }}
                                    </span>
                                </div>

                                <div class="table-responsive">
                                    {{-- Student details table (Field title + Field value) --}}
                                    <table class="table table-hover table-bordered mb-0">
                                        <tbody>
                                            {{-- Field: Student name --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold w-25">
                                                    {{ trans('Students_trans.name') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">{{ $student->name }}</td>
                                            </tr>

                                            {{-- Field: Email --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold">
                                                    {{ trans('Students_trans.email') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">{{ $student->email }}</td>
                                            </tr>

                                            {{-- Field: Gender --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold">
                                                    {{ trans('Students_trans.gender') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">{{ $student->gender?->name ?? '-' }}</td>
                                            </tr>

                                            {{-- Field: Nationality --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold">
                                                    {{ trans('Students_trans.Nationality') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">{{ $student->nationality?->name ?? '-' }}</td>
                                            </tr>

                                            {{-- Field: Grade --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold">
                                                    {{ trans('Students_trans.Grade') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">{{ $student->grade?->Name ?? '-' }}</td>
                                            </tr>

                                            {{-- Field: Classroom --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold">
                                                    {{ trans('Students_trans.classrooms') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">{{ $student->classroom?->name ?? '-' }}</td>
                                            </tr>

                                            {{-- Field: Section --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold">
                                                    {{ trans('Students_trans.section') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">{{ $student->section?->name ?? '-' }}</td>
                                            </tr>

                                            {{-- Field: Date of birth --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold">
                                                    {{ trans('Students_trans.Date_of_Birth') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">
                                                    {{ optional($student->date_birth)->format('Y-m-d') ?? '-' }}
                                                </td>
                                            </tr>

                                            {{-- Field: Parent/Guardian --}}
                                            <tr>
                                                <th scope="row" class="bg-light text-primary font-weight-bold">
                                                    {{ trans('Students_trans.parent') }}
                                                </th>
                                                <td class="font-weight-bold text-dark">{{ $student->guardian?->father_name ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Attachments tab content --}}
                            <div class="tab-pane fade" id="student-attachments-panel" role="tabpanel"
                                aria-labelledby="student-attachments-tab">
                                <div class="mt-3">
                                    {{-- Upload attachment form --}}
                                    <form method="post" action="{{ route('students.uploadAttachments', $student->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="row align-items-end">
                                            {{-- Upload field --}}
                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label for="student_attachments_input"
                                                        class="mb-1">{{ trans('Students_trans.Attachments') }}</label>
                                                    <input id="student_attachments_input" type="file"
                                                        class="form-control-file" accept="image/*" name="photos[]"
                                                        multiple required>
                                                    @error('photos')
                                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                                    @enderror
                                                    @error('photos.*')
                                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Upload submit button --}}
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary btn-sm mb-2">
                                                    {{ trans('Students_trans.submit') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                {{-- Attachments listing table --}}
                                <div class="table-responsive mt-3">
                                    <table class="table table-hover table-bordered mb-0" style="text-align: center;">
                                        <thead>
                                            <tr class="table-secondary">
                                                <th scope="col">#</th>
                                                <th scope="col">{{ trans('Students_trans.filename') }}</th>
                                                <th scope="col">{{ trans('Students_trans.created_at') }}</th>
                                                <th scope="col">{{ trans('Students_trans.Processes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($student->images as $attachment)
                                                {{-- Attachment row --}}
                                                <tr style="vertical-align: middle;">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $attachment->file_name }}</td>
                                                    <td>{{ $attachment->created_at->diffForHumans() }}</td>
                                                    <td>
                                                        {{-- Download action --}}
                                                        <a class="btn btn-outline-info btn-sm"
                                                            href="{{ route('students.downloadAttachment', ['student' => $student->id, 'attachmentId' => $attachment->id]) }}"
                                                            role="button">
                                                            <i class="fas fa-download"></i>
                                                            {{ trans('Students_trans.Download') }}
                                                        </a>

                                                        {{-- Delete action --}}
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#Delete_img{{ $attachment->id }}">
                                                            {{ trans('Students_trans.delete') }}
                                                        </button>
                                                    </td>
                                                </tr>

                                                {{-- Delete attachment modal --}}
                                                @include('pages.students.partials.delete-img', [
                                                    'student' => $student,
                                                    'attachment' => $attachment,
                                                ])
                                            @empty
                                                {{-- Empty state when no attachments are available --}}
                                                <tr>
                                                    <td colspan="4" class="text-muted py-3">
                                                        {{ trans('Students_trans.Attachments') }}: 0
                                                    </td>
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
        </div>
    </div>
@endsection

@section('js')
    @toastr_js
    @toastr_render
@endsection
