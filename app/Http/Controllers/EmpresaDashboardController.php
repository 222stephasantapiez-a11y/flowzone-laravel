<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\HeroImage;
use App\Models\NotificacionAdmin;
use App\Models\Calificacion;
use App\Models\Favorito;
use App\Models\Habitacion;
use App\Models\PaqueteTuristico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmpresaDashboardController extends Controller
{
    public function index()
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->first();

        $statsCalificaciones = collect();
        $statsFavoritos      = collect();
        $promedioEmpresa     = 0;
        $totalFavoritosEmp   = 0;
        $totalReseñasEmp     = 0;
        $resenasDetalladas   = collect();

        if ($empresa) {
            $hotelIds       = $empresa->hoteles()->pluck('id');
            $gastronomiaIds = \App\Models\Gastronomia::where('empresa_id', $empresa->id)->pluck('id');

            $statsHoteles = collect();
            if ($hotelIds->isNotEmpty()) {
                $statsHoteles = Calificacion::where('tipo', 'hotel')
                    ->whereIn('item_id', $hotelIds)
                    ->selectRaw('item_id, round(avg(calificacion),1) as promedio, count(*) as total')
                    ->groupBy('item_id')
                    ->get()
                    ->map(function ($row) {
                        $row->nombre     = \App\Models\Hotel::find($row->item_id)?->nombre ?? 'Hotel #' . $row->item_id;
                        $row->tipo_label = 'Hotel';
                        return $row;
                    });
            }

            $statsGastronomia = collect();
            if ($gastronomiaIds->isNotEmpty()) {
                $statsGastronomia = Calificacion::where('tipo', 'gastronomia')
                    ->whereIn('item_id', $gastronomiaIds)
                    ->selectRaw('item_id, round(avg(calificacion),1) as promedio, count(*) as total')
                    ->groupBy('item_id')
                    ->get()
                    ->map(function ($row) {
                        $row->nombre     = \App\Models\Gastronomia::find($row->item_id)?->nombre ?? 'Plato #' . $row->item_id;
                        $row->tipo_label = 'Gastronomía';
                        return $row;
                    });
            }

            $statsEmpresaDirecta = Calificacion::where('tipo', 'empresa')
                ->where('item_id', $empresa->id)
                ->selectRaw('item_id, round(avg(calificacion),1) as promedio, count(*) as total')
                ->groupBy('item_id')
                ->get()
                ->map(function ($row) use ($empresa) {
                    $row->nombre     = $empresa->nombre;
                    $row->tipo_label = 'Empresa';
                    return $row;
                });

            $statsCalificaciones = $statsHoteles->merge($statsGastronomia)->merge($statsEmpresaDirecta);

            $resenasDetalladas = Calificacion::with('usuario')
                ->where(function ($q) use ($hotelIds, $gastronomiaIds, $empresa) {
                    $q->where(function ($q2) use ($hotelIds) {
                        $q2->where('tipo', 'hotel')->whereIn('item_id', $hotelIds);
                    })->orWhere(function ($q2) use ($gastronomiaIds) {
                        $q2->where('tipo', 'gastronomia')->whereIn('item_id', $gastronomiaIds);
                    })->orWhere(function ($q2) use ($empresa) {
                        $q2->where('tipo', 'empresa')->where('item_id', $empresa->id);
                    });
                })
                ->whereNotNull('comentario')
                ->latest()
                ->get()
                ->map(function ($cal) {
                    $cal->item_nombre = match ($cal->tipo) {
                        'hotel'       => \App\Models\Hotel::find($cal->item_id)?->nombre ?? 'Hotel eliminado',
                        'gastronomia' => \App\Models\Gastronomia::find($cal->item_id)?->nombre ?? 'Plato eliminado',
                        'empresa'     => \App\Models\Empresa::find($cal->item_id)?->nombre ?? 'Empresa',
                        default       => ucfirst($cal->tipo) . ' #' . $cal->item_id,
                    };
                    return $cal;
                });

            $promedioEmpresa = round(
                Calificacion::where(function ($q) use ($hotelIds, $gastronomiaIds) {
                    $q->where(function ($q2) use ($hotelIds) {
                        $q2->where('tipo', 'hotel')->whereIn('item_id', $hotelIds);
                    })->orWhere(function ($q2) use ($gastronomiaIds) {
                        $q2->where('tipo', 'gastronomia')->whereIn('item_id', $gastronomiaIds);
                    });
                })->avg('calificacion') ?? 0,
                1
            );

            $totalReseñasEmp = Calificacion::where(function ($q) use ($hotelIds, $gastronomiaIds) {
                $q->where(function ($q2) use ($hotelIds) {
                    $q2->where('tipo', 'hotel')->whereIn('item_id', $hotelIds);
                })->orWhere(function ($q2) use ($gastronomiaIds) {
                    $q2->where('tipo', 'gastronomia')->whereIn('item_id', $gastronomiaIds);
                });
            })->whereNotNull('comentario')->count();

            $totalFavoritosEmp = Favorito::where(function ($q) use ($hotelIds, $gastronomiaIds) {
                $q->where(function ($q2) use ($hotelIds) {
                    $q2->where('tipo', 'hotel')->whereIn('item_id', $hotelIds);
                })->orWhere(function ($q2) use ($gastronomiaIds) {
                    $q2->where('tipo', 'gastronomia')->whereIn('item_id', $gastronomiaIds);
                });
            })->count();
        }

        return view('empresa.dashboard', [
            'empresa'                 => $empresa,
            'historial'               => $empresa
                ? NotificacionAdmin::where('empresa_id', $empresa->id)->latest()->get()
                : collect(),
            'statsCalificaciones'     => $statsCalificaciones,
            'promedioEmpresa'         => $promedioEmpresa,
            'totalFavoritosEmp'       => $totalFavoritosEmp,
            'totalReseñasEmp'         => $totalReseñasEmp,
            'totalHabitaciones'       => $empresa ? Habitacion::whereHas('hotel', fn($q) => $q->where('empresa_id', $empresa->id))->count() : 0,
            'habitacionesDisponibles' => $empresa ? Habitacion::whereHas('hotel', fn($q) => $q->where('empresa_id', $empresa->id))->where('disponible', true)->count() : 0,
            'totalPaquetes'           => $empresa ? PaqueteTuristico::where('empresa_id', $empresa->id)->count() : 0,
            'paquetesActivos'         => $empresa ? PaqueteTuristico::where('empresa_id', $empresa->id)->where('activo', true)->count() : 0,
            'resenasDetalladas'       => $resenasDetalladas,
            // ── Imágenes hero de la empresa ──
            'heroImagenes'            => $empresa
                ? HeroImage::where('empresa_id', $empresa->id)->where('seccion', 'hero')->orderBy('orden')->get()
                : collect(),
        ]);
    }

    // ══════════════════════════════════════════════════════════
    //  GESTIÓN DE IMAGEN PRINCIPAL (HERO) DESDE EMPRESA
    // ══════════════════════════════════════════════════════════

    /**
     * Subir una nueva imagen hero desde el panel empresa.
     */
    public function heroStore(Request $request)
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();

        $request->validate([
            'titulo' => 'nullable|string|max:200',
            'tipo'   => 'required|in:url,upload',
            'url'    => 'required_if:tipo,url|nullable|url|max:500',
            'imagen' => 'required_if:tipo,upload|nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'imagen.required_if' => 'Debes seleccionar una imagen para subir.',
            'url.required_if'    => 'Debes ingresar la URL de la imagen.',
            'imagen.max'         => 'La imagen no puede superar 4MB.',
        ]);

        $data = [
            'titulo'     => $request->titulo,
            'seccion'    => 'hero',
            'tipo'       => $request->tipo,
            'activa'     => true,
            'empresa_id' => $empresa->id,
            'orden'      => HeroImage::where('empresa_id', $empresa->id)->where('seccion', 'hero')->max('orden') + 1,
        ];

        if ($request->tipo === 'upload' && $request->hasFile('imagen')) {
            $data['url'] = $request->file('imagen')->store('hero/empresas', 'public');
        } else {
            $data['url'] = $request->url;
        }

        HeroImage::create($data);

        return back()->with('success', 'Imagen principal agregada correctamente.');
    }

    /**
     * Activar / desactivar una imagen hero de la empresa.
     */
    public function heroToggle(HeroImage $heroImage)
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();

        // Solo puede tocar sus propias imágenes
        abort_if($heroImage->empresa_id !== $empresa->id, 403);

        $heroImage->update(['activa' => !$heroImage->activa]);

        return back()->with('success', 'Estado de la imagen actualizado.');
    }

    /**
     * Eliminar una imagen hero de la empresa.
     */
    public function heroDestroy(HeroImage $heroImage)
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();

        abort_if($heroImage->empresa_id !== $empresa->id, 403);

        if ($heroImage->tipo === 'upload') {
            Storage::disk('public')->delete($heroImage->url);
        }

        $heroImage->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }

    // ══════════════════════════════════════════════════════════
    //  RESTO DE MÉTODOS ORIGINALES
    // ══════════════════════════════════════════════════════════

    public function responderResena(Request $request, Calificacion $calificacion)
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();

        $esPropia = match ($calificacion->tipo) {
            'hotel'       => $empresa->hoteles()->where('id', $calificacion->item_id)->exists(),
            'gastronomia' => \App\Models\Gastronomia::where('empresa_id', $empresa->id)->where('id', $calificacion->item_id)->exists(),
            'empresa'     => $calificacion->item_id === $empresa->id,
            default       => false,
        };

        abort_if(!$esPropia, 403);

        $request->validate([
            'respuesta_empresa' => 'required|string|min:5|max:800',
        ]);

        $calificacion->update([
            'respuesta_empresa' => $request->respuesta_empresa,
        ]);

        return back()->with('success', 'Respuesta publicada correctamente.');
    }

    public function enviarSolicitud(Request $request)
    {
        $request->validate([
            'tipo'        => ['required', 'in:hotel,restaurante,actualizacion,novedad'],
            'descripcion' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
        ]);

        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();

        $tipos = [
            'hotel'         => 'SOLICITUD NUEVO HOTEL',
            'restaurante'   => 'SOLICITUD NUEVO RESTAURANTE',
            'actualizacion' => 'SOLICITUD ACTUALIZACIÓN DE DATOS',
            'novedad'       => 'NOVEDAD / REPORTE',
        ];

        $mensaje = $tipos[$request->tipo] . "\n" . $request->descripcion;

        NotificacionAdmin::create([
            'empresa_id' => $empresa->id,
            'mensaje'    => $mensaje,
            'leido'      => false,
        ]);

        return back()->with('success', 'Solicitud enviada. El administrador la revisará pronto.');
    }

    public function editarPerfil()
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();
        return view('empresa.perfil', compact('empresa'));
    }

    public function actualizarPerfil(Request $request)
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();

        $request->validate([
            'nombre'            => ['required', 'string', 'max:200'],
            'telefono'          => ['nullable', 'string', 'max:30'],
            'direccion'         => ['nullable', 'string', 'max:400'],
            'tipo_empresa'      => ['nullable', 'in:hotel,restaurante,agencia_turismo,transporte,artesanias,otro'],
            'servicios'         => ['nullable', 'array'],
            'servicios.*'       => ['string', 'max:100'],
            'descripcion'       => ['nullable', 'string', 'max:1000'],
            'sitio_web'         => ['nullable', 'url'],
            'instagram'         => ['nullable', 'string', 'max:200'],
            'facebook'          => ['nullable', 'string', 'max:200'],
            'empresa_logo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'empresa_logo_url'  => ['nullable', 'url'],
        ]);

        $logo = $empresa->logo;
        if ($request->hasFile('empresa_logo_file')) {
            $logo = Storage::disk('public')->putFile('logos/empresas', $request->file('empresa_logo_file'));
        } elseif ($request->filled('empresa_logo_url')) {
            $logo = $request->empresa_logo_url;
        }

        $empresa->update([
            'nombre'       => $request->nombre,
            'telefono'     => $request->telefono,
            'direccion'    => $request->direccion,
            'tipo_empresa' => $request->tipo_empresa,
            'servicios'    => $request->servicios ?? [],
            'descripcion'  => $request->descripcion,
            'logo'         => $logo,
            'sitio_web'    => $request->sitio_web,
            'instagram'    => $request->instagram,
            'facebook'     => $request->facebook,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}