<x-webapp-layout>
    <div class="max-w-4xl mx-auto pb-24">
        {{-- Navigation Header --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-zinc-500 hover:text-white transition group">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Back to Feed</span>
            </a>
            <div class="flex gap-2">
                <span
                    class="px-3 py-1 rounded-full bg-zinc-900 border border-zinc-800 text-[9px] font-bold text-zinc-400 uppercase tracking-tighter">
                    {{ $post->category->name ?? 'Uncategorized' }}
                </span>
            </div>
        </div>

        {{-- Main Post Content --}}
        <article class="forum-card p-8 lg:p-12 bg-[#0a0a0c] mb-12">
            <header class="mb-10">
                <h1
                    class="font-brand text-4xl font-black text-white italic uppercase tracking-tighter leading-none mb-6">
                    {{ $post->title }}
                </h1>

                <div class="flex items-center gap-4 p-4 bg-zinc-950 rounded-2xl border border-zinc-900">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&background=b45309&color=fff"
                        class="w-12 h-12 rounded-xl border border-white/5 shadow-xl">
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-white">{{ $post->author->name }}</h4>
                        <p class="text-[10px] text-zinc-600 font-bold uppercase tracking-widest">
                            {{ $post->author->profile->rank_title ?? 'Artist' }} •
                            {{ $post->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-center">
                            <p class="text-xs font-black text-amber-500">{{ $post->bpm ?? '--' }}</p>
                            <p class="text-[8px] text-zinc-700 font-bold uppercase">BPM</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-black text-red-600">{{ $post->music_key ?? '--' }}</p>
                            <p class="text-[8px] text-zinc-700 font-bold uppercase">Key</p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Quill Content Rendering --}}
            <div class="prose prose-invert prose-amber max-w-none text-zinc-300 leading-relaxed font-medium mb-10">
                {!! $post->content !!}
            </div>

            {{-- Stem Download Section --}}
            @if ($post->stems->count() > 0 || true)
                {{-- Fallback for UI preview --}}
                <div
                    class="bg-zinc-950 rounded-[32px] p-8 border border-zinc-800 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div
                            class="w-20 h-20 rounded-[24px] bg-gradient-to-br from-red-600 to-amber-700 flex items-center justify-center shadow-2xl shadow-red-900/40">
                            <i class="fa-solid fa-file-zipper text-3xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white uppercase italic tracking-tighter font-brand">
                                Master_Stems_Pack.zip</h3>
                            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">High-Quality
                                WAV Files • 42 MB</p>
                        </div>
                    </div>
                    <button
                        class="btn-vault px-10 py-4 text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-red-900/30 w-full md:w-auto">
                        Download Stems
                    </button>
                </div>
            @endif
        </article>

        {{-- Discussion Section --}}
        <section class="space-y-8">
            <h3 class="font-brand text-xl font-bold italic text-white uppercase tracking-tight px-2">
                Discussion <span class="text-zinc-700 ml-2">{{ $post->replies->count() }}</span>
            </h3>

            {{-- Reply Form --}}
            @auth
                <form action="#" class="forum-card p-6 bg-[#0a0a0c]">
                    <textarea rows="3"
                        class="w-full bg-black border border-zinc-800 rounded-2xl p-4 text-sm text-zinc-300 focus:border-amber-600 outline-none transition"
                        placeholder="Add to the conversation..."></textarea>
                    <div class="flex justify-end mt-4">
                        <button class="btn-vault px-8 py-2.5 text-[10px] font-black uppercase tracking-widest">Post
                            Reply</button>
                    </div>
                </form>
            @endauth

            {{-- Replies List --}}
            @foreach ($post->replies as $reply)
                <div class="flex gap-4 group">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=27272a&color=a1a1aa"
                        class="w-10 h-10 rounded-xl flex-shrink-0">
                    <div
                        class="flex-1 bg-zinc-900/30 border border-zinc-800/50 rounded-3xl p-6 group-hover:border-zinc-700 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="text-xs font-bold text-white">{{ $reply->user->name }}</h5>
                            <span
                                class="text-[9px] font-bold text-zinc-600 uppercase">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-zinc-400 font-medium">{{ $reply->content }}</p>
                    </div>
                </div>
            @endforeach
        </section>
    </div>
</x-webapp-layout>
