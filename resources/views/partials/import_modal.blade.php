{{--
    Partial: Modal de Importación CSV/Excel
    Parámetros:
      $importRoute   - nombre de la ruta de importación
      $sampleFile    - nombre del archivo de ejemplo (en public/samples/)
      $columns       - array de columnas requeridas con descripción
      $modalId       - ID único del modal (default: 'importModal')
--}}
@php
    $modalId   = $modalId   ?? 'importModal';
    $sampleFile = $sampleFile ?? null;
@endphp

{{-- Botón disparador --}}
<button type="button"
        class="btn btn-warning btn-sm"
        onclick="document.getElementById('{{ $modalId }}').style.display='flex'">
    <i class="fa-solid fa-file-import"></i> Importar CSV/Excel
</button>

{{-- Modal --}}
<div id="{{ $modalId }}"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5);
            z-index:9999; align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#fff; border-radius:12px; width:100%; max-width:520px;
                box-shadow:0 8px 32px rgba(0,0,0,.18); overflow:hidden;">

        {{-- Header --}}
        <div style="background:var(--primary,#2563eb); color:#fff; padding:1rem 1.25rem;
                    display:flex; align-items:center; justify-content:space-between;">
            <span style="font-weight:600; font-size:1rem;">
                <i class="fa-solid fa-file-import" style="margin-right:.4rem;"></i>
                Importar datos desde CSV / Excel
            </span>
            <button type="button"
                    onclick="document.getElementById('{{ $modalId }}').style.display='none'"
                    style="background:none; border:none; color:#fff; font-size:1.3rem;
                           cursor:pointer; line-height:1;">&times;</button>
        </div>

        {{-- Body --}}
        <div style="padding:1.25rem;">

            {{-- Instrucciones --}}
            <div style="background:#f0f7ff; border:1px solid #bfdbfe; border-radius:8px;
                        padding:.9rem 1rem; margin-bottom:1rem; font-size:.875rem;">
                <p style="margin:0 0 .5rem; font-weight:600; color:#1e40af;">
                    <i class="fa-solid fa-circle-info" style="margin-right:.3rem;"></i>
                    Instrucciones
                </p>
                <ol style="margin:0; padding-left:1.2rem; color:#374151; line-height:1.7;">
                    <li>Descarga el archivo de ejemplo haciendo clic en el botón de abajo.</li>
                    <li>Completa los datos respetando el formato de cada columna.</li>
                    <li>Guarda el archivo como <strong>.xlsx</strong>, <strong>.xls</strong> o <strong>.csv</strong>.</li>
                    <li>Selecciona el archivo y haz clic en <strong>Importar</strong>.</li>
                </ol>
            </div>

            {{-- Columnas requeridas --}}
            @if(!empty($columns))
            <div style="margin-bottom:1rem;">
                <p style="font-size:.8rem; font-weight:600; color:#6b7280; text-transform:uppercase;
                           letter-spacing:.05em; margin:0 0 .4rem;">Columnas requeridas</p>
                <div style="display:flex; flex-wrap:wrap; gap:.35rem;">
                    @foreach($columns as $col => $desc)
                        <span title="{{ $desc }}"
                              style="background:#f3f4f6; border:1px solid #e5e7eb; border-radius:5px;
                                     padding:.2rem .55rem; font-size:.78rem; font-family:monospace;
                                     color:#374151; cursor:default;">
                            {{ $col }}
                        </span>
                    @endforeach
                </div>
                <p style="font-size:.75rem; color:#9ca3af; margin:.4rem 0 0;">
                    Pasa el cursor sobre cada columna para ver su descripción.
                </p>
            </div>
            @endif

            {{-- Descarga ejemplo --}}
            @if($sampleFile)
            <a href="{{ asset('samples/' . $sampleFile) }}"
               download
               style="display:inline-flex; align-items:center; gap:.4rem; font-size:.85rem;
                      color:#16a34a; text-decoration:none; margin-bottom:1rem;
                      border:1px solid #bbf7d0; background:#f0fdf4; border-radius:6px;
                      padding:.4rem .8rem;">
                <i class="fa-solid fa-download"></i>
                Descargar archivo de ejemplo (.xlsx)
            </a>
            @endif

            {{-- Formulario de carga --}}
            <form action="{{ route($importRoute) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom:.75rem;">
                    <label style="display:block; font-size:.85rem; font-weight:600;
                                  color:#374151; margin-bottom:.35rem;">
                        Seleccionar archivo
                    </label>
                    <input type="file"
                           name="archivo"
                           accept=".xlsx,.xls,.csv"
                           required
                           style="width:100%; font-size:.85rem;">
                    <p style="font-size:.75rem; color:#9ca3af; margin:.3rem 0 0;">
                        Formatos aceptados: .xlsx, .xls, .csv — Máx. 5 MB
                    </p>
                </div>

                <div style="display:flex; gap:.6rem; justify-content:flex-end; margin-top:1rem;">
                    <button type="button"
                            onclick="document.getElementById('{{ $modalId }}').style.display='none'"
                            class="btn btn-outline btn-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-upload"></i> Importar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
