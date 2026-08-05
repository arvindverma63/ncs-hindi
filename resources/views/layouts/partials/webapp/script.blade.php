<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
@if(request()->routeIs('webapp.community.chat*'))
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.4/dist/echo.iife.js"></script>
@endif
<script defer src="https://www.gstatic.com/firebasejs/10.12.4/firebase-app-compat.js"></script>
<script defer src="https://www.gstatic.com/firebasejs/10.12.4/firebase-messaging-compat.js"></script>
<script>
    window.ncsAdServiceWorkerUrl = @json(route('ads.sw'));
</script>

@stack('scripts')
<script>
    // Initialize Laravel Echo for WebSocket communication
    @if(request()->routeIs('webapp.community.chat*') && config('broadcasting.default') !== 'null' && config('broadcasting.default') !== 'log')
        @if(config('broadcasting.default') === 'pusher')
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: "{{ config('broadcasting.connections.pusher.key') }}",
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                forceTLS: true,
                encrypted: true
            });
        @elseif(config('broadcasting.default') === 'reverb')
            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: "{{ config('broadcasting.connections.reverb.key') }}",
                wsHost: "{{ config('broadcasting.connections.reverb.options.host') }}",
                wsPort: "{{ config('broadcasting.connections.reverb.options.port') }}",
                wssPort: "{{ config('broadcasting.connections.reverb.options.port') }}",
                forceTLS: "{{ config('broadcasting.connections.reverb.options.useTLS') }}",
                encrypted: true,
                disableStats: true,
            });
        @endif
    @else
        // For log or null drivers, or non-chat routes, create a mock Echo object
        window.Echo = {
            channel: function(name) {
                return {
                    listen: function(event, callback) {
                        console.log('Echo.channel listening for', event, 'on', name);
                        return this;
                    },
                    on: function(event, callback) {
                        console.log('Echo.on listening for', event, 'on', name);
                        return this;
                    }
                };
            }
        };
        console.log('Broadcasting is inactive on this page.');
    @endif

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    });

    @php
        $firebaseConfig = array_filter([
            'apiKey' => config('services.firebase.api_key'),
            'authDomain' => config('services.firebase.auth_domain'),
            'projectId' => config('services.firebase.project_id'),
            'storageBucket' => config('services.firebase.storage_bucket'),
            'messagingSenderId' => config('services.firebase.messaging_sender_id'),
            'appId' => config('services.firebase.app_id'),
        ]);
    @endphp
    window.ncsFirebaseConfig = @json($firebaseConfig);
    window.ncsFirebaseVapidKey = @json(config('services.firebase.vapid_key'));
    window.ncsFirebaseMessagingSaveUrl = @json(route('webapp.notifications.fcm'));
    window.ncsFirebaseServiceWorkerUrl = @json(route('firebase.messaging-sw'));
    console.log('[NCS FCM] Web config', window.ncsFirebaseConfig);
    console.log('[NCS FCM] VAPID key present:', !!window.ncsFirebaseVapidKey);
    console.log('[NCS FCM] Save URL:', window.ncsFirebaseMessagingSaveUrl);
    console.log('[NCS FCM] SW URL:', window.ncsFirebaseServiceWorkerUrl);

    async function registerAdsServiceWorker() {
        if (!('serviceWorker' in navigator)) {
            return null;
        }

        const swUrl = window.ncsAdServiceWorkerUrl || '/sw.js';

        try {
            const registration = await navigator.serviceWorker.register(swUrl, {
                scope: '/',
            });

            console.log('[NCS Ads] Service worker registered', swUrl);
            return registration;
        } catch (error) {
            console.warn('[NCS Ads] Service worker registration failed', error);
            return null;
        }
    }

    const notificationGateKey = 'ncs-notification-gate-seen';
    const notificationPromptKey = 'ncs-notification-prompt-dismissed';
    const notificationModalEl = document.getElementById('notificationGateModal');
    const shareModalEl = document.getElementById('shareMusicModal');
    let firebaseAppInstance = null;
    let firebaseMessagingInstance = null;
    let firebaseWorkerRegistration = null;
    let notificationGateContext = {
        title: 'Get release alerts',
        description: 'Allow notifications so you can get updates when new music is added.',
        music: '',
        actionUrl: '',
        actionLabel: 'Continue',
        actionType: 'continue',
    };
    let shareMusicContext = {
        title: '',
        url: '',
    };

    function refreshNotificationGateModal(context = {}) {
        notificationGateContext = {
            ...notificationGateContext,
            ...context,
        };

        $('#notificationGateTitle').text(notificationGateContext.title);
        $('#notificationGateDescription').text(notificationGateContext.description);
        $('#notificationGateMusic').text(notificationGateContext.music || '');
        $('#notificationGateContinue').text(notificationGateContext.actionLabel || 'Continue');
        $('#notificationGateContinue').data('action-url', notificationGateContext.actionUrl || '');
        $('#notificationGateContinue').data('action-type', notificationGateContext.actionType || 'continue');
        $('#notificationGateContinue').data('music-id', notificationGateContext.stemId || '');
        $('#notificationGateContinue').data('is-mega', notificationGateContext.isMega || false);
    }

    function openNotificationGate(context = {}) {
        if (!notificationModalEl) {
            return;
        }

        console.log('[NCS FCM] Opening notification gate', context);
        refreshNotificationGateModal(context);
        notificationModalEl.classList.remove('hidden');
        notificationModalEl.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeNotificationGate() {
        if (!notificationModalEl) {
            return;
        }

        notificationModalEl.classList.add('hidden');
        notificationModalEl.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function openShareMusicModal(context = {}) {
        if (!shareModalEl) {
            return;
        }

        shareMusicContext = {
            ...shareMusicContext,
            ...context,
        };

        const title = shareMusicContext.title || document.title;
        const url = shareMusicContext.url || window.location.href;
        const message = `${title} - ${url}`;
        const encodedTitle = encodeURIComponent(title);
        const encodedUrl = encodeURIComponent(url);
        const encodedMessage = encodeURIComponent(message);

        $('#shareMusicTitle').text(title);
        $('#shareMusicUrl').text(url);

        const shareLinks = {
            whatsapp: `https://wa.me/?text=${encodedMessage}`,
            x: `https://twitter.com/intent/tweet?text=${encodedMessage}`,
            facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
            telegram: `https://t.me/share/url?url=${encodedUrl}&text=${encodedTitle}`,
            linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`,
            reddit: `https://www.reddit.com/submit?url=${encodedUrl}&title=${encodedTitle}`,
            email: `mailto:?subject=${encodedTitle}&body=${encodedMessage}`,
        };

        Object.entries(shareLinks).forEach(([channel, href]) => {
            $(`[data-share-channel="${channel}"]`).attr('href', href);
        });

        $('[data-share-copy]').data('share-url', url);

        shareModalEl.classList.remove('hidden');
        shareModalEl.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeShareMusicModal() {
        if (!shareModalEl) {
            return;
        }

        shareModalEl.classList.add('hidden');
        shareModalEl.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function waitForServiceWorkerController() {
        return new Promise((resolve) => {
            if (navigator.serviceWorker.controller) {
                resolve();
                return;
            }

            const onControllerChange = () => {
                navigator.serviceWorker.removeEventListener('controllerchange', onControllerChange);
                console.log('[NCS FCM] Service worker controller acquired');
                resolve();
            };

            navigator.serviceWorker.addEventListener('controllerchange', onControllerChange);
        });
    }

    function waitForActivatedWorker(registration) {
        return new Promise((resolve) => {
            if (registration?.active?.state === 'activated') {
                resolve();
                return;
            }

            const worker = registration?.installing || registration?.waiting;

            if (!worker) {
                resolve();
                return;
            }

            const onStateChange = () => {
                console.log('[NCS FCM] Service worker state changed', worker.state);
                if (worker.state === 'activated') {
                    worker.removeEventListener('statechange', onStateChange);
                    resolve();
                }
            };

            if (worker.state === 'activated') {
                resolve();
                return;
            }

            worker.addEventListener('statechange', onStateChange);
        });
    }

    function hasFirebaseWebConfig() {
        const cfg = window.ncsFirebaseConfig || {};
        const missing = [];

        if (!cfg.apiKey) missing.push('apiKey');
        if (!cfg.authDomain) missing.push('authDomain');
        if (!cfg.projectId) missing.push('projectId');
        if (!cfg.storageBucket) missing.push('storageBucket');
        if (!cfg.messagingSenderId) missing.push('messagingSenderId');
        if (!cfg.appId) missing.push('appId');
        if (!window.ncsFirebaseVapidKey) missing.push('vapidKey');

        if (missing.length) {
            console.warn('[NCS FCM] Missing Firebase web settings:', missing);
            return false;
        }

        return true;
    }

    async function ensureFirebaseMessagingReady() {
        console.log('[NCS FCM] Preparing Firebase Messaging');

        if (!hasFirebaseWebConfig()) {
            console.warn('[NCS FCM] Firebase web config is incomplete', window.ncsFirebaseConfig);
            throw new Error('Firebase web settings are missing.');
        }

        if (!window.firebase) {
            console.error('[NCS FCM] Firebase SDK not loaded');
            throw new Error('Firebase scripts failed to load.');
        }

        if (!firebaseAppInstance) {
            console.log('[NCS FCM] Initializing Firebase app');
            firebaseAppInstance = firebase.apps.length ? firebase.app() : firebase.initializeApp(window.ncsFirebaseConfig);
            firebaseMessagingInstance = firebase.messaging(firebaseAppInstance);
        }

        if (!firebaseWorkerRegistration) {
            if (!('serviceWorker' in navigator)) {
                console.error('[NCS FCM] Service workers not supported');
                throw new Error('This browser does not support service workers.');
            }

            console.log('[NCS FCM] Registering service worker', window.ncsFirebaseServiceWorkerUrl);
            firebaseWorkerRegistration = await navigator.serviceWorker.register(window.ncsFirebaseServiceWorkerUrl, {
                scope: '/',
            });
            await navigator.serviceWorker.ready;
            await firebaseWorkerRegistration.update().catch((error) => {
                console.warn('[NCS FCM] Service worker update check failed', error);
            });

            console.log('[NCS FCM] Waiting for active service worker');
            await waitForActivatedWorker(firebaseWorkerRegistration);
            await waitForServiceWorkerController();

            if (!firebaseWorkerRegistration.active) {
                console.warn('[NCS FCM] Worker still not active after wait', {
                    scope: firebaseWorkerRegistration.scope,
                    state: firebaseWorkerRegistration.active?.state || 'missing',
                    controller: !!navigator.serviceWorker.controller,
                });
                throw new Error('Notification service worker is not active yet. Please refresh once and try again.');
            }

            console.log('[NCS FCM] Service worker ready', {
                scope: firebaseWorkerRegistration.scope,
                state: firebaseWorkerRegistration.active?.state || 'unknown',
                controller: !!navigator.serviceWorker.controller,
            });
        }

        return firebaseMessagingInstance;
    }

    async function saveFirebasePushToken() {
        console.log('[NCS FCM] Saving push token');
        const messaging = await ensureFirebaseMessagingReady();

        if (Notification.permission !== 'granted') {
            console.warn('[NCS FCM] Permission is not granted', Notification.permission);
            throw new Error('Notification permission is required.');
        }

        console.log('[NCS FCM] Requesting token');
        const token = await messaging.getToken({
            vapidKey: window.ncsFirebaseVapidKey,
            serviceWorkerRegistration: firebaseWorkerRegistration,
        });

        if (!token) {
            console.error('[NCS FCM] Firebase returned no token');
            throw new Error('Firebase did not return a push token.');
        }

        console.log('[NCS FCM] Token received', token);
        console.log('[NCS FCM] Sending token to server');
        await $.post(window.ncsFirebaseMessagingSaveUrl, {
            fcm: token,
            device_name: navigator.userAgent || 'Web Browser',
        });

        console.log('[NCS FCM] Token saved successfully');
        localStorage.setItem(notificationGateKey, '1');
        localStorage.removeItem(notificationPromptKey);

        return token;
    }

    function triggerFullScreenAd(downloadUrl, isMega = false) {
        const isSongDetail = window.location.pathname.includes('/music/');

        if (isSongDetail) {
            // Onclick popunder ad on download click in song detail screen
            const adUrl = 'https://3nbf4.com/afu.php?zoneid=11132365';
            
            if (isMega) {
                // Open Mega in a new tab first, then attempt to open ad in a new tab
                window.open(downloadUrl, '_blank');
                window.open(adUrl, '_blank');
            } else {
                // Open ad in a new tab, and start download in the current window
                window.open(adUrl, '_blank');
                window.location.href = downloadUrl;
            }
        } else {
            // On other pages, download starts cleanly without ads in a new tab
            const downloadWindow = window.open('about:blank', '_blank');
            if (downloadWindow) {
                downloadWindow.location.href = downloadUrl;
            } else {
                window.location.href = downloadUrl;
            }
        }
    }

    $(document).on('click', '[data-notification-gate]', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const actionUrl = $btn.data('actionUrl') || $btn.attr('href') || '';
        const isDownload = $btn.data('musicAction') === 'download';
        const isMega = $btn.attr('data-is-mega') === 'true' || $btn.data('is-mega') === true;

        // Check if modal was already dismissed or seen
        if (localStorage.getItem(notificationGateKey) || localStorage.getItem(notificationPromptKey)) {
            if (actionUrl) {
                if (isDownload) {
                    triggerFullScreenAd(actionUrl, isMega);
                } else {
                    window.location.href = actionUrl;
                }
            }
            return;
        }

        openNotificationGate({
            title: $btn.data('notificationTitle') || 'Get release alerts',
            description: $btn.data('notificationDescription') || 'Allow notifications so you can get updates when new music is added.',
            music: $btn.data('musicTitle') ? `Music: ${$btn.data('musicTitle')}` : '',
            actionUrl: actionUrl,
            actionLabel: $btn.data('actionLabel') || (isDownload ? 'Continue to download' : 'Continue to view'),
            actionType: $btn.data('musicAction') || 'continue',
            stemId: $btn.data('music-id') || '',
            isMega: isMega,
        });
    });

    // registerAdsServiceWorker();

    $(document).on('click', '#notificationGateAllow', async function() {
        const $btn = $(this);

        $btn.prop('disabled', true);
        console.log('[NCS FCM] Allow button clicked', {
            permission: 'Notification' in window ? Notification.permission : 'unsupported',
        });

        if (!('Notification' in window)) {
            console.warn('[NCS FCM] Notifications unsupported in this browser');
            if (window.toastr) {
                toastr.warning('This browser does not support notifications.');
            }
            $btn.prop('disabled', false);
            return;
        }

        try {
            console.log('[NCS FCM] Requesting browser notification permission');
            const permission = Notification.permission === 'granted'
                ? 'granted'
                : await Notification.requestPermission();
            console.log('[NCS FCM] Permission result', permission);

            if (permission !== 'granted') {
                throw new Error('Notifications were not enabled.');
            }

            await saveFirebasePushToken();
            closeNotificationGate();

            if (window.toastr) {
                toastr.success('Notifications enabled.');
            }
        } catch (error) {
            console.error('[NCS FCM] Notification enable failed', error);
            if (window.toastr) {
                toastr.error(error?.message || 'Could not enable notifications.');
            }
        } finally {
            $btn.prop('disabled', false);
        }
    });

    $(document).on('click', '#notificationGateContinue', function(e) {
        e.preventDefault();

        const actionUrl = $(this).data('action-url');
        const actionType = $(this).data('action-type');
        const stemId = $(this).data('music-id');
        const isMega = $(this).data('is-mega') === true || $(this).attr('data-is-mega') === 'true';

        if (!actionUrl) {
            closeNotificationGate();
            return;
        }

        localStorage.setItem(notificationPromptKey, '1');

        closeNotificationGate();
        
        if (actionType === 'download') {
            triggerFullScreenAd(actionUrl, isMega);
        } else {
            window.location.href = actionUrl;
        }
    });

    $(document).on('click', '#notificationGateLater', function() {
        localStorage.setItem(notificationPromptKey, '1');
        closeNotificationGate();
    });

    $(document).on('click', '[data-notification-dismiss]', function() {
        localStorage.setItem(notificationPromptKey, '1');
        closeNotificationGate();
    });

    $(document).on('click', '[data-music-share-btn]', function(e) {
        e.preventDefault();

        const $btn = $(this);
        openShareMusicModal({
            title: $btn.data('share-title') || document.title,
            url: $btn.data('share-url') || window.location.href,
        });
    });



    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNotificationGate();
            closeShareMusicModal();
        }
    });

    $(document).on('click', '[data-music-like-btn]', function(e) {
        e.preventDefault();

        const $btn = $(this);
        const url = $btn.data('like-url');
        let $card = $btn.closest('[data-like-card]');

        if (!$card.length || $card.is($btn)) {
            $card = $btn.parents().filter(function() {
                return $(this).find('[data-like-count]').length > 0;
            }).first();
        }

        if (!$card.length) {
            $card = $btn.closest('article, section, div').first();
        }

        const $count = $card.find('[data-like-count]').first();
        const $icon = $btn.find('[data-music-like-icon]').first();

        $btn.prop('disabled', true);

        $.post(url, {}, function(res) {
            const liked = !!res.liked;

            $btn.data('liked', liked ? 1 : 0);
            $btn.attr('aria-pressed', liked ? 'true' : 'false');

            if ($count.length && typeof res.count !== 'undefined') {
                $count.text(Number(res.count).toLocaleString());
            }

            if ($icon.length) {
                $icon.toggleClass('fa-solid', liked);
                $icon.toggleClass('fa-regular', !liked);
            }

            $btn.toggleClass('text-red-400', liked);
            $btn.toggleClass('text-zinc-300', !liked);

            if (window.toastr) {
                toastr.success(res.message || (liked ? 'Added to likes.' : 'Removed from likes.'));
            }
        }).fail(function(xhr) {
            if (xhr.status === 401) {
                window.location.href = "{{ route('login') }}";
                return;
            }

            if (window.toastr) {
                toastr.error('Could not update like right now.');
            } else {
                alert('Could not update like right now.');
            }
        }).always(function() {
            $btn.prop('disabled', false);
        });
    });

    $(document).on('click', '[data-share-dismiss]', function() {
        console.log('[NCS Share] Close clicked');
        closeShareMusicModal();
    });

    $(document).on('click', '[data-share-copy]', async function(e) {
        e.preventDefault();

        const url = $(this).data('share-url') || window.location.href;
        console.log('[NCS Share] Copy link requested', url);

        try {
            await navigator.clipboard.writeText(url);
            if (window.toastr) {
                toastr.success('Link copied to clipboard.');
            }
        } catch (err) {
            const $temp = $('<input>');
            $('body').append($temp);
            $temp.val(url).select();
            document.execCommand('copy');
            $temp.remove();

            if (window.toastr) {
                toastr.success('Link copied to clipboard.');
            }
        }
    });

    console.log('NCS Hindi WebApp Initialized');

    /* ==========================================================================
       GLOBAL SPOTIFY-GRADE DUAL ENGINE MUSIC PLAYER SYSTEM (SELF-HEALING)
       ========================================================================== */
    (function() {
        let audio, playerBar, playPauseBtn, playIcon, coverImg, coverFallback, playingIndicator;
        let titleEl, artistEl, currentTimeEl, durationEl, progressBar, volumeBar, muteBtn, volumeIcon, closeBtn, rewindBtn, forwardBtn;

        let currentActiveBtn = null;
        let activeMode = 'html5'; // 'html5' or 'youtube'
        let ytPlayer = null;
        let ytTimer = null;
        let currentSrc = null;

        function ensurePlayerElements() {
            playerBar = document.getElementById('ncsGlobalPlayer');
            audio = document.getElementById('globalNcsAudio');

            if (!playerBar) {
                const playerHtml = `
                <div id="ncsGlobalPlayer" class="fixed bottom-16 lg:bottom-0 left-0 right-0 z-[160] transform translate-y-full opacity-0 pointer-events-none transition-all duration-500 ease-out">
                    <div class="max-w-7xl mx-auto px-3 sm:px-6 pb-2 sm:pb-4">
                        <div class="relative bg-zinc-950/95 border border-zinc-800/90 backdrop-blur-2xl rounded-2xl sm:rounded-3xl p-3 sm:p-4 shadow-2xl shadow-black/90 flex flex-col md:flex-row items-center justify-between gap-3 sm:gap-6 pointer-events-auto">
                            <div class="flex items-center gap-3.5 w-full md:w-1/4 shrink-0">
                                <div class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-xl overflow-hidden bg-zinc-900 border border-zinc-800 shrink-0">
                                    <img id="playerCoverImg" src="" class="w-full h-full object-cover hidden" alt="Track Cover">
                                    <div id="playerCoverFallback" class="w-full h-full flex items-center justify-center text-amber-500">
                                        <i class="fa-solid fa-music text-lg"></i>
                                    </div>
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
                            <div class="flex-1 w-full max-w-2xl flex flex-col items-center gap-1.5">
                                <div class="flex items-center gap-5">
                                    <button type="button" id="playerRewindBtn" class="text-zinc-400 hover:text-white transition text-xs sm:text-sm" title="Rewind 10s">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                    <button type="button" id="playerPlayPauseBtn" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/25 transition transform active:scale-95">
                                        <i id="playerPlayIcon" class="fa-solid fa-play text-base sm:text-lg ml-0.5"></i>
                                    </button>
                                    <button type="button" id="playerForwardBtn" class="text-zinc-400 hover:text-white transition text-xs sm:text-sm" title="Forward 10s">
                                        <i class="fa-solid fa-rotate-right"></i>
                                    </button>
                                </div>
                                <div class="w-full flex items-center gap-3">
                                    <span id="playerCurrentTime" class="text-[10px] font-mono font-bold text-zinc-400 min-w-[32px] text-right">0:00</span>
                                    <div class="relative flex-1 group cursor-pointer">
                                        <input type="range" id="playerProgress" min="0" max="100" value="0" step="0.1" class="w-full h-1.5 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-amber-500 group-hover:h-2 transition-all">
                                    </div>
                                    <span id="playerDuration" class="text-[10px] font-mono font-bold text-zinc-400 min-w-[32px]">0:00</span>
                                </div>
                            </div>
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
                            <audio id="globalNcsAudio" preload="auto" class="hidden"></audio>
                            <div id="youtubeAudioHost" class="hidden"></div>
                        </div>
                    </div>
                </div>`;
                document.body.insertAdjacentHTML('beforeend', playerHtml);
                playerBar = document.getElementById('ncsGlobalPlayer');
                audio = document.getElementById('globalNcsAudio');
            } else if (!audio) {
                audio = document.createElement('audio');
                audio.id = 'globalNcsAudio';
                audio.className = 'hidden';
                audio.preload = 'auto';
                document.body.appendChild(audio);
            }

            if (!document.getElementById('youtubeAudioHost')) {
                const host = document.createElement('div');
                host.id = 'youtubeAudioHost';
                host.className = 'hidden';
                document.body.appendChild(host);
            }

            playPauseBtn = document.getElementById('playerPlayPauseBtn');
            playIcon = document.getElementById('playerPlayIcon');
            coverImg = document.getElementById('playerCoverImg');
            coverFallback = document.getElementById('playerCoverFallback');
            playingIndicator = document.getElementById('playerPlayingIndicator');
            titleEl = document.getElementById('playerTrackTitle');
            artistEl = document.getElementById('playerTrackArtist');
            currentTimeEl = document.getElementById('playerCurrentTime');
            durationEl = document.getElementById('playerDuration');
            progressBar = document.getElementById('playerProgress');
            volumeBar = document.getElementById('playerVolume');
            muteBtn = document.getElementById('playerMuteBtn');
            volumeIcon = document.getElementById('playerVolumeIcon');
            closeBtn = document.getElementById('playerCloseBtn');
            rewindBtn = document.getElementById('playerRewindBtn');
            forwardBtn = document.getElementById('playerForwardBtn');

            bindEvents();
        }

        function extractYoutubeId(url) {
            if (!url) return null;
            const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
            return match ? match[1] : null;
        }

        function formatTime(secs) {
            if (isNaN(secs) || secs < 0) return '0:00';
            const m = Math.floor(secs / 60);
            const s = Math.floor(secs % 60);
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        }

        function showPlayer() {
            if (playerBar) {
                playerBar.classList.remove('translate-y-full', 'opacity-0', 'pointer-events-none');
                playerBar.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
            }
        }

        function hidePlayer() {
            pauseAll();
            if (playerBar) {
                playerBar.classList.add('translate-y-full', 'opacity-0', 'pointer-events-none');
                playerBar.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
            }
            updatePlayButtonsState(false);
        }

        function pauseAll() {
            if (audio) audio.pause();
            if (ytPlayer && typeof ytPlayer.pauseVideo === 'function') {
                try { ytPlayer.pauseVideo(); } catch(e) {}
            }
            if (ytTimer) clearInterval(ytTimer);
        }

        function isCurrentlyPlaying() {
            if (activeMode === 'html5') {
                return audio && !audio.paused;
            } else if (activeMode === 'youtube' && ytPlayer && typeof ytPlayer.getPlayerState === 'function') {
                return ytPlayer.getPlayerState() === 1;
            }
            return false;
        }

        function updatePlayButtonsState(isPlaying) {
            if (typeof isPlaying === 'undefined') {
                isPlaying = isCurrentlyPlaying();
            }
            if (playIcon) {
                playIcon.className = isPlaying ? 'fa-solid fa-pause text-base sm:text-lg' : 'fa-solid fa-play text-base sm:text-lg ml-0.5';
            }
            if (playingIndicator) {
                playingIndicator.style.display = isPlaying ? 'flex' : 'none';
            }

            if (typeof $ !== 'undefined') {
                $('[data-play-audio]').each(function() {
                    const $b = $(this);
                    const src = $b.attr('data-audio-src') || $b.data('audio-src');
                    const icon = $b.find('.js-play-icon');
                    const label = $b.find('.js-play-label');
                    if (currentSrc && src === currentSrc) {
                        if (isPlaying) {
                            icon.removeClass('fa-play').addClass('fa-pause');
                            if (label.length) label.text('Pause Track');
                        } else {
                            icon.removeClass('fa-pause').addClass('fa-play');
                            if (label.length) label.text('Play Track');
                        }
                    } else {
                        icon.removeClass('fa-pause').addClass('fa-play');
                        if (label.length) label.text('Play Track');
                    }
                });
            }
        }
        window.ncsUpdatePlayButtonsState = updatePlayButtonsState;

        function savePlayerState() {
            if (!currentSrc) return;
            try {
                const state = {
                    src: currentSrc,
                    title: titleEl ? titleEl.textContent : '',
                    artist: artistEl ? artistEl.textContent : '',
                    cover: coverImg && !coverImg.classList.contains('hidden') ? coverImg.src : '',
                    currentTime: activeMode === 'html5' ? (audio ? audio.currentTime : 0) : (ytPlayer && typeof ytPlayer.getCurrentTime === 'function' ? ytPlayer.getCurrentTime() : 0),
                    mode: activeMode,
                    isPlaying: isCurrentlyPlaying(),
                    volume: volumeBar ? volumeBar.value : 0.8
                };
                sessionStorage.setItem('ncs_player_state', JSON.stringify(state));
            } catch (e) {}
        }

        function restorePlayerState() {
            try {
                const saved = sessionStorage.getItem('ncs_player_state');
                if (!saved) return;
                const state = JSON.parse(saved);
                if (!state || !state.src) return;

                currentSrc = state.src;
                activeMode = state.mode || 'html5';

                if (titleEl) titleEl.textContent = state.title || 'Unknown Title';
                if (artistEl) artistEl.textContent = state.artist || 'NCS Artist';

                if (state.cover) {
                    if (coverImg) {
                        coverImg.src = state.cover;
                        coverImg.classList.remove('hidden');
                    }
                    if (coverFallback) coverFallback.classList.add('hidden');
                }

                if (volumeBar && state.volume) {
                    volumeBar.value = state.volume;
                }

                showPlayer();

                if (activeMode === 'html5' && audio) {
                    audio.src = state.src;
                    if (volumeBar) audio.volume = parseFloat(volumeBar.value);
                    if (state.currentTime) {
                        audio.currentTime = state.currentTime;
                    }
                    if (state.isPlaying) {
                        audio.play().then(() => {
                            updatePlayButtonsState(true);
                        }).catch(() => {
                            updatePlayButtonsState(false);
                        });
                    } else {
                        updatePlayButtonsState(false);
                    }
                }
            } catch(e) {}
        }

        function startYtProgressTimer() {
            if (ytTimer) clearInterval(ytTimer);
            ytTimer = setInterval(() => {
                if (activeMode === 'youtube' && ytPlayer && typeof ytPlayer.getCurrentTime === 'function') {
                    const cur = ytPlayer.getCurrentTime() || 0;
                    const dur = ytPlayer.getDuration() || 0;
                    if (dur > 0) {
                        const pct = (cur / dur) * 100;
                        if (progressBar) progressBar.value = pct;
                        if (currentTimeEl) currentTimeEl.textContent = formatTime(cur);
                        if (durationEl) durationEl.textContent = formatTime(dur);
                        savePlayerState();
                    }
                }
            }, 500);
        }

        function initYoutubePlayer(videoId, callback) {
            if (window.YT && window.YT.Player) {
                createOrLoadYt(videoId, callback);
                return;
            }

            if (!document.getElementById('ytIframeApi')) {
                const tag = document.createElement('script');
                tag.id = 'ytIframeApi';
                tag.src = "https://www.youtube.com/iframe_api";
                const firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            }

            const oldOnReady = window.onYouTubeIframeAPIReady;
            window.onYouTubeIframeAPIReady = function() {
                if (typeof oldOnReady === 'function') oldOnReady();
                createOrLoadYt(videoId, callback);
            };
        }

        function createOrLoadYt(videoId, callback) {
            if (ytPlayer) {
                ytPlayer.loadVideoById(videoId);
                if (callback) callback();
            } else {
                ytPlayer = new YT.Player('youtubeAudioHost', {
                    height: '0',
                    width: '0',
                    videoId: videoId,
                    playerVars: {
                        'autoplay': 1,
                        'controls': 0,
                        'playsinline': 1,
                    },
                    events: {
                        'onReady': function(event) {
                            if (volumeBar) event.target.setVolume(parseFloat(volumeBar.value) * 100);
                            event.target.playVideo();
                            if (callback) callback();
                        },
                        'onStateChange': function(event) {
                            if (event.data === YT.PlayerState.PLAYING) {
                                updatePlayButtonsState(true);
                                startYtProgressTimer();
                                savePlayerState();
                            } else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
                                updatePlayButtonsState(false);
                                if (ytTimer) clearInterval(ytTimer);
                                savePlayerState();
                            }
                        }
                    }
                });
            }
        }

        function playTrack(src, title, artist, cover, $triggerBtn) {
            ensurePlayerElements();

            if (!src) {
                if (window.toastr) toastr.warning('Audio stream is not available for this track.');
                return;
            }

            const ytId = extractYoutubeId(src);

            if (currentSrc === src) {
                if (isCurrentlyPlaying()) {
                    if (activeMode === 'html5' && audio) audio.pause();
                    else if (activeMode === 'youtube' && ytPlayer) ytPlayer.pauseVideo();
                    updatePlayButtonsState(false);
                } else {
                    if (activeMode === 'html5' && audio) audio.play();
                    else if (activeMode === 'youtube' && ytPlayer) ytPlayer.playVideo();
                    updatePlayButtonsState(true);
                }
                savePlayerState();
                return;
            }

            pauseAll();
            currentSrc = src;
            currentActiveBtn = $triggerBtn || null;

            if (titleEl) titleEl.textContent = title || 'Unknown Title';
            if (artistEl) artistEl.textContent = artist || 'NCS Artist';

            if (cover) {
                if (coverImg) {
                    coverImg.src = cover;
                    coverImg.classList.remove('hidden');
                }
                if (coverFallback) coverFallback.classList.add('hidden');
            } else {
                if (coverImg) coverImg.classList.add('hidden');
                if (coverFallback) coverFallback.classList.remove('hidden');
            }

            showPlayer();

            const musicId = $triggerBtn ? ($triggerBtn.data('music-id') || $triggerBtn.attr('data-music-id')) : null;
            if (musicId && typeof $ !== 'undefined') {
                $.post('/music/' + musicId + '/increment-view').catch(() => {});
            }

            if (ytId) {
                activeMode = 'youtube';
                initYoutubePlayer(ytId, function() {
                    updatePlayButtonsState(true);
                    savePlayerState();
                });
            } else {
                activeMode = 'html5';
                if (audio) {
                    audio.src = src;
                    if (volumeBar) audio.volume = parseFloat(volumeBar.value);
                    audio.play().then(() => {
                        updatePlayButtonsState(true);
                        savePlayerState();
                    }).catch(err => {
                        console.warn('HTML5 Playback error:', err);
                        const fallbackAudio = 'https://cdn.pixabay.com/download/audio/2022/05/27/audio_1808fbf07a.mp3';
                        if (src !== fallbackAudio) {
                            audio.src = fallbackAudio;
                            audio.play().then(() => {
                                updatePlayButtonsState(true);
                                savePlayerState();
                            }).catch(() => {
                                if (window.toastr) toastr.error('Unable to play audio track.');
                            });
                        } else {
                            if (window.toastr) toastr.error('Unable to play audio track.');
                        }
                    });
                }
            }
        }

        let isBound = false;
        function bindEvents() {
            if (isBound) return;
            isBound = true;

            if (playPauseBtn) {
                playPauseBtn.addEventListener('click', function() {
                    if (isCurrentlyPlaying()) {
                        if (activeMode === 'html5' && audio) audio.pause();
                        else if (activeMode === 'youtube' && ytPlayer) ytPlayer.pauseVideo();
                    } else {
                        if (activeMode === 'html5' && audio) audio.play();
                        else if (activeMode === 'youtube' && ytPlayer) ytPlayer.playVideo();
                    }
                    savePlayerState();
                });
            }

            if (audio) {
                audio.addEventListener('play', () => { if (activeMode === 'html5') { updatePlayButtonsState(true); savePlayerState(); } });
                audio.addEventListener('pause', () => { if (activeMode === 'html5') { updatePlayButtonsState(false); savePlayerState(); } });
                audio.addEventListener('timeupdate', () => {
                    if (activeMode === 'html5' && audio.duration) {
                        const pct = (audio.currentTime / audio.duration) * 100;
                        if (progressBar) progressBar.value = pct;
                        if (currentTimeEl) currentTimeEl.textContent = formatTime(audio.currentTime);
                        if (durationEl) durationEl.textContent = formatTime(audio.duration);
                        savePlayerState();
                    }
                });
            }

            if (progressBar) {
                progressBar.addEventListener('input', () => {
                    const pct = progressBar.value / 100;
                    if (activeMode === 'html5' && audio && audio.duration) {
                        audio.currentTime = pct * audio.duration;
                    } else if (activeMode === 'youtube' && ytPlayer && typeof ytPlayer.getDuration === 'function') {
                        const dur = ytPlayer.getDuration();
                        if (dur) ytPlayer.seekTo(pct * dur, true);
                    }
                    savePlayerState();
                });
            }

            if (volumeBar) {
                volumeBar.addEventListener('input', () => {
                    const val = parseFloat(volumeBar.value);
                    if (activeMode === 'html5' && audio) {
                        audio.volume = val;
                        audio.muted = (val === 0);
                    } else if (activeMode === 'youtube' && ytPlayer && typeof ytPlayer.setVolume === 'function') {
                        ytPlayer.setVolume(val * 100);
                    }
                    updateVolumeIcon();
                    savePlayerState();
                });
            }

            if (muteBtn) {
                muteBtn.addEventListener('click', () => {
                    if (activeMode === 'html5' && audio) {
                        audio.muted = !audio.muted;
                    } else if (activeMode === 'youtube' && ytPlayer && typeof ytPlayer.isMuted === 'function') {
                        if (ytPlayer.isMuted()) ytPlayer.unMute();
                        else ytPlayer.mute();
                    }
                    updateVolumeIcon();
                    savePlayerState();
                });
            }

            if (rewindBtn) {
                rewindBtn.addEventListener('click', () => {
                    if (activeMode === 'html5' && audio) {
                        audio.currentTime = Math.max(0, audio.currentTime - 10);
                    } else if (activeMode === 'youtube' && ytPlayer && typeof ytPlayer.getCurrentTime === 'function') {
                        ytPlayer.seekTo(Math.max(0, ytPlayer.getCurrentTime() - 10), true);
                    }
                    savePlayerState();
                });
            }

            if (forwardBtn) {
                forwardBtn.addEventListener('click', () => {
                    if (activeMode === 'html5' && audio && audio.duration) {
                        audio.currentTime = Math.min(audio.duration, audio.currentTime + 10);
                    } else if (activeMode === 'youtube' && ytPlayer && typeof ytPlayer.getCurrentTime === 'function') {
                        ytPlayer.seekTo(ytPlayer.getCurrentTime() + 10, true);
                    }
                    savePlayerState();
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', hidePlayer);
            }

            restorePlayerState();
        }

        function updateVolumeIcon() {
            if (!volumeBar || !volumeIcon) return;
            const val = parseFloat(volumeBar.value);
            if (val === 0) {
                volumeIcon.className = 'fa-solid fa-volume-xmark';
            } else if (val < 0.5) {
                volumeIcon.className = 'fa-solid fa-volume-low';
            } else {
                volumeIcon.className = 'fa-solid fa-volume-high';
            }
        }

        // Global Event listener for all Play buttons
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-play-audio]');
            if (!btn) return;
            e.preventDefault();
            const $btn = typeof $ !== 'undefined' ? $(btn) : null;
            const src = btn.getAttribute('data-audio-src');
            const title = btn.getAttribute('data-audio-title');
            const artist = btn.getAttribute('data-audio-artist');
            const cover = btn.getAttribute('data-audio-cover');

            playTrack(src, title, artist, cover, $btn);
        });

        // Initialize immediately or on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', ensurePlayerElements);
        } else {
            ensurePlayerElements();
        }
    })();

    /* ==========================================================================
       SEAMLESS PJAX PAGE SWITCHING (KEEPS AUDIO PLAYING CONTINUOUSLY)
       ========================================================================== */
    (function() {
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
            if (link.getAttribute('target') === '_blank') return;
            if (link.hasAttribute('download') || link.dataset.noPjax !== undefined) return;
            if (link.dataset.notificationGate !== undefined && link.dataset.musicAction === 'download') return;

            let url;
            try {
                url = new URL(href, window.location.origin);
            } catch (err) {
                return;
            }

            if (url.origin !== window.location.origin) return;

            const pathname = url.pathname;
            if (pathname.startsWith('/admin') ||
                pathname.startsWith('/logout') ||
                pathname.startsWith('/auth') ||
                pathname.endsWith('/download') ||
                pathname.startsWith('/game') ||
                pathname.includes('/game/') ||
                pathname.startsWith('/firebase-messaging-sw.js')) {
                return;
            }

            e.preventDefault();
            if (url.href === window.location.href) return;

            loadPage(url.href, true);
        });

        window.addEventListener('popstate', function() {
            loadPage(window.location.href, false);
        });

        async function loadPage(url, pushToHistory) {
            const mainEl = document.getElementById('appMainContent') || document.querySelector('main');
            if (!mainEl) {
                window.location.href = url;
                return;
            }

            mainEl.style.opacity = '0.55';
            mainEl.style.transition = 'opacity 0.15s ease';

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-PJAX': 'true'
                    }
                });

                if (!response.ok) {
                    window.location.href = url;
                    return;
                }

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                if (doc.title) {
                    document.title = doc.title;
                }

                // Sync sidebar DOM if present in fetched page
                const newAside = doc.querySelector('aside');
                const oldAside = document.querySelector('aside');
                if (newAside && oldAside) {
                    oldAside.innerHTML = newAside.innerHTML;
                }

                // Sync header title if present
                const newHeader = doc.querySelector('header');
                const oldHeader = document.querySelector('header');
                if (newHeader && oldHeader) {
                    const newTitle = newHeader.querySelector('h2');
                    const oldTitle = oldHeader.querySelector('h2');
                    if (newTitle && oldTitle) {
                        oldTitle.innerHTML = newTitle.innerHTML;
                    }
                }

                const newMain = doc.getElementById('appMainContent') || doc.querySelector('main');
                if (newMain) {
                    mainEl.innerHTML = newMain.innerHTML;
                    mainEl.scrollTop = 0;
                } else {
                    window.location.href = url;
                    return;
                }

                if (pushToHistory) {
                    history.pushState(null, '', url);
                }

                updateActiveNavLinks(url);

                const scripts = mainEl.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });

                if (window.ncsUpdatePlayButtonsState) {
                    window.ncsUpdatePlayButtonsState();
                }

                document.dispatchEvent(new CustomEvent('pjax:loaded', { detail: { url } }));

            } catch (err) {
                console.error('[NCS PJAX] Navigation failed:', err);
                window.location.href = url;
            } finally {
                mainEl.style.opacity = '1';
            }
        }

        function updateActiveNavLinks(currentUrl) {
            const path = new URL(currentUrl, window.location.origin).pathname;
            document.querySelectorAll('aside nav a[href], nav a[href]').forEach(a => {
                const href = a.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
                try {
                    const aPath = new URL(href, window.location.origin).pathname;
                    const isSame = (aPath === path) || (aPath !== '/' && path.startsWith(aPath));
                    const activeClasses = ['bg-gradient-to-r', 'from-amber-600/10', 'to-transparent', 'text-amber-500', 'font-bold', 'border-l-2', 'border-amber-600'];
                    const iconEl = a.querySelector('i');
                    
                    if (isSame) {
                        activeClasses.forEach(cls => a.classList.add(cls));
                        a.classList.remove('text-zinc-400');
                        if (iconEl && !iconEl.classList.contains('text-amber-500')) {
                            iconEl.classList.add('text-amber-500');
                        }
                    } else {
                        activeClasses.forEach(cls => a.classList.remove(cls));
                        a.classList.add('text-zinc-400');
                        if (iconEl && iconEl.classList.contains('text-amber-500') && !a.closest('.group:hover')) {
                            iconEl.classList.remove('text-amber-500');
                        }
                    }
                } catch (e) {}
            });
        }
    })();
</script>


