<x-webapp-layout
    title="Music Library | Royalty-Free Tracks - NCS Hindi"
    description="Browse and download official studio-grade Hindi NCS music assets. Perfect for YouTube videos, streaming, and creative projects with zero copyright strikes."
    keywords="ncs hindi library, download royalty free music, no copyright hindi songs, studio grade audio, creators music download"
>
    {{-- 1. Library Search & Stats --}}
    <section class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12 px-2">
        <div class="flex-1">
                <h1 class="font-brand text-4xl font-black text-white uppercase tracking-tighter">
                MUSIC <span class="text-amber-500 italic">Library</span>
            </h1>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-[0.2em] mt-1">
                Official Studio-Grade Hindi NCS Assets
            </p>
        </div>


    </section>

    {{-- 2. Technical Filtering System --}}
    <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-12">
        <div class="md:col-span-6 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-amber-500 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by title, artist, or album..."
                class="w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-4 pl-12 pr-4 text-xs text-white focus:border-amber-500 outline-none transition-all">
        </div>

        <div class="md:col-span-3">
            <select name="category" onchange="this.form.submit()"
                class="w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl px-4 py-4 text-xs text-zinc-400 focus:border-amber-500 outline-none cursor-pointer appearance-none">
                <option value="">All Categories</option>
                @foreach (\App\Models\Category::where('is_active', 1)->get() as $cat)
                    <option value="{{ $cat->slug }}" {{ (request('category') == $cat->slug || (isset($category) && $category->id == $cat->id)) ? 'selected' : '' }}>
                        {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-3">
            <select name="sort" onchange="this.form.submit()"
                class="w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl px-4 py-4 text-xs text-zinc-400 focus:border-amber-500 outline-none cursor-pointer appearance-none">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Releases</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
            </select>
        </div>
    </form>

    {{-- 3. music Grid --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
        @forelse($music as $item)
            @php
                $itemLanguages = collect(explode(',', (string) $item->language))
                    ->map(fn ($language) => trim($language))
                    ->filter()
                    ->values();
            @endphp
            <div class="group bg-zinc-900/30 border border-zinc-800/60 rounded-[32px] overflow-hidden hover:border-amber-500/40 transition-all duration-500" data-like-card>

                {{-- Artwork --}}
                <div class="relative aspect-square m-3 overflow-hidden rounded-[24px] bg-zinc-800">
                    @if ($item->featured_image)
                        <img src="{{ $item->featured_image }}"
                            loading="lazy" decoding="async"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            alt="{{ $item->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fa-solid fa-music text-4xl text-zinc-700"></i>
                        </div>
                    @endif

                    {{-- Status Badges --}}
                    <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                        @if($item->bpm)
                            <span class="px-2 py-1 bg-black/70 backdrop-blur-md rounded-lg text-[8px] font-black text-white border border-white/10">
                                {{ $item->bpm }} BPM
                            </span>
                        @endif
                        @if($item->music_key)
                            <span class="px-2 py-1 bg-amber-500/90 backdrop-blur-md rounded-lg text-[8px] font-black text-black border border-amber-400/20">
                                KEY: {{ $item->music_key }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-5 pt-1">
                    <div class="mb-4">
                        <div class="flex justify-between items-start gap-2">
                            <h4 class="font-brand text-lg font-bold text-white uppercase tracking-tighter truncate">
                                {{ $item->title }}
                            </h4>
                        </div>
                        <p class="text-xs text-zinc-500 font-bold truncate">
                            {{ $item->artist_name ?: 'Official NCS Asset' }}
                        </p>
                    </div>

                    @if ($item->description || $itemLanguages->isNotEmpty())
                        <div class="mb-4 space-y-2">
                            @if ($item->description)
                                <p class="text-xs leading-relaxed text-zinc-500">
                                    {{ \Illuminate\Support\Str::limit($item->description, 95) }}
                                </p>
                            @endif
                            @if ($itemLanguages->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($itemLanguages->take(3) as $language)
                                        <span class="px-2 py-1 rounded-full bg-white/5 border border-white/5 text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                            {{ $language }}
                                        </span>
                                    @endforeach
                                    @if ($itemLanguages->count() > 3)
                                        <span class="px-2 py-1 rounded-full bg-white/5 border border-white/5 text-[8px] font-black uppercase tracking-[0.2em] text-zinc-500">
                                            +{{ $itemLanguages->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Stats --}}
                    <div class="flex items-center justify-between py-3 border-y border-zinc-800/50">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1">
                                <i class="fa-solid fa-heart text-[10px] text-zinc-600"></i>
                                <span data-like-count class="text-[10px] font-black text-zinc-400">{{ number_format($item->like_count) }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="fa-solid fa-download text-[10px] text-zinc-600"></i>
                                <span class="text-[10px] font-black text-zinc-400">{{ number_format($item->download_count) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-5">
                        <a href="{{ route('webapp.music.show', $item->slug) }}"
                            class="block w-full py-3 bg-white text-black rounded-xl text-[10px] font-black uppercase text-center hover:bg-amber-500 hover:text-white transition-all">
                            VIEW
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center border border-dashed border-zinc-800 rounded-[40px]">
                <i class="fa-solid fa-box-open text-4xl text-zinc-800 mb-4"></i>
                <h5 class="text-zinc-500 font-bold uppercase tracking-widest text-xs">No assets found</h5>
            </div>
        @endforelse
    </section>

    <div class="mt-12">
        {{ $music->links('layouts.partials.webapp.pagination') }}
    </div>
</x-webapp-layout>







