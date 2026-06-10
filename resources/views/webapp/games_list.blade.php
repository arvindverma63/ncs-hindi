<x-webapp-layout
    title="{{ $seo['title'] ?? 'NCS Arcade Center' }}"
    description="{{ $seo['description'] ?? 'Play music games and earn credits.' }}"
    keywords="{{ $seo['keywords'] ?? 'games, credits' }}"
>
    {{-- Header Banner with Credits Display --}}
    <div class="relative overflow-hidden rounded-[32px] border border-zinc-800 bg-[#08080a] p-6 sm:p-10 mb-8">
        {{-- Background glowing circles --}}
        <div class="absolute -top-20 -left-20 w-82 h-82 bg-amber-500/10 blur-[80px] rounded-full"></div>
        <div class="absolute -bottom-20 -right-20 w-82 h-82 bg-red-600/10 blur-[80px] rounded-full"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="font-brand text-3xl sm:text-5xl font-black text-white uppercase tracking-tighter">
                    NCS <span class="text-amber-500 italic">Arcade</span> Center
                </h1>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-[0.2em] mt-1.5">
                    Play free online mini-games and earn rewards
                </p>
            </div>

            {{-- Points card --}}
            <div class="flex items-center gap-4 bg-zinc-900/60 border border-zinc-800 rounded-3xl p-4 sm:p-6 shrink-0 w-full md:w-auto shadow-xl">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-500 shrink-0">
                    <i class="fa-solid fa-coins text-2xl"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">Your points balance</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-white mt-0.5">
                        {{ number_format($credits) }} <span class="text-amber-500 text-xs font-bold tracking-widest uppercase">NCS Credits</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Games Grid list (2/3 width on desktop) --}}
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-white font-brand font-bold text-xl uppercase tracking-tight flex items-center gap-3">
                <span class="w-1.5 h-7 bg-amber-500 rounded-full"></span>
                Select a game to play
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Game 1: NCS Rhythm Tapper --}}
                <div class="group bg-zinc-900/30 border border-zinc-800/60 rounded-[30px] overflow-hidden hover:border-amber-500/40 transition-all duration-500 flex flex-col h-full shadow-lg">
                    <div class="relative aspect-video overflow-hidden bg-zinc-850">
                        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            alt="NCS Rhythm Tapper">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="px-2.5 py-1 bg-green-500/90 text-[8px] font-black text-black rounded-lg uppercase tracking-wider">
                                Playable
                            </span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <span class="px-3 py-1 bg-black/60 border border-white/10 rounded-lg text-[9px] font-black text-amber-500 uppercase tracking-widest">
                                +50 Credits per win
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-brand text-lg font-black uppercase text-white tracking-tight mb-2">
                                NCS Rhythm Tapper
                            </h4>
                            <p class="text-xs text-zinc-500 leading-relaxed mb-6 font-medium">
                                Test your reflexes and rhythm! Tap the falling keys in sync with royalty-free tracks to build huge score combos.
                            </p>
                        </div>
                        <a href="{{ route('webapp.game.rhythm-tapper') }}"
                            class="block w-full py-3 bg-white hover:bg-amber-500 text-black hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest text-center transition-all">
                            Start Playing
                        </a>
                    </div>
                </div>

                {{-- Game 2: NCS 2048 --}}
                <div class="group bg-zinc-900/30 border border-zinc-800/60 rounded-[30px] overflow-hidden hover:border-amber-500/40 transition-all duration-500 flex flex-col h-full shadow-lg">
                    <div class="relative aspect-video overflow-hidden bg-zinc-850">
                        <img src="https://images.unsplash.com/photo-1553481187-be93c21490a9?auto=format&fit=crop&w=600&q=80"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            alt="NCS 2048">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="px-2.5 py-1 bg-green-500/90 text-[8px] font-black text-black rounded-lg uppercase tracking-wider">
                                Playable
                            </span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <span class="px-3 py-1 bg-black/60 border border-white/10 rounded-lg text-[9px] font-black text-amber-500 uppercase tracking-widest">
                                +50 Credits per win
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-brand text-lg font-black uppercase text-white tracking-tight mb-2">
                                NCS 2048 Sliding Puzzle
                            </h4>
                            <p class="text-xs text-zinc-500 leading-relaxed mb-6 font-medium">
                                The classic open-source sliding puzzle game! Join numbers, reach the 2048 tile, and earn credits while listening to visualizer beats.
                            </p>
                        </div>
                        <a href="{{ route('webapp.game.2048') }}"
                            class="block w-full py-3 bg-white hover:bg-amber-500 text-black hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest text-center transition-all">
                            Start Playing
                        </a>
                    </div>
                </div>

                {{-- Game 3: NCS Neon Serpent --}}
                <div class="group bg-zinc-900/30 border border-zinc-800/60 rounded-[30px] overflow-hidden hover:border-amber-500/40 transition-all duration-500 flex flex-col h-full shadow-lg">
                    <div class="relative aspect-video overflow-hidden bg-zinc-850">
                        <img src="https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?auto=format&fit=crop&w=600&q=80"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            alt="NCS Neon Serpent">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="px-2.5 py-1 bg-green-500/90 text-[8px] font-black text-black rounded-lg uppercase tracking-wider">
                                Playable
                            </span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <span class="px-3 py-1 bg-black/60 border border-white/10 rounded-lg text-[9px] font-black text-amber-500 uppercase tracking-widest">
                                +50 Credits per win
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-brand text-lg font-black uppercase text-white tracking-tight mb-2">
                                NCS Neon Serpent
                            </h4>
                            <p class="text-xs text-zinc-500 leading-relaxed mb-6 font-medium">
                                A highly visual, retro arcade snake game styled with ambient neon grid glows and retro audio sounds. Score 150 points to win credits!
                            </p>
                        </div>
                        <a href="{{ route('webapp.game.neon-serpent') }}"
                            class="block w-full py-3 bg-white hover:bg-amber-500 text-black hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest text-center transition-all">
                            Start Playing
                        </a>
                    </div>
                </div>

                {{-- Game 4: Beat Maker Challenge (Coming Soon) --}}
                <div class="group bg-zinc-900/10 border border-zinc-900 rounded-[30px] overflow-hidden opacity-60 flex flex-col h-full">
                    <div class="relative aspect-video overflow-hidden bg-zinc-900">
                        <div class="w-full h-full flex items-center justify-center bg-zinc-950">
                            <i class="fa-solid fa-drum text-4xl text-zinc-800"></i>
                        </div>
                        <div class="absolute inset-0 bg-black/40"></div>
                        <div class="absolute top-4 left-4">
                            <span class="px-2.5 py-1 bg-zinc-800 text-[8px] font-black text-zinc-500 rounded-lg uppercase tracking-wider">
                                Coming Soon
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-brand text-lg font-black uppercase text-zinc-400 tracking-tight mb-2">
                                Beat Maker Challenge
                            </h4>
                            <p class="text-xs text-zinc-600 leading-relaxed mb-6 font-medium">
                                Create your own dynamic sound loops. Match the pattern requirements to unlock extra credits.
                            </p>
                        </div>
                        <button disabled class="block w-full py-3 bg-zinc-800/50 text-zinc-600 rounded-xl text-[10px] font-black uppercase tracking-widest text-center cursor-not-allowed">
                            Locked
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Points History Sidebar (1/3 width on desktop) --}}
        <div class="space-y-6">
            <h3 class="text-white font-brand font-bold text-xl uppercase tracking-tight flex items-center gap-3">
                <span class="w-1.5 h-7 bg-amber-500 rounded-full"></span>
                Points History
            </h3>

            <div class="bg-zinc-900/40 border border-zinc-800 rounded-[32px] p-6 backdrop-blur-sm">
                @auth
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1 no-scrollbar">
                        @forelse ($history as $log)
                            <div class="flex items-start justify-between gap-3 pb-3.5 border-b border-zinc-800 last:border-0 last:pb-0">
                                <div>
                                    <p class="text-xs font-bold text-zinc-300 leading-normal">{{ $log->description }}</p>
                                    <span class="text-[8px] text-zinc-600 font-bold uppercase tracking-wider">
                                        {{ $log->created_at ? $log->created_at->diffForHumans() : 'Recently' }}
                                    </span>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-green-500 shrink-0">
                                    +{{ $log->amount }} CR
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-12 text-zinc-600">
                                <i class="fa-solid fa-clock-rotate-left text-3xl mb-3 opacity-30"></i>
                                <p class="text-[11px] font-bold uppercase tracking-wider">No transaction history</p>
                                <p class="text-[9px] text-zinc-500 mt-1">Play games above to earn ncs credits!</p>
                            </div>
                        @endforelse
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fa-solid fa-lock text-3xl mb-4 text-zinc-700"></i>
                        <p class="text-zinc-400 text-xs font-bold uppercase tracking-widest">Login required</p>
                        <p class="text-[9px] text-zinc-600 mt-1 max-w-xs mx-auto leading-relaxed">
                            Sign in to save your score, collect NCS Credits, and track your points history!
                        </p>
                        <a href="{{ route('login') }}" class="btn-vault inline-block mt-4 px-6 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest">
                            Sign In
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</x-webapp-layout>
