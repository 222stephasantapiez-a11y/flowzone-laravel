{{--
    Partial: campo dual de imagen (URL o subida)
    Variables esperadas:
      $currentImage  — valor actual (ruta o URL), puede ser null
      $fieldId       — prefijo único para IDs (ej: 'hotel', 'lugar')
--}}
@php
    use Illuminate\Support\Facades\Storage;
    $currentImage = $currentImage ?? null;
    $fieldId      = $fieldId ?? 'img';
    $nameUrl      = $nameUrl  ?? 'imagen_url';
    $nameFile     = $nameFile ?? 'imagen_file';
    $isUrl        = $currentImage && str_starts_with($currentImage, 'http');
    $defaultTab   = $isUrl ? 'url' : ($currentImage ? 'file' : 'url');
@endphp

<div class="form-group">
    <label>Imagen</label>

    <div class="img-source-tabs">
        <button type="button" class="img-tab {{ $defaultTab === 'url' ? 'active' : '' }}"
                onclick="switchImgTab('{{ $fieldId }}','url', this)">
            <i class="fa-solid fa-link fa-xs"></i> URL
        </button>
        <button type="button" class="img-tab {{ $defaultTab === 'file' ? 'active' : '' }}"
                onclick="switchImgTab('{{ $fieldId }}','file', this)">
            <i class="fa-solid fa-upload fa-xs"></i> Subir archivo
        </button>
    </div>

    {{-- Panel URL --}}
    <div class="img-panel {{ $defaultTab === 'url' ? 'active' : '' }}" id="{{ $fieldId }}-panel-url">
        <input type="url" name="{{ $nameUrl }}" id="{{ $fieldId }}-url-input"
               placeholder="https://ejemplo.com/imagen.jpg"
               value="{{ old($nameUrl, $isUrl ? $currentImage : '') }}"
               oninput="previewFromUrl('{{ $fieldId }}', this.value)">
        <p class="form-hint">Pega la URL directa de la imagen (https://...)</p>
    </div>

    {{-- Panel archivo --}}
    <div class="img-panel {{ $defaultTab === 'file' ? 'active' : '' }}" id="{{ $fieldId }}-panel-file">
        <input type="file" name="{{ $nameFile }}" id="{{ $fieldId }}-file-input"
               accept="image/jpeg,image/png,image/webp"
               onchange="previewFromFile('{{ $fieldId }}', this)">
        <p class="form-hint">JPG, PNG o WebP · máx. 4 MB</p>
    </div>

    {{-- Preview --}}
    <div class="img-preview-wrap" id="{{ $fieldId }}-preview-wrap" style="{{ $currentImage ? '' : 'display:none' }}">
        @if($currentImage)
            <img id="{{ $fieldId }}-preview-img"
                 src="{{ $isUrl ? $currentImage : Storage::disk('public')->url($currentImage) }}"
                 alt="Vista previa">
        @else
            <img id="{{ $fieldId }}-preview-img" src="" alt="Vista previa" style="display:none">
        @endif
    </div>
</div>

<script>
function switchImgTab(id, tab, el) {
    // Ocultar ambos paneles
    document.querySelectorAll('#' + id + '-panel-url, #' + id + '-panel-file')
            .forEach(p => p.classList.remove('active'));

    // Mostrar el panel seleccionado
    document.getElementById(id + '-panel-' + tab).classList.add('active');

    // Actualizar botones activos
    document.querySelectorAll('.img-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');

    // Limpiar el input del tab que NO está activo
    if (tab === 'url') {
        const fileInput = document.getElementById(id + '-file-input');
        if (fileInput) fileInput.value = '';
    } else {
        const urlInput = document.getElementById(id + '-url-input');
        if (urlInput) urlInput.value = '';
    }
}

function previewFromUrl(id, url) {
    const wrap = document.getElementById(id + '-preview-wrap');
    const img  = document.getElementById(id + '-preview-img');
    if (url.trim()) {
        img.src = url;
        img.style.display = '';
        wrap.style.display = '';
    } else {
        wrap.style.display = 'none';
    }
}

function previewFromFile(id, input) {
    const wrap = document.getElementById(id + '-preview-wrap');
    const img  = document.getElementById(id + '-preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.style.display = '';
            wrap.style.display = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>