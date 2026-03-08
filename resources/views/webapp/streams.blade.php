<x-webapp-layout>
    {{-- 1. Library Search & Stats --}}
    <section class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12 px-2">
        <div class="flex-1">
            <h1 class="font-brand text-3xl font-black text-white uppercase tracking-tighter">
                MUSIC <span class="text-amber-500 italic">Library</span>
            </h1>
            <p class="text-xs text-zinc-500 font-bold uppercase tracking-widest mt-1">
                Explore Official High-Fidelity Hindi NCS Assets
            </p>
        </div>

        <div class="flex items-center gap-4 bg-zinc-900/30 p-2 rounded-2xl border border-zinc-800">
            <div class="px-4 py-2 border-r border-zinc-800 text-center">
                <p class="text-lg font-black text-white leading-none">{{ $stems->count() }}</p>
                <p class="text-[8px] text-zinc-500 uppercase font-bold tracking-tighter mt-1">Available</p>
            </div>
            <div class="px-4 py-2 text-center">
                <p class="text-lg font-black text-red-600 leading-none">Studio</p>
                <p class="text-[8px] text-zinc-500 uppercase font-bold tracking-tighter mt-1">Quality</p>
            </div>
        </div>
    </section>

    {{-- 2. Technical Filtering System --}}
    <form action="{{ route('webapp.streams') }}" method="GET" class="flex flex-wrap gap-3 mb-10">
        <div class="relative flex-1 min-w-[280px]">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-amber-600 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by title or artist..."
                class="w-full bg-zinc-900 border border-zinc-800 rounded-xl py-3 pl-12 pr-4 text-xs text-white focus:border-amber-600 outline-none transition">
        </div>

        <select name="category" onchange="this.form.submit()"
            class="bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-xs text-zinc-400 focus:border-red-600 outline-none cursor-pointer">
            <option value="">All Categories</option>
            @foreach (\App\Models\Category::where('is_active', 1)->get() as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}</option>
            @endforeach
        </select>

        <select name="sort" onchange="this.form.submit()"
            class="bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-xs text-zinc-400 focus:border-red-600 outline-none cursor-pointer">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
        </select>
    </form>

    {{-- 3. Stems List (High Density) --}}
    <section class="space-y-4">
        @forelse($stems as $stem)
            <div
                class="forum-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-[#121215] transition-all border border-transparent hover:border-zinc-800 rounded-2xl">
                <div class="flex items-center gap-5">
                    {{-- Visual Indicator: Image or Icon --}}
                    <div
                        class="w-14 h-14 bg-zinc-900 border border-zinc-800 rounded-2xl flex items-center justify-center overflow-hidden group-hover:border-amber-600 transition duration-300">
                        @if ($stem->featured_image)
                            <img src="{{ $stem->featured_image ?? "" }}"
                                class="w-full h-full object-cover" alt="{{ $stem->title }}">
                        @else
                            @php
                                $icon = 'fa-music';
                                $color = 'text-zinc-600';
                                if ($stem->category) {
                                    if (Str::contains(strtolower($stem->category->name), 'vocal')) {
                                        $icon = 'fa-microphone';
                                        $color = 'text-red-600';
                                    } elseif (Str::contains(strtolower($stem->category->name), 'bass')) {
                                        $icon = 'fa-wave-square';
                                        $color = 'text-amber-600';
                                    } elseif (Str::contains(strtolower($stem->category->name), 'drum')) {
                                        $icon = 'fa-drum';
                                        $color = 'text-blue-600';
                                    }
                                }
                            @endphp
                            <i class="fa-solid {{ $icon }} text-xl {{ $color }}"></i>
                        @endif
                    </div>

                    <div class="overflow-hidden">
                        <h4 class="font-bold text-white group-hover:text-amber-500 transition text-truncate">
                            {{ $stem->title }}</h4>
                        <div class="flex items-center gap-3 mt-1">
                            <span
                                class="text-[9px] font-black text-zinc-600 uppercase tracking-widest bg-black px-2 py-0.5 rounded">
                                {{ $stem->category->name ?? 'NCS Release' }}
                            </span>
                            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest">
                                {{ $stem->file_size }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Technical Stats --}}
                <div class="flex flex-wrap items-center gap-6 lg:gap-12 px-2">
                    <div class="text-center min-w-[45px]">
                        <p class="text-xs font-black text-zinc-300">{{ $stem->bpm ?? '--' }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">BPM</p>
                    </div>
                    <div class="text-center min-w-[45px]">
                        <p class="text-xs font-black text-zinc-300">{{ $stem->music_key ?? '--' }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">Key</p>
                    </div>
                    <div class="text-center hidden sm:block">
                        <p class="text-xs font-black text-zinc-300">{{ number_format($stem->download_count) }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">Downloads</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 ml-auto lg:ml-0">
                    <button type="button"
                        onclick="window.location.href='{{ route('webapp.stems.show', $stem->id ?? 1) }}'"
                        class="p-3 bg-zinc-900 border border-zinc-800 rounded-xl text-zinc-400 hover:text-white transition group/btn">
                        <i class="fa-solid fa-circle-info group-hover/btn:scale-110 transition"></i>
                    </button>

                    <a href="{{ asset('storage/' . $stem->file_path) }}" download
                        class="btn-vault px-6 py-2.5 rounded-xl text-[10px] font-black flex items-center gap-2 hover:scale-105 transition-transform">
                        <i class="fa-solid fa-download"></i> GET STEMS
                    </a>
                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-zinc-900/20 rounded-[32px] border border-dashed border-zinc-800">
                <i class="fa-solid fa- music-slash text-4xl text-zinc-800 mb-4"></i>
                <h5 class="text-zinc-500 font-bold uppercase tracking-widest text-xs">No studio assets match your
                    criteria</h5>
            </div>
        @endforelse
    </section>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $stems->links() }}
    </div>

    {{-- 4. Storage & Licensing Alert --}}
    <section
        class="mt-12 bg-gradient-to-r from-red-950/20 to-amber-950/10 border border-red-900/30 rounded-[32px] p-6 lg:p-10 flex flex-col md:flex-row items-center gap-8 shadow-2xl">
        <div
            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-600 to-red-600 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-file-contract text-2xl text-white"></i>
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="font-brand text-lg font-bold text-white uppercase italic tracking-tight">Royalty-Free Creator
                License</h3>
            <p class="text-xs text-zinc-400 mt-1 font-medium leading-relaxed max-w-xl">
                All stems are 100% royalty-free for content creators. No copyright strikes on social platforms when you
                link back to the official NCS Hindi channel.
            </p>
        </div>
        <button
            class="bg-white text-black text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-xl hover:bg-zinc-200 transition">
            Usage Guide
        </button>
    </section>
</x-webapp-layout>
