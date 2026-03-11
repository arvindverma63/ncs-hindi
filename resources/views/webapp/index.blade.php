<x-webapp-layout>
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Category Header Cards remain the same --}}
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
        <article
            class="forum-card p-6 lg:p-8 mb-6 bg-[#0a0a0c] border border-zinc-900/50 hover:border-zinc-700 transition-all duration-300 rounded-3xl relative overflow-hidden group">
            {{-- Category Badge --}}
            <div class="absolute top-0 right-0">
                <div
                    class="bg-gradient-to-l from-amber-600/20 to-transparent px-6 py-2 border-b border-l border-zinc-800 rounded-bl-2xl">
                    <span class="text-[9px] font-black text-amber-500 uppercase tracking-[0.2em]">
                        {{ $post->category->name ?? 'Community' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div
                            class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 flex items-center justify-center font-black text-red-700 font-brand overflow-hidden shadow-inner">
                            @php
                                $authorAvatar = $post->author->profile_image ?? $post->author->avatar;
                                $defaultAvatar =
                                    'https://ui-avatars.com/api/?name=' .
                                    urlencode($post->author->name) .
                                    '&background=b45309&color=fff';
                            @endphp

                            {{-- Logic to prioritize Profile Image (Google/Custom) then Avatar --}}
                            <img src="{{ $authorAvatar ?? $defaultAvatar }}"
                                onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';"
                                referrerpolicy="no-referrer" class="w-full h-full object-cover"
                                alt="{{ $post->author->name }}">
                        </div>
                        @if ($post->is_verified)
                            <div
                                class="absolute -bottom-1 -right-1 w-5 h-5 bg-amber-600 rounded-lg flex items-center justify-center border-2 border-[#0a0a0c]">
                                <i class="fa-solid fa-check text-[10px] text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-white flex items-center gap-2">
                            {{ $post->author->name ?? 'Artist' }}
                        </h5>
                        <p
                            class="text-[10px] text-zinc-600 uppercase font-mono tracking-tighter flex items-center gap-2">
                            <i class="fa-regular fa-clock text-[9px]"></i>
                            {{ $post->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <button
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-zinc-600 hover:text-white hover:bg-zinc-800 transition">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
            </div>

            <div class="mb-4">
                <a href="{{ route('webapp.forum.show', $post->id) }}" class="inline-block">
                    <h2
                        class="text-2xl font-bold tracking-tight leading-tight group-hover:text-amber-500 transition-colors cursor-pointer font-brand uppercase italic text-white">
                        {{ $post->title }}
                    </h2>
                </a>
            </div>

            <div class="text-zinc-400 text-sm leading-relaxed mb-8 font-medium line-clamp-2 max-w-3xl">
                {!! Str::limit(strip_tags($post->content), 180) !!}
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-zinc-900/80 gap-6">
                <div class="flex items-center gap-10">
                    <button
                        class="js-like-btn flex items-center gap-2.5 text-zinc-500 hover:text-red-500 transition group/stat"
                        data-id="{{ $post->id }}" data-type="thread">
                        <div
                            class="w-8 h-8 rounded-full {{ $post->isLikedBy(auth()->id()) ? 'bg-red-500/10' : 'bg-zinc-900' }} flex items-center justify-center transition">
                            <i
                                class="fa-solid fa-heart text-xs {{ $post->isLikedBy(auth()->id()) ? 'text-red-500' : 'text-zinc-500' }}"></i>
                        </div>
                        <span class="js-like-count text-xs font-black tracking-widest">
                            {{ number_format($post->likes()->count()) }}
                        </span>
                    </button>

                    <button class="flex items-center gap-2.5 text-zinc-500 hover:text-amber-500 transition group/stat">
                        <div
                            class="w-8 h-8 rounded-full bg-zinc-900 flex items-center justify-center group-hover/stat:bg-amber-500/10 transition">
                            <i class="fa-solid fa-comment-dots text-xs group-hover/stat:scale-125 transition"></i>
                        </div>
                        <span class="text-xs font-black tracking-widest">{{ $post->replies_count ?? 0 }}</span>
                    </button>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('webapp.forum.show', $post->id) }}"
                        class="flex-1 sm:flex-none px-8 py-3 rounded-xl bg-zinc-900 border border-zinc-800 text-[10px] text-center font-black text-zinc-400 hover:text-white hover:bg-zinc-800 transition uppercase tracking-[0.2em]">
                        Open Thread
                    </a>
                </div>
            </div>
        </article>
    @endforeach

    {{-- Marketplace Snippets and Scripts remain the same --}}
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.js-like-btn', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const $icon = $btn.find('i');
                const $count = $btn.find('.js-like-count');
                const $circle = $btn.find('.w-8');

                $.post('{{ route('webapp.like.toggle') }}', {
                        _token: '{{ csrf_token() }}',
                        id: $btn.data('id'),
                        type: $btn.data('type')
                    })
                    .done(function(res) {
                        $count.text(res.count);
                        if (res.status === 'liked') {
                            $icon.addClass('text-red-500').removeClass('text-zinc-500');
                            $circle.addClass('bg-red-500/10').removeClass('bg-zinc-900');
                        } else {
                            $icon.addClass('text-zinc-500').removeClass('text-red-500');
                            $circle.addClass('bg-zinc-900').removeClass('bg-red-500/10');
                        }
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 401) alert('Please login to like this post.');
                    });
            });
        });
    </script>
</x-webapp-layout>
