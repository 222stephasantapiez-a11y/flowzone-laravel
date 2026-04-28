{{--
    Partial: barra de búsqueda reutilizable
    Variables esperadas:
      $searchRoute  — nombre de la ruta (ej: 'admin.hoteles.index')
      $placeholder  — texto del placeholder (ej: 'Buscar hoteles...')
      $busqueda     — valor actual del campo (puede ser '')
--}}
<form method="GET" action="{{ route($searchRoute) }}"
      style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;margin-bottom:1rem;">
    {{-- Conservar per_page si existe --}}
    @if(request('per_page'))
        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
    @endif

    <input type="text"
           name="busqueda"
           value="{{ $busqueda ?? '' }}"
           placeholder="{{ $placeholder ?? 'Buscar...' }}"
           style="flex:1;min-width:200px;max-width:360px;padding:.45rem .75rem;
                  border:1px solid var(--border,#e2e8f0);border-radius:var(--radius,8px);
                  font-size:.875rem;outline:none;"
           aria-label="{{ $placeholder ?? 'Buscar' }}">

    <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-magnifying-glass fa-xs"></i> Filtrar
    </button>

    @if($busqueda ?? '')
        <a href="{{ route($searchRoute) }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-xmark fa-xs"></i> Limpiar
        </a>
    @endif
</form>
