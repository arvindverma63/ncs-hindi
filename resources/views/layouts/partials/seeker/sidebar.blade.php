@inject('settings', 'App\Services\SettingService')

<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="{{ route('seeker.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ $settings->getImageUrl('logo') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ $settings->getImageUrl('logo') }}" alt="" height="60">
                    </span>
                </a>

                <a href="{{ route('seeker.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ $settings->getImageUrl('logo') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ $settings->getImageUrl('logo') }}" alt="" height="60">
                    </span>
                </a>
            </div>

            <ul id="sidebar-menu">

                <li class="menu-title">Main Workspace</li>

                <li>
                    <a href="{{ route('seeker.dashboard') }}"
                        class="tp-link {{ request()->routeIs('seeker.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <iconify-icon icon="tabler:smart-home"></iconify-icon>
                        </span>
                        <span class="sidebar-text"> Dashboard </span>
                    </a>
                </li>

                <li class="menu-title mt-2">Discovery</li>

                <li>
                    <a href="{{ route('seeker.coaches.index') }}"
                        class="tp-link {{ request()->routeIs('seeker.coaches.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <iconify-icon icon="tabler:users-group"></iconify-icon>
                        </span>
                        <span class="sidebar-text"> Find Coaches </span>
                    </a>
                </li>

                <li class="menu-title mt-2">Account</li>

                <li>
                    <a href="{{ route('seeker.profile.edit') }}"
                        class="tp-link {{ request()->routeIs('seeker.profile.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <iconify-icon icon="tabler:user-circle"></iconify-icon>
                        </span>
                        <span class="sidebar-text"> My Profile </span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);" class="tp-link text-danger"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="nav-icon">
                            <iconify-icon icon="tabler:logout" class="text-danger"></iconify-icon>
                        </span>
                        <span class="sidebar-text"> Logout </span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
