<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagenController extends Controller
{
    public function index()
    {
        $imagenes = HeroImage::orderBy('seccion')->orderBy('orden')->get();
        $secciones = ['hero' => 'Hero (Inicio)', 'destacadas' => 'Secciones Destacadas', 'cards' => 'Cards'];
        return view('admin.imagenes', compact('imagenes', 'secciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'  => 'nullable|string|max:200',
            'seccion' => 'required|in:hero,destacadas,cards',
            'tipo'    => 'required|in:url,upload',
            'url'     => 'required_if:tipo,url|nullable|url|max:500',
            'imagen'  => 'required_if:tipo,upload|nullable|image|max:4096',
        ]);

        $data = [
            'titulo'  => $request->titulo,
            'seccion' => $request->seccion,
            'tipo'    => $request->tipo,
            'activa'  => true,
            'orden'   => HeroImage::where('seccion', $request->seccion)->max('orden') + 1,
        ];

        if ($request->tipo === 'upload' && $request->hasFile('imagen')) {
            $data['url'] = $request->file('imagen')->store('hero', 'public');
        } else {
            $data['url'] = $request->url;
        }

        HeroImage::create($data);

        return back()->with('success', 'Imagen agregada correctamente.');
    }

    public function toggleActiva(HeroImage $imagen)
    {
        $imagen->update(['activa' => !$imagen->activa]);
        return back()->with('success', 'Estado actualizado.');
    }

    public function orden(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
        foreach ($request->ids as $i => $id) {
            HeroImage::where('id', $id)->update(['orden' => $i]);
        }
        return response()->json(['ok' => true]);
    }

    public function destroy(HeroImage $imagen)
    {
        if ($imagen->tipo === 'upload') {
            Storage::disk('public')->delete($imagen->url);
        }
        $imagen->delete();
        return back()->with('success', 'Imagen eliminada.');
    }
}
