<x-webapp-layout>
    {{-- 1. Quill Assets --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        /* Studio Dark Chat Theme */
        #chat-container::-webkit-scrollbar {
            width: 4px;
        }

        #chat-container::-webkit-scrollbar-thumb {
            background: #1a1a1c;
            border-radius: 10px;
        }

        /* Quill Editor Overrides */
        .ql-container.ql-snow {
            border: none !important;
        }

        .ql-editor {
            color: #ffffff !important;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            min-height: 48px;
            padding: 12px 16px !important;
        }

        .ql-editor.ql-blank::before {
            color: #52525b !important;
            font-style: normal;
            left: 16px !important;
        }

        /* Animation for new messages */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden bg-[#050505]">

        {{-- 1. Channels Sidebar --}}
        <aside class="w-64 border-r border-zinc-900 flex flex-col bg-[#08080a]">
            <div class="p-6">
                <h2 class="font-brand text-xl font-black italic text-white uppercase tracking-tighter">
                    Studio <span class="text-amber-500">Rooms</span>
                </h2>
            </div>

            <nav class="flex-1 px-3 space-y-1">
                @forelse ($channels as $channel)
                    <a href="?channel={{ $channel->id }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ optional($activeChannel)->id == $channel->id ? 'bg-amber-600/10 text-amber-500 border border-amber-600/20' : 'text-zinc-500 hover:bg-zinc-900 hover:text-white' }}">
                        <i class="fa-solid {{ $channel->icon }} text-sm"></i>
                        <span class="text-xs font-bold uppercase tracking-widest">{{ $channel->name }}</span>
                    </a>
                @empty
                    <div class="px-4 py-10 text-center text-zinc-800">
                        <i class="fa-solid fa-ghost text-3xl mb-3"></i>
                        <p class="text-[10px] font-black uppercase tracking-widest">No Rooms Found</p>
                    </div>
                @endforelse
            </nav>
        </aside>

        {{-- 2. Main Chat Area --}}
        <main class="flex-1 flex flex-col relative">

            @if ($activeChannel)
                {{-- Channel Header --}}
                <header class="p-4 border-b border-zinc-900 bg-[#0a0a0c] flex justify-between items-center shadow-sm">
                    <div>
                        <h3 class="text-sm font-black text-white uppercase tracking-[0.2em]">{{ $activeChannel->name }}
                        </h3>
                        <p class="text-[9px] text-zinc-600 font-bold uppercase">{{ $activeChannel->description }}</p>
                    </div>
                    <div id="online-count"
                        class="text-[9px] font-black text-green-500 uppercase bg-green-500/10 px-3 py-1 rounded-full">
                        ● Live: <span class="js-presence-count">0</span> Producers
                    </div>
                </header>

                {{-- Message Feed --}}
                <div id="chat-container" class="flex-1 overflow-y-auto p-6 space-y-6 no-scrollbar scroll-smooth">
                    @forelse ($messages as $message)
                        <div class="flex gap-4 group {{ Auth::id() == $message->user_id ? 'flex-row-reverse' : '' }}">
                            <img src="{{ $message->user->profile_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=b45309&color=fff' }}"
                                class="w-10 h-10 rounded-xl object-cover border border-zinc-800"
                                referrerpolicy="no-referrer">

                            <div
                                class="max-w-[70%] {{ Auth::id() == $message->user_id ? 'items-end' : 'items-start' }} flex flex-col">
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="text-[10px] font-bold text-zinc-500 uppercase">{{ $message->user->name }}</span>
                                    <span
                                        class="text-[8px] text-zinc-700 font-bold">{{ $message->created_at->format('H:i') }}</span>
                                </div>

                                <div
                                    class="p-4 rounded-2xl text-sm leading-relaxed {{ Auth::id() == $message->user_id ? 'bg-amber-600 text-black font-semibold rounded-tr-none' : 'bg-zinc-900 text-zinc-300 rounded-tl-none border border-zinc-800' }}">
                                    {!! $message->message !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center px-4">
                            <div
                                class="w-20 h-20 bg-zinc-900/50 rounded-full flex items-center justify-center mb-6 border border-zinc-800">
                                <i class="fa-solid fa-comments text-3xl text-zinc-700"></i>
                            </div>
                            <h3 class="font-brand text-2xl font-black italic text-white uppercase tracking-tighter">
                                Quiet in the <span class="text-amber-500">Studio</span></h3>
                            <p
                                class="text-[10px] text-zinc-500 font-bold uppercase tracking-[0.3em] mt-2 max-w-xs leading-relaxed">
                                No messages in {{ $activeChannel->name }} yet. Start the conversation!
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- 3. Input Area --}}
                <footer class="p-6 bg-[#0a0a0c] border-t border-zinc-900">
                    <form id="chat-form" class="relative">
                        @csrf
                        <input type="hidden" name="channel_id" value="{{ $activeChannel->id }}">

                        <div id="chat-editor-wrapper"
                            class="bg-black border border-zinc-800 rounded-2xl overflow-hidden min-h-[48px] focus-within:border-amber-600/50 transition-colors">
                            <div id="chat-editor"></div>
                        </div>

                        <div class="absolute right-3 bottom-3 flex gap-2 z-10">
                            <button type="button"
                                class="w-8 h-8 rounded-lg bg-zinc-900 text-zinc-500 hover:text-white transition flex items-center justify-center">
                                <i class="fa-solid fa-paperclip text-xs"></i>
                            </button>
                            <button type="submit"
                                class="bg-amber-600 hover:bg-amber-500 text-black px-6 rounded-lg text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-amber-600/20">
                                Send
                            </button>
                        </div>
                    </form>
                </footer>
            @else
                {{-- Global Empty State --}}
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                    <div
                        class="w-24 h-24 bg-red-600/10 rounded-3xl flex items-center justify-center mb-8 border border-red-600/20">
                        <i class="fa-solid fa-triangle-exclamation text-4xl text-red-600"></i>
                    </div>
                    <h2 class="font-brand text-4xl font-black italic text-white uppercase tracking-tighter">System <span
                            class="text-red-600">Offline</span></h2>
                    <p class="text-xs text-zinc-500 font-bold uppercase tracking-widest mt-4">No community channels
                        found.</p>
                </div>
            @endif
        </main>
    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        $(document).ready(function() {
            @if ($activeChannel)
                const container = $('#chat-container');

                var quill = new Quill('#chat-editor', {
                    placeholder: 'Type a message or drop a stem...',
                    theme: 'snow',
                    modules: {
                        toolbar: false
                    }
                });

                const scrollToBottom = () => {
                    setTimeout(() => {
                        container.animate({
                            scrollTop: container.prop("scrollHeight")
                        }, 300);
                    }, 100);
                };

                scrollToBottom();

                // Track last message ID to avoid duplicates
                let lastMessageId = null;
                const existingMessages = container.find('[id^="msg-"]');
                if (existingMessages.length > 0) {
                    const lastMsg = existingMessages.last();
                    lastMessageId = lastMsg.attr('id')?.replace('msg-', '') || null;
                }

                // Function to generate the HTML for a single message
                function appendMessageToUI(data, isMe) {
                    // Check if message already exists
                    if ($(`#msg-${data.id}`).length > 0) {
                        return;
                    }

                    // Check if empty state exists and remove it
                    if (container.find('.fa-comments').length > 0) {
                        container.empty();
                    }

                    const avatar = data.user.profile_image ||
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(data.user.name)}&background=b45309&color=fff`;

                    const time = new Date(data.created_at).toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const html = `
                    <div class="flex gap-4 group animate-fade-in ${isMe ? 'flex-row-reverse' : ''}" id="msg-${data.id}">
                        <img src="${avatar}" class="w-10 h-10 rounded-xl object-cover border border-zinc-800" referrerpolicy="no-referrer">
                        <div class="max-w-[70%] ${isMe ? 'items-end' : 'items-start'} flex flex-col">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-bold text-zinc-500 uppercase">${data.user.name}</span>
                                <span class="text-[8px] text-zinc-700 font-bold">${time}</span>
                            </div>
                            <div class="p-4 rounded-2xl text-sm ${isMe ? 'bg-amber-600 text-black font-medium rounded-tr-none' : 'bg-zinc-900 text-zinc-300 rounded-tl-none border border-zinc-800'}">
                                ${data.message}
                            </div>
                        </div>
                    </div>
                `;
                    container.append(html);
                    lastMessageId = data.id;
                    scrollToBottom();
                }

                // POLLING: Fetch new messages every 2 seconds
                function pollForNewMessages() {
                    $.ajax({
                        url: '{{ route('community.message.index', ['channelId' => $activeChannel->id]) }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response && response.data) {
                                // Process each message in reverse order (oldest first)
                                response.data.forEach(msg => {
                                    appendMessageToUI(msg, msg.user_id ===
                                        '{{ Auth::id() }}');
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('Failed to fetch messages:', xhr);
                        }
                    });
                }

                // Start polling
                setInterval(pollForNewMessages, 6000);

                // 2. WebSocket Listener (as fallback)
                if (typeof Echo !== 'undefined') {
                    Echo.channel('community.chat.{{ $activeChannel->id }}')
                        .listen('MessageSent', (e) => {
                            console.log('Message received via Echo:', e.message);
                            // Append any message (helps with real-time on production with WebSockets)
                            appendMessageToUI(e.message, e.message.user_id === '{{ Auth::id() }}');
                        });
                } else {
                    console.log('Echo not available, relying on polling');
                }

                // 2. Handle Form Submission
                $('#chat-form').on('submit', function(e) {
                    e.preventDefault();
                    const content = quill.root.innerHTML;
                    if (quill.getText().trim().length === 0) return;

                    const $submitBtn = $(this).find('button[type="submit"]');
                    $submitBtn.prop('disabled', true);

                    $.ajax({
                        url: '{{ route('community.message.store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            channel_id: '{{ $activeChannel->id }}',
                            message: content
                        },
                        success: function(response) {
                            quill.setContents([]);
                            quill.focus();
                            $submitBtn.prop('disabled', false).removeClass('opacity-50');

                            // Manually append because Echo will ignore this message for the sender
                            appendMessageToUI(response.message, true);
                        },
                        error: function(xhr) {
                            $submitBtn.prop('disabled', false);
                            alert('Message failed to send. Check your connection.');
                        }
                    });
                });

                $('#chat-editor-wrapper').on('click', function() {
                    quill.focus();
                });
            @endif
        });
    </script>
</x-webapp-layout>
