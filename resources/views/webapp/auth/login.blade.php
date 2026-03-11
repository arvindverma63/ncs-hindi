<x-webapp-layout>
    <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-md w-full space-y-8 forum-card p-10 bg-[#0a0a0c] border border-amber-600/30 shadow-2xl rounded-[2rem]">
            {{-- Header Section --}}
            <div>
                <div
                    class="w-20 h-20 bg-gradient-to-br from-[#b45309] to-[#991b1b] rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-red-900/40 rotate-3 hover:rotate-0 transition-transform duration-500">
                    <i class="fa-brands fa-google text-white text-3xl"></i>
                </div>
                <h2 class="text-center text-3xl font-black font-brand italic text-white uppercase tracking-tighter">
                    Creator <span class="text-amber-500">Vault</span>
                </h2>
                <p class="mt-2 text-center text-[10px] font-bold text-zinc-500 uppercase tracking-[0.3em]">
                    One-Tap Secure Studio Access
                </p>
            </div>

            {{-- Action Section --}}
            <div class="mt-12 space-y-6">
                <p class="text-[9px] font-black text-zinc-700 text-center uppercase tracking-widest px-4">
                    Authorized for NCS Hindi Global Creators Only
                </p>

                <a href="{{ url('auth/google') }}"
                    class="flex items-center justify-center gap-4 w-full bg-white text-black hover:bg-zinc-200 py-4 px-6 rounded-2xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-xl shadow-white/5">
                    <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-6 h-6"
                        alt="Google">
                    <span class="text-xs font-black uppercase tracking-widest">Continue with Google</span>
                </a>

                {{-- Security Notice --}}
                <div class="pt-8 border-t border-zinc-900/50">
                    <div class="flex items-center justify-center gap-6 opacity-40 grayscale">
                        <i class="fa-solid fa-shield-halved text-zinc-500 text-xl"></i>
                        <i class="fa-solid fa-lock text-zinc-500 text-xl"></i>
                        <i class="fa-solid fa-user-shield text-zinc-500 text-xl"></i>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-[9px] font-bold text-zinc-600 uppercase tracking-widest leading-relaxed">
                By entering, you agree to our <br>
                <a href="#" class="text-zinc-400 hover:text-amber-500 underline decoration-zinc-800">Studio
                    Terms</a> &
                <a href="#" class="text-zinc-400 hover:text-amber-500 underline decoration-zinc-800">IP Policy</a>
            </p>
        </div>
    </div>
</x-webapp-layout>
