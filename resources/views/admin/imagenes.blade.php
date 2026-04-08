@extends('layouts.admin')

@section('title', 'Galería / Hero')
@section('page-title', 'Galería / Hero')
@section('page-subtitle', 'Gestiona las imágenes del sitio · Hero, Destacadas y Cards')

@section('content')

{{-- Hero Preview --}}
@php $heroActiva = $imagenes->where('seccion','hero')->where('activa',true)->sortBy('orden')->first(); @endphp
@if($heroActiva)
<div class="admin-section" style="padding:0;overflow:hidden;margin-bottom:1.5rem;">
    <div class="hero-preview" style="position:relative;height:200px;overflow:hidden;border-radius:var(--radius-lg);">
        <img id="preview-img" src="{{ $heroActiva->public_url }}" alt="Hero preview"
             style="width:100%;height:100%;object-fit:cover;">
        <div class="hero-preview-overlay" style="position:absolute;inset:0;background:linear-gradient(160deg,rgba(27,67,50,.7) 0%,rgba(64,145,108,.35) 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:1.5rem;">
            <div style="font-size:.7rem;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.4rem;">
                <i class="fa-solid fa-eye"></i> Preview — Imagen Hero Activa
            </div>
            <h3 style="color:#fff;font-size:1.3rem;margin:0 0 .25rem;">Descubre Ortega, Tolima</h3>
            <p style="color:rgba(255,255,255,.75);font-size:.85rem;margin:0;">Así se verá en la página de inicio</p>
        </div>
    </div>
</div>
@endif

