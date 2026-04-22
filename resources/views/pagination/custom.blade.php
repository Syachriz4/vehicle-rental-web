@if ($paginator->hasPages())
    <nav class="flex items-center gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-xs rounded text-zinc-400 dark:text-zinc-600 bg-zinc-100 dark:bg-zinc-800 cursor-not-allowed">
                <span class="material-symbols-outlined text-base">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-xs rounded text-zinc-700 dark:text-zinc-300 bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 transition-colors">
                <span class="material-symbols-outlined text-base">chevron_left</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-2 py-2 text-xs text-zinc-500 dark:text-zinc-400">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 text-xs rounded font-semibold bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-xs rounded text-zinc-700 dark:text-zinc-300 bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-xs rounded text-zinc-700 dark:text-zinc-300 bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 transition-colors">
                <span class="material-symbols-outlined text-base">chevron_right</span>
            </a>
        @else
            <span class="px-3 py-2 text-xs rounded text-zinc-400 dark:text-zinc-600 bg-zinc-100 dark:bg-zinc-800 cursor-not-allowed">
                <span class="material-symbols-outlined text-base">chevron_right</span>
            </span>
        @endif
    </nav>
@endif
