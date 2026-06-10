<nav class="lg:hidden fixed bottom-4 left-3 right-3 md:bottom-6 md:left-4 md:right-4 h-16 md:h-20 bg-zinc-950/85 border border-zinc-800/50 rounded-[2rem] md:rounded-[2.5rem] px-1 md:px-2 flex items-center justify-around z-[100] backdrop-blur-2xl shadow-2xl shadow-black/50">

    {{-- Feeds Feed --}}
    <a href="{{ route('home') }}"
        aria-label="Thread Feed"
        class="flex flex-col items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl transition-all duration-300 {{ request()->routeIs('home') ? 'text-amber-500 bg-amber-500/10' : 'text-zinc-400' }}">
        <i class="fa-solid fa-layer-group text-lg md:text-xl"></i>
        <span class="text-[6px] md:text-[7px] font-black uppercase tracking-[0.15em] mt-0.5 md:mt-1">Feeds</span>
    </a>

    {{-- Community Chat --}}
    <a href="{{ route('webapp.community.chat') }}"
        aria-label="Community Chat"
        class="flex flex-col items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl transition-all duration-300 {{ request()->routeIs('webapp.community.chat*') ? 'text-amber-500 bg-amber-500/10' : 'text-zinc-400' }}">
        <div class="relative">
            <i class="fa-solid fa-comments text-lg md:text-xl"></i>
            {{-- Optional Notification Dot --}}
            <span class="absolute -top-0.5 -right-0.5 w-1.5 h-1.5 md:w-2 md:h-2 bg-red-500 rounded-full border border-zinc-955"></span>
        </div>
        <span class="text-[6px] md:text-[7px] font-black uppercase tracking-[0.15em] mt-0.5 md:mt-1">Chat</span>
    </a>

    {{-- Floating Center Action: Create --}}
    <div class="relative -mt-10 md:-mt-14">
        <a href="{{ route('webapp.forum.create') }}"
           aria-label="Create New Thread"
           class="flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-amber-500 via-amber-600 to-red-600 rounded-2xl md:rounded-[22px] shadow-[0_8px_24px_rgba(245,158,11,0.3)] border-[4px] md:border-[5px] border-[#08080a] text-white active:scale-90 transition-all duration-300">
            <i class="fa-solid fa-plus text-lg md:text-2xl"></i>
        </a>
    </div>

    {{-- Audio Library --}}
    <a href="{{ route('webapp.streams') }}"
        aria-label="Audio Library"
        class="flex flex-col items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl transition-all duration-300 {{ request()->routeIs('webapp.streams') ? 'text-amber-500 bg-amber-500/10' : 'text-zinc-400' }}">
        <i class="fa-solid fa-box-open text-lg md:text-xl"></i>
        <span class="text-[6px] md:text-[7px] font-black uppercase tracking-[0.15em] mt-0.5 md:mt-1">Audio</span>
    </a>

    {{-- User Profile --}}
    <a href="{{ route('webapp.profile') }}"
        aria-label="User Profile"
        class="flex flex-col items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl transition-all duration-300 {{ request()->routeIs('webapp.profile') ? 'text-amber-500 bg-amber-500/10' : 'text-zinc-400' }}">
        @auth
            @php
                $userAvatar = Auth::user()->profile_image && strlen(Auth::user()->profile_image) > 20
                    ? Auth::user()->profile_image
                    : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=18181b&color=f59e0b&bold=true';
            @endphp
            <img src="{{ $userAvatar }}"
                 class="w-5 h-5 md:w-6 md:h-6 rounded-md md:rounded-lg object-cover {{ request()->routeIs('webapp.profile') ? 'ring-1.5 md:ring-2 ring-amber-500' : 'opacity-70' }}"
                 alt="Profile">
        @else
            <i class="fa-solid fa-user-circle text-lg md:text-xl"></i>
        @endauth
        <span class="text-[6px] md:text-[7px] font-black uppercase tracking-[0.15em] mt-0.5 md:mt-1">Profile</span>
    </a>
</nav>


