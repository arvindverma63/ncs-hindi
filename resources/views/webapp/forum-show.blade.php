<x-webapp-layout>
    <div class="max-w-4xl mx-auto pb-20">
        {{-- Back Button --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-zinc-500 hover:text-white transition mb-8 group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Back to Feed</span>
        </a>

        {{-- Main Post Content --}}
        <article class="forum-card p-8 lg:p-12 mb-10 shadow-2xl border-amber-600/20">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name=NCS+H&background=991b1b&color=fff" class="w-12 h-12 rounded-2xl">
                    <div>
                        <h1 class="text-sm font-bold text-white">{{ $post['author'] }} <span class="stat-badge text-[8px] px-2 py-0.5 rounded ml-2 font-black uppercase">Official</span></h1>
                        <p class="text-[10px] text-zinc-500 font-mono uppercase tracking-widest">{{ $post['time'] }} in {{ $post['category'] }}</p>
                    </div>
                </div>
                <button class="btn-vault px-6 py-2 text-[10px] uppercase font-black flex items-center gap-2">
                    <i class="fa-solid fa-download"></i> Download Stems
                </button>
            </div>

            <h1 class="font-brand text-3xl lg:text-4xl font-black text-white mb-6 leading-tight italic">
                {{ $post['title'] }}
            </h1>

            <div class="prose prose-invert max-w-none text-zinc-300 leading-relaxed mb-10">
                <p>{{ $post['content'] }}</p>
            </div>

            {{-- Post Media Display --}}
            <div class="rounded-[32px] overflow-hidden border border-zinc-800 mb-10">
                <img src="https://images.unsplash.com/photo-1493225255756-d9584f8606e9?auto=format&fit=crop&w=1200&q=80" class="w-full h-auto object-cover opacity-80">
            </div>

            <div class="flex items-center gap-8 py-6 border-t border-zinc-900">
                <button class="flex items-center gap-2 text-zinc-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-heart"></i> <span class="text-xs font-bold">{{ $post['likes'] }} Likes</span>
                </button>
                <button class="flex items-center gap-2 text-zinc-400 hover:text-amber-500 transition">
                    <i class="fa-solid fa-share-nodes"></i> <span class="text-xs font-bold">Share Stems</span>
                </button>
            </div>
        </article>

        {{-- Reply / Comment Input --}}
        <section class="mb-12">
            <h3 class="font-brand text-xl font-bold mb-6 text-white uppercase tracking-tight">Join Discussion</h3>
            <div class="forum-card p-6 bg-[#0a0a0c]">
                <textarea rows="4" placeholder="Share your feedback or technical question..."
                          class="w-full bg-black border border-zinc-800 rounded-2xl p-4 text-sm focus:border-amber-600 outline-none transition mb-4"></textarea>
                <div class="flex justify-end">
                    <button class="btn-vault px-8 py-3 text-[10px] uppercase font-black">Post Reply</button>
                </div>
            </div>
        </section>

        {{-- Replies List --}}
        <section class="space-y-6">
            <h3 class="text-xs font-black text-zinc-600 uppercase tracking-[0.2em] px-2">{{ $post['replies_count'] }} Community Replies</h3>

            @foreach($replies as $reply)
                <div class="flex gap-4 group">
                    <img src="https://ui-avatars.com/api/?name={{ $reply['author'] }}&background=random" class="w-10 h-10 rounded-xl flex-shrink-0">
                    <div class="flex-1 bg-zinc-900/30 border border-zinc-900 p-5 rounded-2xl group-hover:border-zinc-700 transition-all">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-amber-500">{{ $reply['author'] }}</span>
                            <span class="text-[9px] text-zinc-700 uppercase font-bold">{{ $reply['time'] }}</span>
                        </div>
                        <p class="text-sm text-zinc-400 leading-relaxed">{{ $reply['text'] }}</p>
                        <div class="mt-4 flex gap-4">
                            <button class="text-[10px] font-bold text-zinc-600 hover:text-red-500 transition"><i class="fa-solid fa-heart mr-1"></i> {{ $reply['likes'] }}</button>
                            <button class="text-[10px] font-bold text-zinc-600 hover:text-white transition">Reply</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    </div>
</x-webapp-layout>
