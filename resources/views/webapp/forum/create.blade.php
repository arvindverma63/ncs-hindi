<x-webapp-layout>
    {{-- Include Quill Snow Theme from CDN --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        /* Customizing Quill for the NCS Dark Theme */
        .ql-toolbar.ql-snow {
            border: 1px solid #1a1a1c !important;
            background: #0f0f11;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .ql-container.ql-snow {
            border: 1px solid #1a1a1c !important;
            background: #000;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            min-height: 300px;
            font-family: 'Inter', sans-serif;
            color: #d4d4d8;
        }

        .ql-editor.ql-blank::before {
            color: #52525b !important;
            font-style: normal;
        }

        .ql-snow .ql-stroke {
            stroke: #71717a !important;
        }

        .ql-snow .ql-fill {
            fill: #71717a !important;
        }

        .ql-snow .ql-picker {
            color: #71717a !important;
        }
    </style>

    <div class="max-w-4xl mx-auto pb-24">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('home') }}"
                class="w-10 h-10 rounded-xl bg-zinc-900 flex items-center justify-center text-zinc-500 hover:text-white transition">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <h1 class="font-brand text-2xl font-black text-white uppercase italic tracking-tighter">New <span
                    class="text-amber-500">Vault Post</span></h1>
        </div>

        <form id="vault-post-form" action="{{ route('webapp.forum.store') }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="forum-card p-8 lg:p-10 bg-[#0a0a0c]">
                <div class="space-y-6">
                    {{-- Title --}}
                    <div>
                        <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Thread
                            Title</label>
                        <input name="title" type="text" required
                            class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-lg font-bold text-white focus:border-amber-600 outline-none transition mt-1"
                            placeholder="e.g. 'Tum Mile' Official Stems Release">
                    </div>

                    {{-- Category & Metadata Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label
                                class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Category</label>
                            <select name="category_id"
                                class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-zinc-400 focus:border-amber-600 outline-none transition mt-1 appearance-none">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- Quill Editor Description --}}
                    <div>
                        <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Content /
                            Technical Details</label>
                        <div class="mt-1">
                            {{-- The Quill Container --}}
                            <div id="editor-container"></div>
                            {{-- The Hidden Input --}}
                            <input type="hidden" name="content" id="content">
                        </div>
                    </div>
                </div>
            </div>

            {{-- File Upload Card --}}
            <div class="forum-card p-8 border-dashed border-zinc-800 bg-zinc-900/20">
                <div class="flex flex-col items-center justify-center text-center py-4">
                    <div class="w-16 h-16 rounded-2xl bg-zinc-900 flex items-center justify-center mb-4 text-amber-500">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-tight">Upload Stems Zip</h4>
                    <input type="file" name="stem_file" class="hidden" id="stem_file">
                    <button type="button" onclick="document.getElementById('stem_file').click()"
                        class="mt-6 px-6 py-2 rounded-xl border border-zinc-700 text-[10px] font-black text-zinc-400 hover:text-white hover:bg-zinc-800 transition uppercase tracking-widest">
                        Select File
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="submit"
                    class="btn-vault px-12 py-4 text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-red-900/30">
                    Publish to Vault
                </button>
            </div>
        </form>
    </div>

    {{-- Quill Library --}}
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'], // toggled buttons
            ['blockquote', 'code-block'],
            [{
                'header': 1
            }, {
                'header': 2
            }], // custom button values
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'script': 'sub'
            }, {
                'script': 'super'
            }], // superscript/subscript
            [{
                'indent': '-1'
            }, {
                'indent': '+1'
            }], // outdent/indent
            [{
                'direction': 'rtl'
            }], // text direction
            [{
                'size': ['small', false, 'large', 'huge']
            }], // custom dropdown
            [{
                'header': [1, 2, 3, 4, 5, 6, false]
            }],
            [{
                'color': []
            }, {
                'background': []
            }], // dropdown with defaults from theme
            [{
                'font': []
            }],
            [{
                'align': []
            }],
            ['link', 'image', 'video'], // link and media
            ['clean'] // remove formatting button
        ];

        var quill = new Quill('#editor-container', {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Describe your stems, share production secrets, or add mixing tips...',
            theme: 'snow'
        });

        // Sync Quill content to hidden input before form submission
        var form = document.getElementById('vault-post-form');
        form.onsubmit = function() {
            var content = document.querySelector('input[name=content]');
            content.value = quill.root.innerHTML;
        };
    </script>
</x-webapp-layout>
