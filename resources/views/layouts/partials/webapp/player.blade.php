{{-- Global Web Audio Player Bar --}}
<div id="ncsGlobalPlayer" class="fixed bottom-16 lg:bottom-0 left-0 right-0 z-[160] transform translate-y-full opacity-0 pointer-events-none transition-all duration-500 ease-out">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 pb-2 sm:pb-4">
        <div class="relative bg-zinc-950/95 border border-zinc-800/90 backdrop-blur-2xl rounded-2xl sm:rounded-3xl p-3 sm:p-4 shadow-2xl shadow-black/90 flex flex-col md:flex-row items-center justify-between gap-3 sm:gap-6 pointer-events-auto">
            
            {{-- Track Info --}}
            <div class="flex items-center gap-3.5 w-full md:w-1/4 shrink-0">
                <div class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-xl overflow-hidden bg-zinc-900 border border-zinc-800 shrink-0">
                    <img id="playerCoverImg" src="" class="w-full h-full object-cover hidden" alt="Track Cover">
                    <div id="playerCoverFallback" class="w-full h-full flex items-center justify-center text-amber-500">
                        <i class="fa-solid fa-music text-lg"></i>
                    </div>
                    {{-- Playing indicator wave --}}
                    <div id="playerPlayingIndicator" class="absolute inset-0 bg-black/50 backdrop-blur-[1px] hidden items-center justify-center gap-0.5">
                        <span class="w-1 bg-amber-500 rounded-full animate-[bounce_1s_infinite_100ms] h-4"></span>
                        <span class="w-1 bg-amber-500 rounded-full animate-[bounce_1s_infinite_300ms] h-6"></span>
                        <span class="w-1 bg-amber-500 rounded-full animate-[bounce_1s_infinite_200ms] h-3"></span>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 text-[8px] font-black uppercase tracking-wider border border-amber-500/20">Now Playing</span>
                    </div>
                    <h5 id="playerTrackTitle" class="text-xs sm:text-sm font-black text-white uppercase tracking-tight truncate mt-0.5">Track Title</h5>
                    <p id="playerTrackArtist" class="text-[10px] sm:text-xs text-zinc-400 font-semibold truncate">Artist Name</p>
                </div>
            </div>

            {{-- Controls & Progress Slider --}}
            <div class="flex-1 w-full max-w-2xl flex flex-col items-center gap-1.5">
                <div class="flex items-center gap-5">
                    {{-- Rewind 10s --}}
                    <button type="button" id="playerRewindBtn" class="text-zinc-400 hover:text-white transition text-xs sm:text-sm" title="Rewind 10s">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>

                    {{-- Main Play / Pause Button --}}
                    <button type="button" id="playerPlayPauseBtn" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/25 transition transform active:scale-95">
                        <i id="playerPlayIcon" class="fa-solid fa-play text-base sm:text-lg ml-0.5"></i>
                    </button>

                    {{-- Forward 10s --}}
                    <button type="button" id="playerForwardBtn" class="text-zinc-400 hover:text-white transition text-xs sm:text-sm" title="Forward 10s">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>

                {{-- Time Slider --}}
                <div class="w-full flex items-center gap-3">
                    <span id="playerCurrentTime" class="text-[10px] font-mono font-bold text-zinc-400 min-w-[32px] text-right">0:00</span>
                    <div class="relative flex-1 group cursor-pointer">
                        <input type="range" id="playerProgress" min="0" max="100" value="0" step="0.1" class="w-full h-1.5 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-amber-500 group-hover:h-2 transition-all">
                    </div>
                    <span id="playerDuration" class="text-[10px] font-mono font-bold text-zinc-400 min-w-[32px]">0:00</span>
                </div>
            </div>

            {{-- Right Controls: Volume & Close --}}
            <div class="hidden md:flex items-center justify-end gap-3 w-1/4">
                <div class="flex items-center gap-2 bg-zinc-900/80 px-3 py-1.5 rounded-xl border border-zinc-800">
                    <button type="button" id="playerMuteBtn" class="text-zinc-400 hover:text-amber-400 transition text-xs">
                        <i id="playerVolumeIcon" class="fa-solid fa-volume-high"></i>
                    </button>
                    <input type="range" id="playerVolume" min="0" max="1" step="0.05" value="0.8" class="w-16 h-1 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-amber-500">
                </div>

                <button type="button" id="playerCloseBtn" class="w-8 h-8 rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-800 transition flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Hidden Audio Elements --}}
            <audio id="globalNcsAudio" preload="auto" class="hidden"></audio>
            <div id="youtubeAudioHost" class="hidden"></div>
        </div>
    </div>
</div>
