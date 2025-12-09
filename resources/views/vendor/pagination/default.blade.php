@if ($paginator->hasPages())
    <style>
        .pagination a:hover {
            background-color: #f9fafb !important;
            border-color: #d1d5db !important;
        }
        .pagination svg,
        .pagination a svg,
        .pagination span svg,
        .pagination li svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            min-width: 14px !important;
            min-height: 14px !important;
            flex-shrink: 0 !important;
        }
        .pagination li {
            margin: 0 !important;
            list-style: none !important;
        }
        .pagination a,
        .pagination span {
            font-size: 0.875rem !important;
            line-height: 1 !important;
        }
        .pagination a,
        .pagination span[aria-hidden="true"] {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
    </style>
    <nav>
        <ul class="pagination" style="display: flex; list-style: none; padding: 0; margin: 0; gap: 0.5rem; align-items: center; flex-wrap: wrap; justify-content: center;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')" style="opacity: 0.5; cursor: not-allowed;">
                    <span aria-hidden="true" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; color: #9ca3af; background-color: #f3f4f6; min-width: 2rem; height: 2rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px; display: block; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; color: #374151; background-color: white; text-decoration: none; transition: all 0.2s; cursor: pointer; min-width: 2rem; height: 2rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px; display: block; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true" style="opacity: 0.5;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 0.75rem; color: #6b7280;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page">
                                <span style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem 0.5rem; border: 1px solid #3b82f6; border-radius: 0.375rem; color: white; background-color: #3b82f6; font-weight: 500; min-width: 2rem; height: 2rem; font-size: 0.875rem;">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; color: #374151; background-color: white; text-decoration: none; transition: all 0.2s; cursor: pointer; min-width: 2rem; height: 2rem; font-size: 0.875rem;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; color: #374151; background-color: white; text-decoration: none; transition: all 0.2s; cursor: pointer; min-width: 2rem; height: 2rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px; display: block; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')" style="opacity: 0.5; cursor: not-allowed;">
                    <span aria-hidden="true" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; color: #9ca3af; background-color: #f3f4f6; min-width: 2rem; height: 2rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px; display: block; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
