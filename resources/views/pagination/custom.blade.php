@if ($paginator->hasPages())
    <nav style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
        @if ($paginator->onFirstPage())
            <span class="btn btn-ghost" style="padding:8px 14px;opacity:.4;"><x-icon n="back" style="width:14px;height:14px;" /></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-ghost" style="padding:8px 14px;"><x-icon n="back" style="width:14px;height:14px;" /></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="muted" style="padding:0 6px;">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="btn btn-pink" style="padding:8px 15px;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="btn btn-outline" style="padding:8px 15px;">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-ghost" style="padding:8px 14px;transform:scaleX(-1);"><x-icon n="back" style="width:14px;height:14px;" /></a>
        @else
            <span class="btn btn-ghost" style="padding:8px 14px;opacity:.4;transform:scaleX(-1);"><x-icon n="back" style="width:14px;height:14px;" /></span>
        @endif
    </nav>
@endif
