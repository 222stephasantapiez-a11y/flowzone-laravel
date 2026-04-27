{{--
    Partial: Paginación reutilizable
    Parámetros:
      $paginator  - instancia LengthAwarePaginator (requerido)
      $perPage    - valor actual de filas por página (requerido)
      $options    - array de opciones por página (default: [5,10,25,50,100])
--}}
@php
    $options = $options ?? [5, 10, 25, 50, 100];
    $window  = 2; // páginas a cada lado de la actual
    $start   = max(1, $paginator->currentPage() - $window);
    $end     = min($paginator->lastPage(), $paginator->currentPage() + $window);
@endphp

@if($paginator->lastPage() > 1 || $paginator->total() > min($options))
<div class="pagination-bar">

    {{-- Info --}}
    <div class="pagination-info">
        @if($paginator->total() > 0)
            Mostrando
            <strong>{{ number_format($paginator->firstItem()) }}</strong>–<strong>{{ number_format($paginator->lastItem()) }}</strong>
            de <strong>{{ number_format($paginator->total()) }}</strong> registros
        @else
            Sin registros
        @endif
    </div>

    {{-- Botones de página --}}
    @if($paginator->lastPage() > 1)
    <div class="pagination-links">

        {{-- Primera página --}}
        @if($paginator->currentPage() > $window + 1)
            <a href="{{ $paginator->url(1) }}" class="page-btn" title="Primera página">
                <i class="fa-solid fa-angles-left fa-xs"></i>
            </a>
        @endif

        {{-- Anterior --}}
        @if($paginator->onFirstPage())
            <span class="page-btn page-btn--disabled" aria-disabled="true">
                <i class="fa-solid fa-chevron-left fa-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" aria-label="Página anterior">
                <i class="fa-solid fa-chevron-left fa-xs"></i>
            </a>
        @endif

        {{-- Ellipsis izquierdo --}}
        @if($start > 1)
            <a href="{{ $paginator->url(1) }}" class="page-btn">1</a>
            @if($start > 2)
                <span class="page-btn page-btn--disabled">…</span>
            @endif
        @endif

        {{-- Páginas numeradas --}}
        @for($p = $start; $p <= $end; $p++)
            @if($p == $paginator->currentPage())
                <span class="page-btn page-btn--active" aria-current="page">{{ $p }}</span>
            @else
                <a href="{{ $paginator->url($p) }}" class="page-btn">{{ $p }}</a>
            @endif
        @endfor

        {{-- Ellipsis derecho --}}
        @if($end < $paginator->lastPage())
            @if($end < $paginator->lastPage() - 1)
                <span class="page-btn page-btn--disabled">…</span>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-btn">{{ $paginator->lastPage() }}</a>
        @endif

        {{-- Siguiente --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" aria-label="Página siguiente">
                <i class="fa-solid fa-chevron-right fa-xs"></i>
            </a>
        @else
            <span class="page-btn page-btn--disabled" aria-disabled="true">
                <i class="fa-solid fa-chevron-right fa-xs"></i>
            </span>
        @endif

        {{-- Última página --}}
        @if($paginator->currentPage() < $paginator->lastPage() - $window)
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-btn" title="Última página">
                <i class="fa-solid fa-angles-right fa-xs"></i>
            </a>
        @endif

    </div>
    @endif

    {{-- Selector de filas por página --}}
    <form method="GET" action="{{ url()->current() }}" class="per-page-form">
        {{-- Preservar todos los filtros activos excepto page y per_page --}}
        @foreach(request()->except(['page', 'per_page']) as $key => $value)
            @if(is_array($value))
                @foreach($value as $v)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <label class="per-page-label" for="per_page_select">Filas:</label>
        <select id="per_page_select" name="per_page" class="per-page-select"
                onchange="this.form.submit()" aria-label="Registros por página">
            @foreach($options as $n)
                <option value="{{ $n }}" {{ (int)$perPage === (int)$n ? 'selected' : '' }}>
                    {{ $n }}
                </option>
            @endforeach
        </select>
    </form>

</div>
@endif
