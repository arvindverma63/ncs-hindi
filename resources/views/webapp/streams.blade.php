<x-webapp-layout>
    {{-- 1. Library Search & Stats --}}
    <section class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12 px-2">
        <div class="flex-1">
            <h1 class="font-brand text-3xl font-black text-white uppercase tracking-tighter">
                Stems <span class="text-amber-500 italic">Library</span>
            </h1>
            <p class="text-xs text-zinc-500 font-bold uppercase tracking-widest mt-1">
                Explore 5,000+ Royalty-Free Hindi NCS Assets
            </p>
        </div>

        <div class="flex items-center gap-4 bg-zinc-900/30 p-2 rounded-2xl border border-zinc-800">
            <div class="px-4 py-2 border-r border-zinc-800 text-center">
                <p class="text-lg font-black text-white leading-none">524</p>
                <p class="text-[8px] text-zinc-500 uppercase font-bold tracking-tighter mt-1">New Stems</p>
            </div>
            <div class="px-4 py-2 text-center">
                <p class="text-lg font-black text-red-600 leading-none">1.2 TB</p>
                <p class="text-[8px] text-zinc-500 uppercase font-bold tracking-tighter mt-1">Total Audio</p>
            </div>
        </div>
    </section>

    {{-- 2. Technical Filtering System --}}
    <section class="flex flex-wrap gap-3 mb-10">
        <div class="relative flex-1 min-w-[280px]">
            <i class="fa-solid fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-amber-600 text-xs"></i>
            <input type="text" placeholder="Filter by Instrument (Sitar, Tabla, 808...)"
                   class="w-full bg-zinc-900 border border-zinc-800 rounded-xl py-3 pl-12 pr-4 text-xs focus:border-amber-600 outline-none transition">
        </div>
        <select class="bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-xs text-zinc-400 focus:border-red-600 outline-none">
            <option>All BPMs</option>
            <option>70 - 90 (Lofi)</option>
            <option>120 - 140 (Trap)</option>
        </select>
        <select class="bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-xs text-zinc-400 focus:border-red-600 outline-none">
            <option>All Keys</option>
            <option>C Minor</option>
            <option>G# Phrygian</option>
        </select>
    </section>

    {{-- 3. Stems List (High Density) --}}
    <section class="space-y-4">
        @php
            $stems = [
                ['name' => 'Dil Se Vocal Chop Pack', 'category' => 'Vocals', 'bpm' => '128', 'key' => 'Am', 'size' => '42MB', 'downloads' => '2.1k'],
                ['name' => 'Varanasi Sitar Resonance', 'category' => 'Acoustic', 'bpm' => '90', 'key' => 'Cm', 'size' => '120MB', 'downloads' => '1.4k'],
                ['name' => 'Desi Drill Basslines Vol 1', 'category' => 'Bass', 'bpm' => '142', 'key' => 'F#m', 'size' => '88MB', 'downloads' => '3.9k'],
                ['name' => 'Tabla Percussion Stems', 'category' => 'Drums', 'bpm' => '100', 'key' => 'None', 'size' => '210MB', 'downloads' => '5k'],
            ];
        @endphp

        @foreach($stems as $stem)
            <div class="forum-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-[#121215] transition-all">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-zinc-900 border border-zinc-800 rounded-2xl flex items-center justify-center group-hover:border-amber-600 transition duration-300">
                        @if($stem['category'] == 'Vocals')
                            <i class="fa-solid fa-microphone text-xl text-red-600"></i>
                        @elseif($stem['category'] == 'Bass')
                            <i class="fa-solid fa-wave-square text-xl text-amber-600"></i>
                        @else
                            <i class="fa-solid fa-music text-xl text-zinc-600"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-white group-hover:text-amber-500 transition">{{ $stem['name'] }}</h4>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-[9px] font-black text-zinc-600 uppercase tracking-widest bg-black px-2 py-0.5 rounded">{{ $stem['category'] }}</span>
                            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest">{{ $stem['size'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-6 lg:gap-12 px-2">
                    <div class="text-center">
                        <p class="text-xs font-black text-zinc-300">{{ $stem['bpm'] }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">BPM</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-black text-zinc-300">{{ $stem['key'] }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">Key</p>
                    </div>
                    <div class="text-center hidden sm:block">
                        <p class="text-xs font-black text-zinc-300">{{ $stem['downloads'] }}</p>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-tighter">Saved</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 ml-auto lg:ml-0">
                    <button class="p-3 bg-zinc-900 border border-zinc-800 rounded-xl text-zinc-400 hover:text-white transition group/btn">
                        <i class="fa-solid fa-info-circle group-hover/btn:scale-110 transition"></i>
                    </button>
                    <button class="btn-vault px-6 py-2.5 rounded-xl text-[10px] flex items-center gap-2">
                        <i class="fa-solid fa-download"></i> GET STEMS
                    </button>
                </div>
            </div>
        @endforeach
    </section>

    {{-- 4. Storage & Licensing Alert --}}
    <section class="mt-12 bg-gradient-to-r from-red-950/20 to-amber-950/10 border border-red-900/30 rounded-[32px] p-6 lg:p-10 flex flex-col md:flex-row items-center gap-8 shadow-2xl">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-600 to-red-600 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-file-contract text-2xl text-white"></i>
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="font-brand text-lg font-bold text-white uppercase italic tracking-tight">Personal Creator License Included</h3>
            <p class="text-xs text-zinc-400 mt-1 font-medium leading-relaxed max-w-xl">
                All stems in the library are 100% royalty-free for content creators. No copyright strikes on YouTube or Instagram when you link back to NCS Hindi.
            </p>
        </div>
        <button class="bg-white text-black text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-xl hover:bg-zinc-200 transition">
            Usage Guide
        </button>
    </section>
</x-webapp-layout>
