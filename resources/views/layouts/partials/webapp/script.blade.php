<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.4/dist/echo.iife.js"></script>

<script>
    // Initialize Laravel Echo for WebSocket communication
    @if(config('broadcasting.default') !== 'null' && config('broadcasting.default') !== 'log')
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
        // For log or null drivers, create a mock Echo object
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
        console.log('Broadcasting driver is set to "{{ config('broadcasting.default') }}". Real-time updates are disabled. For development, consider using Pusher or Reverb.');
    @endif

    console.log('NCS Hindi WebApp Initialized');
</script>
