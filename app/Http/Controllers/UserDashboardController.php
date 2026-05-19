<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Favorito;
use App\Models\Gastronomia;
use App\Models\Hotel;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    // ── Panel principal ────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab  = $request->get('tab', 'reservas');

        // ── Reservas ──────────────────────────────────────────
        $qReservas = Reserva::with('hotel')->where('usuario_id', $user->id);
        if ($request->filled('estado_reserva')) {
            $qReservas->where('estado', $request->estado_reserva);
        }
        if ($request->filled('fecha_desde')) {
            $qReservas->whereDate('fecha_entrada', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $qReservas->whereDate('fecha_salida', '<=', $request->fecha_hasta);
        }
        $reservas = $qReservas->latest()->get();

        $proxima = Reserva::with('hotel')
            ->where('usuario_id', $user->id)
            ->where('estado', 'confirmada')
            ->where('fecha_entrada', '>=', now()->toDateString())
            ->orderBy('fecha_entrada')
            ->first();

        // ── Calificaciones / Reseñas ──────────────────────────
        $calificaciones = Calificacion::where('usuario_id', $user->id)
            ->latest()->get()
            ->map(function ($cal) {
                $cal->item_nombre = match ($cal->tipo) {
                    'hotel'       => Hotel::find($cal->item_id)?->nombre ?? 'Hotel eliminado',
                    'gastronomia' => Gastronomia::find($cal->item_id)?->nombre ?? 'Plato eliminado',
                    default       => ucfirst($cal->tipo) . ' #' . $cal->item_id,
                };
                $cal->item_url = match ($cal->tipo) {
                    'hotel'       => route('hoteles.detalle', $cal->item_id),
                    default       => null,
                };
                return $cal;
            });

        // ── Favoritos ─────────────────────────────────────────
        $favs      = Favorito::where('usuario_id', $user->id)->get();
        $hotelIds  = $favs->where('tipo', 'hotel')->pluck('item_id');
        $gastroIds = $favs->where('tipo', 'gastronomia')->pluck('item_id');

        $hotelesFav  = Hotel::whereIn('id', $hotelIds)->get()
            ->map(fn($h) => (object)[
                'fav_id'  => $favs->where('tipo','hotel')->where('item_id',$h->id)->first()?->id,
                'tipo'    => 'hotel',
                'item_id' => $h->id,
                'nombre'  => $h->nombre,
                'imagen'  => $h->imagen,
                'precio'  => $h->precio,
                'url'     => route('hoteles.detalle', $h),
            ]);
        $gastroFav = Gastronomia::whereIn('id', $gastroIds)->get()
            ->map(fn($g) => (object)[
                'fav_id'  => $favs->where('tipo','gastronomia')->where('item_id',$g->id)->first()?->id,
                'tipo'    => 'gastronomia',
                'item_id' => $g->id,
                'nombre'  => $g->nombre,
                'imagen'  => $g->imagen,
                'precio'  => $g->precio_promedio,
                'url'     => null,
            ]);
        $favoritos = $hotelesFav->merge($gastroFav)->values();

        // ── Actividad reciente (últimas 10) ───────────────────
        $actReservas = Reserva::where('usuario_id', $user->id)
            ->latest()->take(10)->get()
            ->map(fn($r) => (object)[
                'tipo'    => 'reserva',
                'icono'   => 'fa-calendar-check',
                'color'   => '#16a34a',
                'texto'   => 'Reserva en ' . ($r->hotel?->nombre ?? 'hotel'),
                'detalle' => ucfirst($r->estado),
                'fecha'   => $r->created_at,
            ]);
        $actCalif = Calificacion::where('usuario_id', $user->id)
            ->latest()->take(10)->get()
            ->map(fn($c) => (object)[
                'tipo'    => 'resena',
                'icono'   => 'fa-star',
                'color'   => '#d97706',
                'texto'   => 'Reseña dejada — ' . $c->calificacion . '★',
                'detalle' => \Str::limit($c->comentario ?? '', 50),
                'fecha'   => $c->created_at,
            ]);
        $actFavs = Favorito::where('usuario_id', $user->id)
            ->latest()->take(10)->get()
            ->map(fn($f) => (object)[
                'tipo'    => 'favorito',
                'icono'   => 'fa-heart',
                'color'   => '#dc2626',
                'texto'   => 'Favorito agregado — ' . ucfirst($f->tipo),
                'detalle' => '#' . $f->item_id,
                'fecha'   => $f->created_at,
            ]);

        $actividad = $actReservas->merge($actCalif)->merge($actFavs)
            ->sortByDesc('fecha')->take(10)->values();

        return view('usuario.dashboard', compact(
            'user', 'tab', 'reservas', 'proxima',
            'calificaciones', 'favoritos', 'actividad'
        ));
    }

    // ── Editar perfil (redirect al panel con tab=perfil) ──────
    public function editarPerfil()
    {
        return redirect()->route('usuario.dashboard', ['tab' => 'perfil']);
    }

    // ── Actualizar perfil ──────────────────────────────────────
    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'telefono'     => 'nullable|string|max:20',
            'avatar_file'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'avatar_url'   => 'nullable|url',
        ], [
            'email.unique' => 'Ese correo ya está en uso por otra cuenta.',
        ]);

        // Avatar
        $avatar = $user->avatar;
        if ($request->hasFile('avatar_file')) {
            if ($avatar && !str_starts_with($avatar, 'http')) {
                Storage::disk('public')->delete($avatar);
            }
            $avatar = $request->file('avatar_file')->store('avatars/usuarios', 'public');
        } elseif ($request->filled('avatar_url')) {
            $avatar = $request->avatar_url;
        }

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'avatar'   => $avatar,
        ];

        // Contraseña
        if ($request->filled('password_actual')) {
            $request->validate([
                'password_actual'       => 'required',
                'password'              => 'required|min:8|confirmed',
            ], [
                'password.confirmed' => 'Las contraseñas no coinciden.',
                'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            ]);
            if (!Hash::check($request->password_actual, $user->password)) {
                return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.'])->withInput();
            }
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('usuario.dashboard', ['tab' => 'perfil'])
            ->with('success', 'Perfil actualizado correctamente.');
    }

    // ── Eliminar reseña ────────────────────────────────────────
    public function eliminarResena(Calificacion $calificacion)
    {
        abort_if($calificacion->usuario_id !== Auth::id(), 403);
        $calificacion->delete();
        return back()->with('success', 'Reseña eliminada.');
    }

    // ── Métodos legacy (mantener compatibilidad) ───────────────
    public function cancelarReserva(Request $request, $id)
    {
        $reserva = Reserva::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->where('estado', 'pendiente')
            ->firstOrFail();
        $reserva->update(['estado' => 'cancelada']);
        return redirect()->route('usuario.dashboard')->with('success', 'Reserva cancelada.');
    }

    public function updateProfile(Request $request)
    {
        return $this->actualizarPerfil($request);
    }
}
