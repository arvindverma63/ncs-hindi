<x-webapp-layout
    title="NCS Hindi | Premium Royalty-Free Hindi Music & Soundtracks"
    description="Welcome to NCS Hindi, the premier community & library of royalty-free Hindi music, non-copyright soundtracks, and creator-focused audio assets. Free from copyright strikes."
    keywords="ncs hindi, royalty free hindi music, non copyright bgm, background music for creators, copyright free hindi songs, download studio audio"
>
    @inject('settings', 'App\Services\SettingService')
    {{-- 1. Premium Category Navigation --}}
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <a href="{{ $settings->get('youtube_url', 'https://www.youtube.com') }}" target="_blank" rel="noopener noreferrer" class="group relative overflow-hidden bg-zinc-900/40 backdrop-blur-md border border-zinc-800 p-6 rounded-[2rem] text-center transition-all duration-500 hover:border-amber-500/50 hover:bg-zinc-900/60">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:rotate-3 transition duration-500">
                <i class="fa-brands fa-youtube text-2xl text-amber-500"></i>
            </div>
            <h4 class="text-xs font-black uppercase tracking-widest text-white">YouTube</h4>
            <p class="text-[9px] text-zinc-500 font-bold mt-1 uppercase tracking-tighter">NCS Hindi Channel</p>
        </a>

        <a href="{{ $settings->get('playstore_url', 'https://play.google.com/store/search?q=NCS%20Hindi%20Music&c=apps') }}" target="_blank" rel="noopener noreferrer" class="group relative overflow-hidden bg-zinc-900/40 backdrop-blur-md border border-zinc-800 p-6 rounded-[2rem] text-center transition-all duration-500 hover:border-red-500/50 hover:bg-zinc-900/60">
            <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-14 h-14 bg-red-600/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:-rotate-3 transition duration-500">
                <i class="fa-brands fa-android text-2xl text-red-600"></i>
            </div>
            <h4 class="text-xs font-black uppercase tracking-widest text-white">Android App</h4>
            <p class="text-[9px] text-zinc-500 font-bold mt-1 uppercase tracking-tighter">NCS Hindi Music</p>
        </a>

        <a href="{{ route('webapp.profile') }}" class="group relative overflow-hidden bg-zinc-900/40 backdrop-blur-md border border-zinc-800 p-6 rounded-[2rem] text-center transition-all duration-500 hover:border-amber-600/50 hover:bg-zinc-900/60">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-14 h-14 bg-amber-600/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:rotate-3 transition duration-500">
                <i class="fa-solid fa-user text-2xl text-amber-600"></i>
            </div>
            <h4 class="text-xs font-black uppercase tracking-widest text-white">Profile</h4>
            <p class="text-[9px] text-zinc-500 font-bold mt-1 uppercase tracking-tighter">Your account</p>
        </a>

        <a href="{{ route('webapp.faq') }}" class="group relative overflow-hidden bg-zinc-900/40 backdrop-blur-md border border-zinc-800 p-6 rounded-[2rem] text-center transition-all duration-500 hover:border-zinc-500/50 hover:bg-zinc-900/60">
            <div class="w-14 h-14 bg-zinc-800 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-500">
                <i class="fa-solid fa-shield-halved text-2xl text-zinc-400"></i>
            </div>
            <h4 class="text-xs font-black uppercase tracking-widest text-white">FAQ</h4>
            <p class="text-[9px] text-zinc-500 font-bold mt-1 uppercase tracking-tighter">Legal Guides</p>
        </a>
    </section>

    {{-- 2. Trending Music Section --}}
    <section class="mt-16">
        <div class="flex items-center justify-between mb-8 px-4">
            <div>
                <h3 class="font-brand text-3xl md:text-4xl font-black italic tracking-tighter uppercase text-white leading-none">
                    Trending <span class="text-amber-500">Releases</span>
                </h3>
                <p class="text-[10px] text-zinc-600 font-black tracking-[0.3em] uppercase mt-2">Most Downloaded Sounds this Week</p>
            </div>
            <a href="{{ route('webapp.trending') }}" class="px-6 py-2.5 text-[10px] font-black tracking-widest uppercase bg-zinc-900 border border-zinc-800 hover:border-amber-500/50 hover:bg-zinc-800 text-zinc-300 hover:text-white rounded-xl transition duration-300">
                View All
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($trendingSongs as $song)
                <div class="group relative bg-zinc-900/40 backdrop-blur-md border border-zinc-800/80 rounded-[2rem] p-5 transition-all duration-500 hover:border-amber-500/40 hover:bg-zinc-900/60" data-like-card>
                    <div class="flex items-center gap-4">
                        {{-- Artwork --}}
                        <div class="relative w-20 h-20 rounded-2xl overflow-hidden bg-zinc-800 shrink-0">
                            @if ($song->featured_image)
                                <img src="{{ $song->featured_image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $song->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-music text-xl text-zinc-600"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Metadata --}}
                        <div class="min-w-0 flex-1">
                            <span class="px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-500 text-[8px] font-black uppercase tracking-wider border border-amber-500/20">
                                {{ $song->category->name ?? 'Release' }}
                            </span>
                            <h4 class="font-brand text-base font-bold text-white uppercase tracking-tighter truncate mt-1.5 group-hover:text-amber-500 transition-colors">
                                <a href="{{ route('webapp.music.show', $song->slug) }}">
                                    {{ $song->title }}
                                </a>
                            </h4>
                            <p class="text-xs text-zinc-500 font-bold truncate mt-0.5">
                                {{ $song->artist_name ?: 'NCS Artist' }}
                            </p>
                        </div>
                    </div>

                    {{-- Stats & Quick Links --}}
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-zinc-800/50">
                        <div class="flex items-center gap-3.5">
                            <span class="flex items-center gap-1 text-[10px] text-zinc-500 font-bold">
                                <i class="fa-solid fa-heart text-red-500/80"></i> {{ number_format($song->like_count) }}
                            </span>
                            <span class="flex items-center gap-1 text-[10px] text-zinc-500 font-bold">
                                <i class="fa-solid fa-download text-amber-500/80"></i> {{ number_format($song->download_count) }}
                            </span>
                        </div>
                        <a href="{{ route('webapp.music.show', $song->slug) }}" class="px-4 py-2 bg-white hover:bg-amber-500 text-black hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition duration-300">
                            Listen
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-10 text-center border border-dashed border-zinc-800 rounded-[2rem]">
                    <p class="text-xs text-zinc-500 uppercase tracking-widest font-black">No trending sounds found</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- 3. Feed Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 mt-16 px-4 gap-4">
        <div>
            <h3 class="font-brand text-3xl md:text-4xl font-black italic tracking-tighter uppercase text-white leading-none">
                Global <span class="text-amber-500">Activity</span>
            </h3>
            <p class="text-[10px] text-zinc-600 font-black tracking-[0.3em] uppercase mt-2">NCS Hindi Community Feed</p>
        </div>
        <div class="flex bg-zinc-900/50 p-1 rounded-xl border border-zinc-800 self-start md:self-auto">
            <button class="px-6 py-2 text-[10px] font-black tracking-widest uppercase bg-amber-600 text-white rounded-lg shadow-lg shadow-amber-900/20">Latest</button>
            <a href="{{ route('webapp.trending') }}" class="px-6 py-2 text-[10px] font-black tracking-widest uppercase text-zinc-500 hover:text-white transition">Trending</a>
        </div>
    </div>

    {{-- 3. The Feed --}}
    <div class="space-y-8">
        @foreach ($posts as $post)
            <article class="group relative bg-zinc-900/20 border border-zinc-800/50 rounded-[2.5rem] p-6 lg:p-10 transition-all duration-500 hover:bg-zinc-900/40 hover:border-zinc-700">

                {{-- Dynamic Category Badge --}}
                <div class="absolute top-6 right-8">
                    <span class="px-4 py-1.5 rounded-full bg-zinc-900 border border-zinc-800 text-[8px] font-black text-amber-500 uppercase tracking-[0.2em] group-hover:border-amber-500/30 transition-colors">
                        {{ $post->category->name ?? 'Community' }}
                    </span>
                </div>

                <div class="flex flex-col gap-6">
                    {{-- User Meta --}}
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-zinc-800 group-hover:border-amber-500/50 transition-all duration-500">
                                @php
                                    $authorAvatar = $post->author->profile_image ?? $post->author->avatar;
                                    $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode($post->author->name).'&background=18181b&color=f59e0b&bold=true';
                                @endphp
                                <img src="{{ $authorAvatar ?? $defaultAvatar }}" class="w-full h-full object-cover" alt="{{ $post->author->name }}">
                            </div>
                            @if ($post->is_verified)
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-600 rounded-lg flex items-center justify-center border-[3px] border-[#09090b]">
                                    <i class="fa-solid fa-check text-[9px] text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h5 class="text-white font-bold text-base group-hover:text-amber-500 transition-colors">{{ $post->author->name }}</h5>
                            <p class="text-[10px] text-zinc-600 font-bold uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-regular fa-clock text-[9px]"></i>
                                {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    {{-- Post Body --}}
                    <div class="max-w-4xl">
                        <a href="{{ route('webapp.forum.show', $post->slug) }}">
                            <h2 class="font-brand text-2xl md:text-3xl font-black italic uppercase text-white leading-tight mb-4 group-hover:translate-x-1 transition-transform duration-300">
                                {{ $post->title }}
                            </h2>
                        </a>
                        <div class="text-zinc-400 text-sm md:text-base leading-relaxed font-medium line-clamp-3">
                            {!! Str::limit(strip_tags($post->content), 200) !!}
                        </div>
                    </div>

                    {{-- Footer Actions & Restored Like Logic --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between pt-8 border-t border-zinc-800/50 gap-6">
                        <div class="flex items-center gap-8">
                            {{-- LIKE BUTTON --}}
                            <button class="js-like-btn flex items-center gap-3 group/stat outline-none"
                                    data-id="{{ $post->id }}"
                                    data-type="thread">
                                <div class="js-like-circle w-10 h-10 rounded-xl {{ $post->isLikedBy(auth()->id()) ? 'bg-red-500/20' : 'bg-zinc-800/50' }} flex items-center justify-center transition-all duration-300 group-hover/stat:scale-110">
                                    <i class="js-like-icon fa-solid fa-heart {{ $post->isLikedBy(auth()->id()) ? 'text-red-500' : 'text-zinc-500' }} text-sm"></i>
                                </div>
                                <span class="js-like-count text-xs font-black text-zinc-400 tracking-tighter">
                                    {{ number_format($post->likes()->count()) }}
                                </span>
                            </button>

                            {{-- COMMENT COUNT --}}
                            <div class="flex items-center gap-3 group/stat">
                                <div class="w-10 h-10 rounded-xl bg-zinc-800/50 flex items-center justify-center transition-all duration-300">
                                    <i class="fa-solid fa-comment-dots text-zinc-500 text-sm group-hover/stat:text-amber-500"></i>
                                </div>
                                <span class="text-xs font-black text-zinc-400 tracking-tighter">{{ $post->replies_count ?? 0 }}</span>
                            </div>
                        </div>

                        <a href="{{ route('webapp.forum.show', $post->slug) }}"
                           class="w-full sm:w-auto px-10 py-3.5 rounded-xl bg-white text-black text-[10px] font-black uppercase tracking-[0.2em] hover:bg-amber-500 hover:text-white transition-all duration-300 text-center shadow-xl shadow-white/5">
                            Open Discussion
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-12 px-4">
        {{ $posts->links('layouts.partials.webapp.pagination', ['paginationLabel' => 'posts']) }}
    </div>

    {{-- Script Section --}}
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.js-like-btn', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const $icon = $btn.find('.js-like-icon');
                const $count = $btn.find('.js-like-count');
                const $circle = $btn.find('.js-like-circle');

                $.post('{{ route('webapp.like.toggle') }}', {
                        _token: '{{ csrf_token() }}',
                        id: $btn.data('id'),
                        type: $btn.data('type')
                    })
                    .done(function(res) {
                        $count.text(res.count);
                        if (res.status === 'liked') {
                            $icon.addClass('text-red-500').removeClass('text-zinc-500');
                            $circle.addClass('bg-red-500/20').removeClass('bg-zinc-800/50');
                            // Small animation pop
                            $circle.addClass('scale-125');
                            setTimeout(() => $circle.removeClass('scale-125'), 200);
                        } else {
                            $icon.addClass('text-zinc-500').removeClass('text-red-500');
                            $circle.addClass('bg-zinc-800/50').removeClass('bg-red-500/20');
                        }
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 401) toastr.warning('Please login to participate.');
                    });
            });
        });
    </script>
    @endpush
</x-webapp-layout>







