<x-webapp-layout>
    {{-- 1. Trending Hero Section --}}
    <section class="relative rounded-[32px] overflow-hidden mb-12 group border border-zinc-900 shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent z-10"></div>
        <img src="https://images.unsplash.com/photo-1514525253361-bee8a4874a73?auto=format&fit=crop&w=1600&q=80"
             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-1000">

        <div class="relative z-20 p-8 lg:p-12 flex flex-col justify-center h-80 max-w-2xl">
            <div class="flex items-center gap-2 mb-4">
                <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-black rounded-full uppercase tracking-widest animate-pulse">
                    Hot This Week
                </span>
                <span class="text-zinc-400 text-xs font-bold uppercase tracking-widest">• 50k+ Downloads</span>
            </div>
            <h1 class="font-brand text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                DESI DRILL <br><span class="text-amber-500 italic">VIBRATIONS VOL. 2</span>
            </h1>
            <p class="text-zinc-300 text-sm mb-8 leading-relaxed font-medium">
                The most downloaded stem pack in NCS Hindi history. Featuring 40+ royalty-free loops,
                808s, and vocal chops specifically tuned for modern Hindi Trap.
            </p>
            <div class="flex gap-4">
                <button class="btn-vault px-8 py-3 text-xs uppercase font-black flex items-center gap-2">
                    <i class="fa-solid fa-download"></i> Get Bundle
                </button>
                <button class="px-8 py-3 bg-white/10 backdrop-blur-md border border-white/10 rounded-xl text-xs font-bold hover:bg-white/20 transition">
                    View Details
                </button>
            </div>
        </div>
    </section>

    {{-- 2. Trending Filter Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 px-2">
        <h2 class="font-brand text-2xl font-bold uppercase italic text-white tracking-tight">
            Leaderboard <span class="text-zinc-700 ml-2 not-italic font-normal">/ Top Charts</span>
        </h2>
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2">
            <button class="px-5 py-2 bg-zinc-900 rounded-full text-[10px] font-bold text-amber-500 border border-amber-500/30 whitespace-nowrap">TOP DOWNLOADS</button>
            <button class="px-5 py-2 bg-zinc-950 rounded-full text-[10px] font-bold text-zinc-500 border border-zinc-900 whitespace-nowrap hover:text-white transition">MOST LIKED</button>
            <button class="px-5 py-2 bg-zinc-950 rounded-full text-[10px] font-bold text-zinc-500 border border-zinc-900 whitespace-nowrap hover:text-white transition">NEWEST</button>
        </div>
    </div>

    {{-- 3. Trending Stems Grid --}}
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $trending = [
                ['title' => 'Galliyan Lofi Flip', 'artist' => 'Ritik_Beats', 'downloads' => '12.4k', 'img' => 'https://images.unsplash.com/photo-1493225255756-d9584f8606e9?auto=format&fit=crop&w=400&q=80', 'color' => 'amber'],
                ['title' => 'Mumbai Underground 808', 'artist' => 'Dharavi_Don', 'downloads' => '8.2k', 'img' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=400&q=80', 'color' => 'red'],
                ['title' => 'Sitar Trap Stems', 'artist' => 'Pandit_Flow', 'downloads' => '5.1k', 'img' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=400&q=80', 'color' => 'amber'],
            ];
        @endphp

        @foreach($trending as $item)
            <div class="forum-card group overflow-hidden hover:translate-y-[-4px] transition-all duration-300">
                <div class="relative h-48">
                    <img src="{{ $item['img'] }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition duration-500">
                    <div class="absolute top-4 left-4">
                        <span class="bg-black/60 backdrop-blur-md px-3 py-1 rounded-lg text-[9px] font-black text-white uppercase tracking-widest border border-white/10">
                            #{{ $loop->iteration }} Global
                        </span>
                    </div>
                    <div class="absolute bottom-4 right-4">
                        <div class="flex items-center gap-1 bg-{{ $item['color'] }}-600 px-2 py-1 rounded-md text-[10px] font-bold text-white shadow-lg">
                            <i class="fa-solid fa-arrow-trend-up"></i> Trending
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-bold text-lg text-white group-hover:text-amber-500 transition">{{ $item['title'] }}</h4>
                            <p class="text-xs text-zinc-500 font-medium">By {{ $item['artist'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-tighter">{{ $item['downloads'] }}</p>
                            <p class="text-[8px] text-zinc-600 font-bold uppercase">Downloads</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="flex-1 btn-vault py-2.5 rounded-xl text-[10px] flex items-center justify-center gap-2">
                            <i class="fa-solid fa-download"></i> DOWNLOAD STEMS
                        </button>
                        <button class="w-10 h-10 bg-zinc-900 border border-zinc-800 rounded-xl flex items-center justify-center text-zinc-500 hover:text-red-500 transition">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- 4. Rising Artists Section --}}
    <section class="mt-16 bg-gradient-to-br from-[#111114] to-[#050505] rounded-[40px] p-8 border border-zinc-900">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-brand text-xl font-bold uppercase italic text-white tracking-tight">Rising <span class="text-red-600">Producers</span></h3>
            <button class="text-xs font-bold text-zinc-500 hover:text-white transition uppercase tracking-widest">Discover All</button>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
            @for ($i = 1; $i <= 5; $i++)
                <div class="flex flex-col items-center group cursor-pointer">
                    <div class="relative w-20 h-20 mb-3">
                        <img src="https://ui-avatars.com/api/?name=Artist+{{ $i }}&background=random" class="w-full h-full rounded-2xl object-cover border-2 border-zinc-800 group-hover:border-amber-600 transition duration-300">
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-600 rounded-lg flex items-center justify-center border-2 border-[#111114]">
                            <i class="fa-solid fa-crown text-[8px] text-white"></i>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-white group-hover:text-amber-500 transition">Producer_{{ $i }}</span>
                    <span class="text-[9px] text-zinc-600 font-bold uppercase mt-1 tracking-tighter">Level {{ 10 + $i }}</span>
                </div>
            @endfor
        </div>
    </section>
</x-webapp-layout>
