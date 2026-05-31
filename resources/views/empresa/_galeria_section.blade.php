{{-- ══════════════════════════════════════════════════════════
     PARTIAL: Galería de imágenes de la empresa
══════════════════════════════════════════════════════════ --}}
<div class="admin-section" style="margin-top:2rem;">

    <div style="margin-bottom:1.25rem;">
        <h2 style="display:flex;align-items:center;gap:.5rem;">
            <i class="fa-solid fa-images" style="color:var(--primary);"></i>
            Galería de imágenes
        </h2>
        <p style="font-size:.82rem;color:var(--gray-400);margin:0;">
            Estas imágenes aparecen en el perfil público de tu empresa.
        </p>
    </div>

    {{-- Formulario subir imagen --}}
    <form method="POST" action="{{ route('empresa.galeria.store') }}" enctype="multipart/form-data"
          style="background:var(--gray-50);border:1.5px dashed var(--gray-200);border-radius:var(--radius-md);padding:1.5rem;margin-bottom:1.75rem;">
        @csrf

        <p style="font-size:.85rem;font-weight:700;color:var(--gray-700);margin-bottom:1rem;">
            <i class="fa-solid fa-plus-circle" style="color:var(--green-600);margin-right:.3rem;"></i>
            Agregar imagen a la galería
        </p>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="form-group" style="margin:0;">
                <label style="font-size:.83rem;font-weight:600;display:block;margin-bottom:.4rem;">
                    Imagen <span style="color:var(--danger);">*</span>
                </label>
                <input type="file" name="imagen" accept="image/jpg,image/jpeg,image/png,image/webp"
                       style="width:100%;padding:.6rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;background:#fff;font-family:var(--font-body);">
                <p style="font-size:.75rem;color:var(--gray-400);margin-top:.3rem;">Máx. 5MB — JPG, PNG, WEBP</p>
                @error('imagen')
                    <p style="color:var(--danger);font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-size:.83rem;font-weight:600;display:block;margin-bottom:.4rem;">Categoría</label>
                <select name="categoria"
                        style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;font-family:var(--font-body);outline:none;">
                    <option value="general">🖼️ General</option>
                    <option value="piscina">🏊 Piscina</option>
                    <option value="habitacion">🛏️ Habitación</option>
                    <option value="restaurante">🍽️ Restaurante</option>
                    <option value="salon">🎉 Salón</option>
                    <option value="exterior">🌿 Exterior</option>
                    <option value="spa">💆 Spa</option>
                    <option value="parqueadero">🚗 Parqueadero</option>
                    <option value="otro">📷 Otro</option>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-size:.83rem;font-weight:600;display:block;margin-bottom:.4rem;">
                    Título <span style="font-weight:400;color:var(--gray-400);">(opcional)</span>
                </label>
                <input type="text" name="titulo" placeholder="Ej: Vista a la piscina" maxlength="200"
                       style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;font-family:var(--font-body);outline:none;">
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-upload fa-xs"></i> Subir imagen
        </button>
    </form>

    {{-- Grid de imágenes existentes --}}
    @php
        $galeria = \App\Models\EmpresaImagen::where('empresa_id', $empresa->id)->orderBy('orden')->get();
    @endphp

    @if($galeria->isEmpty())
        <div style="text-align:center;padding:2.5rem 1rem;color:var(--gray-400);">
            <i class="fa-regular fa-image" style="font-size:2rem;display:block;margin-bottom:.6rem;opacity:.3;"></i>
            <p style="font-size:.88rem;margin:0;">No tienes imágenes en la galería aún. Sube una arriba.</p>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
            @foreach($galeria as $img)
            @php $src = str_starts_with($img->ruta, 'http') ? $img->ruta : asset('storage/' . $img->ruta); @endphp
            <div style="border:1.5px solid var(--gray-200);border-radius:var(--radius-md);overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.05);">

                {{-- Thumbnail --}}
                <div style="position:relative;aspect-ratio:4/3;overflow:hidden;background:var(--gray-100);">
                    <img src="{{ $src }}" alt="{{ $img->titulo ?? 'Imagen galería' }}"
                         style="width:100%;height:100%;object-fit:cover;display:block;opacity:{{ $img->activa ? '1' : '0.4' }};"
                         onerror="this.parentElement.style.background='var(--gray-200)';this.style.display='none'">
                    <span style="position:absolute;top:.5rem;left:.5rem;padding:.2rem .55rem;border-radius:2rem;font-size:.7rem;font-weight:700;
                                 background:var(--green-50);color:var(--green-700);border:1px solid var(--green-200);">
                        {{ ucfirst($img->categoria ?? 'general') }}
                    </span>
                    <span style="position:absolute;top:.5rem;right:.5rem;padding:.2rem .55rem;border-radius:2rem;font-size:.7rem;font-weight:700;
                                 background:{{ $img->activa ? '#d1fae5' : '#fee2e2' }};
                                 color:{{ $img->activa ? '#065f46' : '#991b1b' }};">
                        {{ $img->activa ? '● Activa' : '● Inactiva' }}
                    </span>
                </div>

                <div style="padding:.75rem;">
                    @if($img->titulo)
                    <p style="font-size:.82rem;font-weight:700;color:var(--gray-800);margin:0 0 .6rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $img->titulo }}
                    </p>
                    @endif

                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                        {{-- Toggle --}}
                        <form method="POST" action="{{ route('empresa.galeria.toggle', $img) }}" style="margin:0;">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="padding:.3rem .65rem;font-size:.74rem;font-weight:600;border:none;border-radius:var(--radius-sm);cursor:pointer;
                                           background:{{ $img->activa ? '#fef3c7' : '#d1fae5' }};
                                           color:{{ $img->activa ? '#92400e' : '#065f46' }};">
                                <i class="fa-solid fa-{{ $img->activa ? 'eye-slash' : 'eye' }} fa-xs"
                                   style="color:{{ $img->activa ? 'var(--gray-500)' : 'var(--green-700)' }};"></i>
                                {{ $img->activa ? 'Ocultar' : 'Mostrar' }}
                            </button>
                        </form>

                        {{-- Eliminar --}}
                        <form method="POST" action="{{ route('empresa.galeria.destroy', $img) }}" style="margin:0;"
                              onsubmit="return confirm('¿Seguro que quieres eliminar esta imagen?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:.3rem .65rem;font-size:.74rem;font-weight:600;border:none;border-radius:var(--radius-sm);cursor:pointer;background:#fee2e2;color:#991b1b;">
                                <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
