<x-webapp-layout>
    <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 forum-card p-10 bg-[#0a0a0c] border-amber-600/30 shadow-2xl">
            <div>
                <div class="w-16 h-16 bg-gradient-to-br from-[#b45309] to-[#991b1b] rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-red-900/40">
                    <i class="fa-solid fa-right-to-bracket text-white text-2xl"></i>
                </div>
                <h2 class="text-center text-3xl font-black font-brand italic text-white uppercase tracking-tighter">
                    Creator <span class="text-amber-500">Login</span>
                </h2>
                <p class="mt-2 text-center text-[10px] font-bold text-zinc-500 uppercase tracking-[0.3em]">
                    Access your NCS Hindi Studio
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Email Address</label>
                        <input name="email" type="email" required
                               class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1"
                               placeholder="producer@ncshindi.com">
                        @error('email') <p class="text-red-500 text-[10px] mt-1 ml-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Password</label>
                        <input name="password" type="password" required
                               class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 bg-black border-zinc-800 rounded text-amber-600 focus:ring-amber-600">
                        <label class="ml-2 block text-[10px] font-bold text-zinc-500 uppercase">Remember me</label>
                    </div>
                    <div class="text-[10px] font-bold uppercase">
                        <a href="#" class="text-amber-600 hover:text-amber-400">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full btn-vault py-4 text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-red-900/20">
                    Enter Vault
                </button>
            </form>

            <p class="text-center text-[10px] font-bold text-zinc-600 uppercase">
                New to the ecosystem?
                <a href="{{ route('register') }}" class="text-white hover:text-amber-500 ml-1">Join as Producer</a>
            </p>
        </div>
    </div>
</x-webapp-layout>
