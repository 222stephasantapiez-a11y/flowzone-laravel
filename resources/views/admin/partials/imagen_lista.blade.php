@if($lista->isEmpty())
    <div class="empty-state" style="text-align:center;padding:3rem;color:var(--gray-400);">
        <i class="fa-solid fa-images" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.35;"></i>
        <h3 style="font-size:1rem;color:var(--gray-600);margin-bottom:.35rem;">Sin imágenes en esta sección</h3>
        <p style="font-size:.85rem;">Agrega imágenes usando la pestaña "Agregar imagen"</p>
    </div>
@else
    <div id="sortable-images" class="gallery-grid" data-sortable
         style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1rem;">
        @foreach($lista->sortBy('orden') as $img)
            <div class="gallery-item" data-id="{{ $img->id }}" draggable="true"
                 style="position:relative;border-radius:var(--radius-md);overflow:hidden;background:var(--gray-100);aspect-ratio:4/3;cursor:grab;{{ !$img->activa ? 'opacity:.45;' : '' }}">

                {{-- Drag handle --}}
                <div class="gallery-drag-handle" title="Arrastrar para reordenar"
                     style="position:absolute;top:.5rem;left:.5rem;z-index:10;background:rgba(0,0,0,.45);color:#fff;border-radius:var(--radius-sm);padding:.25rem .4rem;font-size:.7rem;cursor:grab;line-height:1;">
                    <i class="fa-solid fa-grip-dots-vertical"></i>
                </div>

                {{-- Estado activo/inactivo --}}
                <div style="position:absolute;top:.5rem;right:.5rem;z-index:10;">
                    @if($img->activa)
                        <span style="background:#10b981;color:#fff;font-size:.65rem;font-weight:700;padding:.2rem .55rem;border-radius:var(--radius-full);letter-spacing:.04em;">
                            <i class="fa-solid fa-eye fa-xs"></i> Activa
                        </span>
                    @else
                        <span style="background:#94a3b8;color:#fff;font-size:.65rem;font-weight:700;padding:.2rem .55rem;border-radius:var(--radius-full);letter-spacing:.04em;">
                            <i class="fa-solid fa-eye-slash fa-xs"></i> Inactiva
                        </span>
                    @endif
                </div>

                <img src="{{ $img->public_url }}"
                     alt="{{ $img->titulo ?? 'Imagen' }}"
                     style="width:100%;height:100%;object-fit:cover;display:block;"
                     onerror="this.src='https://via.placeholder.com/300x200?text=Error'">

                {{-- Info overlay --}}
                <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,.7) 0%,transparent 100%);padding:.6rem .75rem .5rem;color:#fff;">
                    <div style="font-weight:600;font-size:.72rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $img->titulo ?? 'Sin título' }}
                    </div>
                    <div style="opacity:.7;font-size:.65rem;">{{ $img->tipo === 'upload' ? 'Subida' : 'URL' }}</div>
                </div>

                {{-- Acciones --}}
                <div class="gallery-item-actions"
                     style="position:absolute;bottom:.5rem;right:.5rem;display:flex;gap:.3rem;z-index:10;">
                    <form method="POST" action="{{ route('admin.imagenes.toggle', $img) }}" style="margin:0;">
                        @csrf @method('PATCH')
                        <button type="submit"
                                style="background:{{ $img->activa ? '#10b981' : '#94a3b8' }};color:#fff;border:none;border-radius:var(--radius-sm);padding:.3rem .5rem;font-size:.7rem;cursor:pointer;line-height:1;"
                                title="{{ $img->activa ? 'Desactivar' : 'Activar' }}">
                            <i class="fa-solid fa-{{ $img->activa ? 'toggle-on' : 'toggle-off' }}"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.imagenes.destroy', $img) }}" style="margin:0;"
                          onsubmit="return confirm('¿Eliminar esta imagen?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                style="background:#ef4444;color:#fff;border:none;border-radius:var(--radius-sm);padding:.3rem .5rem;font-size:.7rem;cursor:pointer;line-height:1;"
                                title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <p style="font-size:.78rem;color:var(--gray-400);">
        <i class="fa-solid fa-grip-dots-vertical"></i>
        Arrastra las imágenes para cambiar el orden. Los cambios se guardan automáticamente.
    </p>
@endif
