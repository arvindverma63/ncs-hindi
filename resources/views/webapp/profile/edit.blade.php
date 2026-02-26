<x-webapp-layout>
    <div class="max-w-3xl mx-auto pb-24">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('webapp.profile') }}" class="w-10 h-10 rounded-xl bg-zinc-900 flex items-center justify-center text-zinc-500 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="font-brand text-2xl font-black text-white uppercase italic tracking-tighter">Edit <span class="text-amber-500">Studio</span></h1>
        </div>

        <form action="{{ route('webapp.profile.update') }}" method="POST" class="forum-card p-8 lg:p-12 space-y-8 bg-[#0a0a0c]">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Studio Name --}}
                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Studio / Artist Name</label>
                    <input name="studio_name" type="text" value="{{ $profile->studio_name }}" required
                           class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1">
                </div>

                {{-- Bio --}}
                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Studio Bio</label>
                    <textarea name="bio" rows="4"
                              class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1">{{ $profile->bio }}</textarea>
                </div>

                {{-- Website --}}
                <div>
                    <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Portfolio URL</label>
                    <input name="website_url" type="url" value="{{ $profile->website_url }}"
                           class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1" placeholder="https://...">
                </div>

                {{-- Instagram --}}
                <div>
                    <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Instagram Handle</label>
                    <input name="instagram_url" type="text" value="{{ $profile->instagram_url }}"
                           class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1" placeholder="@username">
                </div>
            </div>

            <div class="pt-6 border-t border-zinc-900 flex justify-end gap-4">
                <a href="{{ route('webapp.profile') }}" class="px-8 py-3 rounded-xl border border-zinc-800 text-[10px] font-bold text-zinc-500 hover:text-white transition uppercase">Cancel</a>
                <button type="submit" class="btn-vault px-10 py-3 text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-900/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-webapp-layout>
