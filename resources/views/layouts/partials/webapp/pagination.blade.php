@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between w-full">
        {{-- Mobile View --}}
        <div class="flex justify-between items-center w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] bg-zinc-950 border border-zinc-900 text-zinc-600 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left mr-1.5"></i> {!! __('Prev') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] bg-zinc-900/50 border border-zinc-800 text-zinc-300 hover:text-white hover:border-amber-500/40 transition duration-155">
                    <i class="fa-solid fa-chevron-left mr-1.5 text-amber-500"></i> {!! __('Prev') !!}
                </a>
            @endif

            <span class="text-[10px] font-black uppercase tracking-[0.1em] text-zinc-400">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] bg-zinc-900/50 border border-zinc-800 text-zinc-300 hover:text-white hover:border-amber-500/40 transition duration-155">
                    {!! __('Next') !!} <i class="fa-solid fa-chevron-right ml-1.5 text-amber-500"></i>
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] bg-zinc-950 border border-zinc-900 text-zinc-600 cursor-not-allowed">
                    {!! __('Next') !!} <i class="fa-solid fa-chevron-right ml-1.5"></i>
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-6">
            <div>
                <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">
                    {!! __('Showing') !!}
                    <span class="text-white font-black">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="text-white font-black">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="text-white font-black">{{ $paginator->total() }}</span>
                    {!! $paginationLabel ?? __('releases') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex gap-1.5">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-3.5 py-2.5 rounded-xl text-[10px] bg-zinc-950 border border-zinc-900 text-zinc-600 cursor-not-allowed" aria-hidden="true">
                                <i class="fa-solid fa-chevron-left"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3.5 py-2.5 rounded-xl text-[10px] bg-zinc-900/50 border border-zinc-800 text-zinc-400 hover:text-white hover:border-amber-500/40 transition duration-155" aria-label="{{ __('pagination.previous') }}">
                            <i class="fa-solid fa-chevron-left text-amber-500"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2.5 rounded-xl text-[11px] font-bold bg-zinc-950 border border-zinc-900 text-zinc-600 cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2.5 rounded-xl text-[11px] font-black bg-gradient-to-r from-amber-500 to-amber-600 text-black border border-amber-400 shadow-lg shadow-amber-500/10 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2.5 rounded-xl text-[11px] font-bold bg-zinc-900/50 border border-zinc-800 text-zinc-400 hover:text-white hover:border-amber-500/40 transition duration-155" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3.5 py-2.5 rounded-xl text-[10px] bg-zinc-900/50 border border-zinc-800 text-zinc-400 hover:text-white hover:border-amber-500/40 transition duration-155" aria-label="{{ __('pagination.next') }}">
                            <i class="fa-solid fa-chevron-right text-amber-500"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-3.5 py-2.5 rounded-xl text-[10px] bg-zinc-950 border border-zinc-900 text-zinc-600 cursor-not-allowed" aria-hidden="true">
                                <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
