{{-- Global Web Audio Player Bar --}}
<style>
    /* Default / Dark Mode Player Bar Styles */
    html body #ncsGlobalPlayer .player-card {
        background-color: rgba(9, 9, 11, 0.96) !important;
        border-color: rgba(39, 39, 42, 0.9) !important;
        color: #ffffff !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9) !important;
    }

    html body #ncsGlobalPlayer h5,
    html body #ncsGlobalPlayer #playerTrackTitle {
        color: #ffffff !important;
    }

    html body #ncsGlobalPlayer p,
    html body #ncsGlobalPlayer #playerTrackArtist {
        color: #cbd5e1 !important;
    }

    html body #ncsGlobalPlayer #playerRewindBtn,
    html body #ncsGlobalPlayer #playerForwardBtn,
    html body #ncsGlobalPlayer #playerMuteBtn {
        color: #cbd5e1 !important;
        background: transparent !important;
    }

    html body #ncsGlobalPlayer #playerRewindBtn:hover,
    html body #ncsGlobalPlayer #playerForwardBtn:hover,
    html body #ncsGlobalPlayer #playerMuteBtn:hover {
        color: #f59e0b !important;
    }

    html body #ncsGlobalPlayer #playerCurrentTime,
    html body #ncsGlobalPlayer #playerDuration {
        color: #cbd5e1 !important;
    }

    html body #ncsGlobalPlayer #playerCloseBtn {
        background-color: rgba(24, 24, 27, 0.9) !important;
        border-color: rgba(39, 39, 42, 0.9) !important;
        color: #cbd5e1 !important;
    }

    html body #ncsGlobalPlayer #playerCloseBtn:hover {
        background-color: rgba(39, 39, 42, 1) !important;
        color: #ffffff !important;
    }

    html body #ncsGlobalPlayer .player-subpanel {
        background-color: rgba(24, 24, 27, 0.9) !important;
        border-color: rgba(39, 39, 42, 0.9) !important;
    }

    html body #ncsGlobalPlayer #playerProgress,
    html body #ncsGlobalPlayer #playerVolume {
        accent-color: #f59e0b !important;
        background-color: #27272a !important;
    }

    /* Light Mode Adaptive Player Bar Styles */
    html.light body #ncsGlobalPlayer .player-card {
        background-color: rgba(255, 255, 255, 0.96) !important;
        border-color: rgba(226, 232, 240, 0.9) !important;
        color: #0f172a !important;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.12) !important;
    }

    html.light body #ncsGlobalPlayer h5,
    html.light body #ncsGlobalPlayer #playerTrackTitle {
        color: #0f172a !important;
    }

    html.light body #ncsGlobalPlayer p,
    html.light body #ncsGlobalPlayer #playerTrackArtist {
        color: #475569 !important;
    }

    html.light body #ncsGlobalPlayer #playerRewindBtn,
    html.light body #ncsGlobalPlayer #playerForwardBtn,
    html.light body #ncsGlobalPlayer #playerMuteBtn {
        color: #475569 !important;
        background: transparent !important;
    }

    html.light body #ncsGlobalPlayer #playerRewindBtn:hover,
    html.light body #ncsGlobalPlayer #playerForwardBtn:hover,
    html.light body #ncsGlobalPlayer #playerMuteBtn:hover {
        color: #d97706 !important;
    }

    html.light body #ncsGlobalPlayer #playerCurrentTime,
    html.light body #ncsGlobalPlayer #playerDuration {
        color: #475569 !important;
    }

    html.light body #ncsGlobalPlayer #playerCloseBtn {
        background-color: #f1f5f9 !important;
        border-color: #e2e8f0 !important;
        color: #475569 !important;
    }

    html.light body #ncsGlobalPlayer #playerCloseBtn:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }

    html.light body #ncsGlobalPlayer .player-subpanel {
        background-color: #f1f5f9 !important;
        border-color: #e2e8f0 !important;
    }

    html.light body #ncsGlobalPlayer #playerProgress,
    html.light body #ncsGlobalPlayer #playerVolume {
        accent-color: #f59e0b !important;
        background-color: #cbd5e1 !important;
    }
</style>

