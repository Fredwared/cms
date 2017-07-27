@if ($paginator->hasPages())
    <ul class="pagination">
        <?php
        $start = $paginator->currentPage() - config('cms.limit_pagination'); // show 3 pagination links before current
        $end = $paginator->currentPage() + config('cms.limit_pagination'); // show 3 pagination links after current
        if ($start < 1) $start = 1; // reset start to 1
        if ($end >= $paginator->lastPage()) $end = $paginator->lastPage(); // reset end to last page
        ?>

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @if ($start > 1)
            <li><a href="{{ $paginator->url(1) }}">1</a></li>
            @if ($start > 2)
                <li class="disabled"><span>...</span></li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $paginator->currentPage())
                <li class="active"><span>{{ $i }}</span></li>
            @else
                <li><a href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor
        @if ($end < $paginator->lastPage())
            @if ($end < $paginator->lastPage() - 1)
                <li class="disabled"><span>...</span></li>
            @endif
            <li><a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="disabled"><span>&raquo;</span></li>
        @endif
    </ul>
@endif