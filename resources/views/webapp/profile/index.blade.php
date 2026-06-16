<x-webapp-layout>
    @php
        $displayName = $profile?->studio_name ?? $user->name;
        $bio = $profile?->bio ?? 'No bio set for this profile yet.';
        $avatar = $user->profile_image && strlen($user->profile_image) > 20
            ? $user->profile_image
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=b45309&color=fff&size=240';
        $portfolioUrl = $profile?->website_url ?? null;
        $instagramUrl = $profile?->instagram_url ?? null;
    @endphp

    <div class="max-w-7xl mx-auto pb-24">
        <section class="relative overflow-hidden rounded-[36px] border border-white/5 bg-gradient-to-br from-[#100b08] via-[#09090b] to-[#030303]">
            <div class="absolute inset-0 opacity-35 bg-[radial-gradient(circle_at_top_right,_rgba(245,158,11,0.18),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(153,27,27,0.22),_transparent_30%)]"></div>
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-500/15 blur-[110px]"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-red-600/10 blur-[120px]"></div>

            <div class="relative grid lg:grid-cols-[1.15fr_0.85fr] gap-6 p-6 md:p-8 lg:p-10">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/5 text-[9px] font-black uppercase tracking-[0.22em] text-amber-400">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        Profile overview
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-end gap-5">
                        <img src="{{ $avatar }}" alt="{{ $user->name }}"
                            class="w-28 h-28 md:w-36 md:h-36 rounded-[30px] object-cover border-4 border-black shadow-2xl">
                        <div class="space-y-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-zinc-500">User Profile</p>
                            <h1 class="font-brand text-3xl md:text-5xl font-black uppercase tracking-tighter text-white leading-[0.92]">
                                {{ $displayName }}
                            </h1>
                            @if (!empty($profile?->rank_title))
                                <p class="text-amber-500 font-bold text-xs md:text-sm uppercase tracking-[0.2em]">
                                    {{ $profile->rank_title }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <p class="max-w-2xl text-sm md:text-base text-zinc-300 leading-relaxed">
                        {{ $bio }}
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('webapp.profile.edit') }}"
                            class="btn-vault px-6 py-3 rounded-2xl text-[10px] uppercase font-black tracking-[0.2em]">
                            Edit Profile
                        </a>
                        <a href="{{ route('webapp.streams') }}"
                            class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 text-[10px] uppercase font-black tracking-[0.2em] text-zinc-200 hover:text-white hover:border-amber-500/40 transition">
                            View Music Library
                        </a>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-[24px] bg-black/40 border border-white/5 p-4">
                            <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black">Liked songs</p>
                            <p class="mt-2 text-3xl font-black text-white">{{ number_format($profileStats['liked'] ?? 0) }}</p>
                        </div>
                        <div class="rounded-[24px] bg-black/40 border border-white/5 p-4">
                            <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black">Viewed songs</p>
                            <p class="mt-2 text-3xl font-black text-white">{{ number_format($profileStats['viewed'] ?? 0) }}</p>
                        </div>
                        <div class="rounded-[24px] bg-black/40 border border-white/5 p-4">
                            <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black">Downloaded songs</p>
                            <p class="mt-2 text-3xl font-black text-white">{{ number_format($profileStats['downloaded'] ?? 0) }}</p>
                        </div>
                        <div class="rounded-[24px] bg-black/40 border border-white/5 p-4">
                            <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black">Uploads</p>
                            <p class="mt-2 text-3xl font-black text-white">{{ number_format($profileStats['uploads'] ?? 0) }}</p>
                        </div>
                    </div>

                    <div class="rounded-[28px] bg-black/35 border border-white/5 p-5">
                        <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black mb-3">Profile links</p>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-zinc-500">Member since</span>
                                <span class="text-white font-bold">{{ optional($user->created_at)->format('M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-zinc-500">Profile name</span>
                                <span class="text-white font-bold truncate text-right">{{ $displayName }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-zinc-500">Website</span>
                                @if ($portfolioUrl)
                                    <a href="{{ $portfolioUrl }}" target="_blank" class="text-amber-400 hover:text-amber-300 font-bold truncate text-right">
                                        Open link
                                    </a>
                                @else
                                    <span class="text-zinc-600">Not set</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-zinc-500">Instagram</span>
                                @if ($instagramUrl)
                                    <a href="{{ $instagramUrl }}" target="_blank" class="text-amber-400 hover:text-amber-300 font-bold truncate text-right">
                                        Open link
                                    </a>
                                @else
                                    <span class="text-zinc-600">Not set</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-8 grid lg:grid-cols-[0.95fr_1.05fr] gap-6">
            <div class="space-y-6">
                <div class="forum-card p-6 bg-[#0a0a0c] border border-white/5">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h3 class="font-brand text-sm md:text-base font-black uppercase tracking-widest text-zinc-400">About Profile</h3>
                        <span class="text-[9px] uppercase tracking-[0.2em] text-zinc-600 font-black">Bio</span>
                    </div>
                    <p class="text-sm text-zinc-300 leading-relaxed">
                        {{ $bio }}
                    </p>
                </div>

                <div class="forum-card p-6 bg-[#0a0a0c] border border-white/5">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h3 class="font-brand text-sm md:text-base font-black uppercase tracking-widest text-zinc-400">Profile Actions</h3>
                        <span class="text-[9px] uppercase tracking-[0.2em] text-zinc-600 font-black">Quick links</span>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('webapp.profile.edit') }}"
                            class="flex items-center justify-between rounded-2xl bg-white/5 border border-white/5 px-4 py-3 hover:border-amber-500/30 transition">
                            <span class="text-sm font-bold text-white">Edit your profile</span>
                            <i class="fa-solid fa-pen text-zinc-500"></i>
                        </a>
                        <a href="{{ route('webapp.streams') }}"
                            class="flex items-center justify-between rounded-2xl bg-white/5 border border-white/5 px-4 py-3 hover:border-amber-500/30 transition">
                            <span class="text-sm font-bold text-white">Browse music library</span>
                            <i class="fa-solid fa-box-open text-zinc-500"></i>
                        </a>
                        <a href="{{ route('webapp.bug-reports.create') }}"
                            class="flex items-center justify-between rounded-2xl bg-white/5 border border-white/5 px-4 py-3 hover:border-amber-500/30 transition">
                            <span class="text-sm font-bold text-white">Report website bug</span>
                            <i class="fa-solid fa-bug text-zinc-500"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="forum-card p-6 bg-[#0a0a0c] border border-white/5">
                    <div class="flex items-center justify-between gap-3 mb-5">
                        <div>
                            <h3 class="font-brand text-xl md:text-2xl font-black uppercase tracking-tight text-white">Liked songs</h3>
                            <p class="mt-1 text-[11px] text-zinc-500">Songs you have liked.</p>
                        </div>
                        <span class="text-[9px] uppercase tracking-[0.2em] text-zinc-600 font-black">{{ number_format($profileStats['liked'] ?? 0) }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($likedSongs as $song)
                            @php
                                $cover = $song->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=900&q=80';
                            @endphp
                            <div class="flex items-center gap-4 rounded-[24px] border border-white/5 bg-black/35 p-3">
                                <img src="{{ $cover }}" alt="{{ $song->title }}" class="w-16 h-16 rounded-2xl object-cover shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] uppercase tracking-[0.2em] text-amber-400 font-black">Liked</p>
                                    <h4 class="text-sm font-bold text-white truncate">{{ $song->title }}</h4>
                                    <p class="text-xs text-zinc-500 truncate">{{ $song->artist_name ?: 'Unknown artist' }}</p>
                                    <div class="mt-2 flex flex-wrap gap-2 text-[9px] uppercase tracking-[0.2em]">
                                        <span class="px-2 py-1 rounded-full bg-white/5 text-zinc-400">{{ $song->category?->name ?? 'Uncategorized' }}</span>
                                        <span class="px-2 py-1 rounded-full bg-white/5 text-zinc-400">{{ number_format($song->like_count) }} likes</span>
                                    </div>
                                </div>
                                <a href="{{ route('webapp.music.show', $song->slug) }}"
                                    class="px-3.5 py-2.5 rounded-2xl bg-amber-500 text-black text-[9px] font-black uppercase tracking-[0.2em] shrink-0">
                                    View
                                </a>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-white/10 bg-black/20 p-6 text-center">
                                <p class="text-sm font-bold text-zinc-500">No liked songs yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="forum-card p-6 bg-[#0a0a0c] border border-white/5">
                    <div class="flex items-center justify-between gap-3 mb-5">
                        <div>
                            <h3 class="font-brand text-xl md:text-2xl font-black uppercase tracking-tight text-white">Viewed songs</h3>
                            <p class="mt-1 text-[11px] text-zinc-500">Songs you recently opened.</p>
                        </div>
                        <span class="text-[9px] uppercase tracking-[0.2em] text-zinc-600 font-black">{{ number_format($profileStats['viewed'] ?? 0) }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($viewedSongs as $song)
                            @php
                                $cover = $song->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=900&q=80';
                            @endphp
                            <div class="flex items-center gap-4 rounded-[24px] border border-white/5 bg-black/35 p-3">
                                <img src="{{ $cover }}" alt="{{ $song->title }}" class="w-16 h-16 rounded-2xl object-cover shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] uppercase tracking-[0.2em] text-cyan-400 font-black">Viewed</p>
                                    <h4 class="text-sm font-bold text-white truncate">{{ $song->title }}</h4>
                                    <p class="text-xs text-zinc-500 truncate">{{ $song->artist_name ?: 'Unknown artist' }}</p>
                                    <div class="mt-2 flex flex-wrap gap-2 text-[9px] uppercase tracking-[0.2em]">
                                        <span class="px-2 py-1 rounded-full bg-white/5 text-zinc-400">{{ $song->category?->name ?? 'Uncategorized' }}</span>
                                        <span class="px-2 py-1 rounded-full bg-white/5 text-zinc-400">{{ number_format($song->view_count) }} views</span>
                                    </div>
                                </div>
                                <a href="{{ route('webapp.music.show', $song->slug) }}"
                                    class="px-3.5 py-2.5 rounded-2xl bg-white/5 border border-white/10 text-[9px] font-black uppercase tracking-[0.2em] text-white shrink-0">
                                    Open
                                </a>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-white/10 bg-black/20 p-6 text-center">
                                <p class="text-sm font-bold text-zinc-500">No viewed songs yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="forum-card p-6 bg-[#0a0a0c] border border-white/5">
                    <div class="flex items-center justify-between gap-3 mb-5">
                        <div>
                            <h3 class="font-brand text-xl md:text-2xl font-black uppercase tracking-tight text-white">Downloaded songs</h3>
                            <p class="mt-1 text-[11px] text-zinc-500">Songs you downloaded from the library.</p>
                        </div>
                        <span class="text-[9px] uppercase tracking-[0.2em] text-zinc-600 font-black">{{ number_format($profileStats['downloaded'] ?? 0) }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($downloadedSongs as $song)
                            @php
                                $cover = $song->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=900&q=80';
                            @endphp
                            <div class="flex items-center gap-4 rounded-[24px] border border-white/5 bg-black/35 p-3">
                                <img src="{{ $cover }}" alt="{{ $song->title }}" class="w-16 h-16 rounded-2xl object-cover shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] uppercase tracking-[0.2em] text-emerald-400 font-black">Downloaded</p>
                                    <h4 class="text-sm font-bold text-white truncate">{{ $song->title }}</h4>
                                    <p class="text-xs text-zinc-500 truncate">{{ $song->artist_name ?: 'Unknown artist' }}</p>
                                    <div class="mt-2 flex flex-wrap gap-2 text-[9px] uppercase tracking-[0.2em]">
                                        <span class="px-2 py-1 rounded-full bg-white/5 text-zinc-400">{{ $song->category?->name ?? 'Uncategorized' }}</span>
                                        <span class="px-2 py-1 rounded-full bg-white/5 text-zinc-400">{{ number_format($song->download_count) }} downloads</span>
                                    </div>
                                </div>
                                <a href="{{ route('webapp.music.download', $song->id) }}" target="_blank" rel="noopener noreferrer"
                                    class="px-3.5 py-2.5 rounded-2xl bg-emerald-500 text-black text-[9px] font-black uppercase tracking-[0.2em] shrink-0 inline-flex items-center gap-2">
                                    <i class="fa-solid fa-download"></i> NCS Version
                                </a>
                            </div>
                        @empty
                            <div class="rounded-[24px] border border-dashed border-white/10 bg-black/20 p-6 text-center">
                                <p class="text-sm font-bold text-zinc-500">No downloaded songs yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        /* Light Mode Premium Profile Overrides */
        html.light section.bg-gradient-to-br.from-\[\#100b08\] {
            background: linear-gradient(135deg, var(--card), var(--panel)) !important;
            border-color: var(--border) !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02) !important;
        }
        html.light section.bg-gradient-to-br.from-\[\#100b08\] h1 {
            color: #09090b !important;
        }
        html.light section.bg-gradient-to-br.from-\[\#100b08\] p.text-zinc-300 {
            color: #27272a !important;
        }
        html.light section.bg-gradient-to-br.from-\[\#100b08\] div.bg-black\/40 {
            background-color: var(--card) !important;
            border-color: var(--border) !important;
        }
        html.light section.bg-gradient-to-br.from-\[\#100b08\] div.bg-black\/40 p.text-white {
            color: #09090b !important;
        }
        html.light section.bg-gradient-to-br.from-\[\#100b08\] div.bg-black\/35 {
            background-color: var(--card) !important;
            border-color: var(--border) !important;
        }
        html.light section.bg-gradient-to-br.from-\[\#100b08\] div.bg-black\/35 span.text-white {
            color: #09090b !important;
        }
        html.light section.bg-gradient-to-br.from-\[\#100b08\] a.bg-white\/5 {
            background-color: var(--panel) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
        html.light section.bg-gradient-to-br.from-\[\#100b08\] a.bg-white\/5:hover {
            border-color: #b45309 !important;
            color: #b45309 !important;
        }

        /* Song & Activity Cards in Light Mode */
        html.light div.rounded-\[24px\].bg-black\/35 {
            background-color: var(--card) !important;
            border-color: var(--border) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01) !important;
        }
        html.light div.rounded-\[24px\].bg-black\/35 h4.text-white {
            color: #09090b !important;
        }
        html.light div.rounded-\[24px\].bg-black\/35 span.bg-white\/5 {
            background-color: var(--panel) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
        html.light div.rounded-\[24px\].bg-black\/35 a.bg-white\/5 {
            background-color: var(--panel) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
        html.light div.rounded-\[24px\].bg-black\/35 a.bg-white\/5:hover {
            border-color: #b45309 !important;
            color: #b45309 !important;
        }

        /* Quick Action Blocks */
        html.light div.forum-card a.bg-white\/5 {
            background-color: var(--panel) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
        html.light div.forum-card a.bg-white\/5 span {
            color: #09090b !important;
        }
        html.light div.forum-card a.bg-white\/5:hover {
            border-color: #b45309 !important;
        }
        html.light div.forum-card a.bg-white\/5:hover span {
            color: #b45309 !important;
        }
    </style>
</x-webapp-layout>







