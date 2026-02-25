<x-webapp-layout>
    <div class="max-w-5xl mx-auto pb-24">

        {{-- Profile Header / Cover --}}
        <section class="relative mb-12">
            <div class="h-48 lg:h-64 rounded-[40px] bg-gradient-to-br from-[#4b3a24] to-[#050402] border border-zinc-800 overflow-hidden relative">
                <div class="absolute inset-0 opacity-20" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
                {{-- Decorative Glow --}}
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-red-600/20 blur-[100px] rounded-full"></div>
            </div>

            <div class="absolute -bottom-10 left-10 flex flex-col md:flex-row md:items-end gap-6">
                <div class="relative group">
                    <img src="https://ui-avatars.com/api/?name=Aaryan&background=b45309&color=fff&size=200"
                         class="w-32 h-32 lg:w-40 lg:h-40 rounded-[32px] border-8 border-black shadow-2xl object-cover">
                    <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-4 border-black rounded-full shadow-[0_0_15px_rgba(34,197,94,0.5)]"></div>
                </div>
                <div class="mb-4">
                    <h1 class="font-brand text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        {{ $user['name'] }}
                    </h1>
                    <p class="text-amber-500 font-bold text-xs mt-2 uppercase tracking-widest">{{ $user['rank'] }}</p>
                </div>
            </div>

            <div class="absolute -bottom-10 right-10 hidden md:flex gap-3">
                <button class="btn-vault px-6 py-3 text-[10px] uppercase font-black">Edit Studio</button>
                <button class="bg-zinc-900 border border-zinc-800 px-4 py-3 rounded-xl hover:text-red-500 transition">
                    <i class="fa-solid fa-share-nodes"></i>
                </button>
            </div>
        </section>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-20">

            {{-- Left Column: Info --}}
            <div class="space-y-6">
                <div class="forum-card p-6">
                    <h3 class="font-brand text-sm font-black text-zinc-500 uppercase tracking-widest mb-4">Bio</h3>
                    <p class="text-sm text-zinc-400 leading-relaxed font-medium">
                        {{ $user['bio'] }}
                    </p>
                </div>

                <div class="forum-card p-6">
                    <h3 class="font-brand text-sm font-black text-zinc-500 uppercase tracking-widest mb-6">Studio Stats</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-xl font-black text-white leading-none">{{ $user['stats']['uploads'] }}</p>
                            <p class="text-[8px] text-zinc-600 font-bold uppercase mt-1">Uploads</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-black text-red-600 leading-none">{{ $user['stats']['downloads'] }}</p>
                            <p class="text-[8px] text-zinc-600 font-bold uppercase mt-1">Get Stems</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-black text-amber-500 leading-none">{{ $user['stats']['likes'] }}</p>
                            <p class="text-[8px] text-zinc-600 font-bold uppercase mt-1">Hearts</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Activity --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between px-2 mb-2">
                    <h2 class="font-brand text-xl font-bold italic text-white uppercase tracking-tight">Recent Studio Work</h2>
                    <button class="text-[10px] font-black text-zinc-600 hover:text-amber-500 transition uppercase tracking-widest">View Library</button>
                </div>

                @foreach($recent_uploads as $upload)
                <div class="forum-card p-6 flex items-center justify-between group hover:border-amber-600/40 transition-all duration-300">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-zinc-950 flex items-center justify-center border border-zinc-800 group-hover:bg-amber-600/10 transition">
                            <i class="fa-solid fa-file-audio text-xl {{ $loop->first ? 'text-red-600' : 'text-amber-600' }}"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">{{ $upload['title'] }}</h4>
                            <p class="text-[10px] text-zinc-600 font-mono uppercase tracking-widest mt-1">{{ $upload['date'] }} • {{ $upload['type'] }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="w-10 h-10 rounded-xl bg-zinc-900 flex items-center justify-center text-zinc-500 hover:text-white transition">
                            <i class="fa-solid fa-ellipsis-v text-xs"></i>
                        </button>
                        <button class="btn-vault px-5 py-2.5 rounded-xl text-[10px] uppercase font-black">Download</button>
                    </div>
                </div>
                @endforeach

                {{-- Empty Showcase Placeholder --}}
                <div class="forum-card p-12 border-dashed flex flex-col items-center justify-center text-center opacity-50">
                    <div class="w-16 h-16 rounded-full bg-zinc-900 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-plus text-zinc-700"></i>
                    </div>
                    <p class="text-xs font-bold text-zinc-600 uppercase tracking-widest">Add Gear Showcase</p>
                </div>
            </div>
        </div>
    </div>
</x-webapp-layout>
