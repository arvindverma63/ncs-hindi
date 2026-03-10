<x-webapp-layout>
    {{-- 1. Quill Assets --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        /* Studio Dark Theme Overrides */
        .ql-toolbar.ql-snow {
            border: 1px solid #27272a !important;
            border-radius: 1rem 1rem 0 0;
            background: #0a0a0c;
        }

        .ql-container.ql-snow {
            border: 1px solid #27272a !important;
            border-radius: 0 0 1rem 1rem;
            background: #000;
            color: #d4d4d8;
            font-family: 'Inter', sans-serif;
        }

        /* Whitelisted Fonts CSS */
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="serif"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="serif"]::before {
            content: 'Serif';
            font-family: Georgia, serif;
        }

        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="monospace"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="monospace"]::before {
            content: 'Monospace';
            font-family: Monaco, monospace;
        }

        .ql-editor {
            min-height: 200px;
            font-size: 0.875rem;
        }

        .ql-snow .ql-stroke {
            stroke: #71717a;
        }

        .ql-snow .ql-fill {
            fill: #71717a;
        }

        .ql-snow .ql-picker {
            color: #71717a;
        }

        .ql-snow.ql-toolbar button:hover .ql-stroke,
        .ql-snow.ql-toolbar button.ql-active .ql-stroke {
            stroke: #f59e0b;
        }
    </style>

    <div class="max-w-4xl mx-auto pb-24">
        {{-- Navigation --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-zinc-500 hover:text-white transition group">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">Back to Feed</span>
            </a>
            <span
                class="px-3 py-1 rounded-full bg-zinc-900 border border-zinc-800 text-[9px] font-bold text-zinc-400 uppercase tracking-tighter">
                {{ $post->category->name ?? 'Uncategorized' }}
            </span>
        </div>

        {{-- Main Post --}}
        <article class="forum-card p-8 lg:p-12 bg-[#0a0a0c] mb-12">
            <header class="mb-10">
                <h1
                    class="font-brand text-4xl font-black text-white italic uppercase tracking-tighter leading-none mb-6">
                    {{ $post->title }}
                </h1>
                <div class="flex items-center gap-4 p-4 bg-zinc-950 rounded-2xl border border-zinc-900">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&background=b45309&color=fff"
                        class="w-12 h-12 rounded-xl">
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-white">{{ $post->author->name }}</h4>
                        <p class="text-[10px] text-zinc-600 font-bold uppercase tracking-widest">
                            {{ $post->author->profile->rank_title ?? 'Artist' }} •
                            {{ $post->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </header>
            <div class="prose prose-invert prose-amber max-w-none text-zinc-300 leading-relaxed font-medium mb-10">
                {!! $post->content !!}
            </div>
        </article>

        {{-- Discussion --}}
        <section class="space-y-8">
            <h3 class="font-brand text-xl font-bold italic text-white uppercase tracking-tight px-2">
                Discussion <span class="text-zinc-700 ml-2">{{ $post->replies->count() }}</span>
            </h3>


            @foreach ($post->replies as $reply)
                <div class="flex gap-4 group">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=27272a&color=a1a1aa"
                        class="w-10 h-10 rounded-xl flex-shrink-0">
                    <div
                        class="flex-1 bg-zinc-900/30 border border-zinc-800/50 rounded-3xl p-6 group-hover:border-zinc-700 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="text-xs font-bold text-white">{{ $reply->user->name }}</h5>
                            <span
                                class="text-[9px] font-bold text-zinc-600 uppercase">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>

                        <div id="reply-body-{{ $reply->id }}"
                            class="text-sm text-zinc-400 font-medium prose prose-invert prose-sm max-w-none">
                            {!! $reply->content !!}
                        </div>

                        {{-- Edit/Delete Actions (24h Window) --}}
                        @if (Auth::id() === $reply->user_id && $reply->created_at->diffInHours(now()) < 24)
                            <div class="mt-4 pt-4 border-t border-zinc-800/50 flex gap-4">
                                <button onclick="prepareEdit('{{ $reply->id }}')"
                                    class="text-[10px] font-black text-amber-600 hover:text-amber-400 uppercase tracking-widest flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('webapp.forum.reply.delete', $reply->id) }}" method="POST"
                                    onsubmit="return confirm('Delete permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-[10px] font-black text-zinc-700 hover:text-red-600 uppercase tracking-widest flex items-center gap-1">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            @auth
                <form id="replyForm" action="{{ route('webapp.forum.reply', $post->id) }}" method="POST"
                    class="forum-card p-6 bg-[#0a0a0c]">
                    @csrf
                    <div id="method_field"></div> {{-- Placeholder for PUT method during edit --}}

                    <div id="reply-editor" class="mb-4"></div>
                    <input type="hidden" name="content" id="hiddenContent">

                    <div class="flex justify-between items-center">
                        <div id="edit-indicator"
                            class="hidden text-[10px] font-bold text-amber-500 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-pen-nib"></i> Editing Mode
                            <button type="button" onclick="cancelEdit()"
                                class="text-zinc-500 hover:text-white underline ml-2">Cancel</button>
                        </div>
                        <button type="submit" id="submit-btn"
                            class="btn-vault px-8 py-2.5 text-[10px] font-black uppercase tracking-widest ml-auto">
                            Post Reply
                        </button>
                    </div>
                </form>
            @endauth

        </section>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        let quill;
        document.addEventListener('DOMContentLoaded', function() {
            var Font = Quill.import('formats/font');
            Font.whitelist = ['serif', 'monospace'];
            Quill.register(Font, true);

            var toolbarOptions = [
                [{
                    'font': Font.whitelist
                }],
                [{
                    'header': [1, 2, 3, false]
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                ['blockquote', 'code-block'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                ['link', 'image', 'video'],
                ['clean']
            ];

            quill = new Quill('#reply-editor', {
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Add to the conversation...',
                theme: 'snow'
            });

            const form = document.getElementById('replyForm');
            if (form) {
                form.onsubmit = function() {
                    document.querySelector('#hiddenContent').value = quill.root.innerHTML;
                    if (quill.getText().trim().length === 0 && !quill.root.innerHTML.includes('<img')) {
                        alert("Content cannot be empty.");
                        return false;
                    }
                    return true;
                };
            }
        });

        function prepareEdit(replyId) {
            const content = document.getElementById(`reply-body-${replyId}`).innerHTML;
            quill.root.innerHTML = content;

            const form = document.getElementById('replyForm');
            form.action = `/forum/reply/${replyId}`;
            document.getElementById('method_field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('edit-indicator').classList.remove('hidden');
            document.getElementById('submit-btn').innerText = 'Update Reply';

            window.scrollTo({
                top: form.offsetTop - 100,
                behavior: 'smooth'
            });
        }

        function cancelEdit() {
            location.reload(); // Simplest way to reset form state
        }
    </script>
</x-webapp-layout>
