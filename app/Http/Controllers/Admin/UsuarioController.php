<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exports\UsuariosExport;
use App\Imports\UsuariosImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 🔎 FILTROS
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // 📄 PAGINACIÓN
        $perPage = $request->get('per_page', 10);

        // ORDENAMIENTO (AGREGAR AQUÍ)
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        // (opcional pero recomendado)
        $allowedSorts = ['id', 'name', 'email', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
        $sort = 'id';
        }

        // 🔁 CAMBIAR ESTA LÍNEA
        $usuarios = $query->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString(); // mantiene filtros

        return view('admin.usuarios', compact('usuarios', 'perPage'));
    }

    // 📤 EXPORTAR A EXCEL
    public function exportExcel()
    {
        return Excel::download(new UsuariosExport, 'usuarios.xlsx');
    }

    // 📄 EXPORTAR A PDF
    public function exportPdf()
    {
        $usuarios = User::all();

        $pdf = Pdf::loadView('admin.pdf.usuarios', compact('usuarios'));

        return $pdf->download('usuarios.pdf');
    }

    // 📥 IMPORTAR EXCEL
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new UsuariosImport, $request->file('archivo'));

        return back()->with('success', 'Usuarios importados correctamente');
    }
}