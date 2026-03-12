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

        /* Message hover menu */
        .message-menu {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
        }

        .group:hover .message-menu,
        .group:focus-within .message-menu {
            opacity: 1;
            visibility: visible;
        }

        /* Media upload preview */
        .media-preview {
            max-width: 300px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-top: 8px;
        }

        /* Responsive design */
        @media (max-width: 1024px) {
            aside {
                width: 16rem;
            }
        }

        @media (max-width: 768px) {
            .flex.h-\[calc\(100vh-64px\)\] {
                flex-direction: column;
            }

            aside {
                width: 100%;
                max-height: 120px;
                flex-direction: row;
                overflow-x: auto;
                border-right: none;
                border-bottom: 1px solid #27272a;
            }

            nav {
                flex-direction: row !important;
                padding-right: 1rem !important;
            }

            main {
                width: 100%;
            }

            .max-w-\[70%\] {
                max-width: 85% !important;
            }

            #chat-container {
                padding: 1rem !important;
            }

            .text-sm.font-black {
                font-size: 0.75rem;
            }

            footer {
                padding: 1rem !important;
            }
        }

        @media (max-width: 480px) {
            .max-w-\[70%\] {
                max-width: 100% !important;
            }

            .w-10.h-10 {
                width: 2rem;
                height: 2rem;
            }

            .p-4 {
                padding: 0.75rem;
            }

            .text-sm {
                font-size: 0.75rem !important;
            }
        }
    </style>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden bg-[#050505]">

        {{-- 1. Channels Sidebar --}}
        <aside class="w-64 border-r border-zinc-900 flex flex-col bg-[#08080a] overflow-y-auto">
            <div class="p-6">
                <h2 class="font-brand text-xl font-black italic text-white uppercase tracking-tighter">
                    Studio <span class="text-amber-500">Rooms</span>
                </h2>
            </div>

            <nav class="flex-1 px-3 space-y-1 overflow-y-auto">
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
                <div id="chat-container" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 md:space-y-6 no-scrollbar scroll-smooth">
                    @forelse ($messages as $message)
                        <div class="flex gap-3 md:gap-4 group {{ Auth::id() == $message->user_id ? 'flex-row-reverse' : '' }}" id="msg-{{ $message->id }}">
                            <img src="{{ $message->user->profile_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=b45309&color=fff' }}"
                                class="w-8 md:w-10 h-8 md:h-10 rounded-xl object-cover border border-zinc-800 flex-shrink-0"
                                referrerpolicy="no-referrer">

                            <div
                                class="max-w-[70%] {{ Auth::id() == $message->user_id ? 'items-end' : 'items-start' }} flex flex-col">
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="text-[9px] md:text-[10px] font-bold text-zinc-500 uppercase">{{ $message->user->name }}</span>
                                    <span
                                        class="text-[7px] md:text-[8px] text-zinc-700 font-bold">{{ $message->created_at->format('H:i') }}</span>
                                </div>

                                <div
                                    class="p-3 md:p-4 rounded-2xl text-xs md:text-sm leading-relaxed {{ Auth::id() == $message->user_id ? 'bg-amber-600 text-black font-semibold rounded-tr-none' : 'bg-zinc-900 text-zinc-300 rounded-tl-none border border-zinc-800' }}">
                                    {!! $message->message !!}
                                </div>
                            </div>

                            @if(Auth::id() == $message->user_id)
                            <div class="message-menu flex gap-1">
                                <button type="button" onclick="deleteMessage('{{ $message->id }}')" class="w-7 h-7 rounded-lg bg-red-600/20 text-red-500 hover:bg-red-600/30 transition flex items-center justify-center text-xs" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            @endif
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
                <footer class="p-4 md:p-6 bg-[#0a0a0c] border-t border-zinc-900">
                    <form id="chat-form" class="relative">
                        @csrf
                        <input type="hidden" name="channel_id" value="{{ $activeChannel->id }}">
                        <input type="file" id="media-input" name="file" class="hidden" accept="image/*,audio/*,video/*,.pdf,.doc,.docx" />

                        {{-- Media Preview --}}
                        <div id="media-preview" class="mb-3 hidden">
                            <img id="preview-img" class="media-preview hidden" />
                            <video id="preview-video" class="media-preview hidden" controls />
                            <div id="preview-filename" class="text-[10px] text-zinc-400 mt-2"></div>
                        </div>

                        <div id="chat-editor-wrapper"
                            class="bg-black border border-zinc-800 rounded-2xl overflow-hidden min-h-[48px] focus-within:border-amber-600/50 transition-colors">
                            <div id="chat-editor"></div>
                        </div>

                        <div class="absolute right-2 md:right-3 bottom-2 md:bottom-3 flex gap-1 md:gap-2 z-10">
                            <button type="button" id="media-btn" onclick="document.getElementById('media-input').click()"
                                class="w-7 md:w-8 h-7 md:h-8 rounded-lg bg-zinc-900 text-zinc-500 hover:text-white transition flex items-center justify-center">
                                <i class="fa-solid fa-paperclip text-xs"></i>
                            </button>
                            <button type="button" id="clear-media-btn" onclick="clearMediaPreview()" class="w-7 md:w-8 h-7 md:h-8 rounded-lg bg-red-600/20 text-red-500 hover:bg-red-600/30 transition flex items-center justify-center hidden">
                                <i class="fa-solid fa-x text-xs"></i>
                            </button>
                            <button type="submit"
                                class="bg-amber-600 hover:bg-amber-500 text-black px-4 md:px-6 py-1.5 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-amber-600/20">
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
        // Global file upload handler
        let selectedFile = null;

        document.getElementById('media-input')?.addEventListener('change', function(e) {
            selectedFile = this.files[0];
            if (selectedFile) {
                const previewDiv = document.getElementById('media-preview');
                const previewImg = document.getElementById('preview-img');
                const previewVideo = document.getElementById('preview-video');
                const previewFilename = document.getElementById('preview-filename');
                const clearBtn = document.getElementById('clear-media-btn');

                // Reset previews
                previewImg.classList.add('hidden');
                previewVideo.classList.add('hidden');
                previewDiv.classList.remove('hidden');

                if (selectedFile.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImg.src = event.target.result;
                        previewImg.classList.remove('hidden');
                    };
                    reader.readAsDataURL(selectedFile);
                } else if (selectedFile.type.startsWith('video/') || selectedFile.type.startsWith('audio/')) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewVideo.src = event.target.result;
                        previewVideo.classList.remove('hidden');
                    };
                    reader.readAsDataURL(selectedFile);
                } else {
                    previewFilename.textContent = selectedFile.name;
                }

                clearBtn.classList.remove('hidden');
            }
        });

        function clearMediaPreview() {
            selectedFile = null;
            document.getElementById('media-input').value = '';
            document.getElementById('media-preview').classList.add('hidden');
            document.getElementById('preview-img').classList.add('hidden');
            document.getElementById('preview-video').classList.add('hidden');
            document.getElementById('clear-media-btn').classList.add('hidden');
        }

        function deleteMessage(messageId) {
            if (!confirm('Are you sure you want to delete this message?')) return;

            $.ajax({
                url: `/community/messages/${messageId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(`#msg-${messageId}`).fadeOut(300, function() {
                        $(this).remove();
                    });
                },
                error: function(xhr) {
                    alert('Failed to delete message');
                    console.error(xhr);
                }
            });
        }

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

                // Track messages to prevent duplicates
                let loadedMessageIds = new Set();

                // Get all currently displayed message IDs
                const existingMessages = container.find('[id^="msg-"]');
                existingMessages.each(function() {
                    const id = $(this).attr('id')?.replace('msg-', '');
                    if (id) {
                        loadedMessageIds.add(id);
                    }
                });

                // Function to generate the HTML for a single message
                function appendMessageToUI(data, isMe) {
                    // Check if message already exists
                    if (loadedMessageIds.has(data.id)) {
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

                    // Build media attachment HTML
                    let mediaHTML = '';
                    if (data.metadata && data.metadata.file_path) {
                        const fileType = data.metadata.mime_type;
                        const fileName = data.metadata.file_name;
                        const fileUrl = data.metadata.file_path;

                        if (fileType.startsWith('image/')) {
                            mediaHTML = `<img src="${fileUrl}" class="media-preview" alt="Attached image" />`;
                        } else if (fileType.startsWith('audio/')) {
                            mediaHTML = `<audio class="media-preview w-full" controls><source src="${fileUrl}" type="${fileType}"></audio>`;
                        } else if (fileType.startsWith('video/')) {
                            mediaHTML = `<video class="media-preview w-full" controls><source src="${fileUrl}" type="${fileType}"></video>`;
                        } else {
                            mediaHTML = `<a href="${fileUrl}" class="inline-flex items-center gap-2 p-2 bg-opacity-10 rounded border border-current text-xs">
                                <i class="fa-solid fa-file"></i> ${fileName}
                            </a>`;
                        }
                    }

                    const deleteBtn = isMe ? `<div class="message-menu flex gap-1">
                            <button type="button" onclick="deleteMessage('${data.id}')" class="w-7 h-7 rounded-lg bg-red-600/20 text-red-500 hover:bg-red-600/30 transition flex items-center justify-center text-xs" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>` : '';

                    const html = `
                    <div class="flex gap-3 md:gap-4 group animate-fade-in ${isMe ? 'flex-row-reverse' : ''}" id="msg-${data.id}">
                        <img src="${avatar}" class="w-8 md:w-10 h-8 md:h-10 rounded-xl object-cover border border-zinc-800 flex-shrink-0" referrerpolicy="no-referrer">
                        <div class="max-w-[70%] ${isMe ? 'items-end' : 'items-start'} flex flex-col">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[9px] md:text-[10px] font-bold text-zinc-500 uppercase">${data.user.name}</span>
                                <span class="text-[7px] md:text-[8px] text-zinc-700 font-bold">${time}</span>
                            </div>
                            <div class="p-3 md:p-4 rounded-2xl text-xs md:text-sm ${isMe ? 'bg-amber-600 text-black font-medium rounded-tr-none' : 'bg-zinc-900 text-zinc-300 rounded-tl-none border border-zinc-800'}">
                                ${data.message}
                                ${mediaHTML}
                            </div>
                        </div>
                        ${deleteBtn}
                    </div>
                `;
                    container.append(html);
                    loadedMessageIds.add(data.id);
                    scrollToBottom();
                }

                // POLLING: Fetch new messages every 2 seconds
                function pollForNewMessages() {
                    $.ajax({
                        url: '{{ route('community.message.index', ['channelId' => $activeChannel->id]) }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response && response.data && response.data.length > 0) {
                                response.data.forEach(msg => {
                                    if (!loadedMessageIds.has(msg.id)) {
                                        appendMessageToUI(msg, msg.user_id === '{{ Auth::id() }}');
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('Failed to fetch messages:', xhr);
                        }
                    });
                }

                // Start polling after 3 seconds
                setTimeout(() => {
                    setInterval(pollForNewMessages, 2000);
                }, 3000);

                // WebSocket Listener (as fallback for real-time updates)
                if (typeof Echo !== 'undefined') {
                    Echo.channel('community.chat.{{ $activeChannel->id }}')
                        .listen('MessageSent', (e) => {
                            console.log('Message received via Echo:', e.message);
                            appendMessageToUI(e.message, e.message.user_id === '{{ Auth::id() }}');
                        });
                } else {
                    console.log('Echo not available, relying on polling');
                }

                // Handle Form Submission
                $('#chat-form').on('submit', function(e) {
                    e.preventDefault();
                    const content = quill.root.innerHTML;
                    const text = quill.getText().trim();

                    if (text.length === 0 && !selectedFile) {
                        console.warn('Empty message and no file');
                        return;
                    }

                    const $submitBtn = $(this).find('button[type="submit"]');
                    $submitBtn.prop('disabled', true);

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('channel_id', '{{ $activeChannel->id }}');
                    formData.append('message', content);

                    if (selectedFile) {
                        formData.append('file', selectedFile);
                    }

                    $.ajax({
                        url: '{{ route('community.message.store') }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log('Message sent successfully:', response);
                            quill.setContents([]);
                            quill.focus();
                            clearMediaPreview();
                            $submitBtn.prop('disabled', false);
                            appendMessageToUI(response.message, true);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', {status: status, error: error, response: xhr.responseText});
                            let errorMsg = 'Failed to send message. Please try again.';
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON?.errors;
                                if (errors) {
                                    errorMsg = Object.values(errors).flat().join('\n');
                                }
                            }
                            alert(errorMsg);
                            $submitBtn.prop('disabled', false);
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
