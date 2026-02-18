{{-- resources/views/components/ui/user-dropdown.blade.php --}}

<li class="nav-item dropdown mr-30">
    <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
        aria-expanded="false">
        <img src="{{ URL::asset('assets/images/user_icon.png') }}" alt="avatar">
    </a>

    <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-header">
            <div class="media">
                <div class="media-body">
                    <h5 class="mt-0 mb-0">{{ Auth::user()->name }}</h5>
                    <span>{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="#"><i class="text-secondary ti-reload"></i>Activity</a>
        <a class="dropdown-item" href="#"><i class="text-success ti-email"></i>Messages</a>
        <a class="dropdown-item" href="#"><i class="text-warning ti-user"></i>Profile</a>
        <a class="dropdown-item" href="#"><i class="text-dark ti-layers-alt"></i>Projects
            <span class="badge badge-info">6</span>
        </a>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="#"><i class="text-info ti-settings"></i>Settings</a>
        <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="text-danger ti-unlock"></i>{{ __('Sidebar_trans.Logoff') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</li>