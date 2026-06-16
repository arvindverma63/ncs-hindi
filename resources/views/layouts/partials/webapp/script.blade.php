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
</script>


