@php
    $activeGuard = \App\Support\Auth\GuardResolver::currentGuard() ?? 'admin';
    $activeUser = auth()->guard($activeGuard)->user();
    $displayName = $activeUser?->name ?? ($activeUser?->father_name ?? $activeUser?->email);
    $profileRoute = match ($activeGuard) {
        'teacher' => route('teacher.profile'),
        'student' => route('student.profile'),
        default => '#',
    };
@endphp

<li class="nav-item dropdown mr-30">
    <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
        aria-expanded="false">
        <img src="{{ URL::asset('assets/images/user_icon.png') }}" alt="avatar">
    </a>

    <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-header">
            <div class="media">
                <div class="media-body">
                    <h5 class="mt-0 mb-0">{{ $displayName }}</h5>
                    <span>{{ $activeUser?->email }}</span>
                </div>
            </div>
        </div>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="#"><i
                class="text-secondary ti-reload"></i>{{ trans('Sidebar_trans.activity') }}</a>
        <a class="dropdown-item" href="#"><i
                class="text-success ti-email"></i>{{ trans('Sidebar_trans.messages') }}</a>
        <a class="dropdown-item" href="{{ $profileRoute }}"><i
                class="text-warning ti-user"></i>{{ trans('Sidebar_trans.profile') }}</a>
        <a class="dropdown-item" href="#"><i
                class="text-dark ti-layers-alt"></i>{{ trans('Sidebar_trans.projects') }}
            <span class="badge badge-info">6</span>
        </a>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="{{ route('auth.selection') }}">
            <i class="text-primary ti-exchange-vertical"></i>{{ trans('Sidebar_trans.switch_account') }}
        </a>

        @if ($activeGuard === 'admin')
            <a class="dropdown-item" href="{{ route('settings.index') }}"><i
                    class="text-info ti-settings"></i>{{ trans('Sidebar_trans.settings') }}</a>
        @endif

        <a class="dropdown-item" href="{{ route('logout.guard', ['guard' => $activeGuard]) }}"
            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="text-danger ti-unlock"></i>{{ __('Sidebar_trans.Logoff') }}
        </a>

        <form id="logout-form" action="{{ route('logout.guard', ['guard' => $activeGuard]) }}" method="POST"
            style="display: none;">
            @csrf
        </form>
    </div>
</li>
