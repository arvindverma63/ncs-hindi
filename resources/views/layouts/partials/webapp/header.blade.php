<header class="h-16 md:h-20 flex items-center justify-between px-4 md:px-8 bg-black/50 backdrop-blur-xl border-b border-white/5 z-50">
    <h2 class="font-brand text-sm md:text-lg font-bold text-white uppercase italic tracking-tighter">Discussion & Music</h2>
    <div class="flex items-center gap-4">
        <div class="relative hidden md:block" id="search-container">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
            <input type="text" id="vault-search" placeholder="Search the Threads..." aria-label="Search threads and music"
                class="bg-zinc-900/50 border border-zinc-800 rounded-full py-2 px-12 text-sm focus:border-amber-700 outline-none w-64 transition">

            <div id="search-results"
                class="absolute top-full mt-2 w-80 bg-zinc-900 border border-zinc-800 rounded-xl shadow-2xl hidden overflow-hidden">
                <div class="p-4 text-xs text-zinc-500 uppercase font-bold border-b border-white/5">Results</div>
                <div id="results-list" class="max-h-96 overflow-y-auto"></div>
            </div>
        </div>
        <button id="theme-toggle" type="button" aria-label="Toggle theme" class="w-10 h-10 rounded-xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 hover:text-white transition">
            <i class="fa-solid fa-moon text-sm" id="theme-toggle-dark-icon"></i>
            <i class="fa-solid fa-sun text-sm hidden" id="theme-toggle-light-icon"></i>
        </button>
        {{-- Mobile Mini Game Link --}}
        {{-- <a href="{{ route('webapp.game') }}" aria-label="Play Mini Game" class="md:hidden w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 hover:bg-amber-500/20 transition shadow-[0_0_15px_rgba(245,158,11,0.2)]">
            <i class="fa-solid fa-gamepad text-sm"></i>
        </a> --}}
        <a href="{{ route('webapp.forum.create') }}" aria-label="Create New Thread" class="hidden md:inline-flex btn-vault px-6 py-2.5 text-[10px] uppercase font-black">
            New Thread
        </a>
    </div>
</header>


@push('scripts')
    <script>
        $(document).ready(function() {
            const $searchInput = $('#vault-search');
            const $resultsContainer = $('#search-results');
            const $resultsList = $('#results-list');
            let searchTimer;

            $searchInput.on('input', function() {
                const query = $(this).val();
                clearTimeout(searchTimer);

                if (query.length < 3) {
                    $resultsContainer.addClass('hidden');
                    return;
                }

                // Show a loading state in the dropdown
                $resultsContainer.removeClass('hidden');
                $resultsList.html(
                    '<div class="p-4 text-center text-zinc-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Searching...</div>'
                    );

                searchTimer = setTimeout(() => {
                    fetchResults(query);
                }, 300);
            });

            // Redirect on Enter
            $searchInput.on('keypress', function(e) {
                if (e.which === 13 && $(this).val().length >= 3) {
                    window.location.href = "{{ route('webapp.search.index') }}?query=" + encodeURIComponent(
                        $(this).val());
                }
            });

            function fetchResults(query) {
                $.ajax({
                    url: "{{ route('webapp.search') }}",
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        let html = '';

                        if (data.threads && data.threads.length > 0) {
                            html +=
                                `<div class="p-2 bg-white/5 text-[10px] text-amber-500 font-bold uppercase">Discussions</div>`;
                            $.each(data.threads, function(i, thread) {
                                html +=
                                    `<a href="/forum/thread/${thread.slug}" class="block p-3 hover:bg-white/5 text-sm border-b border-white/5 transition">${thread.title}</a>`;
                            });
                        }

                        if (data.music && data.music.length > 0) {
                            html +=
                                `<div class="p-2 bg-white/5 text-[10px] text-amber-500 font-bold uppercase mt-2">Music music</div>`;
                            $.each(data.music, function(i, music) {
                                html += `
                                <a href="/music/${music.slug}" class="flex items-center gap-3 p-3 hover:bg-white/5 border-b border-white/5 transition">
                                    <div class="text-sm">
                                        <p class="font-medium text-zinc-200">${music.title}</p>
                                        <p class="text-xs text-zinc-500">${music.artist_name || 'Unknown Artist'}</p>
                                    </div>
                                </a>`;
                            });
                        }

                        if (html === '') {
                            html =
                                `<div class="p-4 text-sm text-zinc-500 text-center">No results found for "${query}"</div>`;
                        }

                        $resultsList.html(html);
                    },
                    error: function(xhr) {
                        $resultsList.html(
                            '<div class="p-4 text-sm text-red-500 text-center">Error fetching results.</div>'
                            );
                    }
                });
            }

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#search-container').length) {
                    $resultsContainer.addClass('hidden');
                }
            });

            // Theme Switcher Toggle Logic
            const $themeToggleBtn = $('#theme-toggle');
            const $themeToggleDarkIcon = $('#theme-toggle-dark-icon');
            const $themeToggleLightIcon = $('#theme-toggle-light-icon');

            function updateIcons() {
                if ($('html').hasClass('light')) {
                    $themeToggleDarkIcon.addClass('hidden');
                    $themeToggleLightIcon.removeClass('hidden');
                } else {
                    $themeToggleDarkIcon.removeClass('hidden');
                    $themeToggleLightIcon.addClass('hidden');
                }
            }

            // Set initial icons on document ready
            updateIcons();

            if ($themeToggleBtn.length) {
                $themeToggleBtn.on('click', function () {
                    if ($('html').hasClass('light')) {
                        $('html').removeClass('light').addClass('dark');
                        localStorage.theme = 'dark';
                    } else {
                        $('html').removeClass('dark').addClass('light');
                        localStorage.theme = 'light';
                    }
                    updateIcons();
                });
            }
        });
    </script>
@endpush







