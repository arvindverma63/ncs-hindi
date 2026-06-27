<aside class="hidden lg:flex flex-col w-72 bg-[#050505] border-r border-[#1a1a1c] h-screen sticky top-0">
    {{-- Brand Identity --}}
    <div class="p-8 mb-4">
        <div class="flex items-center gap-3 group cursor-pointer">
            <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#b45309] to-[#991b1b] flex items-center justify-center shadow-lg shadow-red-900/20 group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-compact-disc text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-black font-brand tracking-tighter text-white leading-none">
                    NCS <span class="text-amber-600">HINDI</span>
                </h1>
                <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-[0.2em] mt-1">Creator Ecosystem</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 overflow-y-auto no-scrollbar space-y-8">
        <div>
            <p class="px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Discovery</p>
            <div class="space-y-1">
                <a href="{{ route('home') }}"
                    aria-label="Go to Thread Feed"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('home') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                    <span class="text-sm">Thread Feed</span>
                </a>

                {{-- Added: Community Chat Route --}}
                <a href="{{ route('webapp.community.chat') }}"
                    aria-label="Go to Community Chat"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('webapp.community.chat*') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                    <i
                        class="fa-solid fa-comments text-sm {{ request()->routeIs('webapp.community.chat*') ? 'text-amber-500' : 'group-hover:text-amber-500' }}"></i>
                    <span class="text-sm">Community Chat</span>
                </a>

                <a href="{{ route('webapp.trending') }}"
                    aria-label="Go to Trending Discussions and Music"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('webapp.trending') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                    <i
                        class="fa-solid fa-fire text-sm {{ request()->routeIs('webapp.trending') ? 'text-amber-500' : 'group-hover:text-red-500' }}"></i>
                    <span class="text-sm">Trending</span>
                </a>

                <a href="{{ route('webapp.streams') }}"
                    aria-label="Go to Music Library"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('webapp.streams') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                    <i
                        class="fa-solid fa-box-open text-sm {{ request()->routeIs('webapp.streams') ? 'text-amber-500' : 'group-hover:text-amber-500' }}"></i>
                    <span class="text-sm">Music Library</span>
                </a>

