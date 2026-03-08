<x-webapp-layout>
    {{-- 1. Header Section --}}
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
                <p class="text-lg font-black text-white leading-none">{{ $stems->total() }}</p>
                <p class="text-[8px] text-zinc-500 uppercase font-bold tracking-tighter mt-1">Available</p>
            </div>
            <div class="px-4 py-2 text-center">
                <p class="text-lg font-black text-red-600 leading-none">Studio</p>
                <p class="text-[8px] text-zinc-500 uppercase font-bold tracking-tighter mt-1">Quality</p>
            </div>
        </div>
    </section>

    {{-- 2. Filtering System --}}
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

    {{-- 3. High-Density List --}}
    <section class="space-y-4">
        @forelse($stems as $stem)
            <div
                class="forum-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-[#121215] transition-all border border-transparent hover:border-zinc-800 rounded-2xl">
                <div class="flex items-center gap-5">
                    {{-- Artwork & Play Button --}}
                    <div
                        class="w-14 h-14 bg-zinc-900 border border-zinc-800 rounded-2xl flex items-center justify-center overflow-hidden relative group/play">
                        @if ($stem->featured_image)
                            <img src="{{ $stem->featured_image }}"
                                class="w-full h-full object-cover group-hover:opacity-40 transition"
                                alt="{{ $stem->title }}">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center group-hover:opacity-20 transition text-zinc-600">
                                <i class="fa-solid fa-music text-xl"></i>
                            </div>
                        @endif

                        {{-- Fixed JavaScript String Injection --}}
                        <button
                            onclick="togglePreview(this, '{{ asset('storage/' . $stem->file_path) }}', '{{ $stem->id }}')"
                            class="absolute inset-0 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all scale-75 group-hover:scale-100">
                            <i class="fa-solid fa-play text-xl play-icon"></i>
                            <i class="fa-solid fa-pause text-xl pause-icon hidden"></i>
                        </button>
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

                {{-- Dynamic Stats: Likes, Views, Uploaded Date --}}
                <div class="flex flex-wrap items-center gap-6 lg:gap-12 px-2">
                    <div class="text-center min-w-[45px]">
                        <p class="text-xs font-black text-zinc-300">{{ number_format($stem->like_count ?? 0) }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">Likes</p>
                    </div>
                    <div class="text-center min-w-[45px]">
                        <p class="text-xs font-black text-zinc-300">{{ number_format($stem->view_count ?? 0) }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">Views</p>
                    </div>
                    <div class="text-center hidden sm:block">
                        <p class="text-xs font-black text-zinc-300">{{ $stem->created_at->format('d M Y') }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">Uploaded</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 ml-auto lg:ml-0">
                    <button type="button" onclick="window.location.href='{{ route('webapp.stems.show', $stem->id) }}'"
                        class="p-3 bg-zinc-900 border border-zinc-800 rounded-xl text-zinc-400 hover:text-white transition group/btn">
                        <i class="fa-solid fa-circle-info group-hover/btn:scale-110 transition"></i>
                    </button>

                    <a href="{{ route('webapp.stems.download', $stem->id) }}"
                        class="btn-vault px-6 py-2.5 rounded-xl text-[10px] font-black flex items-center gap-2 hover:scale-105 transition-transform">
                        <i class="fa-solid fa-download"></i> GET STEMS
                    </a>
                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-zinc-900/20 rounded-[32px] border border-dashed border-zinc-800">
                <i class="fa-solid fa-music-slash text-4xl text-zinc-800 mb-4"></i>
                <h5 class="text-zinc-500 font-bold uppercase tracking-widest text-xs">No studio assets match your
                    criteria</h5>
            </div>
        @endforelse
    </section>

    <div class="mt-10">
        {{ $stems->links() }}
    </div>
</x-webapp-layout>

@push('scripts')
    <script>
        let currentAudio = new Audio();
        let currentBtn = null;

        function togglePreview(btn, url, id) {
            const playIcon = btn.querySelector('.play-icon');
            const pauseIcon = btn.querySelector('.pause-icon');

            if (currentBtn === btn) {
                if (currentAudio.paused) {
                    currentAudio.play();
                    showPause(btn);
                } else {
                    currentAudio.pause();
                    showPlay(btn);
                }
                return;
            }

            if (currentBtn) {
                showPlay(currentBtn);
            }

            currentAudio.src = url;
            currentAudio.play();
            currentBtn = btn;
            showPause(btn);

            currentAudio.onended = () => {
                showPlay(btn);
                currentBtn = null;
            };
        }

        function showPlay(btn) {
            btn.querySelector('.play-icon').classList.remove('hidden');
            btn.querySelector('.pause-icon').classList.add('hidden');
            btn.closest('.w-14').classList.remove('border-amber-500', 'ring-2', 'ring-amber-500/20');
        }

        function showPause(btn) {
            btn.querySelector('.play-icon').classList.add('hidden');
            btn.querySelector('.pause-icon').classList.remove('hidden');
            btn.closest('.w-14').classList.add('border-amber-500', 'ring-2', 'ring-amber-500/20');
        }
    </script>
@endpush
