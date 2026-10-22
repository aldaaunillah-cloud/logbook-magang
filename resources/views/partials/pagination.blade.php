@if($paginator->hasPages())
    <nav class="mt-7 flex flex-wrap items-center justify-between gap-4" aria-label="Navigasi halaman">
        <p class="text-xs text-muted">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} catatan</p>
        <div class="flex items-center gap-3">
            @if($paginator->previousPageUrl())<a href="{{ $paginator->previousPageUrl() }}" class="button button-quiet" aria-label="Halaman sebelumnya">← <span class="hidden sm:inline">Sebelumnya</span></a>@endif
            <span class="text-xs text-muted">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
            @if($paginator->nextPageUrl())<a href="{{ $paginator->nextPageUrl() }}" class="button button-quiet" aria-label="Halaman berikutnya"><span class="hidden sm:inline">Berikutnya</span> →</a>@endif
        </div>
    </nav>
@endif