{{-- Hiding Game Section as requested --}}
                {{-- <a href="{{ route('webapp.game') }}"
                    aria-label="Play Mini Game"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('webapp.game') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                    <i
                        class="fa-solid fa-gamepad text-sm {{ request()->routeIs('webapp.game') ? 'text-amber-500' : 'group-hover:text-amber-500' }}"></i>
                    <span class="text-sm">Mini Game</span>
                </a> --}}
            </div>
        </div>

        @php
            $settings = app(\App\Services\SettingService::class);
        @endphp

        <div>
            <p class="px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Official Channels</p>
            <div class="space-y-1">
                <a href="{{ $settings->get('youtube_url', 'https://www.youtube.com') }}" target="_blank" rel="noopener noreferrer"
                    aria-label="Visit our Official YouTube Channel (external link)"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-zinc-400 hover:text-white hover:bg-zinc-900/50 transition-all group">
                    <i class="fa-brands fa-youtube text-sm group-hover:text-amber-500"></i>
                    <span class="text-sm">YouTube Channel</span>
                </a>
                <a href="{{ $settings->get('playstore_url', 'https://play.google.com/store') }}" target="_blank" rel="noopener noreferrer"
                    aria-label="Download our Android App from Play Store (external link)"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-zinc-400 hover:text-white hover:bg-zinc-900/50 transition-all group">
                    <i class="fa-brands fa-android text-sm group-hover:text-red-500"></i>
                    <span class="text-sm">Android App</span>
                </a>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between px-4 mb-4">
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Top Genres</p>
                <a href="{{ route('webapp.trending') }}" aria-label="View all music genres" class="text-[10px] text-zinc-400 hover:text-white transition-colors uppercase tracking-widest">
                    View all
                </a>
            </div>

            <div class="space-y-1">
                @forelse ($sidebarCategories ?? [] as $category)
                    <a href="{{ route('webapp.music.genre', $category->slug) }}"
                        aria-label="Filter music by genre {{ $category->name }}"
                        class="flex items-center justify-between px-4 py-2 rounded-xl hover:bg-zinc-900/30 group transition-all {{ (request('category') == $category->slug || request()->route('slug') == $category->slug) ? 'bg-zinc-900/40 border border-amber-500/20' : '' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600 shadow-[0_0_8px_rgba(220,38,38,0.5)] shrink-0"></span>
                            <span class="text-xs text-zinc-400 group-hover:text-zinc-200 transition-colors truncate">
                                {{ $category->name }}
                            </span>
                        </div>
                        <span class="text-[10px] font-black text-zinc-400 group-hover:text-amber-500 shrink-0">
                            {{ number_format($category->public_stems_count) }}
                        </span>
                    </a>
                @empty
                    <div class="px-4 py-3 rounded-xl bg-zinc-900/30 text-[10px] text-zinc-400">
                        No active categories yet.
                    </div>
                @endforelse
            </div>
        </div>

        @auth
            <div>
                <p class="px-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Support</p>
                <div class="space-y-1">
                    <a href="{{ route('webapp.bug-reports.create') }}"
                        aria-label="Report a Bug"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('webapp.bug-reports.*') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-400 hover:text-white hover:bg-zinc-900/50' }}">
                        <i
                            class="fa-solid fa-bug text-sm {{ request()->routeIs('webapp.bug-reports.*') ? 'text-amber-500' : 'group-hover:text-amber-500' }}"></i>
                        <span class="text-sm">Report a Bug</span>
                    </a>
                </div>
            </div>
        @endauth
    </nav>

    {{-- User Profile Section --}}
    <div class="p-4">
        @auth
            <div class="bg-gradient-to-b from-[#111114] to-[#050505] rounded-3xl p-4 border border-[#1a1a1c] shadow-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="relative">
                        @php
                            $userAvatar =
                                Auth::user()->profile_image && strlen(Auth::user()->profile_image) > 20
                                    ? Auth::user()->profile_image
                                    : null;

                            $defaultAvatar =
                                'https://ui-avatars.com/api/?name=' .
                                urlencode(Auth::user()->name) .
                                '&background=b45309&color=fff';
                        @endphp

                        <img src="{{ $userAvatar ?? $defaultAvatar }}"
                            onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';" referrerpolicy="no-referrer"
                            class="w-10 h-10 rounded-xl object-cover border border-white/10"
                            alt="{{ Auth::user()->name }}">

                        <div
                            class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-[#111114] rounded-full">
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-zinc-500 font-medium uppercase tracking-tighter">
                            {{-- {{ Auth::user()->profile->rank_title ?? 'Level 1 Artist' }} --}}
                        </p>
                    </div>
                </div>

                <a href="{{ route('webapp.profile') }}"
                    aria-label="View your Profile"
                    class="block w-full text-center py-2 bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 rounded-xl text-[10px] font-bold text-zinc-400 hover:text-white transition-all {{ request()->routeIs('webapp.profile') ? 'border-amber-600 text-white' : '' }}">
                    VIEW PROFILE
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        aria-label="Sign Out"
                        class="w-full text-[8px] font-black text-zinc-400 hover:text-red-600 uppercase tracking-widest transition-colors outline-none">
                        Sign Out
                    </button>
                </form>
            </div>
        @else
            <div class="bg-zinc-900/50 rounded-3xl p-6 border border-dashed border-zinc-800 text-center">
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-4">Join the Ecosystem</p>
                <a href="{{ route('login') }}"
                    aria-label="Login to NCS Hindi"
                    class="btn-vault block w-full py-2 text-[10px] font-black uppercase">Login</a>
            </div>
        @endauth
    </div>
</aside>







