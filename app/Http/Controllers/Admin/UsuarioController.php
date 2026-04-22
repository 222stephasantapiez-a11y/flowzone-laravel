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

    // filtro por ID
    if ($request->id) {
        $query->where('id', $request->id);
    }

    // filtro por nombre
    if ($request->name) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    // filtro por email
    if ($request->email) {
        $query->where('email', 'like', '%' . $request->email . '%');
    }

    $usuarios = $query->paginate(10);

    return view('admin.usuarios', compact('usuarios'));
}
    
    public function exportExcel()
    {
        return Excel::download(new UsuariosExport, 'usuarios.xlsx');
    }

    public function exportPdf()
    {
        $usuarios = User::all();

        $pdf = Pdf::loadView('admin.pdf.usuarios', compact('usuarios'));

        return $pdf->download('usuarios.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new UsuariosImport, $request->file('archivo'));

        return back()->with('success', 'Usuarios importados correctamente');
    }
}