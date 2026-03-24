<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Estados válidos del enum
    const ESTADOS = ['pendiente', 'confirmada', 'cancelada'];

    public function index()
    {
        // with() carga hotel y usuario en una sola query (evita N+1)
        $reservas = Reserva::with(['hotel', 'usuario'])
                            ->orderBy('id', 'desc')
                            ->get();

        $hoteles  = Hotel::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('admin.reservas', compact('reservas', 'hoteles', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id'    => 'required|exists:users,id',
            'hotel_id'      => 'required|exists:hoteles,id',
            'fecha_entrada' => 'required|date',
            'fecha_salida'  => 'required|date|after:fecha_entrada',
            'num_personas'  => 'required|integer|min:1',
            'precio_total'  => 'required|numeric|min:0',
            'estado'        => 'required|in:pendiente,confirmada,cancelada',
        ]);

        Reserva::create($request->only([
            'usuario_id', 'hotel_id', 'fecha_entrada',
            'fecha_salida', 'num_personas', 'precio_total', 'estado',
        ]));

        return redirect()->route('admin.reservas.index')
                         ->with('success', 'Reserva creada correctamente.');
    }

    public function edit(Reserva $reserva)
    {
        $reservas = Reserva::with(['hotel', 'usuario'])->orderBy('id', 'desc')->get();
        $hoteles  = Hotel::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('admin.reservas', compact('reservas', 'hoteles', 'usuarios', 'reserva'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'usuario_id'    => 'required|exists:users,id',
            'hotel_id'      => 'required|exists:hoteles,id',
            'fecha_entrada' => 'required|date',
            'fecha_salida'  => 'required|date|after:fecha_entrada',
            'num_personas'  => 'required|integer|min:1',
            'precio_total'  => 'required|numeric|min:0',
            'estado'        => 'required|in:pendiente,confirmada,cancelada',
        ]);

        $reserva->update($request->only([
            'usuario_id', 'hotel_id', 'fecha_entrada',
            'fecha_salida', 'num_personas', 'precio_total', 'estado',
        ]));

        return redirect()->route('admin.reservas.index')
                         ->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('admin.reservas.index')
                         ->with('success', 'Reserva eliminada correctamente.');
    }
}
