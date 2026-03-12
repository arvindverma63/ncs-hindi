@inject('settings', 'App\Services\SettingService')

<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ $settings->getImageUrl('logo') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ $settings->getImageUrl('logo') }}" alt="" height="60">
                    </span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ $settings->getImageUrl('logo') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ $settings->getImageUrl('logo') }}" alt="" height="60">
                    </span>
                </a>
            </div>

            <ul id="sidebar-menu">

                <li class="menu-title">Menu</li>
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="tp-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <iconify-icon icon="tabler:assembly"></iconify-icon>
                        </span>
                        <span class="sidebar-text"> Dashboard </span>
                    </a>
                </li>


                @can('users.view')
                    <li class="menu-title mt-2">User Management</li>
                    <li>
                        <a href="#sidebarUsers" data-bs-toggle="collapse"
                            class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <iconify-icon icon="tabler:users"></iconify-icon>
                            </span>
                            <span class="sidebar-text"> Users </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="sidebarUsers">
                            <ul class="nav-second-level">
                                <li><a href="{{ route('admin.users.index') }}"
                                        class="tp-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">All
                                        Users</a></li>
                                @can('users.create')
                                    <li><a href="{{ route('admin.users.create') }}"
                                            class="tp-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">Add
                                            User</a></li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @if (auth()->user()->can('coaches.view') ||
                        auth()->user()->can('seekers.view') ||
                        auth()->user()->can('categories.view') ||
                        auth()->user()->can('blogs.view'))
                    <li class="menu-title mt-2">Platform Management</li>
                @endif

                <li>
                    <a href="{{ route('community-channels.index') }}"
                        class="tp-link {{ request()->routeIs('community-channels.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <iconify-icon icon="tabler:messages"></iconify-icon>
                        </span>
                        <span class="sidebar-text"> Studio Rooms </span>
                    </a>
                </li>

                {{-- NEW: Stem Management Section --}}
                @can('stems.view')
                    <li>
                        <a href="#sidebarStems" data-bs-toggle="collapse"
                            class="{{ request()->routeIs('admin.stems.*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <iconify-icon icon="tabler:music"></iconify-icon>
                            </span>
                            <span class="sidebar-text"> Music Stems </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.stems.*') ? 'show' : '' }}" id="sidebarStems">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('admin.stems.index') }}"
                                        class="tp-link {{ request()->routeIs('admin.stems.index') ? 'active' : '' }}">
                                        All Stems
                                    </a>
                                </li>
                                @can('stems.create')
                                    <li>
                                        <a href="{{ route('admin.stems.create') }}"
                                            class="tp-link {{ request()->routeIs('admin.stems.create') ? 'active' : '' }}">
                                            Upload Stem
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('blogs.view')
                    <li>
                        <a href="#sidebarBlogs" data-bs-toggle="collapse"
                            class="{{ request()->routeIs(['admin.blogs.*', 'admin.media.*']) ? 'active' : '' }}">
                            <span class="nav-icon">
                                <iconify-icon icon="tabler:news"></iconify-icon>
                            </span>
                            <span class="sidebar-text"> Blogs & Media </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs(['admin.blogs.*', 'admin.media.*']) ? 'show' : '' }}"
                            id="sidebarBlogs">
                            <ul class="nav-second-level">
                                <li><a href="{{ route('admin.blogs.index') }}"
                                        class="tp-link {{ request()->routeIs('admin.blogs.index') ? 'active' : '' }}">All
                                        Blogs</a></li>
                                @can('blogs.create')
                                    <li><a href="{{ route('admin.blogs.create') }}"
                                            class="tp-link {{ request()->routeIs('admin.blogs.create') ? 'active' : '' }}">Create
                                            Post</a></li>
                                @endcan
                                @can('media.view')
                                    <li><a href="{{ route('admin.media.index') }}"
                                            class="tp-link {{ request()->routeIs('admin.media.index') ? 'active' : '' }}">Media
                                            Gallery</a></li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('settings.manage')
                    <li class="menu-title mt-2">Pages</li>
                    <li>
                        <a href="{{ route('admin.pages.index') }}"
                            class="tp-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <iconify-icon icon="tabler:file-description"></iconify-icon>
                            </span>
                            <span class="sidebar-text"> Manage Pages </span>
                        </a>
                    </li>
                @endcan

                @if (auth()->user()->can('roles.view') || auth()->user()->can('settings.manage'))
                    <li class="menu-title mt-2">Configuration</li>
                @endif

                @can('roles.view')
                    <li>
                        <a href="#sidebarRoles" data-bs-toggle="collapse"
                            class="{{ request()->routeIs(['admin.roles.*', 'admin.permissions.*']) ? 'active' : '' }}">
                            <span class="nav-icon">
                                <iconify-icon icon="tabler:shield-lock"></iconify-icon>
                            </span>
                            <span class="sidebar-text"> Roles & Permissions </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs(['admin.roles.*', 'admin.permissions.*']) ? 'show' : '' }}"
                            id="sidebarRoles">
                            <ul class="nav-second-level">
                                <li><a href="{{ route('admin.roles.index') }}"
                                        class="tp-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">Roles</a>
                                </li>
                                <li><a href="{{ route('admin.permissions.index') }}"
                                        class="tp-link {{ request()->routeIs('admin.permissions.index') ? 'active' : '' }}">Permissions</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('settings.manage')
                    <li>
                        <a href="#sidebarSettings" data-bs-toggle="collapse"
                            class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <iconify-icon icon="tabler:settings"></iconify-icon>
                            </span>
                            <span class="sidebar-text"> Settings </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.settings.*') ? 'show' : '' }}"
                            id="sidebarSettings">
                            <ul class="nav-second-level">
                                <li><a href="{{ route('admin.settings.index') }}" class="tp-link">General Settings</a>
                                </li>
                                <li><a href="{{ route('admin.settings.sms-gateway') }}" class="tp-link">SMS Gateway</a>
                                </li>
                                <li><a href="{{ route('admin.settings.mail-config') }}" class="tp-link">Mail
                                        Configuration</a></li>
                                <li><a href="{{ route('admin.settings.social-links') }}" class="tp-link">Social Links</a>
                                </li>
                                <li><a href="{{ route('admin.settings.page-setting') }}" class="tp-link">Page
                                        Settings</a></li>
                            </ul>
                        </div>
                    </li>
                @endcan

            </ul>
        </div>
    </div>
</div>
