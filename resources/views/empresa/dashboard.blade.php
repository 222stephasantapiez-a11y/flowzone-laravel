@extends('layouts.empresa')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen de tu empresa')

@section('content')
@php use Illuminate\Support\Facades\Storage; use Illuminate\Support\Str; @endphp

@if(!$empresa)
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        No se encontraron datos de empresa asociados a tu cuenta.
        Contacta al administrador en <a href="{{ route('contacto') }}">esta página</a>.
    </div>
@else

@php
    $totalBlog      = \App\Models\BlogPost::where('empresa_id', $empresa->id)->count();
    $blogPublicados = \App\Models\BlogPost::where('empresa_id', $empresa->id)->where('publicado', true)->count();
    $totalPlatos    = \App\Models\Gastronomia::where('empresa_id', $empresa->id)->count();

    // Separar respuestas del admin de solicitudes enviadas
    $esDelAdmin = fn($n) =>
        str_starts_with($n->mensaje, 'RESPUESTA DEL ADMIN:') ||
        str_starts_with($n->mensaje, 'APROBACIÓN:') ||
        str_starts_with($n->mensaje, 'TU PUBLICACIÓN FUE APROBADA') ||
        str_starts_with($n->mensaje, 'PUBLICACIÓN') ||
        str_starts_with($n->mensaje, 'RECHAZ');
    $respuestasAdmin    = $historial->filter($esDelAdmin);
    $solicitudesEnviadas = $historial->reject($esDelAdmin);
@endphp

