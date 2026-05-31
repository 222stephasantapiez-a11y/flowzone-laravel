<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\EmpresaImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmpresaImagenController extends Controller
{
    private function empresaDelUsuario(): Empresa
    {
        return Empresa::where('usuario_id', Auth::id())->firstOrFail();
    }

    public function store(Request $request)
    {
        $empresa = $this->empresaDelUsuario();

        $request->validate([
            'imagen'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'titulo'    => 'nullable|string|max:200',
            'categoria' => 'nullable|in:general,piscina,habitacion,restaurante,salon,exterior,spa,parqueadero,otro',
        ]);

        $ruta  = $request->file('imagen')->store("empresas/{$empresa->id}/galeria", 'public');
        $orden = (EmpresaImagen::where('empresa_id', $empresa->id)->max('orden') ?? 0) + 1;

        EmpresaImagen::create([
            'empresa_id' => $empresa->id,
            'ruta'       => $ruta,
            'titulo'     => $request->titulo,
            'categoria'  => $request->categoria,
            'orden'      => $orden,
        ]);

        return back()->with('success', 'Imagen agregada a tu galería.');
    }

    public function toggle(EmpresaImagen $imagen)
    {
        $empresa = $this->empresaDelUsuario();
        abort_if($imagen->empresa_id !== $empresa->id, 403);

        $imagen->update(['activa' => !$imagen->activa]);

        return back()->with('success', 'Visibilidad de la imagen actualizada.');
    }

    public function destroy(EmpresaImagen $imagen)
    {
        $empresa = $this->empresaDelUsuario();
        abort_if($imagen->empresa_id !== $empresa->id, 403);

        Storage::disk('public')->delete($imagen->ruta);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada de tu galería.');
    }
}
