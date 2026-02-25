<nav
    class="lg:hidden fixed bottom-0 left-0 right-0 h-20 bg-[#08080a] border-t border-zinc-900 px-6 flex items-center justify-around z-[100] backdrop-blur-xl bg-opacity-90">

    {{-- Vault / Home --}}
    <a href="{{ route('home') }}"
        class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('home') ? 'text-amber-500' : 'text-zinc-600' }}">
        <i class="fa-solid fa-layer-group text-lg"></i>
        <span class="text-[9px] font-bold uppercase tracking-tighter">Vault</span>
    </a>

    {{-- Trending / Hot --}}
    <a href="{{ route('webapp.trending') }}"
        class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('webapp.trending') ? 'text-amber-500' : 'text-zinc-600' }}">
        <i class="fa-solid fa-fire text-lg"></i>
        <span class="text-[9px] font-bold uppercase tracking-tighter">Hot</span>
    </a>

    {{-- Center Action Button (Create) --}}
    <a href="#" class="flex flex-col items-center -mt-10 group">
        <div
            class="w-14 h-14 btn-vault rounded-2xl flex items-center justify-center shadow-2xl shadow-red-900/40 border-4 border-black text-white group-active:scale-95 transition-transform duration-200">
            <i class="fa-solid fa-plus text-xl"></i>
        </div>
    </a>

    {{-- Stems Library --}}
    <a href="{{ route('webapp.streams') }}"
        class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('webapp.streams') ? 'text-amber-500' : 'text-zinc-600' }}">
        <i class="fa-solid fa-box text-lg"></i>
        <span class="text-[9px] font-bold uppercase tracking-tighter">Stems</span>
    </a>

    {{-- Profile Page (Updated) --}}
    <a href="{{ route('webapp.profile') }}"
        class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('webapp.profile') ? 'text-amber-500' : 'text-zinc-600' }}">
        <i class="fa-solid fa-user text-lg"></i>
        <span class="text-[9px] font-bold uppercase tracking-tighter">Profile</span>
    </a>
</nav>
