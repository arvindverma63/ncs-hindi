<x-webapp-layout>
    <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg w-full space-y-8 forum-card p-10 bg-[#0a0a0c] border-amber-600/30 shadow-2xl">
            <div class="text-center">
                <h2 class="text-3xl font-black font-brand italic text-white uppercase tracking-tighter">
                    Join the <span class="text-amber-500">Movement</span>
                </h2>
                <p class="mt-2 text-[10px] font-bold text-zinc-500 uppercase tracking-[0.3em]">
                    Create your producer profile
                </p>
            </div>

            <form class="mt-8 space-y-5" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Full
                            Name</label>
                        <input name="name" type="text" required
                            class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1"
                            placeholder="Aaryan Sharma">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Email</label>
                        <input name="email" type="email" required
                            class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1"
                            placeholder="producer@rawsio.com">
                    </div>
                    <div>
                        <label
                            class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Password</label>
                        <input name="password" type="password" required
                            class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1">
                    </div>
                    <div>
                        <label
                            class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Confirm</label>
                        <input name="password_confirmation" type="password" required
                            class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1">
                    </div>
                </div>

                <div class="p-4 bg-zinc-950 rounded-xl border border-zinc-900">
                    <p class="text-[9px] text-zinc-500 leading-relaxed font-medium">
                        By registering, you agree to the <span class="text-amber-600">NCS Hindi Usage Policy</span>.
                        Your account will start at <span class="text-white">Level 1</span> with access to public stems.
                    </p>
                </div>

                <button type="submit" class="w-full btn-vault py-4 text-xs font-black uppercase tracking-[0.2em]">
                    Initialize Studio
                </button>
            </form>

            <p class="text-center text-[10px] font-bold text-zinc-600 uppercase">
                Already have a studio?
                <a href="{{ route('login') }}" class="text-white hover:text-amber-500 ml-1">Login here</a>
            </p>
        </div>
    </div>
</x-webapp-layout>