<div id="notificationGateModal"
    class="fixed inset-0 z-[200] hidden items-center justify-center px-4 py-6 bg-black/70 backdrop-blur-sm">
    <div class="absolute inset-0" data-notification-dismiss></div>
    <div class="relative w-full max-w-lg rounded-[28px] border border-zinc-800 bg-[#0f0f12] text-white shadow-2xl shadow-black/50 overflow-hidden">
        <div class="flex items-start justify-between gap-4 border-b border-zinc-800 px-4 md:px-5 py-4">
            <div>
                <p class="text-[10px] uppercase tracking-[0.25em] text-amber-400 font-black">Notifications</p>
                <h5 class="font-brand text-2xl font-black uppercase tracking-tight">Stay updated</h5>
            </div>
            <button type="button" class="text-zinc-400 hover:text-white transition" data-notification-dismiss aria-label="Close">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-4 md:p-5">
            <div class="rounded-[24px] bg-black/40 border border-zinc-800 p-4 md:p-5">
                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bell text-amber-400"></i>
                    </div>
                    <div class="min-w-0">
                        <h6 id="notificationGateTitle" class="text-lg font-black text-white uppercase tracking-tight">
                            Get release alerts
                        </h6>
                        <p id="notificationGateDescription" class="mt-2 text-sm text-zinc-400 leading-relaxed">
                            Allow notifications so you can get updates when new music is added.
                        </p>
                        <p id="notificationGateMusic" class="mt-3 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"></p>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                <button type="button" id="notificationGateAllow"
                    class="btn-vault flex-1 px-4 py-3 rounded-2xl text-[10px] font-black tracking-[0.2em] uppercase">
                    Allow notifications
                </button>
                <button type="button" id="notificationGateContinue"
                    class="flex-1 px-4 py-3 rounded-2xl bg-white/5 border border-zinc-800 text-[10px] font-black tracking-[0.2em] uppercase text-zinc-200 hover:border-amber-500/40 hover:text-white transition">
                    Continue
                </button>
            </div>

            <button type="button" id="notificationGateLater"
                class="mt-3 w-full text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 hover:text-zinc-300 transition">
                Not now
            </button>
        </div>
    </div>
</div>

