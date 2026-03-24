<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\NotificacionAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaDashboardController extends Controller
{
    public function index()
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->first();

        return view('empresa.dashboard', [
            'empresa'    => $empresa,
            'historial'  => $empresa
                ? NotificacionAdmin::where('empresa_id', $empresa->id)->latest()->get()
                : collect(),
        ]);
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
}
