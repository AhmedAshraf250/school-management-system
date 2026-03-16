@extends('layouts.master')

@section('css')
    @toastr_css
@endsection

@section('title')
    {{ trans('main_trans.list_Promotions') }}
@endsection

@section('PageTitle')
    {{ trans('main_trans.list_Promotions') }}
@endsection

@section('content')
    {{-- Promotions management page wrapper --}}
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    {{-- Top actions --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <a href="{{ route('promotions.create') }}" class="btn btn-success btn-sm">
                            {{ trans('main_trans.add_Promotion') }}
                        </a>

                        @if ($promotions->isNotEmpty())
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#RollbackAllPromotionsModal">
                                {{ trans('Students_trans.rollback_all') }}
                            </button>
                        @endif
                    </div>

                    {{-- Promotions listing table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50"
                            style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('Students_trans.name') }}</th>
                                    <th class="table-danger">{{ trans('Students_trans.from_grade') }}</th>
                                    <th class="table-danger">{{ trans('Students_trans.from_classroom') }}</th>
                                    <th class="table-danger">{{ trans('Students_trans.from_section') }}</th>
                                    <th class="table-danger">{{ trans('Students_trans.academic_year_from') }}</th>
                                    <th class="table-success">{{ trans('Students_trans.to_grade') }}</th>
                                    <th class="table-success">{{ trans('Students_trans.to_classroom') }}</th>
                                    <th class="table-success">{{ trans('Students_trans.to_section') }}</th>
                                    <th class="table-success">{{ trans('Students_trans.academic_year_to') }}</th>
                                    <th>{{ trans('Students_trans.created_at') }}</th>
                                    <th>{{ trans('Students_trans.Processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($promotions as $promotion)
                                    {{-- Promotion row --}}
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $promotion->student?->name ?? '-' }}</td>
                                        <td>{{ $promotion->fromGrade?->Name ?? '-' }}</td>
                                        <td>{{ $promotion->fromClassroom?->name ?? '-' }}</td>
                                        <td>{{ $promotion->fromSection?->name ?? '-' }}</td>
                                        <td>{{ $promotion->academic_year_from }}</td>
                                        <td>{{ $promotion->toGrade?->Name ?? '-' }}</td>
                                        <td>{{ $promotion->toClassroom?->name ?? '-' }}</td>
                                        <td>{{ $promotion->toSection?->name ?? '-' }}</td>
                                        <td>{{ $promotion->academic_year_to }}</td>
                                        <td>{{ optional($promotion->promoted_at)->diffForHumans() ?? '-' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                data-toggle="modal"
                                                data-target="#RollbackOnePromotionModal{{ $promotion->id }}">
                                                {{ trans('Students_trans.rollback_one') }}
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Rollback one modal --}}
                                    @include('pages.students.admin.promotion.delete-one', ['promotion' => $promotion])
                                @empty
                                    {{-- Empty state --}}
                                    <tr>
                                        <td colspan="12" class="text-muted py-3">
                                            {{ trans('Students_trans.no_promotions') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Rollback all modal --}}
                    @include('pages.students.admin.promotion.delete-all')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @toastr_js
    @toastr_render
@endsection
