@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Tombol Previous --}}
        @if ($paginator->onFirstPage())
            <span style="opacity:.4;cursor:not-allowed;padding:6px 12px;border:1px solid #ddd;border-radius:5px;font-size:13px">← Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding:6px 12px;border:1px solid #ddd;border-radius:5px;font-size:13px;text-decoration:none;color:#333">← Prev</a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:6px 12px;border:1px solid #ddd;border-radius:5px;font-size:13px">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:6px 12px;background:#3498db;color:#fff;border:1px solid #3498db;border-radius:5px;font-size:13px;font-weight:600">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding:6px 12px;border:1px solid #ddd;border-radius:5px;font-size:13px;text-decoration:none;color:#333">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding:6px 12px;border:1px solid #ddd;border-radius:5px;font-size:13px;text-decoration:none;color:#333">Next →</a>
        @else
            <span style="opacity:.4;cursor:not-allowed;padding:6px 12px;border:1px solid #ddd;border-radius:5px;font-size:13px">Next →</span>
        @endif
    </div>
@endif