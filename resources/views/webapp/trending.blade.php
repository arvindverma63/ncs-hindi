<x-webapp-layout
    title="Trending Music Charts | NCS Hindi"
    description="Discover the most played, liked, and downloaded royalty-free Hindi music on the official NCS Hindi chart. Live rank updates and creator highlights."
    keywords="trending hindi music, top ncs hindi, non copyright music chart, most downloaded hindi music, creator music chart"
>
    @push('heads')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <style>
            .glass-panel {
                background: rgba(12, 12, 15, 0.78);
                border: 1px solid rgba(255, 255, 255, 0.06);
                box-shadow: 0 24px 80px rgba(0, 0, 0, 0.32);
                backdrop-filter: blur(16px);
            }
            .soft-border {
                border: 1px solid rgba(255, 255, 255, 0.06);
            }
            @keyframes waveform {
                0% { transform: scaleY(0.4); }
                100% { transform: scaleY(1); }
            }
        </style>
    @endpush

    @php
        $sort = $filters['sort'] ?? 'downloads';
        $featuredImage = $featuredStem?->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1600&q=80';
    @endphp

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
        <div>
            <h1 class="font-brand text-2xl md:text-4xl font-black uppercase tracking-tighter text-white">
                Trending Music
            </h1>
            <p class="text-[10px] md:text-xs font-black uppercase tracking-[0.2em] text-zinc-500 mt-1">
                Ranked by downloads. Updated in real-time.
            </p>
        </div>

        {{-- Total Stats with Divider Line --}}
        <div class="flex items-center gap-4 md:gap-6 self-start md:self-auto bg-white/5 soft-border rounded-2xl px-5 py-3 backdrop-blur-md">
            <div class="text-center">
                <p class="text-[8px] font-black uppercase tracking-[0.25em] text-zinc-500">Likes</p>
                <p class="text-sm md:text-base font-black text-amber-500 mt-0.5">{{ number_format($trendingStats['likes']) }}</p>
            </div>
            <div class="h-8 w-px bg-white/10"></div>
            <div class="text-center">
                <p class="text-[8px] font-black uppercase tracking-[0.25em] text-zinc-500">Downloads</p>
                <p class="text-sm md:text-base font-black text-amber-500 mt-0.5">{{ number_format($trendingStats['downloads']) }}</p>
            </div>
            <div class="h-8 w-px bg-white/10"></div>
            <div class="text-center">
                <p class="text-[8px] font-black uppercase tracking-[0.25em] text-zinc-500">Views</p>
                <p class="text-sm md:text-base font-black text-amber-500 mt-0.5">{{ number_format($trendingStats['views']) }}</p>
            </div>
        </div>
    </div>

    {{-- Hero Featured Section --}}
    @if ($featuredStem)
        <section class="glass-panel soft-border rounded-[24px] mb-8 relative overflow-hidden flex flex-col lg:flex-row items-center gap-6 p-4 md:p-6 lg:p-8">
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_right,_rgba(245,158,11,0.2),_transparent_40%),radial-gradient(circle_at_bottom_left,_rgba(153,27,27,0.2),_transparent_40%)]"></div>
            
            {{-- Left Image --}}
            <div class="relative w-full sm:w-64 aspect-square shrink-0 rounded-[16px] overflow-hidden shadow-2xl z-10">
                <img src="{{ $featuredImage }}" alt="{{ $featuredStem->title }}" class="absolute inset-0 w-full h-full object-cover">
            </div>

            {{-- Center Content --}}
            <div class="flex-1 space-y-4 z-10 w-full">
                <span class="inline-block px-3 py-1 rounded-full bg-amber-500 text-black text-[9px] font-black uppercase tracking-[0.2em]">
                    Now Trending
                </span>
                <h2 class="font-brand text-3xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-white leading-none">
                    {{ $featuredStem->title }}
                </h2>
                <p class="text-zinc-300 text-sm md:text-base font-medium">
                    {{ $featuredStem->artist_name ?: '' }}
                </p>
                <div class="flex items-center gap-2">
                    @if ($featuredStem->category)
                        <span class="px-2.5 py-1 rounded bg-white/5 soft-border text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400">
                            {{ $featuredStem->category->name }}
                        </span>
                    @endif
                    @if ($featuredStem->language)
                        <span class="px-2.5 py-1 rounded bg-white/5 soft-border text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400">
                            {{ Str::before($featuredStem->language, ',') }}
                        </span>
                    @endif
                </div>
                <p class="text-zinc-400 text-xs sm:text-sm max-w-lg leading-relaxed">
                    Get ready to feel the unstoppable energy with the official track "{{ $featuredStem->title }}" from {{ $featuredStem->artist_name ?: 'the creator' }}.
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('webapp.music.show', $featuredStem->slug) }}" class="btn-vault px-8 py-3 rounded-full text-[10px] font-black tracking-[0.2em] uppercase">
                        Play Now
                    </a>
                    @if(Auth::check())
                        <button type="button"
                            data-music-like-btn
                            class="w-11 h-11 flex items-center justify-center rounded-full bg-white/5 soft-border text-zinc-400 hover:text-red-400 hover:border-red-400/50 transition {{ $featuredStem->isLikedBy(auth()->id()) ? 'text-red-400' : '' }}"
                            data-like-url="{{ route('webapp.music.like', $featuredStem->id) }}"
                            data-music-id="{{ $featuredStem->id }}"
                            data-liked="{{ $featuredStem->isLikedBy(auth()->id()) ? 1 : 0 }}">
                            <i data-music-like-icon class="fa-heart {{ $featuredStem->isLikedBy(auth()->id()) ? 'fa-solid' : 'fa-regular' }}"></i>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Right Stats & Waveform --}}
            <div class="w-full lg:w-72 shrink-0 border-t lg:border-t-0 lg:border-l border-white/10 pt-6 lg:pt-0 lg:pl-8 flex flex-col justify-between z-10 self-stretch">
                <div class="space-y-4 mb-8">
                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-download w-4"></i> Downloads</span>
                        <span class="text-white text-sm tracking-normal">{{ number_format($featuredStem->download_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">
                        <span class="flex items-center gap-2"><i class="fa-regular fa-heart w-4"></i> Likes</span>
                        <span class="text-white text-sm tracking-normal">{{ number_format($featuredStem->like_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-eye w-4"></i> Views</span>
                        <span class="text-white text-sm tracking-normal">{{ number_format($featuredStem->view_count) }}</span>
                    </div>
                </div>
                
                {{-- Animated Waveform Graphic --}}
                <div class="flex items-end gap-[3px] h-8 justify-between opacity-80 mt-auto">
                    @for($i = 0; $i < 40; $i++)
                        <div class="flex-1 bg-gradient-to-t from-amber-600 to-amber-400 rounded-t-sm" style="height: {{ rand(20, 100) }}%; animation: waveform {{ rand(10, 25)/10 }}s infinite alternate; transform-origin: bottom;"></div>
                    @endfor
                </div>
            </div>
        </section>
    @endif

    {{-- Filters (Hidden on very small screens to keep it clean like mockup, visible on tablet+) --}}
    <section class="mb-6 md:mb-8 glass-panel p-3 md:p-4 rounded-[18px]">
        <form action="{{ route('webapp.trending') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-[1.8fr_0.85fr_0.85fr_auto] gap-2.5 md:gap-3">
            <div class="relative sm:col-span-2 xl:col-span-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-500 text-[11px]"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, artist, album, or tags"
                    class="w-full rounded-2xl bg-black/40 soft-border pl-10 pr-3.5 py-2.5 text-[13px] text-white outline-none focus:border-amber-500/50">
            </div>
            <select name="category" class="w-full rounded-2xl bg-black/40 soft-border px-3.5 py-2.5 text-[13px] text-zinc-300 outline-none focus:border-amber-500/50">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                        {{ $category->name }} ({{ number_format($category->stems_count) }})
                    </option>
                @endforeach
            </select>
            <select name="sort" class="w-full rounded-2xl bg-black/40 soft-border px-3.5 py-2.5 text-[13px] text-zinc-300 outline-none focus:border-amber-500/50">
                <option value="downloads" {{ $sort === 'downloads' ? 'selected' : '' }}>Top downloads</option>
                <option value="likes" {{ $sort === 'likes' ? 'selected' : '' }}>Most liked</option>
                <option value="views" {{ $sort === 'views' ? 'selected' : '' }}>Most viewed</option>
                <option value="newest" {{ $sort === 'newest' || $sort === 'latest' ? 'selected' : '' }}>Newest releases</option>
            </select>
            <div class="flex items-stretch gap-2 sm:col-span-2 xl:col-span-1">
                <button type="submit" class="btn-vault px-4 py-2.5 rounded-2xl text-[9px] font-black tracking-[0.2em] whitespace-nowrap flex-1 xl:flex-none">
                    Filter
                </button>
                <a href="{{ route('webapp.trending') }}" class="px-4 py-2.5 rounded-2xl bg-white/5 soft-border text-[9px] font-black tracking-[0.2em] uppercase text-zinc-300 hover:text-white hover:border-amber-500/40 transition flex-1 xl:flex-none text-center">
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- Trending List View --}}
    <section class="mb-8">
        <div class="flex items-end justify-between mb-4">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">
                Showing {{ number_format($trendingStems->count()) }} of {{ number_format($trendingStems->total()) }} results
            </p>
            <div class="flex gap-1.5">
                {{-- Pagination at top right (compact) --}}
                @if ($trendingStems->hasPages())
                    <div class="flex gap-1">
                        @if (!$trendingStems->onFirstPage())
                            <a href="{{ $trendingStems->previousPageUrl() }}" class="w-7 h-7 flex items-center justify-center rounded bg-white/5 text-zinc-400 hover:text-white transition"><i class="fa-solid fa-chevron-left text-[10px]"></i></a>
                        @endif
                        @if ($trendingStems->hasMorePages())
                            <a href="{{ $trendingStems->nextPageUrl() }}" class="w-7 h-7 flex items-center justify-center rounded bg-white/5 text-zinc-400 hover:text-white transition"><i class="fa-solid fa-chevron-right text-[10px]"></i></a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if ($trendingStems->isNotEmpty())
            <div class="flex flex-col rounded-[20px] glass-panel soft-border overflow-hidden">
                {{-- Table Header --}}
                <div class="hidden md:grid grid-cols-[3rem_minmax(0,2.5fr)_minmax(0,2fr)_minmax(0,1.5fr)_auto] gap-4 p-4 border-b border-white/5 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 bg-black/40 items-center">
                    <div class="text-center">#</div>
                    <div>Song</div>
                    <div>Artist</div>
                    <div class="text-center">Stats</div>
                    <div class="text-center w-24">Actions</div>
                </div>
                
                {{-- Table Rows --}}
                <div class="divide-y divide-white/5">
                    @foreach ($trendingStems as $music)
                        @php
                            $heroImage = $music->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1200&q=80';
                            $stemLanguages = collect(explode(',', (string) $music->language))
                                ->map(fn ($language) => trim($language))
                                ->filter()
                                ->values();
                        @endphp
                        <div class="grid grid-cols-[auto_1fr] md:grid-cols-[3rem_minmax(0,2.5fr)_minmax(0,2fr)_minmax(0,1.5fr)_auto] gap-3 md:gap-4 p-3 md:p-4 items-center hover:bg-white/5 transition group">
                            
                            {{-- Rank --}}
                            <div class="text-center text-xs font-black text-zinc-500 hidden md:block">
                                {{ ($trendingStems->firstItem() ?? 1) + $loop->index }}
                            </div>
                            
                            {{-- Song Details --}}
                            <div class="flex items-center gap-3 md:gap-4 min-w-0 col-span-2 md:col-span-1">
                                <div class="relative w-12 h-12 md:w-14 md:h-14 rounded-[10px] overflow-hidden shrink-0 shadow-lg">
                                    <img src="{{ $heroImage }}" alt="{{ $music->title }}" class="w-full h-full object-cover">
                                    <div class="absolute top-0 left-0 bg-black/80 text-white text-[8px] font-black px-1.5 py-0.5 rounded-br-[6px] md:hidden">
                                        {{ ($trendingStems->firstItem() ?? 1) + $loop->index }}
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-brand text-sm md:text-base font-black uppercase tracking-tight text-white truncate group-hover:text-amber-400 transition">
                                        {{ $music->title }}
                                        @if($loop->first && $trendingStems->onFirstPage())
                                            <span class="inline-block ml-2 px-1.5 py-0.5 rounded bg-amber-500 text-black text-[7px] font-black uppercase tracking-widest align-middle relative -top-0.5">New</span>
                                        @endif
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1 hidden md:flex">
                                        <span class="px-1.5 py-0.5 rounded bg-white/5 soft-border text-[8px] font-black uppercase tracking-[0.2em] text-zinc-500">
                                            {{ $music->category->name ?? 'Music' }}
                                        </span>
                                        @if ($stemLanguages->isNotEmpty())
                                            <span class="px-1.5 py-0.5 rounded bg-white/5 soft-border text-[8px] font-black uppercase tracking-[0.2em] text-zinc-500">
                                                {{ $stemLanguages->first() }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 text-[10px] text-zinc-500 truncate md:hidden">
                                        {{ $music->artist_name ?: '' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Artist (Desktop Only) --}}
                            <div class="hidden md:block min-w-0">
                                <p class="text-xs text-zinc-400 truncate font-medium">
                                    {{ $music->artist_name ?: '' }}
                                </p>
                            </div>

                            {{-- Stats --}}
                            <div class="flex items-center justify-start md:justify-center gap-4 col-span-2 md:col-span-1 mt-1 md:mt-0 opacity-70 group-hover:opacity-100 transition">
                                <div class="flex items-center gap-1.5 text-zinc-400" title="Downloads">
                                    <i class="fa-solid fa-download text-[10px]"></i>
                                    <span class="text-[10px] font-bold">{{ number_format($music->download_count) }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-zinc-400" title="Likes">
                                    <i class="fa-regular fa-heart text-[10px]"></i>
                                    <span class="text-[10px] font-bold">{{ number_format($music->like_count) }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-zinc-400" title="Views">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                    <span class="text-[10px] font-bold">{{ number_format($music->view_count) }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-end gap-2 col-start-2 md:col-start-auto row-start-1 md:row-start-auto self-start md:self-auto">
                                <a href="{{ route('webapp.music.show', $music->slug) }}" class="w-8 h-8 md:w-9 md:h-9 rounded-full soft-border flex items-center justify-center text-zinc-300 hover:text-white hover:bg-white/10 hover:border-white/20 transition">
                                    <i class="fa-solid fa-play text-[10px] ml-0.5"></i>
                                </a>
                                <button type="button"
                                    data-music-share-btn
                                    data-share-title="{{ $music->title }}"
                                    data-share-url="{{ route('webapp.music.show', $music->slug) }}"
                                    class="w-8 h-8 md:w-9 md:h-9 rounded-full flex items-center justify-center text-zinc-500 hover:text-white transition">
                                    <i class="fa-solid fa-ellipsis-vertical text-[12px]"></i>
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                {{ $trendingStems->links('layouts.partials.webapp.pagination') }}
            </div>
        @else
            <div class="glass-panel rounded-[24px] p-6 md:p-8 text-center">
                <i class="fa-solid fa-wave-square text-4xl text-zinc-700"></i>
                <h4 class="mt-3 font-brand text-lg md:text-2xl font-black uppercase tracking-tight text-white">
                    No releases match your filters
                </h4>
                <p class="mt-2 text-[11px] md:text-sm text-zinc-500">
                    Try clearing the search or category filters to reveal the full chart.
                </p>
                <a href="{{ route('webapp.trending') }}" class="inline-flex mt-4 btn-vault px-4 py-2.5 rounded-2xl text-[8px] font-black tracking-[0.2em] uppercase">
                    Reset filters
                </a>
            </div>
        @endif
    </section>

</x-webapp-layout>







