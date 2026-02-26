<aside class="hidden lg:flex flex-col w-72 bg-[#050505] border-r border-[#1a1a1c] h-screen sticky top-0">
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
                <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-[0.2em] mt-1">Creator Ecosystem</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 overflow-y-auto no-scrollbar space-y-8">
        <div>
            <p class="px-4 text-[10px] font-black text-zinc-700 uppercase tracking-widest mb-4">Discovery</p>
            <div class="space-y-1">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('home') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-500 hover:text-white hover:bg-zinc-900/50' }}">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                    <span class="text-sm">Vault Feed</span>
                </a>

                <a href="{{ route('webapp.trending') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('webapp.trending') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-500 hover:text-white hover:bg-zinc-900/50' }}">
                    <i
                        class="fa-solid fa-fire text-sm {{ request()->routeIs('webapp.trending') ? 'text-amber-500' : 'group-hover:text-red-500' }}"></i>
                    <span class="text-sm">Trending</span>
                </a>

                <a href="{{ route('webapp.streams') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('webapp.streams') ? 'bg-gradient-to-r from-amber-600/10 to-transparent text-amber-500 font-bold border-l-2 border-amber-600' : 'text-zinc-500 hover:text-white hover:bg-zinc-900/50' }}">
                    <i
                        class="fa-solid fa-box-open text-sm {{ request()->routeIs('webapp.streams') ? 'text-amber-500' : 'group-hover:text-amber-500' }}"></i>
                    <span class="text-sm">Stems Library</span>
                </a>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between px-4 mb-4">
                <p class="text-[10px] font-black text-zinc-700 uppercase tracking-widest">Top Genres</p>
                <i
                    class="fa-solid fa-plus text-[10px] text-zinc-700 hover:text-white cursor-pointer transition-colors"></i>
            </div>
            <div class="space-y-1">
                <div
                    class="flex items-center justify-between px-4 py-2 rounded-xl hover:bg-zinc-900/30 group cursor-pointer transition-all">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-600 shadow-[0_0_8px_rgba(220,38,38,0.5)]"></span>
                        <span class="text-xs text-zinc-500 group-hover:text-zinc-200 transition-colors">Bollywood
                            Trap</span>
                    </div>
                    <i class="fa-solid fa-arrow-trend-up text-[10px] text-zinc-800 group-hover:text-red-500"></i>
                </div>
            </div>
        </div>
    </nav>

    <div class="p-4">
        @auth
            <div class="bg-gradient-to-b from-[#111114] to-[#050505] rounded-3xl p-4 border border-[#1a1a1c] shadow-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=b45309&color=fff"
                            class="w-10 h-10 rounded-xl object-cover border border-white/10" alt="Avatar">
                        <div
                            class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-[#111114] rounded-full">
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-zinc-500 font-medium uppercase tracking-tighter">
                            {{ Auth::user()->profile->rank_title ?? 'Level 1 Artist' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-1.5 mb-4">
                    <div class="flex justify-between text-[8px] font-black uppercase text-zinc-600 tracking-widest">
                        <span>Rank XP</span>
                        <span>{{ Auth::user()->profile->xp_count ?? 0 }}%</span>
                    </div>
                    <div class="h-1 w-full bg-zinc-900 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-amber-600 to-red-600"
                            style="width: {{ Auth::user()->profile->xp_count ?? 0 }}%"></div>
                    </div>
                </div>

                <a href="{{ route('webapp.profile') }}"
                    class="block w-full text-center py-2 bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 rounded-xl text-[10px] font-bold text-zinc-400 hover:text-white transition-all {{ request()->routeIs('webapp.profile') ? 'border-amber-600 text-white' : '' }}">
                    VIEW STUDIO
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="w-full text-[8px] font-black text-zinc-700 hover:text-red-600 uppercase tracking-widest transition-colors">
                        Sign Out
                    </button>
                </form>
            </div>
        @else
            <div class="bg-zinc-900/50 rounded-3xl p-6 border border-dashed border-zinc-800 text-center">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-4">Join the Ecosystem</p>
                <a href="{{ route('login') }}"
                    class="btn-vault block w-full py-2 text-[10px] font-black uppercase">Login</a>
            </div>
        @endauth
    </div>
</aside>
