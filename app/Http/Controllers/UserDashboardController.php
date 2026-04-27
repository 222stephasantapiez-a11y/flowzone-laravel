<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Reserva;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $reservas = Reserva::with('hotel')
            ->where('usuario_id', $user->id)
            ->latest()
            ->get();

        $proxima = Reserva::with('hotel')
            ->where('usuario_id', $user->id)
            ->where('estado', 'confirmada')
            ->where('fecha_entrada', '>=', now()->toDateString())
            ->orderBy('fecha_entrada')
            ->first();

        return view('pages.user_dashboard', [
            'user'          => $user,
            'reservas'      => $reservas,
            'pendientes'    => $reservas->where('estado', 'pendiente'),
            'confirmadas'   => $reservas->where('estado', 'confirmada'),
            'canceladas'    => $reservas->where('estado', 'cancelada'),
            'total_gastado' => $reservas->whereNotIn('estado', ['cancelada'])->sum('precio_total'),
            'proxima'       => $proxima,
        ]);
    }

    public function cancelarReserva(Request $request, $id)
    {
        $reserva = Reserva::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->where('estado', 'pendiente')
            ->firstOrFail();

        $reserva->update(['estado' => 'cancelada']);

        return redirect()->route('dashboard')->with('success', '✓ Reserva cancelada correctamente.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
        ]);

        $data = [
            'name'     => $request->name,
            'telefono' => $request->telefono,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password'              => 'min:8',
                'password_confirmation' => 'same:password',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('dashboard')->with('success', '✓ Perfil actualizado correctamente.');
    }
}