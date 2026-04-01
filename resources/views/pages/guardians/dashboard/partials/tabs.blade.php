{{-- Guardian dashboard tabs navigation --}}
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-pills flex-wrap gap-2">
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('guardian.dashboard') ? 'active' : '' }}"
                    href="{{ route('guardian.dashboard') }}">
                    <i class="ti-layout-grid2 mr-1"></i>{{ trans('main_trans.guardian_overview_tab') }}
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('guardian.dashboard.attendance') ? 'active' : '' }}"
                    href="{{ route('guardian.dashboard.attendance') }}">
                    <i class="ti-check-box mr-1"></i>{{ trans('main_trans.guardian_attendance_tab') }}
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('guardian.dashboard.financial') ? 'active' : '' }}"
                    href="{{ route('guardian.dashboard.financial') }}">
                    <i class="ti-wallet mr-1"></i>{{ trans('main_trans.guardian_financial_tab') }}
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('guardian.profile') ? 'active' : '' }}"
                    href="{{ route('guardian.profile') }}">
                    <i class="ti-id-badge mr-1"></i>{{ trans('main_trans.profile_tab_title') }}
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('guardian.calendar') ? 'active' : '' }}"
                    href="{{ route('guardian.calendar') }}">
                    <i class="ti-calendar mr-1"></i>{{ trans('main_trans.dashboard_calendar_title') }}
                </a>
            </li>
        </ul>
    </div>
</div>
