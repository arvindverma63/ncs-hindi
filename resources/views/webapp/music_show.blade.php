<x-webapp-layout
    :title="$music->title . ' | ' . ($music->artist_name ?: 'NCS Artist') . ' - NCS Hindi'"
    :description="\Illuminate\Support\Str::limit(strip_tags($music->description ?: 'Download ' . $music->title . ' by ' . ($music->artist_name ?: 'NCS Artist') . ' on NCS Hindi. Royalty-free, non-copyright Hindi music for creators.'), 155)"
    :keywords="$music->tags_keywords ?: 'ncs hindi, ' . $music->title . ', ' . $music->artist_name . ', royalty free'"
    :og-image="$music->featured_image ?: ''"
    og-type="music.song"
>
    @push('heads')
    {{-- Quill Editor CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <style>
        /* ---- Quill Dark Theme ---- */
        .ql-toolbar.ql-snow {
            background: #1c1c1f;
            border: 1px solid #3f3f46 !important;
            border-bottom: none !important;
            border-radius: 14px 14px 0 0;
            padding: 10px;
        }
        .ql-container.ql-snow {
            background: #09090b;
            border: 1px solid #3f3f46 !important;
            border-radius: 0 0 14px 14px;
            min-height: 130px;
            font-size: 14px;
            color: #e4e4e7;
        }
        .ql-editor { min-height: 110px; }
        .ql-editor.ql-blank::before { color: #52525b; font-style: normal; }
        .ql-toolbar .ql-stroke { stroke: #a1a1aa; }
        .ql-toolbar .ql-fill { fill: #a1a1aa; }
        .ql-toolbar .ql-picker { color: #a1a1aa; }
        .ql-toolbar button:hover .ql-stroke,
        .ql-toolbar button.ql-active .ql-stroke { stroke: #f59e0b; }
        .ql-toolbar button:hover .ql-fill,
        .ql-toolbar button.ql-active .ql-fill { fill: #f59e0b; }
        .ql-toolbar .ql-picker-label:hover,
        .ql-toolbar .ql-picker-item:hover { color: #f59e0b; }
        .ql-picker-options {
            background: #18181b !important;
            border: 1px solid #3f3f46 !important;
            border-radius: 8px !important;
        }
        .ql-snow .ql-tooltip {
            background: #18181b;
            border: 1px solid #3f3f46;
            color: #e4e4e7;
            border-radius: 8px;
        }
        .ql-snow .ql-tooltip input { background: #09090b; border: 1px solid #3f3f46; color: #e4e4e7; border-radius: 6px; }
        .ql-editor img { max-width: 100%; border-radius: 10px; margin: 8px 0; }
        /* ---- Comment Cards ---- */
        .comment-card {
            border-left: 3px solid #3f3f46;
            transition: border-color 0.2s;
        }
        .comment-card:hover { border-left-color: #f59e0b; }
        .reply-card { border-left: 2px solid #27272a; margin-left: 20px; }
        /* ---- Reaction Bar ---- */
        .reaction-bar {
            display: none;
            position: absolute;
            bottom: calc(100% + 8px);
            left: 0;
            background: #18181b;
            border: 1px solid #3f3f46;
            border-radius: 50px;
            padding: 6px 10px;
            gap: 4px;
            z-index: 50;
            white-space: nowrap;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
        }
        .reaction-wrapper:hover .reaction-bar,
        .reaction-wrapper:focus-within .reaction-bar { display: flex; }
        .reaction-emoji {
            cursor: pointer;
            font-size: 1.35rem;
            line-height: 1;
            padding: 4px 5px;
            border-radius: 50%;
            transition: transform 0.15s;
        }
        .reaction-emoji:hover { transform: scale(1.4); }
        .reaction-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 999px;
            background: #27272a;
            border: 1px solid #3f3f46;
            color: #a1a1aa;
            cursor: pointer;
            transition: all 0.15s;
        }
        .reaction-count-badge.active { background: #451a03; border-color: #f59e0b; color: #f59e0b; }
        /* ---- Upload progress overlay ---- */
        #img-uploading-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(4px);
            align-items: center; justify-content: center;
        }
        #img-uploading-overlay.show { display: flex; }
        /* ---- Animated comment insertion ---- */
        @keyframes slideInComment {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .comment-animate { animation: slideInComment 0.3s ease forwards; }
    </style>
    @endpush

    @php
        $stemLanguages = collect(explode(',', (string) $music->language))
            ->map(fn($language) => trim($language))
            ->filter()
            ->values();
        $reactionEmojis = [
            'like'  => ['emoji' => '👍', 'label' => 'Like'],
            'love'  => ['emoji' => '❤️',  'label' => 'Love'],
            'haha'  => ['emoji' => '😂', 'label' => 'Haha'],
            'wow'   => ['emoji' => '😮', 'label' => 'Wow'],
            'sad'   => ['emoji' => '😢', 'label' => 'Sad'],
            'angry' => ['emoji' => '😡', 'label' => 'Angry'],
        ];
    @endphp

    {{-- Image Uploading Overlay --}}
    <div id="img-uploading-overlay">
        <div class="text-center text-white">
            <svg class="animate-spin w-10 h-10 mx-auto mb-3 text-amber-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <p class="text-sm font-bold">Uploading image…</p>
        </div>
    </div>

    {{-- 1. Hero Player & Dynamic Background --}}
    <section class="relative mb-10 rounded-3xl md:rounded-[40px] overflow-hidden border border-zinc-800 bg-zinc-900/40 group">
        <div
            class="absolute inset-0 bg-gradient-to-br from-amber-600/20 via-transparent to-black opacity-60 transition-opacity group-hover:opacity-80">
        </div>

        <div class="relative p-4 sm:p-10 lg:p-16 flex flex-col md:flex-row items-center md:items-end gap-6 md:gap-8 lg:gap-14">
            {{-- Artwork with Hover Effect --}}
            <div class="relative w-40 h-40 sm:w-56 sm:h-56 lg:w-72 lg:h-72 flex-shrink-0">
                <div class="absolute -inset-4 bg-amber-500/20 blur-3xl rounded-full opacity-50"></div>
                @if ($music->featured_image)
                    <img src="{{ $music->featured_image }}"
                        class="relative w-full h-full object-cover rounded-2xl md:rounded-3xl border border-zinc-700 shadow-2xl transition-transform duration-500 hover:scale-105"
                        alt="{{ $music->title }}">
                @else
                    <div
                        class="relative w-full h-full bg-zinc-800 rounded-2xl md:rounded-3xl flex items-center justify-center border border-zinc-700">
                        <i class="fa-solid fa-compact-disc text-5xl md:text-6xl text-zinc-600 animate-spin-slow"></i>
                    </div>
                @endif
            </div>

            {{-- Metadata Content --}}
            <div class="flex-1 text-center md:text-left z-10 w-full min-w-0">
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-2 mb-3">
                    <span
                        class="px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/20">
                        {{ $music->category->name ?? 'Music Release' }}
                    </span>
                    @if ($stemLanguages->isNotEmpty())
                        <span
                            class="px-2.5 py-0.5 rounded-full bg-zinc-800 text-zinc-400 text-[9px] font-bold uppercase border border-zinc-700">
                            <i class="fa-solid fa-earth-asia mr-1"></i> {{ $stemLanguages->first() }}
                            @if ($stemLanguages->count() > 1)
                                +{{ $stemLanguages->count() - 1 }}
                            @endif
                        </span>
                    @endif
                </div>

                <h1
                    class="text-2xl sm:text-4xl lg:text-6xl font-brand font-black text-white uppercase tracking-tighter mb-2 leading-none truncate">
                    {{ $music->title }}
                </h1>

                <p class="text-xl lg:text-2xl text-zinc-400 font-bold mb-8">
                    {{ $music->artist_name ?: 'NCS Artist' }}
                    @if ($music->album_movie_name)
                        <span class="text-zinc-600 mx-2">•</span>
                        <span class="text-amber-500/80">{{ $music->album_movie_name }}</span>
                    @endif
                </p>

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                    {{-- Primary Action: Download --}}
                    @if ($music->mega_link)
                        <a href="{{ route('webapp.music.download', $music->id) }}" target="_blank" rel="noopener noreferrer"
                            data-notification-gate data-music-action="download" data-music-title="{{ $music->title }}"
                            data-action-url="{{ route('webapp.music.download', $music->id) }}" data-action-label="Continue to download"
                            data-music-id="{{ $music->id }}" data-is-mega="true"
                            class="w-full sm:w-auto bg-white text-black px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-amber-500 hover:text-white transition-all duration-300 shadow-xl shadow-white/5">
                            <i class="fa-solid fa-cloud-arrow-down text-lg"></i> NCS Version
                        </a>
                    @else
                        <a href="{{ route('webapp.music.download', $music->id) }}" target="_blank"
                            rel="noopener noreferrer" data-notification-gate data-music-action="download"
                            data-music-title="{{ $music->title }}"
                            data-action-url="{{ route('webapp.music.download', $music->id) }}"
                            data-action-label="Continue to download"
                            data-music-id="{{ $music->id }}"
                            class="w-full sm:w-auto btn-vault px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:scale-105 transition-all shadow-lg shadow-amber-600/20">
                            <i class="fa-solid fa-download text-lg"></i> NCS Version
                        </a>
                    @endif

                    {{-- Secondary Actions --}}
                    <div class="flex gap-3 w-full sm:w-auto justify-center" data-like-card>
                        @if($music->youtube_link)
                            <a href="{{ $music->youtube_link }}" target="_blank" rel="noopener noreferrer"
                                class="flex-1 sm:flex-none p-4 bg-zinc-800/80 backdrop-blur-md border border-zinc-700 rounded-2xl text-zinc-300 hover:text-red-500 hover:border-red-500/50 transition-all group">
                                <i class="fa-brands fa-youtube group-hover:scale-125 transition text-lg"></i>
                            </a>
                        @endif
                        <button type="button" data-music-like-btn
                            data-like-url="{{ route('webapp.music.like', $music->id) }}"
                            data-liked="{{ auth()->check() && $music->isLikedBy(auth()->id()) ? 1 : 0 }}"
                            aria-pressed="{{ auth()->check() && $music->isLikedBy(auth()->id()) ? 'true' : 'false' }}"
                            class="flex-1 sm:flex-none p-4 bg-zinc-800/80 backdrop-blur-md border border-zinc-700 rounded-2xl text-zinc-300 hover:text-red-500 hover:border-red-500/50 transition-all group {{ auth()->check() && $music->isLikedBy(auth()->id()) ? 'text-red-400' : '' }}">
                            <i data-music-like-icon
                                class="fa-heart group-hover:scale-125 transition {{ auth()->check() && $music->isLikedBy(auth()->id()) ? 'fa-solid' : 'fa-regular' }}"></i>
                        </button>
                        <div
                            class="flex min-w-[92px] items-center justify-center rounded-2xl bg-zinc-800/60 border border-zinc-700 px-4 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                            <span data-like-count>{{ number_format($music->like_count) }}</span>
                            <span class="ml-1">likes</span>
                        </div>
                        <button type="button" data-music-share-btn data-share-title="{{ $music->title }}"
                            data-share-url="{{ url()->current() }}"
                            class="flex-1 sm:flex-none p-4 bg-zinc-800/80 backdrop-blur-md border border-zinc-700 rounded-2xl text-zinc-300 hover:text-amber-500 hover:border-amber-500/50 transition-all group">
                            <i class="fa-solid fa-share-nodes group-hover:rotate-12 transition"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Information Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

        {{-- Technical Specifications Card --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-zinc-900/40 border border-zinc-800 rounded-[32px] p-4 sm:p-10 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3
                        class="text-white font-brand font-bold text-xl uppercase tracking-tight flex items-center gap-3">
                        <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                        Music Info
                    </h3>
                </div>

                @if ($music->description)
                    <div class="mt-10 pt-8 border-t border-zinc-800/50">
                        <h4
                            class="text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-align-left text-amber-500"></i> Description
                        </h4>
                        <p class="text-zinc-400 text-base leading-relaxed font-medium">
                            {{ $music->description }}
                        </p>
                    </div>
                @endif

                @if ($stemLanguages->isNotEmpty())
                    <div class="mt-6 pt-6 border-t border-zinc-800/50">
                        <h4
                            class="text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-language text-amber-500"></i> Languages
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($stemLanguages as $language)
                                <span
                                    class="px-3 py-2 rounded-xl bg-white/5 border border-white/5 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">
                                    {{ $language }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Licensing & Usage Card --}}
            <div
                class="bg-gradient-to-br from-zinc-900 to-black border border-zinc-800 rounded-[32px] p-5 md:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fa-solid fa-shield-halved text-7xl text-white"></i>
                </div>
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <div
                        class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center border border-amber-500/20 shrink-0">
                        <i class="fa-solid fa-certificate text-amber-500 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-xl font-bold uppercase tracking-tight text-center md:text-left">
                            Music Usage License</h3>
                        <div class="text-zinc-500 text-sm mt-3 leading-relaxed text-center md:text-left space-y-3">
                            @php
                                $settings = app(\App\Services\SettingService::class);
                                $globalLicense = $settings->get('global_license_text', 'This music is safe for use in YouTube, Twitch, Shorts, Reels, and other social media content. You may use this track in monetized videos as long as you give clear credit in the description. Include the track title and artist name, and do not claim the music as your own original composition.');
                            @endphp
                            <p>{!! nl2br(e($music->license_text ?: $globalLicense)) !!}</p>
                        </div>
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-4">
                            <span class="flex items-center gap-2 text-[10px] font-bold text-green-500 uppercase">
                                <i class="fa-solid fa-check-circle"></i> Monetization Active
                            </span>
                            <span class="flex items-center gap-2 text-[10px] font-bold text-green-500 uppercase">
                                <i class="fa-solid fa-check-circle"></i> No Copyright Strike
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Stats & Discovery --}}
        <div class="space-y-6">
            {{-- Community Performance Card --}}
            <div class="bg-zinc-900/40 border border-zinc-800 rounded-[32px] p-8 text-center backdrop-blur-sm">
                <h4 class="text-[10px] text-zinc-600 font-black uppercase tracking-widest mb-6">Music Stats</h4>
                <div class="grid grid-cols-3 gap-4 relative">
                    <div class="absolute inset-y-4 left-1/3 w-[1px] bg-zinc-800"></div>
                    <div class="absolute inset-y-4 left-2/3 w-[1px] bg-zinc-800"></div>
                    <div>
                        <p class="text-3xl font-black text-white">{{ number_format($music->download_count) }}</p>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-tighter mt-2">Downloads</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-white">{{ number_format($music->view_count) }}</p>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-tighter mt-2">Music Views</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-white">{{ number_format($music->like_count) }}</p>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-tighter mt-2">Likes</p>
                    </div>
                </div>
            </div>

            {{-- Interactive Keywords --}}
            @if ($music->tags_keywords)
                <div class="bg-zinc-900/40 border border-zinc-800 rounded-[32px] p-8 backdrop-blur-sm">
                    <h4
                        class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-tags text-amber-500"></i> Metadata Tags
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach (explode(',', $music->tags_keywords) as $tag)
                            <a href="#"
                                class="px-4 py-2 bg-black rounded-xl text-[11px] font-bold text-zinc-400 border border-zinc-800 hover:border-amber-500/50 hover:text-white transition-all">
                                #{{ trim($tag) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Share Link Card --}}
            <div class="p-1 rounded-[32px] bg-gradient-to-r from-amber-500/20 to-transparent">
                <div class="bg-zinc-900 border border-zinc-800 rounded-[30px] p-6">
                    <p class="text-white font-bold text-sm mb-4">Share this Music</p>
                    <button type="button" data-music-share-btn data-share-title="{{ $music->title }}"
                        data-share-url="{{ url()->current() }}"
                        class="w-full bg-black/50 border border-zinc-800 rounded-xl p-3 text-left text-zinc-500 text-xs hover:border-amber-500/40 hover:text-zinc-300 transition-colors">
                        Open share options
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- 3. COMMENTS SECTION --}}
    {{-- ===================================================== --}}
    <section id="comments-section" class="mb-16">
        <div class="flex items-center gap-4 mb-8">
            <span class="w-2 h-10 bg-amber-500 rounded-full shrink-0"></span>
            <h2 class="text-white font-brand font-black text-2xl uppercase tracking-tight">
                Community Comments
            </h2>
            <span class="ml-2 px-3 py-1 bg-zinc-800 border border-zinc-700 rounded-full text-zinc-400 text-xs font-bold">
                {{ $comments->count() + $comments->sum(fn($c) => $c->replies->count()) }}
            </span>
        </div>

        {{-- New Comment Form --}}
        <div class="bg-zinc-900/60 border border-zinc-800 rounded-[28px] p-6 md:p-8 mb-8 backdrop-blur-sm">
            <div class="flex items-center gap-3 mb-5">
                @auth
                    @php
                        $ava = auth()->user()->profile_image && strlen(auth()->user()->profile_image) > 20
                            ? auth()->user()->profile_image
                            : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=18181b&color=f59e0b&bold=true&size=64';
                    @endphp
                    <img src="{{ $ava }}" alt="You" class="w-10 h-10 rounded-xl object-cover border border-zinc-700 shrink-0">
                    <span class="text-sm font-bold text-zinc-300">{{ auth()->user()->name }}</span>
                @else
                    <div class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user text-zinc-500"></i>
                    </div>
                    <span class="text-sm font-bold text-zinc-500">Commenting as guest</span>
                @endauth
            </div>

            <form id="main-comment-form" data-music-id="{{ $music->id }}">
                @csrf
                @guest
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                    <input type="text" name="name" id="comment-guest-name" required
                        placeholder="Your name *"
                        class="bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500 transition-colors">
                    <input type="email" name="email" id="comment-guest-email" required
                        placeholder="Your email * (won't be shown)"
                        class="bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500 transition-colors">
                </div>
                @endguest

                {{-- Quill Editor Mount Point --}}
                <div id="main-quill-editor" class="mb-4"></div>
                <input type="hidden" id="main-quill-content" name="comment">

                <div class="flex items-center justify-between flex-wrap gap-3 mt-4">
                    <p class="text-xs text-zinc-600">
                        <i class="fa-solid fa-image mr-1 text-amber-500/60"></i> Images supported — drag & drop or use toolbar
                    </p>
                    <button type="submit" id="main-comment-submit"
                        class="px-7 py-2.5 bg-amber-500 hover:bg-amber-400 text-black font-black text-xs uppercase rounded-xl transition-all flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Post Comment
                    </button>
                </div>
            </form>
        </div>

        {{-- Comments List --}}
        <div id="comments-list">
            @forelse ($comments as $comment)
                @include('webapp.partials.music_comment', [
                    'comment'       => $comment,
                    'userReactions' => $userReactions,
                    'reactionEmojis'=> $reactionEmojis,
                    'musicId'       => $music->id,
                ])
            @empty
                <div id="no-comments-placeholder" class="text-center py-16 text-zinc-600">
                    <i class="fa-regular fa-comments text-5xl mb-4 block opacity-30"></i>
                    <p class="font-bold text-base">No comments yet. Be the first!</p>
                </div>
            @endforelse
        </div>
    </section>

    @push('scripts')
    {{-- Quill JS --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
    (function () {
        const MUSIC_ID    = '{{ $music->id }}';
        const CSRF_TOKEN  = '{{ csrf_token() }}';
        const UPLOAD_URL  = '{{ route("webapp.music.comments.upload-image") }}';
        const COMMENT_URL = '{{ route("webapp.music.comments.store", $music->id) }}';
        const REACT_BASE  = '/music/comments/'; // /{id}/react appended dynamically

        // ============================================================
        // HELPERS
        // ============================================================
        function jsonFetch(url, data) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            }).then(r => r.json());
        }

        function showAlert(msg, type = 'error') {
            const el = document.createElement('div');
            el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl text-sm font-bold shadow-lg ${type === 'success' ? 'bg-green-700 text-white' : 'bg-red-700 text-white'}`;
            el.textContent = msg;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 3500);
        }

        // ============================================================
        // IMAGE UPLOAD HANDLER (shared by all Quill instances)
        // ============================================================
        function makeImageHandler(quillInstance) {
            return function () {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.click();
                input.onchange = async () => {
                    const file = input.files[0];
                    if (!file) return;
                    document.getElementById('img-uploading-overlay').classList.add('show');
                    const fd = new FormData();
                    fd.append('file', file);
                    fd.append('_token', CSRF_TOKEN);
                    try {
                        const resp = await fetch(UPLOAD_URL, { method: 'POST', body: fd });
                        const json = await resp.json();
                        if (json.location) {
                            const range = quillInstance.getSelection(true);
                            quillInstance.insertEmbed(range.index, 'image', json.location);
                            quillInstance.setSelection(range.index + 1);
                        } else {
                            showAlert('Image upload failed. Try again.');
                        }
                    } catch (e) {
                        showAlert('Image upload failed. Try again.');
                    } finally {
                        document.getElementById('img-uploading-overlay').classList.remove('show');
                    }
                };
            };
        }

        // ============================================================
        // INIT QUILL
        // ============================================================
        function initQuill(containerId, placeholder) {
            const q = new Quill('#' + containerId, {
                theme: 'snow',
                placeholder: placeholder || 'Write something…',
                modules: {
                    toolbar: {
                        container: [
                            [{ header: [2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ color: [] }, { background: [] }],
                            ['blockquote', 'code-block'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['link', 'image'],
                            ['clean'],
                        ],
                        handlers: { image: makeImageHandler.bind(null) } // attached below
                    },
                },
            });
            // Attach handler after init to get quill reference
            q.getModule('toolbar').addHandler('image', makeImageHandler(q));
            return q;
        }

        const mainQuill = initQuill('main-quill-editor', 'Share your thoughts about this track…');

        // ============================================================
        // BUILD COMMENT HTML (for dynamically inserted comments)
        // ============================================================
        function buildCommentHtml(c, isReply = false) {
            const reactions = ['like', 'love', 'haha', 'wow', 'sad', 'angry'];
            const emojiMap  = { like:'👍', love:'❤️', haha:'😂', wow:'😮', sad:'😢', angry:'😡' };
            let reactionBarHtml = reactions.map(type => `
                <button class="reaction-emoji" data-type="${type}" title="${type}" aria-label="${type}">
                    ${emojiMap[type]}
                </button>`).join('');

            let reactionCounts = reactions.map(type => {
                const cnt = (c.reaction_counts || {})[type] || 0;
                return cnt > 0 ? `<button class="reaction-count-badge" data-type="${type}">${emojiMap[type]} ${cnt}</button>` : '';
            }).join('');

            const replyBtn = !isReply
                ? `<button class="reply-toggle-btn text-xs text-zinc-500 hover:text-amber-400 font-bold flex items-center gap-1 transition" data-comment-id="${c.id}">
                    <i class="fa-solid fa-reply text-amber-500/60"></i> Reply
                   </button>`
                : '';

            return `
            <div class="comment-card comment-animate ${isReply ? 'reply-card' : ''} bg-zinc-900/40 border border-zinc-800 rounded-2xl p-5 mb-4" data-comment-id="${c.id}">
                <div class="flex gap-3">
                    <img src="${c.avatar}" alt="${c.display_name}" class="w-9 h-9 rounded-xl object-cover border border-zinc-700 shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="text-sm font-bold text-white">${c.display_name}</span>
                            ${c.is_auth ? '<span class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[9px] font-black rounded-full uppercase tracking-wider">Member</span>' : '<span class="px-2 py-0.5 bg-zinc-800 border border-zinc-700 text-zinc-500 text-[9px] font-black rounded-full uppercase tracking-wider">Guest</span>'}
                            <span class="text-zinc-600 text-xs">${c.created_at_human}</span>
                        </div>
                        <div class="quill-content text-zinc-300 text-sm leading-relaxed mb-3">${c.comment}</div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="reaction-wrapper relative">
                                <button class="reaction-trigger text-xs text-zinc-500 hover:text-amber-400 font-bold flex items-center gap-1 transition">
                                    <i class="fa-regular fa-face-smile text-amber-500/60"></i> React
                                </button>
                                <div class="reaction-bar">
                                    ${reactionBarHtml}
                                </div>
                            </div>
                            ${replyBtn}
                            <div class="reaction-counts-row flex gap-1 flex-wrap">${reactionCounts}</div>
                        </div>
                        ${!isReply ? `<div class="replies-container mt-4 space-y-3"></div>
                        <div class="reply-form-container mt-3" style="display:none"></div>` : ''}
                    </div>
                </div>
            </div>`;
        }

        // ============================================================
        // SUBMIT MAIN COMMENT
        // ============================================================
        document.getElementById('main-comment-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const content = mainQuill.root.innerHTML.trim();
            if (!content || content === '<p><br></p>') {
                showAlert('Please write something first.');
                return;
            }
            const btn = document.getElementById('main-comment-submit');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Posting…';

            const payload = { comment: content };
            @guest
            payload.name  = document.getElementById('comment-guest-name')?.value;
            payload.email = document.getElementById('comment-guest-email')?.value;
            @endguest

            try {
                const json = await jsonFetch(COMMENT_URL, payload);
                if (json.success) {
                    const placeholder = document.getElementById('no-comments-placeholder');
                    if (placeholder) placeholder.remove();
                    const list = document.getElementById('comments-list');
                    list.insertAdjacentHTML('afterbegin', buildCommentHtml(json.comment));
                    attachCommentListeners(list.firstElementChild);
                    mainQuill.setContents([]);
                    showAlert('Comment posted!', 'success');
                } else {
                    showAlert(json.message || 'Failed to post comment.');
                }
            } catch (err) {
                showAlert('Something went wrong. Try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Post Comment';
            }
        });

        // ============================================================
        // REACTION HANDLER
        // ============================================================
        async function handleReaction(commentId, type, cardEl) {
            try {
                const json = await jsonFetch(`${REACT_BASE}${commentId}/react`, { type });
                if (json.success) {
                    // Update reaction count badges
                    const countsRow = cardEl.querySelector('.reaction-counts-row');
                    const emojiMap = { like:'👍', love:'❤️', haha:'😂', wow:'😮', sad:'😢', angry:'😡' };
                    let html = '';
                    Object.entries(json.counts).forEach(([t, cnt]) => {
                        if (cnt > 0) {
                            const isActive = json.user_reaction === t;
                            html += `<button class="reaction-count-badge ${isActive ? 'active' : ''}" data-type="${t}">${emojiMap[t]} ${cnt}</button>`;
                        }
                    });
                    countsRow.innerHTML = html;
                    // Re-bind count badge clicks
                    countsRow.querySelectorAll('.reaction-count-badge').forEach(badge => {
                        badge.addEventListener('click', () => handleReaction(commentId, badge.dataset.type, cardEl));
                    });
                }
            } catch (e) { showAlert('Reaction failed.'); }
        }

        // ============================================================
        // REPLY SYSTEM
        // ============================================================
        function openReplyForm(commentId, cardEl) {
            const container = cardEl.querySelector('.reply-form-container');
            if (!container) return;
            if (container.style.display !== 'none' && container.innerHTML !== '') {
                container.style.display = 'none';
                container.innerHTML = '';
                return;
            }

            const replyId = `reply-quill-${commentId.substring(0, 8)}`;
            container.innerHTML = `
                <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-4">
                    <div id="${replyId}"></div>
                    <div class="mt-3 flex justify-end gap-2">
                        <button class="cancel-reply px-4 py-2 text-xs text-zinc-500 hover:text-white font-bold rounded-xl border border-zinc-700 hover:border-zinc-500 transition">Cancel</button>
                        <button class="submit-reply px-5 py-2 bg-amber-500 hover:bg-amber-400 text-black text-xs font-black rounded-xl transition flex items-center gap-1">
                            <i class="fa-solid fa-reply"></i> Reply
                        </button>
                    </div>
                </div>`;
            container.style.display = 'block';

            const replyQuill = initQuill(replyId, 'Write a reply…');

            container.querySelector('.cancel-reply').addEventListener('click', () => {
                container.style.display = 'none';
                container.innerHTML = '';
            });

            container.querySelector('.submit-reply').addEventListener('click', async () => {
                const content = replyQuill.root.innerHTML.trim();
                if (!content || content === '<p><br></p>') {
                    showAlert('Please write something first.');
                    return;
                }
                const payload = { comment: content, parent_id: commentId };
                @guest
                const gName  = document.getElementById('comment-guest-name')?.value;
                const gEmail = document.getElementById('comment-guest-email')?.value;
                if (!gName || !gEmail) {
                    showAlert('Please fill in your name and email above to reply as a guest.');
                    document.getElementById('comment-guest-name')?.scrollIntoView({ behavior: 'smooth' });
                    return;
                }
                payload.name  = gName;
                payload.email = gEmail;
                @endguest

                try {
                    const json = await jsonFetch(COMMENT_URL, payload);
                    if (json.success) {
                        const repliesContainer = cardEl.querySelector('.replies-container');
                        repliesContainer.insertAdjacentHTML('beforeend', buildCommentHtml(json.comment, true));
                        attachCommentListeners(repliesContainer.lastElementChild);
                        container.style.display = 'none';
                        container.innerHTML = '';
                        showAlert('Reply posted!', 'success');
                    } else {
                        showAlert(json.message || 'Failed to post reply.');
                    }
                } catch (err) {
                    showAlert('Something went wrong.');
                }
            });
        }

        // ============================================================
        // ATTACH LISTENERS TO A COMMENT CARD
        // ============================================================
        function attachCommentListeners(cardEl) {
            if (!cardEl) return;
            const commentId = cardEl.dataset.commentId;

            // Reaction emoji picks
            cardEl.querySelectorAll('.reaction-emoji').forEach(btn => {
                btn.addEventListener('click', () => handleReaction(commentId, btn.dataset.type, cardEl));
            });

            // Reaction count badges
            cardEl.querySelectorAll('.reaction-count-badge').forEach(badge => {
                badge.addEventListener('click', () => handleReaction(commentId, badge.dataset.type, cardEl));
            });

            // Reply toggle
            const replyBtn = cardEl.querySelector('.reply-toggle-btn');
            if (replyBtn) {
                replyBtn.addEventListener('click', () => openReplyForm(commentId, cardEl));
            }
        }

        // ============================================================
        // ATTACH LISTENERS TO ALL SERVER-RENDERED COMMENTS
        // ============================================================
        document.querySelectorAll('#comments-list [data-comment-id]').forEach(attachCommentListeners);

    })();
    </script>
    @endpush

</x-webapp-layout>
