<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LugaresExport;
use App\Imports\LugaresImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lugar;
use Illuminate\Support\Facades\Storage;

class LugarController extends Controller
{
    public function index(Request $request)
{
    $query = Lugar::query();

    if ($request->nombre) {
        $query->where('nombre', 'like', '%' . $request->nombre . '%');
    }

    if ($request->categoria) {
        $query->where('categoria', $request->categoria);
    }

    if ($request->ubicacion) {
        $query->where('ubicacion', 'like', '%' . $request->ubicacion . '%');
    }

    if ($request->precio_entrada) {
        $query->where('precio_entrada', '<=', $request->precio_entrada);
    }

    $lugares = $query->paginate(10)->withQueryString();

    return view('admin.lugares', compact('lugares'));
}
    private function handleImageUpload(Request $request, ?string $currentImage = null): string
    {
        if ($request->hasFile('imagen_file')) {
            if ($currentImage && !str_starts_with($currentImage, 'http')) {
                Storage::disk('public')->delete($currentImage);
            }
            return $request->file('imagen_file')->store('uploads/lugares', 'public');
        }

        if ($request->filled('imagen_url')) {
            if ($currentImage && !str_starts_with($currentImage, 'http')) {
                Storage::disk('public')->delete($currentImage);
            }
            return $request->imagen_url;
        }

        return $currentImage ?? '';
    }

    private function rules(bool $isUpdate = false): array
    {
        $imgRequired = $isUpdate ? 'nullable' : 'required_without:imagen_file';
        return [
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'required|string',
            'categoria'    => 'required|string|max:100',
            'imagen_url'   => "$imgRequired|nullable|url",
            'imagen_file'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }
}