<div id="ncsGlobalPlayer" class="fixed bottom-16 lg:bottom-0 left-0 right-0 z-[160] transform translate-y-full opacity-0 pointer-events-none transition-all duration-500 ease-out">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 pb-2 sm:pb-4">
        <div class="relative player-card border backdrop-blur-2xl rounded-2xl sm:rounded-3xl p-3 sm:p-4 flex flex-col md:flex-row items-center justify-between gap-3 sm:gap-6 pointer-events-auto">
            
            {{-- Track Info --}}
            <div class="flex items-center gap-3.5 w-full md:w-1/4 shrink-0">
                <div class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-xl overflow-hidden player-subpanel border shrink-0">
                    <img id="playerCoverImg" src="" class="w-full h-full object-cover hidden" alt="Track Cover">
                    <div id="playerCoverFallback" class="w-full h-full flex items-center justify-center text-amber-500">
                        <i class="fa-solid fa-music text-lg"></i>
                    </div>
                    {{-- Playing indicator wave --}}
                    <div id="playerPlayingIndicator" class="absolute inset-0 bg-black/60 backdrop-blur-[1px] hidden items-center justify-center gap-0.5">
                        <span class="w-1 bg-amber-500 rounded-full animate-[bounce_1s_infinite_100ms] h-4"></span>
                        <span class="w-1 bg-amber-500 rounded-full animate-[bounce_1s_infinite_300ms] h-6"></span>
                        <span class="w-1 bg-amber-500 rounded-full animate-[bounce_1s_infinite_200ms] h-3"></span>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-500 text-[8px] font-black uppercase tracking-wider border border-amber-500/30">Now Playing</span>
                    </div>
                    <h5 id="playerTrackTitle" class="text-xs sm:text-sm font-black uppercase tracking-tight truncate mt-0.5">Track Title</h5>
                    <p id="playerTrackArtist" class="text-[10px] sm:text-xs font-semibold truncate">Artist Name</p>
                </div>
            </div>

            {{-- Controls & Progress Slider --}}
            <div class="flex-1 w-full max-w-2xl flex flex-col items-center gap-1.5">
                <div class="flex items-center gap-5">
                    {{-- Rewind 10s --}}
                    <button type="button" id="playerRewindBtn" class="transition text-xs sm:text-sm p-1" title="Rewind 10s">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>

                    {{-- Main Play / Pause Button --}}
                    <button type="button" id="playerPlayPauseBtn" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/25 transition transform active:scale-95 border-0">
                        <i id="playerPlayIcon" class="fa-solid fa-play text-base sm:text-lg ml-0.5"></i>
                    </button>

                    {{-- Forward 10s --}}
                    <button type="button" id="playerForwardBtn" class="transition text-xs sm:text-sm p-1" title="Forward 10s">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>

                {{-- Time Slider --}}
                <div class="w-full flex items-center gap-3">
                    <span id="playerCurrentTime" class="text-[10px] font-mono font-bold min-w-[36px] text-right">0:00</span>
                    <div class="relative flex-1 group cursor-pointer">
                        <input type="range" id="playerProgress" min="0" max="100" value="0" step="0.1" class="w-full h-1.5 rounded-lg appearance-none cursor-pointer group-hover:h-2 transition-all">
                    </div>
                    <span id="playerDuration" class="text-[10px] font-mono font-bold min-w-[36px]">0:00</span>
                </div>
            </div>

            {{-- Right Controls: Volume & Close --}}
            <div class="hidden md:flex items-center justify-end gap-3 w-1/4">
                <div class="flex items-center gap-2 player-subpanel px-3 py-1.5 rounded-xl border">
                    <button type="button" id="playerMuteBtn" class="transition text-xs">
                        <i id="playerVolumeIcon" class="fa-solid fa-volume-high"></i>
                    </button>
                    <input type="range" id="playerVolume" min="0" max="1" step="0.05" value="0.8" class="w-16 h-1 rounded-lg appearance-none cursor-pointer">
                </div>

                <button type="button" id="playerCloseBtn" class="w-8 h-8 rounded-xl border transition flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Hidden Audio Elements --}}
            <audio id="globalNcsAudio" preload="auto" class="hidden"></audio>
            <div id="youtubeAudioHost" class="hidden"></div>
        </div>
    </div>
</div>