<div id="shareMusicModal"
    class="fixed inset-0 z-[200] hidden items-center justify-center px-4 py-6 bg-black/70 backdrop-blur-sm">
    <div class="absolute inset-0" data-share-dismiss></div>
    <div
        class="relative w-full max-w-2xl rounded-[28px] border border-zinc-800 bg-[#101014] text-white shadow-2xl shadow-black/50 overflow-hidden">
        <div class="flex items-start justify-between gap-4 border-b border-zinc-800 px-4 md:px-5 py-4">
            <div>
                <p class="text-[10px] uppercase tracking-[0.25em] text-amber-400 font-black">Share music</p>
                <h5 class="font-brand text-2xl font-black uppercase tracking-tight">Spread the track</h5>
            </div>
            <button type="button" class="text-zinc-400 hover:text-white transition" data-share-dismiss
                aria-label="Close">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-4 md:p-5">
            <div class="rounded-[24px] bg-black/40 border border-zinc-800 p-4 md:p-5 mb-4">
                <p class="text-[10px] uppercase tracking-[0.2em] text-zinc-500 font-black mb-2">Release</p>
                <h6 id="shareMusicTitle"
                    class="text-lg md:text-xl font-black text-white uppercase tracking-tight"></h6>
                <p id="shareMusicUrl" class="mt-2 text-xs text-zinc-500 break-all"></p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="#" target="_blank" rel="noopener" data-share-channel="whatsapp"
                    class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-green-500/50 hover:bg-green-500/10 transition">
                    <i class="fa-brands fa-whatsapp text-lg text-green-500"></i>
                    <span
                        class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">WhatsApp</span>
                </a>
                <a href="#" target="_blank" rel="noopener" data-share-channel="x"
                    class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-white/30 hover:bg-white/5 transition">
                    <i class="fa-brands fa-x-twitter text-lg text-white"></i>
                    <span
                        class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">X</span>
                </a>
                <a href="#" target="_blank" rel="noopener" data-share-channel="facebook"
                    class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-blue-500/50 hover:bg-blue-500/10 transition">
                    <i class="fa-brands fa-facebook text-lg text-blue-500"></i>
                    <span
                        class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Facebook</span>
                </a>
                <a href="#" target="_blank" rel="noopener" data-share-channel="telegram"
                    class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-sky-500/50 hover:bg-sky-500/10 transition">
                    <i class="fa-brands fa-telegram text-lg text-sky-400"></i>
                    <span
                        class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Telegram</span>
                </a>
                <a href="#" target="_blank" rel="noopener" data-share-channel="linkedin"
                    class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-blue-400/50 hover:bg-blue-400/10 transition">
                    <i class="fa-brands fa-linkedin text-lg text-blue-400"></i>
                    <span
                        class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">LinkedIn</span>
                </a>
                <a href="#" target="_blank" rel="noopener" data-share-channel="reddit"
                    class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-orange-500/50 hover:bg-orange-500/10 transition">
                    <i class="fa-brands fa-reddit text-lg text-orange-500"></i>
                    <span
                        class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Reddit</span>
                </a>
                <a href="#" target="_blank" rel="noopener" data-share-channel="email"
                    class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-amber-500/50 hover:bg-amber-500/10 transition">
                    <i class="fa-solid fa-envelope text-lg text-amber-400"></i>
                    <span
                        class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Email</span>
                </a>
                <button type="button" data-share-copy
                    class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-amber-500/50 hover:bg-amber-500/10 transition">
                    <i class="fa-solid fa-link text-lg text-amber-400"></i>
                    <span class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Copy
                        link</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="fullScreenAdModal"
    class="fixed inset-0 z-[300] hidden flex-col items-center justify-center bg-[#050507] text-white p-6">
    {{-- Background glowing elements --}}
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/10 blur-[120px] rounded-full"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-red-600/10 blur-[120px] rounded-full"></div>
    
    <div class="relative w-full max-w-2xl px-4 flex flex-col items-center text-center">
        {{-- Close / Skip button --}}
        <div class="absolute top-0 right-0 z-10">
            <button type="button" id="skipAdBtn" disabled
                class="px-5 py-2.5 bg-zinc-900 border border-zinc-800 rounded-full text-xs font-black uppercase tracking-widest text-zinc-500 transition duration-300">
                Wait <span id="adCountdown">5</span>s
            </button>
        </div>

        <div class="mb-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/10 border border-amber-500/20 rounded-full text-[10px] font-black uppercase tracking-widest text-amber-500 mb-6">
                <i class="fa-solid fa-rectangle-ad text-xs"></i> Sponsored Advertisement
            </div>
        </div>

        {{-- Simulated Interactive Ad --}}
        <div class="w-full aspect-[16/9] rounded-3xl border border-zinc-800 bg-zinc-900/40 backdrop-blur-md overflow-hidden relative mb-8 shadow-2xl flex flex-col items-center justify-center p-6 sm:p-10">
            <div class="absolute inset-0 bg-cover bg-center filter blur-[2px] opacity-20" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80');"></div>
            
            <div class="relative z-10">
                <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-[0_0_30px_rgba(245,158,11,0.4)]">
                    <i class="fa-solid fa-gamepad text-black text-xl"></i>
                </div>
                <h3 class="font-brand text-xl sm:text-2xl font-black uppercase tracking-tight text-white mb-2">
                    NCS Premium Arcade
                </h3>
                <p class="text-zinc-400 text-xs max-w-md mx-auto leading-relaxed mb-6">
                    Play mini games, earn exclusive NCS Credits, and view your points history. Join the rhythm challenge today!
                </p>
                <a href="{{ route('game') }}" target="_blank" id="adLinkButton" class="inline-flex items-center gap-2 btn-vault px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">
                    Play Games <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div>
            <h4 id="adStatusText" class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-500 mb-2">Preparing your download...</h4>
            <p class="text-[9px] text-zinc-600 uppercase tracking-widest">Your download will start in a new tab automatically once active.</p>
        </div>
    </div>
</div>







