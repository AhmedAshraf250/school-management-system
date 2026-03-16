<!DOCTYPE html>
<html lang="en">
@section('title')
    {{ trans('main_trans.Main_title') }}
@stop

<head>
    @include('layouts.partials.head')
    @stack('css')
    <style>
        .student-profile-link {
            color: #1e40af;
            font-weight: 600;
            text-decoration: underline;
            transition: color 0.2s ease;
        }

        .student-profile-link:hover,
        .student-profile-link:focus {
            color: #dc2626;
            text-decoration: underline;
        }
    </style>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
</head>

<body style="font-family: 'Cairo', sans-serif">
    @php
        $admin = auth()->guard('admin')->user();
        $studentsCount = \App\Models\Student::query()->count();
        $teachersCount = \App\Models\Teacher::query()->count();
        $guardiansCount = \App\Models\Guardian::query()->count();
        $sectionsCount = \App\Models\Section::query()->count();

        $latestStudents = \App\Models\Student::query()
            ->with(['gender:id,name', 'grade:id,Name', 'classroom:id,name', 'section:id,name'])
            ->latest()
            ->limit(5)
            ->get();

        $latestTeachers = \App\Models\Teacher::query()
            ->with(['gender:id,name', 'specialization:id,name'])
            ->latest()
            ->limit(5)
            ->get();

        $latestGuardians = \App\Models\Guardian::query()->latest()->limit(5)->get();

        $latestInvoices = \App\Models\FeeInvoice::query()
            ->with(['student:id,name', 'grade:id,Name', 'classroom:id,name', 'fee:id,title'])
            ->latest()
            ->limit(10)
            ->get();
    @endphp

    <div class="wrapper" style="font-family: 'Cairo', sans-serif">
        {{-- Global preloader --}}
        <x-preloader />

        {{-- Main header --}}
        @include('layouts.partials.main-header')

        {{-- Main sidebar --}}
        @include('layouts.partials.main-sidebar')

        <div class="content-wrapper">
            <div class="container-fluid">
                {{-- Unified dashboard title --}}
                @include('layouts.partials.dashboard-title', [
                    'roleLabel' => trans('main_trans.role_admin'),
                    'identity' => $admin?->name ?? ($admin?->email ?? trans('main_trans.admin')),
                ])

                {{-- Welcome message --}}
                <div class="row">
                    <div class="col-12 mb-30">
                        <div class="alert alert-primary mb-0" role="alert">
                            <strong>{{ trans('main_trans.welcome_user', ['name' => $admin?->name]) }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Dashboard statistics cards --}}
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="float-left">
                                        <span class="text-success">
                                            <i class="fas fa-user-graduate highlight-icon" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="float-right text-right">
                                        <p class="card-text text-dark">{{ trans('main_trans.students') }}</p>
                                        <h4>{{ $studentsCount }}</h4>
                                    </div>
                                </div>
                                <p class="text-muted pt-3 mb-0 mt-2 border-top">
                                    <i class="fas fa-binoculars mr-1" aria-hidden="true"></i>
                                    <a href="{{ route('students.index') }}"><span
                                            class="text-danger">{{ trans('main_trans.dashboard_view_data') }}</span></a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="float-left">
                                        <span class="text-warning">
                                            <i class="fas fa-chalkboard-teacher highlight-icon" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="float-right text-right">
                                        <p class="card-text text-dark">{{ trans('main_trans.Teachers') }}</p>
                                        <h4>{{ $teachersCount }}</h4>
                                    </div>
                                </div>
                                <p class="text-muted pt-3 mb-0 mt-2 border-top">
                                    <i class="fas fa-binoculars mr-1" aria-hidden="true"></i>
                                    <a href="{{ route('teachers.index') }}"><span
                                            class="text-danger">{{ trans('main_trans.dashboard_view_data') }}</span></a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="float-left">
                                        <span class="text-success">
                                            <i class="fas fa-user-tie highlight-icon" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="float-right text-right">
                                        <p class="card-text text-dark">{{ trans('main_trans.Parents') }}</p>
                                        <h4>{{ $guardiansCount }}</h4>
                                    </div>
                                </div>
                                <p class="text-muted pt-3 mb-0 mt-2 border-top">
                                    <i class="fas fa-binoculars mr-1" aria-hidden="true"></i>
                                    <a href="{{ route('guardians') }}"><span
                                            class="text-danger">{{ trans('main_trans.dashboard_view_data') }}</span></a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="float-left">
                                        <span class="text-primary">
                                            <i class="fas fa-chalkboard highlight-icon" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="float-right text-right">
                                        <p class="card-text text-dark">{{ trans('main_trans.sections') }}</p>
                                        <h4>{{ $sectionsCount }}</h4>
                                    </div>
                                </div>
                                <p class="text-muted pt-3 mb-0 mt-2 border-top">
                                    <i class="fas fa-binoculars mr-1" aria-hidden="true"></i>
                                    <a href="{{ route('sections.index') }}"><span
                                            class="text-danger">{{ trans('main_trans.dashboard_view_data') }}</span></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Latest data tabs --}}
                <div class="row">
                    <div class="col-xl-12 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">
                                <div class="tab nav-border" style="position: relative;">
                                    <div class="d-block d-md-flex justify-content-between">
                                        <div class="d-block w-100">
                                            <h5 style="font-family: 'Cairo', sans-serif" class="card-title">
                                                {{ trans('main_trans.dashboard_recent_operations') }}</h5>
                                        </div>
                                        <div class="d-block d-md-flex nav-tabs-custom">
                                            <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active show" id="students-tab" data-toggle="tab"
                                                        href="#students" role="tab" aria-controls="students"
                                                        aria-selected="true">{{ trans('main_trans.students') }}</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="teachers-tab" data-toggle="tab"
                                                        href="#teachers" role="tab" aria-controls="teachers"
                                                        aria-selected="false">{{ trans('main_trans.Teachers') }}</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="guardians-tab" data-toggle="tab"
                                                        href="#guardians" role="tab" aria-controls="guardians"
                                                        aria-selected="false">{{ trans('main_trans.Parents') }}</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="invoices-tab" data-toggle="tab"
                                                        href="#invoices" role="tab" aria-controls="invoices"
                                                        aria-selected="false">{{ trans('fees_trans.invoices') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content" id="dashboardTabsContent">
                                        {{-- Latest students tab --}}
                                        <div class="tab-pane fade active show" id="students" role="tabpanel"
                                            aria-labelledby="students-tab">
                                            <div class="table-responsive mt-15">
                                                <table style="text-align: center"
                                                    class="table center-aligned-table table-hover mb-0">
                                                    <thead>
                                                        <tr class="table-info text-danger">
                                                            <th>#</th>
                                                            <th>{{ trans('Students_trans.name') }}</th>
                                                            <th>{{ trans('Students_trans.email') }}</th>
                                                            <th>{{ trans('Students_trans.gender') }}</th>
                                                            <th>{{ trans('Students_trans.Grade') }}</th>
                                                            <th>{{ trans('Students_trans.classrooms') }}</th>
                                                            <th>{{ trans('Students_trans.section') }}</th>
                                                            <th>{{ trans('Students_trans.created_at') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($latestStudents as $student)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    <a class="student-profile-link"
                                                                        href="{{ route('students.show', $student->id) }}">
                                                                        {{ $student->name }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ $student->email }}</td>
                                                                <td>{{ $student->gender?->name }}</td>
                                                                <td>{{ $student->grade?->Name }}</td>
                                                                <td>{{ $student->classroom?->name }}</td>
                                                                <td>{{ $student->section?->name }}</td>
                                                                <td class="text-success">{{ $student->created_at }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="alert-danger" colspan="8">
                                                                    {{ trans('main_trans.dashboard_no_data') }}</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- Latest teachers tab --}}
                                        <div class="tab-pane fade" id="teachers" role="tabpanel"
                                            aria-labelledby="teachers-tab">
                                            <div class="table-responsive mt-15">
                                                <table style="text-align: center"
                                                    class="table center-aligned-table table-hover mb-0">
                                                    <thead>
                                                        <tr class="table-info text-danger">
                                                            <th>#</th>
                                                            <th>{{ trans('Teacher_trans.Name_Teacher') }}</th>
                                                            <th>{{ trans('Teacher_trans.Gender') }}</th>
                                                            <th>{{ trans('Teacher_trans.Joining_Date') }}</th>
                                                            <th>{{ trans('Teacher_trans.specialization') }}</th>
                                                            <th>{{ trans('Students_trans.created_at') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($latestTeachers as $teacher)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $teacher->name }}</td>
                                                                <td>{{ $teacher->gender?->name }}</td>
                                                                <td>{{ $teacher->joining_date?->format('Y-m-d') }}</td>
                                                                <td>{{ $teacher->specialization?->name }}</td>
                                                                <td class="text-success">{{ $teacher->created_at }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="alert-danger" colspan="6">
                                                                    {{ trans('main_trans.dashboard_no_data') }}</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- Latest guardians tab --}}
                                        <div class="tab-pane fade" id="guardians" role="tabpanel"
                                            aria-labelledby="guardians-tab">
                                            <div class="table-responsive mt-15">
                                                <table style="text-align: center"
                                                    class="table center-aligned-table table-hover mb-0">
                                                    <thead>
                                                        <tr class="table-info text-danger">
                                                            <th>#</th>
                                                            <th>{{ trans('Parent_trans.Name_Father') }}</th>
                                                            <th>{{ trans('Parent_trans.Email') }}</th>
                                                            <th>{{ trans('main_trans.dashboard_father_national_id') }}
                                                            </th>
                                                            <th>{{ trans('main_trans.dashboard_father_phone') }}</th>
                                                            <th>{{ trans('Students_trans.created_at') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($latestGuardians as $guardian)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $guardian->father_name }}</td>
                                                                <td>{{ $guardian->email }}</td>
                                                                <td>{{ $guardian->father_national_id }}</td>
                                                                <td>{{ $guardian->father_phone }}</td>
                                                                <td class="text-success">{{ $guardian->created_at }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="alert-danger" colspan="6">
                                                                    {{ trans('main_trans.dashboard_no_data') }}</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- Latest invoices tab --}}
                                        <div class="tab-pane fade" id="invoices" role="tabpanel"
                                            aria-labelledby="invoices-tab">
                                            <div class="table-responsive mt-15">
                                                <table style="text-align: center"
                                                    class="table center-aligned-table table-hover mb-0">
                                                    <thead>
                                                        <tr class="table-info text-danger">
                                                            <th>#</th>
                                                            <th>{{ trans('main_trans.dashboard_invoice_date') }}</th>
                                                            <th>{{ trans('Students_trans.name') }}</th>
                                                            <th>{{ trans('Students_trans.Grade') }}</th>
                                                            <th>{{ trans('Students_trans.classrooms') }}</th>
                                                            <th>{{ trans('fees_trans.fee_type') }}</th>
                                                            <th>{{ trans('fees_trans.amount') }}</th>
                                                            <th>{{ trans('Students_trans.created_at') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($latestInvoices as $invoice)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ optional($invoice->invoice_date)->format('Y-m-d') }}
                                                                </td>
                                                                <td>{{ $invoice->student?->name }}</td>
                                                                <td>{{ $invoice->grade?->Name }}</td>
                                                                <td>{{ $invoice->classroom?->name }}</td>
                                                                <td>{{ $invoice->fee?->title }}</td>
                                                                <td>{{ $invoice->amount }}</td>
                                                                <td class="text-success">{{ $invoice->created_at }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="alert-danger" colspan="8">
                                                                    {{ trans('main_trans.dashboard_no_data') }}</td>
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

                {{-- Calendar section --}}
                <div class="row">
                    <div class="col-xl-12 mb-30">
                        <livewire:calendar.calendar-widget :editable="true" :compact="false" />
                    </div>
                </div>
            </div>

            {{-- Footer section --}}
            @include('layouts.partials.footer')
        </div>
    </div>

    @include('layouts.partials.footer-scripts')
    @stack('scripts')
</body>

</html>
