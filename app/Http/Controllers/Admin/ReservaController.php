<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ReservasExport;
use App\Imports\ReservasImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\HandlesImport;
use App\Models\Reserva;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    use HandlesImport;
    const ESTADOS = ['pendiente', 'confirmada', 'cancelada'];

    public function index(Request $request)
    {
        $query = Reserva::with(['hotel', 'usuario']);

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_entrada', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_salida', '<=', $request->fecha_fin);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $perPage  = $request->get('per_page', 10);

        // Ordenamiento
        $sort      = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $allowedSorts = ['id', 'fecha_entrada', 'fecha_salida', 'precio_total', 'estado', 'num_personas'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        $direction = $direction === 'desc' ? 'desc' : 'asc';

        $reservas = $query->orderBy($sort, $direction)->paginate($perPage)->withQueryString();

        $usuarios = User::all();
        $hoteles  = Hotel::all();

        return view('admin.reservas', compact('reservas', 'usuarios', 'hoteles', 'perPage', 'sort', 'direction'));
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
        $perPage  = 10;
        $reservas = Reserva::with(['hotel', 'usuario'])->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        $hoteles  = Hotel::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('admin.reservas', compact('reservas', 'hoteles', 'usuarios', 'reserva', 'perPage'));
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

    public function exportExcel()
    {
        return Excel::download(new ReservasExport, 'reservas.xlsx');
    }

    public function importExcel(Request $request)
    {
        return $this->runImport($request, new ReservasImport, 'admin.reservas.index');
    }

    public function exportPdf()
    {
        $reservas = Reserva::with(['usuario', 'hotel'])->get();

        $pdf = Pdf::loadView('admin.pdf.reservas', compact('reservas'));

        return $pdf->download('reservas.pdf');
    }
    public function cambiarEstado(Request $request, Reserva $reserva)
{
    $request->validate([
        'estado' => 'required|in:pendiente,confirmada,cancelada',
    ]);

    $reserva->update(['estado' => $request->estado]);

    return redirect()->route('admin.reservas.index')
                     ->with('success', 'Estado actualizado correctamente.');
}
}