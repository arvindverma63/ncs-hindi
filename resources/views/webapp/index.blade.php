<x-webapp-layout>
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('webapp.streams') }}" class="forum-card p-5 text-center group cursor-pointer border-dashed">
            <div
                class="w-12 h-12 bg-amber-500/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition duration-300">
                <i class="fa-solid fa-microphone-lines text-xl text-amber-500"></i>
            </div>
            <h4 class="text-xs font-bold">Vocal Stems</h4>
            <p class="text-[9px] text-zinc-600">Free to Download</p>
        </a>

        <a href="{{ route('webapp.trending') }}" class="forum-card p-5 text-center group cursor-pointer border-dashed">
            <div
                class="w-12 h-12 bg-red-600/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition duration-300">
                <i class="fa-solid fa-sliders text-xl text-red-600"></i>
            </div>
            <h4 class="text-xs font-bold">Mixing Tips</h4>
            <p class="text-[9px] text-zinc-600">Active Discussions</p>
        </a>

        <a href="{{ route('webapp.streams') }}" class="forum-card p-5 text-center group cursor-pointer border-dashed">
            <div
                class="w-12 h-12 bg-amber-600/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition duration-300">
                <i class="fa-solid fa-guitar text-xl text-amber-600"></i>
            </div>
            <h4 class="text-xs font-bold">Acoustic Samples</h4>
            <p class="text-[9px] text-zinc-600">Library Access</p>
        </a>

        <div class="forum-card p-5 text-center group cursor-pointer border-dashed">
            <div
                class="w-12 h-12 bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition duration-300">
                <i class="fa-solid fa-shield-halved text-xl text-zinc-500"></i>
            </div>
            <h4 class="text-xs font-bold">Copyright FAQ</h4>
            <p class="text-[9px] text-zinc-600">Usage Guides</p>
        </div>
    </section>

    <div class="flex items-center justify-between mb-8 mt-12 px-2">
        <div>
            <h3 class="font-brand text-2xl font-bold italic tracking-tight uppercase text-white">Recent Activity</h3>
            <p class="text-[10px] text-zinc-500 font-bold tracking-widest uppercase mt-1">NCS Hindi Global Feed</p>
        </div>
        <div class="flex gap-4">
            <button class="text-xs font-bold text-amber-500 border-b-2 border-amber-600 pb-1">LATEST</button>
            <a href="{{ route('webapp.trending') }}"
                class="text-xs font-bold text-zinc-600 hover:text-white transition">TRENDING</a>
        </div>
    </div>

    @foreach ($posts as $post)
        <article class="forum-card p-6 lg:p-8 mb-6 bg-[#0a0a0c]">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-zinc-900 border border-zinc-800 flex items-center justify-center font-black text-red-700 font-brand overflow-hidden">
                        @if ($post->author && $post->author->avatar)
                            <img src="{{ asset('uploads/avatars/' . $post->author->avatar) }}"
                                class="w-full h-full object-cover">
                        @else
                            NC
                        @endif
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-white">
                            {{ $post->author->name ?? 'Artist' }}
                            @if ($post->is_verified)
                                <span
                                    class="bg-amber-600/20 text-amber-500 text-[8px] px-1.5 py-0.5 rounded ml-2 font-black uppercase tracking-tighter border border-amber-600/30">Verified</span>
                            @endif
                        </h5>
                        <p class="text-[10px] text-zinc-600 uppercase font-mono tracking-tighter">
                            {{ $post->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <button class="text-zinc-600 hover:text-white transition">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
            </div>

            <a href="{{ route('webapp.forum.show', $post->id) }}">
                <h2
                    class="text-2xl font-bold mb-4 tracking-tight leading-tight hover:text-amber-600 transition cursor-pointer font-brand uppercase italic text-white">
                    {{ $post->title }}
                </h2>
            </a>

            <div class="text-zinc-400 text-sm leading-relaxed mb-6 font-medium line-clamp-2">
                {!! Str::limit(strip_tags($post->content), 180) !!}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="h-56 rounded-3xl overflow-hidden border border-zinc-800 shadow-2xl relative group">
                    <img src="https://images.unsplash.com/photo-1493225255756-d9584f8606e9?auto=format&fit=crop&w=800&q=80"
                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                        <span
                            class="text-[10px] font-black text-white uppercase tracking-widest">{{ $post->category->name ?? 'Vault' }}</span>
                    </div>
                </div>

                <div
                    class="h-56 rounded-3xl overflow-hidden border border-zinc-800 bg-black flex flex-col items-center justify-center space-y-3 group cursor-pointer hover:bg-zinc-900/50 transition">
                    <div
                        class="w-16 h-16 rounded-2xl bg-zinc-900 flex items-center justify-center group-hover:rotate-12 transition duration-500">
                        <i class="fa-solid fa-file-zipper text-3xl text-red-700"></i>
                    </div>
                    <div class="text-center">
                        <span class="text-xs font-bold text-zinc-300 block">Stems_Package.zip</span>
                        <span class="text-[10px] text-zinc-600 uppercase font-bold tracking-widest">
                            @if ($post->stems_count)
                                {{ $post->stems_count }} Files
                            @else
                                Get Stems
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-zinc-900 gap-6">
                <div class="flex items-center gap-8">
                    <button class="flex items-center gap-2 text-zinc-500 hover:text-red-600 transition group">
                        <i class="fa-solid fa-heart group-hover:scale-125 transition"></i>
                        <span class="text-xs font-bold">{{ number_format($post->likes_count ?? 0) }}</span>
                    </button>
                    <button class="flex items-center gap-2 text-zinc-500 hover:text-amber-500 transition group">
                        <i class="fa-solid fa-comment-dots group-hover:scale-125 transition"></i>
                        <span class="text-xs font-bold">{{ $post->replies_count ?? 0 }}</span>
                    </button>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('webapp.forum.show', $post->id) }}"
                        class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl border border-zinc-800 text-[10px] text-center font-bold text-zinc-400 hover:text-white hover:bg-zinc-900 transition uppercase tracking-widest">
                        Details
                    </a>
                    <button
                        class="flex-1 sm:flex-none btn-vault px-8 py-2.5 text-[10px] uppercase font-black flex items-center justify-center gap-2">
                        <i class="fa-solid fa-download"></i> Download
                    </button>
                </div>
            </div>
        </article>
    @endforeach

    <section class="mt-12">
        <h3 class="font-brand text-xl font-bold mb-6 text-zinc-500 uppercase tracking-widest px-2">Marketplace Snippets
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('webapp.streams') }}"
                class="forum-card p-4 flex items-center gap-4 hover:translate-x-1 transition duration-300 bg-[#0a0a0c]">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-600 to-red-900 flex-shrink-0"></div>
                <div>
                    <h5 class="text-sm font-bold text-white">Premium Basslines</h5>
                    <p class="text-[10px] text-zinc-500">Curated Stems</p>
                </div>
                <i class="fa-solid fa-chevron-right ml-auto text-zinc-700"></i>
            </a>
        </div>
    </section>
</x-webapp-layout>