{{-- Tabs --}}
<div class="admin-section">
    <div class="img-manager-tabs" style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.5rem;border-bottom:1px solid var(--gray-200);padding-bottom:1rem;">
        <button class="img-manager-tab active" onclick="showTab('hero',this)">
            <i class="fa-solid fa-image"></i> Hero ({{ $imagenes->where('seccion','hero')->count() }})
        </button>
        <button class="img-manager-tab" onclick="showTab('destacadas',this)">
            <i class="fa-solid fa-star"></i> Destacadas ({{ $imagenes->where('seccion','destacadas')->count() }})
        </button>
        <button class="img-manager-tab" onclick="showTab('cards',this)">
            <i class="fa-solid fa-th-large"></i> Cards ({{ $imagenes->where('seccion','cards')->count() }})
        </button>
        <button class="img-manager-tab" onclick="showTab('nueva',this)" style="margin-left:auto;color:var(--primary);">
            <i class="fa-solid fa-plus"></i> Agregar imagen
        </button>
    </div>

    {{-- Tab: Agregar nueva --}}
    <div id="tab-nueva" class="img-tab-content" style="display:none;">
        <h3 style="margin-bottom:1.5rem;font-size:1rem;color:var(--gray-900);">
            <i class="fa-solid fa-plus-circle" style="color:var(--primary);"></i> Agregar nueva imagen
        </h3>
        <form method="POST" action="{{ route('admin.imagenes.store') }}" enctype="multipart/form-data" class="admin-form">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Título (opcional)</label>
                    <input type="text" name="titulo" placeholder="Ej: Paisaje del río Ortega" value="{{ old('titulo') }}">
                </div>
                <div class="form-group">
                    <label>Sección *</label>
                    <select name="seccion" required>
                        @foreach($secciones as $key => $label)
                            <option value="{{ $key }}" {{ old('seccion') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Fuente de imagen *</label>
                <div class="img-source-tabs" style="display:flex;gap:.5rem;margin-bottom:.75rem;">
                    <button type="button" class="img-tab active" onclick="setImgTipo('url',this)">
                        <i class="fa-solid fa-link fa-xs"></i> URL
                    </button>
                    <button type="button" class="img-tab" onclick="setImgTipo('upload',this)">
                        <i class="fa-solid fa-upload fa-xs"></i> Subir archivo
                    </button>
                </div>
                <input type="hidden" name="tipo" id="img-tipo" value="url">

                <div class="img-panel active" id="panel-url">
                    <input type="url" name="url" id="imagen_url" placeholder="https://images.unsplash.com/..." value="{{ old('url') }}">
                    <div class="form-hint" style="margin-top:.35rem;font-size:.78rem;color:var(--gray-400);">
                        <i class="fa-solid fa-info-circle"></i> Pega la URL completa de la imagen
                    </div>
                </div>
                <div class="img-panel" id="panel-upload" style="display:none;">
                    <input type="file" name="imagen" id="imagen_file" accept="image/*">
                    <div class="form-hint" style="margin-top:.35rem;font-size:.78rem;color:var(--gray-400);">
                        <i class="fa-solid fa-info-circle"></i> JPG, PNG, WebP — máx. 4MB
                    </div>
                </div>

                {{-- Preview --}}
                <div class="img-preview-wrap" id="img-preview-wrap" style="margin-top:.8rem;border-radius:var(--radius-md);overflow:hidden;max-height:220px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;min-height:100px;">
                    <div class="img-preview-placeholder" style="text-align:center;color:var(--gray-400);padding:1.5rem;">
                        <i class="fa-solid fa-image" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                        <span style="font-size:.82rem;">La preview aparecerá aquí</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid "></i> Agregar imagen
            </button>
        </form>
    </div>

    {{-- Tab: Hero --}}
    <div id="tab-hero" class="img-tab-content">
        @include('admin.partials.imagen_lista', ['lista' => $imagenes->where('seccion','hero'), 'seccion' => 'hero'])
    </div>

    {{-- Tab: Destacadas --}}
    <div id="tab-destacadas" class="img-tab-content" style="display:none;">
        @include('admin.partials.imagen_lista', ['lista' => $imagenes->where('seccion','destacadas'), 'seccion' => 'destacadas'])
    </div>

    {{-- Tab: Cards --}}
    <div id="tab-cards" class="img-tab-content" style="display:none;">
        @include('admin.partials.imagen_lista', ['lista' => $imagenes->where('seccion','cards'), 'seccion' => 'cards'])
    </div>
</div>

@endsection

@push('scripts')
<script>
function showTab(name, btn) {
    document.querySelectorAll('.img-tab-content').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.img-manager-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).style.display = 'block';
    btn.classList.add('active');
}

function setImgTipo(tipo, btn) {
    document.querySelectorAll('.img-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('img-tipo').value = tipo;
    document.getElementById('panel-url').style.display = tipo === 'url' ? 'block' : 'none';
    document.getElementById('panel-upload').style.display = tipo === 'upload' ? 'block' : 'none';
    updatePreview();
}

function updatePreview() {
    const wrap = document.getElementById('img-preview-wrap');
    const tipo = document.getElementById('img-tipo').value;
    let src = '';

    if (tipo === 'url') {
        src = document.getElementById('imagen_url').value;
    }

    if (src) {
        wrap.innerHTML = '<img src="' + src + '" style="width:100%;max-height:220px;object-fit:cover;" onerror="this.parentElement.innerHTML=\'<div style=text-align:center;padding:1.5rem;color:var(--danger)><i class=fa-solid fa-triangle-exclamation></i><br><span style=font-size:.82rem>URL inválida o imagen no accesible</span></div>\'">';
    } else {
        wrap.innerHTML = '<div class="img-preview-placeholder" style="text-align:center;color:var(--gray-400);padding:1.5rem;"><i class="fa-solid fa-image" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.4;"></i><span style="font-size:.82rem;">La preview aparecerá aquí</span></div>';
    }
}

document.getElementById('imagen_url')?.addEventListener('input', updatePreview);

document.getElementById('imagen_file')?.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const wrap = document.getElementById('img-preview-wrap');
        wrap.innerHTML = '<img src="' + e.target.result + '" style="width:100%;max-height:220px;object-fit:cover;">';
    };
    reader.readAsDataURL(file);
});

// Drag & drop order
document.querySelectorAll('.gallery-grid[data-sortable]').forEach(grid => {
    let dragged = null;
    grid.querySelectorAll('.gallery-item').forEach(item => {
        item.setAttribute('draggable', 'true');
        item.addEventListener('dragstart', () => { dragged = item; item.style.opacity = '.4'; });
        item.addEventListener('dragend', () => { item.style.opacity = '1'; saveOrder(grid); });
        item.addEventListener('dragover', e => { e.preventDefault(); grid.insertBefore(dragged, item); });
    });
});

function saveOrder(grid) {
    const ids = [...grid.querySelectorAll('.gallery-item')].map(i => i.dataset.id);
    fetch('{{ route("admin.imagenes.orden") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ ids })
    });
}
</script>
@endpush