{{-- Notificaciones del admin --}}
@if($respuestasAdmin->count() > 0)
<div class="admin-section" style="border-left:4px solid var(--green-600);margin-bottom:1.5rem;background:#f0fdf4;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:.5rem;">
        <h2 style="font-size:1rem;font-weight:700;color:var(--green-800);display:flex;align-items:center;gap:.5rem;margin:0;">
            <i class="fa-solid fa-bell" style="color:var(--green-600);"></i>
            Respuestas del administrador
            <span style="background:var(--green-600);color:#fff;border-radius:2rem;padding:.1rem .55rem;font-size:.75rem;font-weight:700;">{{ $respuestasAdmin->count() }}</span>
        </h2>
        <form method="POST" action="{{ route('empresa.notificaciones.leer-todas') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm" style="font-size:.75rem;">
                <i class="fa-solid fa-check-double fa-xs"></i> Marcar todas leídas
            </button>
        </form>
    </div>
    <div style="display:flex;flex-direction:column;gap:.75rem;">
        @foreach($respuestasAdmin as $resp)
        <div style="background:#fff;border:1px solid #bbf7d0;border-radius:var(--radius-md);padding:1rem 1.25rem;display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.4rem;">
                    <i class="fa-solid fa-comment-dots" style="color:var(--green-600);font-size:.85rem;"></i>
                    <span style="font-size:.75rem;font-weight:700;color:var(--green-700);text-transform:uppercase;letter-spacing:.05em;">Admin respondió</span>
                    <span style="font-size:.72rem;color:var(--gray-400);">{{ $resp->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <p style="font-size:.9rem;color:var(--gray-800);margin:0;">
                    @if(str_starts_with($resp->mensaje, 'APROBACIÓN:'))
                        <i class="fa-solid fa-circle-check fa-xs" style="color:var(--green-600);"></i>
                        {{ Str::after($resp->mensaje, 'APROBACIÓN: ') }}
                    @elseif(str_starts_with($resp->mensaje, 'TU PUBLICACIÓN FUE APROBADA') || str_starts_with($resp->mensaje, 'PUBLICACIÓN'))
                        <i class="fa-solid fa-newspaper fa-xs" style="color:var(--green-600);"></i>
                        {{ $resp->mensaje }}
                    @elseif(str_starts_with($resp->mensaje, 'RECHAZ'))
                        <i class="fa-solid fa-circle-xmark fa-xs" style="color:#dc2626;"></i>
                        {{ $resp->mensaje }}
                    @else
                        {{ Str::after($resp->mensaje, 'RESPUESTA DEL ADMIN: ') }}
                    @endif
                </p>
            </div>
            <form method="POST" action="{{ route('empresa.notificaciones.leer', $resp->id) }}" style="flex-shrink:0;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-outline btn-sm" style="font-size:.75rem;white-space:nowrap;">
                    <i class="fa-solid fa-check fa-xs"></i> Marcar leída
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:1.5rem;">

    <a href="{{ route('empresa.blog.index') }}" style="text-decoration:none;display:contents;">
        <div class="stat-card green" style="cursor:pointer;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="stat-icon-wrap" style="background:rgba(64,145,108,.12);color:var(--green-700);">
                <i class="fa-solid fa-newspaper"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalBlog }}</h3>
                <p>Posts del blog</p>
            </div>
        </div>
    </a>

    <a href="{{ route('empresa.blog.index') }}" style="text-decoration:none;display:contents;">
        <div class="stat-card teal" style="cursor:pointer;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="stat-icon-wrap" style="background:rgba(6,182,212,.12);color:#06b6d4;">
                <i class="fa-solid fa-eye"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $blogPublicados }}</h3>
                <p>Posts publicados</p>
            </div>
        </div>
    </a>

    @php
        $tieneGastronomia = in_array($empresa->tipo_empresa, ['restaurante'])
            || ($empresa->tipo_empresa === 'hotel' && in_array('Restaurante propio', $empresa->servicios ?? []));
    @endphp

    @if($tieneGastronomia)
    <a href="{{ route('empresa.gastronomia.index') }}" style="text-decoration:none;display:contents;">
        <div class="stat-card orange" style="cursor:pointer;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="stat-icon-wrap" style="background:rgba(217,119,6,.12);color:var(--warning);">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalPlatos }}</h3>
                <p>Platos / servicios</p>
            </div>
        </div>
    </a>
    @endif

    <a href="#historial" style="text-decoration:none;display:contents;">
        <div class="stat-card blue" style="cursor:pointer;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="stat-icon-wrap" style="background:rgba(59,130,246,.12);color:#3b82f6;">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $historial->count() }}</h3>
                <p>Notificaciones</p>
            </div>
        </div>
    </a>

    <a href="{{ route('empresa.dashboard') }}" style="text-decoration:none;display:contents;">
        <div class="stat-card red" style="cursor:pointer;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="stat-icon-wrap" style="background:rgba(239,68,68,.12);color:#ef4444;">
                <i class="fa-solid fa-heart"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalFavoritosEmp }}</h3>
                <p>Favoritos recibidos</p>
            </div>
        </div>
    </a>

    <a href="#resenas" style="text-decoration:none;display:contents;">
        <div class="stat-card purple" style="cursor:pointer;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="stat-icon-wrap" style="background:rgba(139,92,246,.12);color:#8b5cf6;">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $promedioEmpresa > 0 ? $promedioEmpresa.'/5' : '—' }}</h3>
                <p>Calificación promedio</p>
                @if($totalReseñasEmp > 0)
                    <span class="stat-sub ok">{{ $totalReseñasEmp }} reseña(s)</span>
                @endif
            </div>
        </div>
    </a>

    <a href="{{ route('empresa.habitaciones.index') }}" style="text-decoration:none;display:contents;">
        <div class="stat-card blue" style="cursor:pointer;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="stat-icon-wrap" style="background:rgba(59,130,246,.12);color:#3b82f6;">
                <i class="fa-solid fa-bed"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $habitacionesDisponibles }} <span style="font-size:.75rem;font-weight:400;color:var(--gray-400);">/ {{ $totalHabitaciones }}</span></h3>
                <p>Habitaciones disponibles</p>
            </div>
        </div>
    </a>

    <a href="{{ route('empresa.planes.index') }}" style="text-decoration:none;display:contents;">
        <div class="stat-card teal" style="cursor:pointer;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="stat-icon-wrap" style="background:rgba(6,182,212,.12);color:#06b6d4;">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $paquetesActivos }} <span style="font-size:.75rem;font-weight:400;color:var(--gray-400);">/ {{ $totalPaquetes }}</span></h3>
                <p>Paquetes activos</p>
            </div>
        </div>
    </a>

</div>

