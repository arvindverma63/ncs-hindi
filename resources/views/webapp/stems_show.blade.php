<x-webapp-layout>
    {{-- 1. Hero Player Section --}}
    <section class="relative mb-8 rounded-[40px] overflow-hidden border border-zinc-800 bg-zinc-900/40">
        <div class="absolute inset-0 bg-gradient-to-b from-amber-600/10 to-transparent opacity-50"></div>

        <div class="relative p-6 lg:p-12 flex flex-col md:flex-row items-center gap-8 lg:gap-12">
            {{-- Artwork --}}
            <div class="w-48 h-48 lg:w-64 lg:h-64 flex-shrink-0 shadow-2xl shadow-black/50">
                @if ($stem->featured_image)
                    <img src="{{ $stem->featured_image ?? "" }}"
                        class="w-full h-full object-cover rounded-3xl border border-zinc-700" alt="{{ $stem->title }}">
                @else
                    <div
                        class="w-full h-full bg-zinc-800 rounded-3xl flex items-center justify-center border border-zinc-700">
                        <i class="fa-solid fa-music text-5xl text-zinc-600"></i>
                    </div>
                @endif
            </div>

            {{-- Metadata --}}
            <div class="flex-1 text-center md:text-left">
                <span
                    class="inline-block px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-widest mb-4 border border-amber-500/20">
                    {{ $stem->category->name ?? 'NCS Release' }}
                </span>
                <h1 class="text-3xl lg:text-5xl font-brand font-black text-white uppercase tracking-tighter mb-2">
                    {{ $stem->title }}
                </h1>
                <p class="text-zinc-400 font-bold text-lg mb-8">{{ $stem->artist_name ?: 'Official NCS Hindi Asset' }}
                </p>

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                    <a href="{{ route('webapp.stems.download', $stem->id) }}"
                        class="btn-vault px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center gap-3 hover:scale-105 transition shadow-lg shadow-amber-600/20">
                        <i class="fa-solid fa-download"></i> Download Stems
                    </a>

                    <button onclick="toggleLike({{ $stem->id }})"
                        class="p-4 bg-zinc-800 border border-zinc-700 rounded-2xl text-zinc-300 hover:text-red-500 transition group">
                        <i class="fa-solid fa-heart group-hover:scale-110 transition"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- 2. Technical Specs --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-zinc-900/30 border border-zinc-800 rounded-[32px] p-8">
                <h3 class="text-white font-brand font-bold uppercase tracking-tight mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-amber-500 text-sm"></i> Technical Details
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="p-4 bg-black/40 rounded-2xl border border-zinc-800">
                        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mb-1">BPM</p>
                        <p class="text-xl font-black text-white">{{ $stem->bpm ?? '--' }}</p>
                    </div>
                    <div class="p-4 bg-black/40 rounded-2xl border border-zinc-800">
                        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mb-1">Key</p>
                        <p class="text-xl font-black text-white">{{ $stem->music_key ?? '--' }}</p>
                    </div>
                    <div class="p-4 bg-black/40 rounded-2xl border border-zinc-800">
                        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mb-1">Size</p>
                        <p class="text-xl font-black text-white">{{ $stem->file_size }}</p>
                    </div>
                    <div class="p-4 bg-black/40 rounded-2xl border border-zinc-800">
                        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mb-1">Format</p>
                        <p class="text-xl font-black text-white">MP3 / 320</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-3">Description</h4>
                    <p class="text-zinc-400 text-sm leading-relaxed">
                        {{ $stem->description ?: 'No additional description provided for this studio asset.' }}
                    </p>
                </div>
            </div>

            {{-- 3. Licensing Box --}}
            <div class="bg-gradient-to-br from-zinc-900 to-black border border-zinc-800 rounded-[32px] p-8">
                <div class="flex items-start gap-6">
                    <div
                        class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center border border-green-500/20">
                        <i class="fa-solid fa-shield-check text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold uppercase tracking-tight">Royalty-Free Usage</h3>
                        <p class="text-zinc-500 text-xs mt-2 leading-relaxed">
                            This asset is cleared for use in all user-generated content (YouTube, Twitch, TikTok,
                            Instagram).
                            You are free to monetize your videos using these stems, provided you credit the official NCS
                            Hindi channel in your description.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Sidebar Stats --}}
        <div class="space-y-6">
            <div class="bg-zinc-900/30 border border-zinc-800 rounded-[32px] p-6 text-center">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-2xl font-black text-white">{{ number_format($stem->download_count) }}</p>
                        <p class="text-[8px] text-zinc-500 font-bold uppercase tracking-tighter mt-1">Total Downloads
                        </p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-white">{{ number_format($stem->like_count) }}</p>
                        <p class="text-[8px] text-zinc-500 font-bold uppercase tracking-tighter mt-1">Community Likes
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            @if ($stem->tags_keywords)
                <div class="bg-zinc-900/30 border border-zinc-800 rounded-[32px] p-6">
                    <h4 class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-4">Keywords</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach (explode(',', $stem->tags_keywords) as $tag)
                            <span
                                class="px-3 py-1 bg-black rounded-lg text-[10px] text-zinc-400 border border-zinc-800 hover:border-zinc-600 transition cursor-default">
                                #{{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleLike(id) {
                fetch(`/stems/${id}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            toastr.error(data.error);
                        } else {
                            toastr.success(data.message);
                        }
                    });
            }
        </script>
    @endpush
</x-webapp-layout>
