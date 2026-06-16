<x-webapp-layout>
    <div class="min-h-screen bg-black text-white p-8">
        <div class="mb-12">
            <h1 class="text-4xl font-brand font-black uppercase italic tracking-tighter">
                Search Results <span class="text-amber-600">.</span>
            </h1>
            <p class="text-zinc-500 mt-2">Showing results for: <span class="text-white font-mono">"{{ $query }}"</span></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

            <div class="lg:col-span-8 space-y-6">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-comments text-amber-500"></i> Discussions
                    </h2>
                    <span class="text-xs text-zinc-500 bg-zinc-900 px-3 py-1 rounded-full">{{ $threads->total() }} matches</span>
                </div>

                @forelse($threads as $thread)
                    <a href="{{ url('/forum/thread/' . $thread->slug) }}" class="block group bg-zinc-900/30 border border-zinc-800 p-6 rounded-2xl hover:border-amber-700/50 transition-all">
                        <h3 class="text-lg font-bold group-hover:text-amber-500 transition">{{ $thread->title }}</h3>
                        <p class="text-zinc-500 text-sm mt-2 line-clamp-2">
                            {{ Str::limit(strip_tags($thread->content), 180) }}
                        </p>
                        <div class="mt-4 flex items-center gap-4 text-[10px] uppercase font-bold text-zinc-600">
                            <span><i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}</span>
                            <span><i class="fa-regular fa-eye mr-1"></i> {{ $thread->view_count ?? 0 }} Views</span>
                        </div>
                    </a>
                @empty
                    <div class="text-zinc-600 py-10 italic">No discussions found matching your search.</div>
                @endforelse

                <div class="mt-6">
                    {{ $threads->appends(['query' => $query, 'stems_page' => $music->currentPage()])->links('layouts.partials.webapp.pagination') }}
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-compact-disc text-amber-500"></i> music
                    </h2>
                    <span class="text-xs text-zinc-500 bg-zinc-900 px-3 py-1 rounded-full">{{ $music->total() }} matches</span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($music as $song)
                        <div class="bg-zinc-900/50 border border-zinc-800 p-4 rounded-xl flex items-center gap-4 hover:bg-zinc-800/50 transition">
                            <div class="w-16 h-16 bg-zinc-800 rounded-lg flex-shrink-0 overflow-hidden">
                                @if($song->featured_image)
                                    <img src="{{ asset('storage/' . $song->featured_image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-zinc-700">
                                        <i class="fa-solid fa-music text-xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-sm leading-tight">{{ $song->title }}</h4>
                                <p class="text-xs text-amber-600 mt-1">{{ $song->artist_name }}</p>
                                <a href="{{ url('/music/' . $song->slug) }}" class="text-[10px] uppercase font-black text-white mt-2 inline-flex items-center gap-1 hover:underline">
                                    <i class="fa-solid fa-download"></i> NCS Version
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-zinc-600 py-10 italic text-sm">No music found.</div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $music->appends(['query' => $query, 'threads_page' => $threads->currentPage()])->links('layouts.partials.webapp.pagination') }}
                </div>
            </div>

        </div>
    </div>
</x-webapp-layout>