{{-- Calificaciones por servicio --}}
@if($statsCalificaciones->isNotEmpty())
<div class="admin-section" style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-star" style="color:var(--gold-500);"></i> Calificaciones por servicio
    </h2>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr><th>Servicio</th><th>Tipo</th><th>Promedio</th><th>Reseñas</th></tr>
            </thead>
            <tbody>
                @foreach($statsCalificaciones as $s)
                <tr>
                    <td>{{ $s->nombre }}</td>
                    <td><span class="badge badge-info">{{ $s->tipo_label }}</span></td>
                    <td><span style="color:var(--gold-500);font-weight:700;"><i class="fa-solid fa-star fa-xs"></i> {{ $s->promedio }}</span></td>
                    <td>{{ $s->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Reseñas de usuarios --}}
<div class="admin-section" style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-comments" style="color:var(--green-600);"></i> Reseñas de usuarios
    </h2>
    @if($resenasDetalladas->isNotEmpty())
    <div style="display:flex;flex-direction:column;gap:1rem;">
        @foreach($resenasDetalladas as $resena)
        <div style="border:1px solid var(--gray-100);border-radius:var(--radius-md);padding:1rem 1.25rem;background:#fff;">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-bottom:.6rem;">
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <span style="font-weight:700;color:var(--gray-900);">{{ $resena->usuario->name ?? 'Usuario' }}</span>
                    <span class="badge badge-info">{{ ucfirst($resena->tipo) }}</span>
                    <span style="font-size:.82rem;color:var(--gray-500);">— {{ $resena->item_nombre }}</span>
                </div>
                <div style="color:var(--gold-500);font-weight:700;font-size:.95rem;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= $resena->calificacion ? 'solid' : 'regular' }} fa-star fa-xs"></i>
                    @endfor
                    <span style="font-size:.82rem;color:var(--gray-600);margin-left:.3rem;">{{ $resena->calificacion }}/5</span>
                </div>
            </div>
            <p style="font-size:.9rem;color:var(--gray-700);margin:0 0 .75rem;">"{{ $resena->comentario }}"</p>
            @if($resena->respuesta_empresa)
            <div style="background:#f0fdf4;border-left:3px solid var(--green-500);border-radius:var(--radius-sm);padding:.6rem 1rem;">
                <span style="font-size:.75rem;font-weight:700;color:var(--green-700);text-transform:uppercase;letter-spacing:.05em;">Tu respuesta</span>
                <p style="margin:.3rem 0 0;font-size:.88rem;color:var(--gray-700);">{{ $resena->respuesta_empresa }}</p>
            </div>
            @else
            <form method="POST" action="{{ route('empresa.resenas.responder', $resena) }}">
                @csrf @method('PATCH')
                <textarea name="respuesta_empresa" rows="2" placeholder="Escribe tu respuesta a esta reseña..."
                          style="width:100%;resize:vertical;padding:.5rem .75rem;border:1px solid var(--gray-200);border-radius:var(--radius-sm);font-size:.88rem;margin-bottom:.5rem;"
                          required></textarea>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-reply fa-xs"></i> Responder
                </button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <p style="color:var(--gray-400);font-size:.9rem;">Aún no tienes reseñas con comentarios.</p>
    @endif
</div>

{{-- Info empresa --}}
<div class="admin-section">
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-building" style="color:var(--green-600);"></i> Información registrada
    </h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Empresa</div>
            <div style="font-weight:600;color:var(--gray-900);">{{ $empresa->nombre }}</div>
        </div>
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Tipo</div>
            <div style="font-weight:600;color:var(--gray-900);">
                @php $tipoLabels = ['hotel'=>'🏨 Hotel/Hospedaje','restaurante'=>'🍽️ Restaurante','agencia_turismo'=>'🧭 Agencia de turismo','transporte'=>'🚌 Transporte','artesanias'=>'🎨 Artesanías','otro'=>'📦 Otro']; @endphp
                {{ $tipoLabels[$empresa->tipo_empresa] ?? '—' }}
            </div>
        </div>
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">NIT</div>
            <div style="font-weight:600;color:var(--gray-900);">{{ $empresa->nit ?? '—' }}</div>
        </div>
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Teléfono</div>
            <div style="font-weight:600;color:var(--gray-900);">{{ $empresa->telefono ?? '—' }}</div>
        </div>
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Dirección</div>
            <div style="font-weight:600;color:var(--gray-900);">{{ $empresa->direccion ?? '—' }}</div>
        </div>
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Estado</div>
            @if($empresa->aprobado)
                <span class="badge badge-success"><i class="fa-solid fa-circle-check fa-xs"></i> Aprobada</span>
            @else
                <span class="badge badge-warning"><i class="fa-solid fa-clock fa-xs"></i> Pendiente</span>
            @endif
        </div>
        @if($empresa->logo)
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.4rem;">Logo</div>
            <img src="{{ Str::startsWith($empresa->logo, 'http') ? $empresa->logo : Storage::url($empresa->logo) }}"
                 alt="Logo" style="width:60px;height:60px;object-fit:cover;border-radius:var(--radius-sm);">
        </div>
        @endif
        @if($empresa->descripcion)
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;grid-column:1/-1;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Descripción</div>
            <div style="color:var(--gray-700);font-size:.9rem;">{{ $empresa->descripcion }}</div>
        </div>
        @endif
        @if($empresa->servicios && count($empresa->servicios))
        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:.9rem 1rem;grid-column:1/-1;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">Servicios</div>
            <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                @foreach($empresa->servicios as $srv)
                    <span style="background:rgba(64,145,108,.12);color:var(--green-700);border-radius:2rem;padding:.25rem .7rem;font-size:.8rem;font-weight:500;">{{ $srv }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @if($empresa->sitio_web || $empresa->instagram || $empresa->facebook)
    <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-top:1rem;">
        @if($empresa->sitio_web)
            <a href="{{ $empresa->sitio_web }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa-solid fa-globe fa-xs"></i> Sitio web</a>
        @endif
        @if($empresa->instagram)
            <a href="https://instagram.com/{{ ltrim($empresa->instagram,'@') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa-brands fa-instagram fa-xs"></i> Instagram</a>
        @endif
        @if($empresa->facebook)
            <a href="{{ Str::startsWith($empresa->facebook,'http') ? $empresa->facebook : 'https://'.$empresa->facebook }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa-brands fa-facebook fa-xs"></i> Facebook</a>
        @endif
    </div>
    @endif
    <div style="margin-top:1.2rem;">
        <a href="{{ route('empresa.perfil.edit') }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square fa-xs"></i> Editar perfil
        </a>
    </div>
</div>

{{-- Formulario de solicitud --}}
<div class="admin-section">
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);margin-bottom:.4rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-paper-plane" style="color:var(--green-600);"></i> Enviar solicitud al administrador
    </h2>
    <p style="color:var(--gray-400);font-size:.875rem;margin-bottom:1.5rem;">Solicita agregar un nuevo servicio, actualiza tus datos o reporta una novedad.</p>
    <form method="POST" action="{{ route('empresa.solicitud') }}" class="admin-form">
        @csrf
        <div class="form-row">
            <div class="form-group" style="flex:1;">
                <label for="tipo">Tipo de solicitud *</label>
                <select id="tipo" name="tipo" required>
                    <option value="">— Selecciona —</option>
                    <option value="actualizacion" {{ old('tipo') === 'actualizacion' ? 'selected' : '' }}>✏️ Actualización de datos</option>
                    <option value="novedad"       {{ old('tipo') === 'novedad'       ? 'selected' : '' }}>📢 Novedad / Reporte</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" name="descripcion" rows="5" required maxlength="1000"
                      placeholder="Describe detalladamente tu solicitud...">{{ old('descripcion') }}</textarea>
            <small style="color:var(--gray-400);font-size:.78rem;">Máximo 1000 caracteres.</small>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-paper-plane fa-xs"></i> Enviar solicitud
        </button>
    </form>
</div>

{{-- Historial de solicitudes enviadas --}}
<div class="admin-section" id="historial">
    <div class="admin-section-header">
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);display:flex;align-items:center;gap:.5rem;">
            <i class="fa-solid fa-clock-rotate-left" style="color:var(--green-600);"></i> Mis solicitudes pendientes
        </h2>
        <span class="badge badge-info">{{ $solicitudesEnviadas->count() }}</span>
    </div>

    @if($solicitudesEnviadas->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-inbox"></i>
            <p>No tienes solicitudes pendientes.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Solicitud</th>
                        <th style="white-space:nowrap;">Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudesEnviadas as $notif)
                    <tr>
                        <td style="max-width:500px;white-space:pre-wrap;font-size:.875rem;">{{ $notif->mensaje }}</td>
                        <td style="white-space:nowrap;color:var(--gray-400);font-size:.82rem;">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge badge-warning"><i class="fa-solid fa-clock fa-xs"></i> Pendiente</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endif

@endsection